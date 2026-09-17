<?php

namespace App\Http\Controllers;

use App\Models\Template;
use App\Models\Category;
use App\Models\UserPurchase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TemplatesController extends Controller
{
    public function index(Request $request)
    {
        $query = Template::with('category')
            ->where('is_active', true);

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

        if ($request->sort === 'price_asc') {
            $query->orderBy('price', 'asc');
        } elseif ($request->sort === 'price_desc') {
            $query->orderBy('price', 'desc');
        } else {
            $query->latest();
        }

        $templates      = $query->get();
        $categories     = Category::where('is_active', true)->orderBy('name')->get();
        $activePurchase = Auth::check()
            ? Auth::user()->activePurchase?->load(['plan', 'selectedTemplate'])
            : null;

        // Plan mínimo requerido por tier, para la opción "con hosting".
        // La opción "solo descargar" usa el precio propio de cada plantilla
        // ($template->price), no hace falta ningún plan para eso.
        $plansByTier = $this->plansByTier();

        return view('templates.index', compact('templates', 'categories', 'activePurchase', 'plansByTier'));
    }

    public function show(Template $template)
    {
        abort_if(! $template->is_active, 404);

        $activePurchase = Auth::check()
            ? Auth::user()->activePurchase?->load(['plan', 'selectedTemplate'])
            : null;

        $plansByTier = $this->plansByTier();

        return view('templates.show', compact('template', 'activePurchase', 'plansByTier'));
    }

    private function plansByTier(): array
    {
        return \App\Models\Plan::where('is_active', true)
            ->whereNotNull('template_tier')
            ->get()
            ->keyBy('template_tier')
            ->all();
    }
}
