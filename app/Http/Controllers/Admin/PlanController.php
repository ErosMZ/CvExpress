<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Plan;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PlanController extends Controller
{
    public function index()
    {
        $plans = Plan::orderBy('sort_order')->get();
        return view('admin.plans.index', compact('plans'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'        => ['required', 'string', 'max:255'],
            'price'       => ['required', 'numeric', 'min:0'],
            'color'       => ['required', 'string', 'max:50'],
            'badge_label' => ['nullable', 'string', 'max:100'],
            'features'    => ['nullable', 'string'],
            'sort_order'  => ['nullable', 'integer'],
            'category'    => ['nullable', 'in:template,web_download,pdf_download'],
        ]);

        $features = array_values(array_filter(
            array_map('trim', explode("\n", $request->features ?? ''))
        ));
        $category = $request->category ?: 'template';

        Plan::create([
            'slug'          => Str::slug($request->name),
            'name'          => $request->name,
            'price'         => $request->price,
            'billing_cycle' => $this->billingCycleFor($category),
            'color'         => $request->color,
            'badge_label'   => $request->badge_label ?: null,
            'features'      => $features,
            'is_active'     => $request->has('is_active'),
            'sort_order'    => $request->sort_order ?? 0,
            // El nivel de plantillas solo tiene sentido para planes con hosting.
            'template_tier' => $category === 'template' ? ($request->template_tier ?: null) : null,
            'category'      => $category,
        ]);

        return redirect()->route('admin.plans.index')
            ->with('success', "Plan «{$request->name}» creado correctamente.");
    }

    public function update(Request $request, Plan $plan)
    {
        $request->validate([
            'name'        => ['required', 'string', 'max:255'],
            'price'       => ['required', 'numeric', 'min:0'],
            'color'       => ['required', 'string', 'max:50'],
            'badge_label' => ['nullable', 'string', 'max:100'],
            'features'    => ['nullable', 'string'],
            'sort_order'  => ['nullable', 'integer'],
            'category'    => ['nullable', 'in:template,web_download,pdf_download'],
        ]);

        $features = array_values(array_filter(
            array_map('trim', explode("\n", $request->features ?? ''))
        ));
        $category = $request->category ?: 'template';

        $plan->update([
            'slug'          => Str::slug($request->name),
            'name'          => $request->name,
            'price'         => $request->price,
            'billing_cycle' => $this->billingCycleFor($category),
            'color'         => $request->color,
            'badge_label'   => $request->badge_label ?: null,
            'features'      => $features,
            'is_active'     => $request->has('is_active'),
            'sort_order'    => $request->sort_order ?? $plan->sort_order,
            'template_tier' => $category === 'template' ? ($request->template_tier ?: null) : null,
            'category'      => $category,
        ]);

        return redirect()->route('admin.plans.index')
            ->with('success', "Plan «{$plan->name}» actualizado correctamente.");
    }

    /**
     * Los planes de descarga (web o PDF) son de pago único; los de
     * plantillas con hosting se facturan anualmente.
     */
    private function billingCycleFor(string $category): string
    {
        return in_array($category, ['web_download', 'pdf_download'], true) ? 'once' : 'annual';
    }

    public function destroy(Plan $plan)
    {
        $name = $plan->name;
        $plan->delete();

        return redirect()->route('admin.plans.index')
            ->with('success', "Plan «{$name}» eliminado.");
    }
}
