<?php

namespace App\Http\Controllers;

use App\Models\Template;
use App\Models\Category;
use Illuminate\Http\Request;

class TemplatesController extends Controller
{
    public function index(Request $request)
    {
        $query = Template::with('category')
            ->where('is_active', true)
            ->latest();

        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }

        if ($request->filled('tipo')) {
            if ($request->tipo === 'premium') {
                $query->where('is_premium', true);
            } elseif ($request->tipo === 'gratis') {
                $query->where('is_premium', false);
            }
        }

        $templates  = $query->get();
        $categories = Category::where('is_active', true)->orderBy('name')->get();

        return view('templates.index', compact('templates', 'categories'));
    }

    public function show(Template $template)
    {
        abort_if(! $template->is_active, 404);

        return view('templates.show', compact('template'));
    }
}
