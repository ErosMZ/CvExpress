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
        ]);

        $features = array_values(array_filter(
            array_map('trim', explode("\n", $request->features ?? ''))
        ));

        Plan::create([
            'slug'          => Str::slug($request->name),
            'name'          => $request->name,
            'price'         => $request->price,
            'color'         => $request->color,
            'badge_label'   => $request->badge_label ?: null,
            'features'      => $features,
            'is_active'     => $request->has('is_active'),
            'sort_order'    => $request->sort_order ?? 0,
            'template_tier' => $request->template_tier ?: null,
            'category'      => $request->category ?: 'template',
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
        ]);

        $features = array_values(array_filter(
            array_map('trim', explode("\n", $request->features ?? ''))
        ));

        $plan->update([
            'slug'          => Str::slug($request->name),
            'name'          => $request->name,
            'price'         => $request->price,
            'color'         => $request->color,
            'badge_label'   => $request->badge_label ?: null,
            'features'      => $features,
            'is_active'     => $request->has('is_active'),
            'sort_order'    => $request->sort_order ?? $plan->sort_order,
            'template_tier' => $request->template_tier ?: null,
            'category'      => $request->category ?: 'template',
        ]);

        return redirect()->route('admin.plans.index')
            ->with('success', "Plan «{$plan->name}» actualizado correctamente.");
    }

    public function destroy(Plan $plan)
    {
        $name = $plan->name;
        $plan->delete();

        return redirect()->route('admin.plans.index')
            ->with('success', "Plan «{$name}» eliminado.");
    }
}
