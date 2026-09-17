<?php

namespace App\Http\Controllers;

use App\Models\Plan;
use App\Models\Template;
use App\Models\UserPurchase;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Password;

class DashboardController extends Controller
{
    public function index()
    {
        // Solo planes de CV Web (con hosting o solo descarga) — nunca PDF aquí.
        $plans          = Plan::where('is_active', true)->whereIn('category', ['template', 'web_download'])->orderBy('sort_order')->get();
        $activePurchase = Auth::user()->activePurchase;
        $allPurchases   = Auth::user()->purchases()->with(['plan', 'selectedTemplate'])->latest()->get();

        $availableTemplates = $this->availableTemplatesFor($activePurchase);

        $cvData           = $this->cleanCvData(Auth::user()->cv_data ?? []);
        $photoUrl         = Auth::user()->profile_photo
                            ? '/storage/' . Auth::user()->profile_photo
                            : null;
        $photoOrientation = $activePurchase?->selectedTemplate?->photo_orientation;

        return view('dashboard', compact('plans', 'activePurchase', 'allPurchases', 'availableTemplates', 'cvData', 'photoUrl', 'photoOrientation'));
    }

    public function cvWeb()
    {
        $user           = Auth::user();
        $activePurchase = $user->activePurchase;
        // Solo planes de CV Web (con hosting o solo descarga) — nunca PDF aquí.
        $plans          = Plan::where('is_active', true)->whereIn('category', ['template', 'web_download'])->orderBy('sort_order')->get();

        $availableTemplates = $this->availableTemplatesFor($activePurchase);

        $cvData           = $this->cleanCvData($user->cv_data ?? []);
        $photoUrl         = $user->profile_photo ? '/storage/' . $user->profile_photo : null;
        $photoOrientation = $activePurchase?->selectedTemplate?->photo_orientation;
        $selected         = $activePurchase?->selectedTemplate;
        $tiers            = Template::PLAN_TIERS;
        $u                = $user;

        // Para ofrecer "añadir hosting" a quien solo tiene un plan de descarga.
        $upgradePlan = $plans->where('category', 'template')->sortBy('price')->first();

        return view('cv-web', compact(
            'activePurchase', 'availableTemplates', 'cvData',
            'photoUrl', 'photoOrientation', 'selected', 'tiers', 'plans', 'u', 'upgradePlan'
        ));
    }

    public function cvWebSelectTemplate(UserPurchase $purchase, Template $template)
    {
        abort_if($purchase->user_id !== Auth::id(), 403);
        abort_if($purchase->status !== 'active', 403);
        abort_unless($purchase->canUseTemplate($template), 403);

        $purchase->update(['selected_template_id' => $template->id]);

        return redirect()->route('cv-web.editor')
            ->with('success', 'Plantilla «' . $template->name . '» seleccionada. ¡Empieza a editar tu CV!');
    }

    /**
     * Descarga la plantilla seleccionada + los cambios del editor como un .zip
     * autónomo (index.html con los datos ya incrustados + css/js/img + foto).
     *
     * El HTML llega desde el navegador: es exactamente el DOM del iframe de la
     * vista previa, que ya tiene aplicados todos los cambios en vivo.
     */
    public function cvWebDownloadZip(Request $request)
    {
        $user           = Auth::user();
        $activePurchase = $user->activePurchase;
        abort_if(! $activePurchase, 403);

        $template = $activePurchase->selectedTemplate;
        abort_if(! $template, 422, 'No hay ninguna plantilla seleccionada.');

        $data = $request->validate([
            'html' => ['required', 'string', 'max:5000000'],
        ]);

        $workDir = $this->buildSiteWorkDir($user, $template, $data['html']);

        try {
            $zipName = 'cv-web-' . Str::slug($template->name ?: 'plantilla') . '-' . now()->format('Ymd-His') . '.zip';
            $zipPath = storage_path('app/tmp/' . $zipName);
            @unlink($zipPath);

            $zip = new \ZipArchive();
            if ($zip->open($zipPath, \ZipArchive::CREATE | \ZipArchive::OVERWRITE) !== true) {
                abort(500, 'No se pudo generar el archivo ZIP.');
            }

            foreach ($this->iterateWorkDir($workDir) as $local => $file) {
                $file->isDir() ? $zip->addEmptyDir($local) : $zip->addFile($file->getPathname(), $local);
            }
            $zip->close();

            return response()->download($zipPath, $zipName)->deleteFileAfterSend(true);
        } finally {
            File::deleteDirectory($workDir);
        }
    }

    /**
     * Publica (o actualiza) la web del usuario en su subdominio: genera el
     * mismo paquete que el ZIP y lo guarda en el disco "sites", desde donde
     * lo sirve PublicSiteController para cualquiera que visite
     * {subdominio}.{dominio configurado}.
     */
    public function cvWebPublish(Request $request): JsonResponse
    {
        $user           = Auth::user();
        $activePurchase = $user->activePurchase;
        abort_if(! $activePurchase, 403);
        abort_unless($activePurchase->canHost(), 403, 'Tu plan es solo de descarga y no incluye hosting.');
        abort_unless($activePurchase->hosting_type === 'subdomain' && $activePurchase->subdomain, 422, 'Primero reserva tu subdominio.');

        $template = $activePurchase->selectedTemplate;
        abort_if(! $template, 422, 'No hay ninguna plantilla seleccionada.');

        $data = $request->validate([
            'html' => ['required', 'string', 'max:5000000'],
        ]);

        $subdomain = $activePurchase->subdomain;
        $workDir   = $this->buildSiteWorkDir($user, $template, $data['html']);

        try {
            $disk = Storage::disk('sites');
            $disk->deleteDirectory($subdomain);

            foreach ($this->iterateWorkDir($workDir) as $local => $file) {
                if ($file->isDir()) continue;
                $disk->put($subdomain . '/' . $local, file_get_contents($file->getPathname()));
            }
        } finally {
            File::deleteDirectory($workDir);
        }

        $domain = config('services.public_sites.domain');
        $port   = $request->getPort();
        $port   = in_array($port, [80, 443], true) ? '' : ':' . $port;
        $url    = $request->getScheme() . '://' . $subdomain . '.' . $domain . $port . '/';

        return response()->json(['ok' => true, 'url' => $url]);
    }

    /**
     * Genera un directorio temporal con la plantilla del usuario (assets +
     * index.html ya con sus datos incrustados + foto de perfil). Lo usan
     * tanto la descarga ZIP como la publicación por subdominio.
     */
    private function buildSiteWorkDir($user, Template $template, string $html): string
    {
        abort_unless(preg_match('/^[A-Za-z0-9._-]+$/', (string) $template->slug), 422, 'Plantilla no válida.');

        // Carpeta de la plantilla en disco (misma resolución que preview_html_url)
        $baseDir   = public_path('previews/' . $template->slug);
        $sourceDir = null;
        if (is_file($baseDir . '/index.html')) {
            $sourceDir = $baseDir;
        } else {
            foreach (glob($baseDir . '/*/index.html') ?: [] as $p) {
                $sourceDir = dirname($p);
                break;
            }
        }
        abort_if(! $sourceDir || ! is_dir($sourceDir), 404, 'No se encontró la plantilla en el servidor.');

        $workDir = storage_path('app/tmp/cvweb-' . $user->id . '-' . Str::random(8));
        File::deleteDirectory($workDir);
        File::ensureDirectoryExists($workDir);

        // 1. Copiar todos los assets de la plantilla (css/js/img…)
        File::copyDirectory($sourceDir, $workDir);

        // 2. HTML editado
        $html = preg_replace('/<base\b[^>]*>/i', '', $html);

        // 3. Incrustar la foto de perfil como archivo local
        if ($user->profile_photo) {
            $photoAbs = storage_path('app/public/' . $user->profile_photo);
            if (is_file($photoAbs)) {
                $ext      = pathinfo($photoAbs, PATHINFO_EXTENSION) ?: 'jpg';
                $photoRel = 'img/perfil.' . strtolower($ext);
                File::ensureDirectoryExists($workDir . '/img');
                File::copy($photoAbs, $workDir . '/' . $photoRel, true);

                $html = str_replace([
                    asset('storage/' . $user->profile_photo),
                    url('storage/' . $user->profile_photo),
                    '/storage/' . $user->profile_photo,
                    'storage/' . $user->profile_photo,
                ], $photoRel, $html);
            }
        }

        // 4. Quitar el host absoluto que haya podido quedar embebido
        $html = str_replace([rtrim(url('/'), '/') . '/', rtrim(url('/'), '/')], '', $html);

        file_put_contents($workDir . '/index.html', $html);

        return $workDir;
    }

    /**
     * Recorre un directorio de trabajo devolviendo [ruta_relativa => SplFileInfo].
     */
    private function iterateWorkDir(string $workDir): \Generator
    {
        $files = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($workDir, \FilesystemIterator::SKIP_DOTS),
            \RecursiveIteratorIterator::SELF_FIRST
        );
        foreach ($files as $file) {
            $local = str_replace('\\', '/', substr($file->getPathname(), strlen($workDir) + 1));
            yield $local => $file;
        }
    }

    public function selectTemplate(UserPurchase $purchase, Template $template)
    {
        abort_if($purchase->user_id !== Auth::id(), 403);
        abort_if($purchase->status !== 'active', 403);
        abort_unless($purchase->canUseTemplate($template), 403);

        $purchase->update(['selected_template_id' => $template->id]);

        return redirect()->route('cv-web.editor')
            ->with('success', 'Plantilla «' . $template->name . '» seleccionada. ¡Empieza a editar tu CV!')
            ->with('show_hub', true);
    }

    public function activatePlan(Plan $plan)
    {
        $user = Auth::user();

        // Cancelar solo el plan activo anterior de la MISMA categoría (p. ej.
        // otro plan de CV Web). No toca un plan de PDF activo en paralelo.
        $user->purchases()
            ->where('status', 'active')
            ->whereHas('plan', fn ($q) => $q->where('category', $plan->category))
            ->update(['status' => 'cancelled']);

        $user->purchases()->create([
            'plan_id'      => $plan->id,
            'status'       => 'active',
            'amount_paid'  => $plan->price,
            'hosting_type' => 'none',
            'purchased_at' => now(),
        ]);

        return redirect()->route('dashboard')
            ->with('success', '¡Plan ' . $plan->name . ' activado correctamente! Ahora configura tu hosting.')
            ->with('open_section', 'orders');
    }

    public function updateHosting(Request $request, UserPurchase $purchase)
    {
        abort_if($purchase->user_id !== Auth::id(), 403);
        abort_unless($purchase->canHost(), 403, 'Tu plan es solo de descarga y no incluye hosting.');

        $request->validate([
            'hosting_type' => ['required', 'in:subdomain,paid_hosting,none'],
            'subdomain'    => ['nullable', 'string', 'max:50'],
        ]);

        $subdomain = null;
        if ($request->hosting_type === 'subdomain') {
            $subdomain = Str::slug($request->subdomain ?: Auth::user()->name) ?: 'mi-portfolio';
        }

        $purchase->update([
            'hosting_type' => $request->hosting_type,
            'subdomain'    => $subdomain,
        ]);

        $msg = match ($request->hosting_type) {
            'subdomain'    => "Subdominio configurado: {$subdomain}.cvxpress.es",
            'paid_hosting' => 'Hosting de pago seleccionado. Nos pondremos en contacto pronto.',
            default        => 'Hosting reiniciado. Elige de nuevo cómo publicar tu web.',
        };

        // Las peticiones AJAX (p. ej. desde el modal "Publicar web" del editor
        // CV Web) reciben JSON en lugar de una redirección, para no recargar
        // la página y perder cambios sin guardar.
        if ($request->wantsJson()) {
            return response()->json([
                'ok'           => true,
                'message'      => $msg,
                'hosting_type' => $purchase->hosting_type,
                'subdomain'    => $purchase->subdomain,
            ]);
        }

        return redirect()->back()
            ->with('success', $msg)
            ->with('open_section', 'orders');
    }

    public function cancelPlan(UserPurchase $purchase)
    {
        abort_if($purchase->user_id !== Auth::id(), 403);

        $purchase->update(['status' => 'cancelled']);

        return redirect()->route('dashboard')
            ->with('success', 'Plan cancelado.')
            ->with('open_section', 'orders');
    }

    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name'             => ['required', 'string', 'max:255'],
            'email'            => ['required', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'job_title'        => ['nullable', 'string', 'max:255'],
            'phone'            => ['nullable', 'string', 'max:50'],
            'location'         => ['nullable', 'string', 'max:255'],
            'bio'              => ['nullable', 'string', 'max:2000'],
            'linkedin_url'     => ['nullable', 'url', 'max:255'],
            'website_url'      => ['nullable', 'url', 'max:255'],
            'cv_file'          => ['nullable', 'file', 'mimes:pdf', 'max:10240'],
            'current_password' => ['nullable', 'string'],
            'password'         => ['nullable', 'confirmed', Password::min(8)],
        ]);

        $user->name         = $request->name;
        $user->email        = $request->email;
        $user->job_title    = $request->job_title;
        $user->phone        = $request->phone;
        $user->location     = $request->location;
        $user->bio          = $request->bio;
        $user->linkedin_url = $request->linkedin_url;
        $user->website_url  = $request->website_url;

        if ($request->hasFile('cv_file')) {
            if ($user->cv_path) {
                Storage::disk('local')->delete($user->cv_path);
            }

            $path = $request->file('cv_file')->storeAs(
                'cvs/' . $user->id,
                'cv_' . time() . '.pdf',
                'local'
            );

            $user->cv_path          = $path;
            $user->cv_original_name = $request->file('cv_file')->getClientOriginalName();
            $user->cv_uploaded_at   = now();
        }

        if ($request->filled('password')) {
            if (!$request->filled('current_password') || !Hash::check($request->current_password, $user->password)) {
                return back()
                    ->withErrors(['current_password' => 'La contraseña actual no es correcta.'])
                    ->with('open_section', 'profile');
            }
            $user->password = Hash::make($request->password);
        }

        $user->save();

        return redirect()->route('dashboard')
            ->with('success', 'Cambios guardados correctamente.')
            ->with('open_section', $request->hasFile('cv_file') ? 'cvs' : 'profile');
    }

    public function updateCvData(Request $request)
    {
        $user = Auth::user();
        $user->cv_data = $request->input('cv_data', []);
        $user->save();
        return response()->json(['ok' => true]);
    }

    public function clearCvData(): \Illuminate\Http\JsonResponse
    {
        $user = Auth::user();

        if ($user->profile_photo) {
            Storage::disk('public')->delete($user->profile_photo);
        }

        $user->update(['cv_data' => [], 'profile_photo' => null]);

        return response()->json(['ok' => true]);
    }

    public function uploadPhoto(Request $request): \Illuminate\Http\JsonResponse
    {
        $request->validate([
            'photo' => ['required', 'image', 'mimes:jpeg,jpg,png,webp', 'max:3072'],
        ]);

        $user = Auth::user();

        if ($user->profile_photo) {
            Storage::disk('public')->delete($user->profile_photo);
        }

        $path = $request->file('photo')->store('photos/' . $user->id, 'public');
        $user->update(['profile_photo' => $path]);

        return response()->json(['url' => '/storage/' . $path]);
    }

    /**
     * Plantillas que el usuario puede elegir en el editor CV Web.
     * - Plan con hosting: todas las de su nivel (y por debajo).
     * - Plan de solo descarga: únicamente la plantilla concreta que compró
     *   (cada una tiene su propio precio, no es un pase a todo el catálogo).
     */
    private function availableTemplatesFor(?UserPurchase $activePurchase)
    {
        if (! $activePurchase) {
            return collect();
        }

        if (! $activePurchase->canHost()) {
            return Template::whereKey($activePurchase->selected_template_id)
                ->where('is_active', true)
                ->with('category')
                ->get();
        }

        return Template::whereIn('plan_tier', $activePurchase->accessibleTiers())
            ->where('is_active', true)
            ->with('category')
            ->latest()
            ->get();
    }

    private function cleanCvData(array $data): array
    {
        array_walk_recursive($data, function (&$v) {
            if (!is_string($v)) return;
            $t = trim($v);
            if (strcasecmp($t, 'null') === 0 || preg_match('/^null\s*[–\-]\s*null$/i', $t)) {
                $v = '';
            }
        });
        return $data;
    }

    public function downloadCv()
    {
        $user = Auth::user();

        abort_if(!$user->cv_path, 404);
        abort_if(!Storage::disk('local')->exists($user->cv_path), 404);

        return Storage::disk('local')->download(
            $user->cv_path,
            $user->cv_original_name ?? 'curriculum.pdf'
        );
    }
}
