<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Template;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class TemplateController extends Controller
{
    public function index()
    {
        $templates = Template::with('category')->latest()->get();

        return view('admin.templates.index', compact('templates'));
    }

    public function create()
    {
        $categories = Category::where('is_active', true)->get();

        return view('admin.templates.create', compact('categories'));
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
        | Crear plantilla
        |--------------------------------------------------------------------------
        */

        Template::create([

            'category_id' => $request->category_id,

            'name' => $request->name,

            'slug' => $slug,

            'description' => $request->description,

            'folder' => $folderPath,

            'preview_image' => $previewPath,

            'main_file' => $zipPath,

            'price' => $request->price ?? 0,

            'is_premium' => $request->has('is_premium'),

            'is_featured' => $request->has('is_featured'),

            'is_active' => $request->has('is_active'),

        ]);

        return redirect()
            ->route('templates.index')
            ->with('success', 'Plantilla creada correctamente.');
    }

    public function edit(Template $template)
    {
        $categories = Category::where('is_active', true)->get();

        return view('admin.templates.edit', compact('template', 'categories'));
    }

    public function update(Request $request, Template $template)
    {
        $request->validate([
            'name' => 'required',
            'category_id' => 'nullable|exists:categories,id',
            'price' => 'nullable|numeric|min:0',
        ]);

        $oldFolder = $template->folder;

        $newSlug = Str::slug($request->name);

        $newFolder = "templates/$newSlug";

        /*
        |--------------------------------------------------------------------------
        | Renombrar carpeta
        |--------------------------------------------------------------------------
        */

        if ($oldFolder !== $newFolder) {

            Storage::disk('public')
                ->move($oldFolder, $newFolder);
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
        }

        /*
        |--------------------------------------------------------------------------
        | Update
        |--------------------------------------------------------------------------
        */

        $template->update([

            'category_id' => $request->category_id,

            'name' => $request->name,

            'slug' => $newSlug,

            'description' => $request->description,

            'folder' => $newFolder,

            'preview_image' => $previewPath,

            'main_file' => $zipPath,

            'price' => $request->price ?? 0,

            'is_premium' => $request->has('is_premium'),

            'is_featured' => $request->has('is_featured'),

            'is_active' => $request->has('is_active'),

        ]);

        return redirect()
            ->route('templates.index')
            ->with('success', 'Plantilla actualizada correctamente.');
    }

    public function destroy(Template $template)
    {
        Storage::disk('public')->deleteDirectory($template->folder);

        $template->delete();

        return redirect()
            ->route('templates.index')
            ->with('success', 'Plantilla eliminada.');
    }
}