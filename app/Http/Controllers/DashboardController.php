<?php

namespace App\Http\Controllers;

use App\Models\Plan;
use App\Models\Template;
use App\Models\UserPurchase;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Password;

class DashboardController extends Controller
{
    public function index()
    {
        $plans          = Plan::where('is_active', true)->orderBy('sort_order')->get();
        $activePurchase = Auth::user()->activePurchase;
        $allPurchases   = Auth::user()->purchases()->with(['plan', 'selectedTemplate'])->latest()->get();

        $availableTemplates = collect();
        if ($activePurchase) {
            $tiers = $activePurchase->accessibleTiers();
            $availableTemplates = Template::whereIn('plan_tier', $tiers)
                ->where('is_active', true)
                ->with('category')
                ->latest()
                ->get();
        }

        $cvData = $this->cleanCvData(Auth::user()->cv_data ?? []);

        return view('dashboard', compact('plans', 'activePurchase', 'allPurchases', 'availableTemplates', 'cvData'));
    }

    public function selectTemplate(UserPurchase $purchase, Template $template)
    {
        abort_if($purchase->user_id !== Auth::id(), 403);
        abort_if($purchase->status !== 'active', 403);

        $tiers = $purchase->accessibleTiers();
        abort_if(! in_array($template->plan_tier, $tiers), 403);

        $purchase->update(['selected_template_id' => $template->id]);

        return redirect()->route('dashboard')
            ->with('success', 'Plantilla «' . $template->name . '» seleccionada correctamente.')
            ->with('open_section', 'templates');
    }

    public function activatePlan(Plan $plan)
    {
        $user = Auth::user();

        // Cancelar plan activo anterior
        $user->purchases()->where('status', 'active')->update(['status' => 'cancelled']);

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

        $request->validate([
            'hosting_type' => ['required', 'in:subdomain,paid_hosting'],
            'subdomain'    => ['nullable', 'string', 'max:50'],
        ]);

        $subdomain = null;
        if ($request->hosting_type === 'subdomain') {
            $subdomain = Str::slug($request->subdomain ?: Auth::user()->name);
        }

        $purchase->update([
            'hosting_type' => $request->hosting_type,
            'subdomain'    => $subdomain,
        ]);

        $msg = $request->hosting_type === 'subdomain'
            ? "Subdominio configurado: {$subdomain}.expresscv.es"
            : 'Hosting de pago seleccionado. Nos pondremos en contacto pronto.';

        return redirect()->route('dashboard')
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
