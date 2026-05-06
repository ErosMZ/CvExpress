<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Template;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Models\Category;

class TemplateController extends Controller
{
    public function index()
    {
        $templates = Template::all();

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
            'name' => 'required',
            'description' => 'nullable',
            'preview_image' => 'nullable|image',
            'template_zip' => 'required|file|mimes:zip',
        ]);

        $slug = Str::slug($request->name);

        $folderPath = "templates/$slug";

        Storage::disk('public')->makeDirectory($folderPath);

        // Guardar preview
        $previewPath = null;

        if ($request->hasFile('preview_image')) {

            $previewPath = $request->file('preview_image')
                ->store($folderPath, 'public');
        }

        // Guardar ZIP
        $zipPath = $request->file('template_zip')
            ->store($folderPath, 'public');

        Template::create([
            'name' => $request->name,
            'slug' => $slug,
            'description' => $request->description,
            'folder' => $folderPath,
            'preview_image' => $previewPath,
        ]);

        return redirect()->route('templates.index');
    }

    public function edit(Template $template)
    {
        return view('admin.templates.edit', compact('template'));
    }

    public function update(Request $request, Template $template)
    {
        $request->validate([
            'name' => 'required',
        ]);

        $oldFolder = $template->folder;

        $newSlug = Str::slug($request->name);

        $newFolder = "templates/$newSlug";

        // Renombrar carpeta
        if ($oldFolder !== $newFolder) {

            Storage::disk('public')
                ->move($oldFolder, $newFolder);
        }

        $template->update([
            'name' => $request->name,
            'slug' => $newSlug,
            'folder' => $newFolder,
        ]);

        return redirect()->route('templates.index');
    }

    public function destroy(Template $template)
    {
        Storage::disk('public')->deleteDirectory($template->folder);

        $template->delete();

        return redirect()->route('templates.index');
    }
}