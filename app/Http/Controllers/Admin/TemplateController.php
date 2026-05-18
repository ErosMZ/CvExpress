<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Plan;
use App\Models\Template;
use App\Models\Category;
use App\Models\UserPurchase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use ZipArchive;

class TemplateController extends Controller
{
    public function index()
    {
        $templates  = Template::with('category')->latest()->get();
        $categories = Category::where('is_active', true)->orderBy('name')->get();

        return view('admin.templates.index', compact('templates', 'categories'));
    }

    public function bulkDestroy(Request $request)
    {
        $ids = $request->input('ids', []);

        if (empty($ids)) {
            return redirect()->route('templates.index')
                ->with('error', 'No seleccionaste ninguna plantilla.');
        }

        $templates = Template::whereIn('id', $ids)->get();

        foreach ($templates as $template) {
            Storage::disk('public')->deleteDirectory($template->folder);
            $this->deleteDirectory(public_path("previews/{$template->slug}"));
            $template->delete();
        }

        $count = $templates->count();

        return redirect()->route('templates.index')
            ->with('success', "$count plantilla(s) eliminada(s) correctamente.");
    }

    public function create()
    {
        $categories = Category::where('is_active', true)->get();
        $plansByTier = $this->plansByTier();

        return view('admin.templates.create', compact('categories', 'plansByTier'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'           => 'required',
            'description'    => 'nullable',
            'category_id'    => 'nullable|exists:categories,id',
            'price'          => 'nullable|numeric|min:0',
            'preview_image'  => 'nullable|image',
            'template_zip'   => 'required|file|mimes:zip',
        ]);

        $slug = Str::slug($request->name);

        $folderPath = "templates/$slug";

        Storage::disk('public')->makeDirectory($folderPath);

        /*
        |--------------------------------------------------------------------------
        | Preview
        |--------------------------------------------------------------------------
        */

        $previewPath = null;

        if ($request->hasFile('preview_image')) {

            $previewPath = $request->file('preview_image')
                ->store($folderPath, 'public');
        }

        /*
        |--------------------------------------------------------------------------
        | ZIP
        |--------------------------------------------------------------------------
        */

        $zipPath = $request->file('template_zip')
            ->store($folderPath, 'public');

        /*
        |--------------------------------------------------------------------------
        | Extraer ZIP para vista previa
        |--------------------------------------------------------------------------
        */

        $this->extractPreview(
            Storage::disk('public')->path($zipPath),
            public_path("previews/$slug")
        );

        /*
        |--------------------------------------------------------------------------
        | Crear plantilla
        |--------------------------------------------------------------------------
        */

        Template::create([
            'category_id'   => $request->category_id,
            'name'          => $request->name,
            'slug'          => $slug,
            'description'   => $request->description,
            'folder'        => $folderPath,
            'preview_image' => $previewPath,
            'main_file'     => $zipPath,
            'price'         => $request->price ?? 0,
            'plan_tier'          => $request->plan_tier ?? 'basic',
            'is_premium'         => $request->plan_tier !== 'basic',
            'is_featured'        => $request->has('is_featured'),
            'is_active'          => $request->has('is_active'),
            'photo_orientation'  => $request->photo_orientation ?: null,
        ]);

        return redirect()
            ->route('templates.index')
            ->with('success', 'Plantilla creada correctamente.');
    }

    public function edit(Template $template)
    {
        $categories  = Category::where('is_active', true)->get();
        $plansByTier = $this->plansByTier();

        return view('admin.templates.edit', compact('template', 'categories', 'plansByTier'));
    }

    public function update(Request $request, Template $template)
    {
        $request->validate([
            'name' => 'required',
            'category_id' => 'nullable|exists:categories,id',
            'price' => 'nullable|numeric|min:0',
        ]);

        $oldSlug   = $template->slug;
        $oldFolder = $template->folder;

        $newSlug   = Str::slug($request->name);
        $newFolder = "templates/$newSlug";

        /*
        |--------------------------------------------------------------------------
        | Renombrar carpeta storage
        |--------------------------------------------------------------------------
        */

        if ($oldFolder !== $newFolder) {
            Storage::disk('public')->move($oldFolder, $newFolder);
        }

        /*
        |--------------------------------------------------------------------------
        | Renombrar carpeta preview pública si cambió el slug
        |--------------------------------------------------------------------------
        */

        if ($oldSlug !== $newSlug) {
            $oldPublic = public_path("previews/$oldSlug");
            $newPublic = public_path("previews/$newSlug");
            if (is_dir($oldPublic)) {
                rename($oldPublic, $newPublic);
            }
        }

        /*
        |--------------------------------------------------------------------------
        | Actualizar preview si hay nueva
        |--------------------------------------------------------------------------
        */

        $previewPath = $template->preview_image;

        if ($request->hasFile('preview_image')) {

            if ($previewPath) {
                Storage::disk('public')->delete($previewPath);
            }

            $previewPath = $request->file('preview_image')
                ->store($newFolder, 'public');
        }

        /*
        |--------------------------------------------------------------------------
        | Actualizar ZIP si hay nuevo
        |--------------------------------------------------------------------------
        */

        $zipPath = $template->main_file;

        if ($request->hasFile('template_zip')) {

            if ($zipPath) {
                Storage::disk('public')->delete($zipPath);
            }

            $zipPath = $request->file('template_zip')
                ->store($newFolder, 'public');

            $this->deleteDirectory(public_path("previews/$newSlug"));

            $this->extractPreview(
                Storage::disk('public')->path($zipPath),
                public_path("previews/$newSlug")
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Update
        |--------------------------------------------------------------------------
        */

        $template->update([
            'category_id'   => $request->category_id,
            'name'          => $request->name,
            'slug'          => $newSlug,
            'description'   => $request->description,
            'folder'        => $newFolder,
            'preview_image' => $previewPath,
            'main_file'     => $zipPath,
            'price'         => $request->price ?? 0,
            'plan_tier'          => $request->plan_tier ?? 'basic',
            'is_premium'         => $request->plan_tier !== 'basic',
            'is_featured'        => $request->has('is_featured'),
            'is_active'          => $request->has('is_active'),
            'photo_orientation'  => $request->photo_orientation ?: null,
        ]);

        return redirect()
            ->route('templates.index')
            ->with('success', 'Plantilla actualizada correctamente.');
    }

    public function destroy(Template $template)
    {
        Storage::disk('public')->deleteDirectory($template->folder);
        $this->deleteDirectory(public_path("previews/{$template->slug}"));

        $template->delete();

        return redirect()
            ->route('templates.index')
            ->with('success', 'Plantilla eliminada.');
    }

    private function extractPreview(string $zipAbsPath, string $destAbsPath): void
    {
        $zip = new ZipArchive();

        if ($zip->open($zipAbsPath) !== true) {
            return;
        }

        if (! is_dir($destAbsPath)) {
            mkdir($destAbsPath, 0755, true);
        }

        $zip->extractTo($destAbsPath);
        $zip->close();
    }

    private function deleteDirectory(string $dir): void
    {
        if (! is_dir($dir)) {
            return;
        }

        foreach (array_diff(scandir($dir), ['.', '..']) as $item) {
            $path = $dir . DIRECTORY_SEPARATOR . $item;
            is_dir($path) ? $this->deleteDirectory($path) : unlink($path);
        }

        rmdir($dir);
    }

    private function plansByTier(): array
    {
        $plans  = \App\Models\Plan::where('is_active', true)->get()->keyBy('slug');
        $result = [];
        foreach (Template::PLAN_TIERS as $tierKey => $_) {
            foreach (\App\Models\UserPurchase::TIER_ACCESS as $planSlug => $tiers) {
                if (end($tiers) === $tierKey && isset($plans[$planSlug])) {
                    $result[$tierKey] = $plans[$planSlug];
                    break;
                }
            }
        }
        return $result;
    }
}