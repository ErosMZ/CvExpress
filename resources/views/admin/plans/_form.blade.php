@php
    $colors = [
        ['value' => '#16a34a', 'bg' => '#dcfce7', 'label' => 'Verde'],
        ['value' => '#1A56DB', 'bg' => '#dbeafe', 'label' => 'Azul'],
        ['value' => '#7c3aed', 'bg' => '#ede9fe', 'label' => 'Púrpura'],
        ['value' => '#d97706', 'bg' => '#fef3c7', 'label' => 'Ámbar'],
        ['value' => '#db2777', 'bg' => '#fce7f3', 'label' => 'Rosa'],
        ['value' => '#0891b2', 'bg' => '#cffafe', 'label' => 'Cyan'],
        ['value' => '#475569', 'bg' => '#f1f5f9', 'label' => 'Gris'],
    ];
    $selectedColor = $plan->color ?? '#16a34a';
    $prefix = isset($edit) && $edit ? 'edit' : 'create';
@endphp

<div class="pm-form-grid">
    <div class="form-group" style="grid-column:1/-1;">
        <label class="form-label" for="{{ $prefix }}_name">Nombre del plan</label>
        <input type="text" id="{{ $prefix }}_name" name="name" class="form-input"
               value="{{ $plan->name ?? '' }}" placeholder="ej. Plan Pro" required>
    </div>

    <div class="form-group">
        <label class="form-label" for="{{ $prefix }}_price">Precio (€)</label>
        <input type="number" id="{{ $prefix }}_price" name="price" class="form-input"
               step="0.01" min="0" value="{{ $plan->price ?? '' }}" placeholder="0.00" required>
    </div>

    <div class="form-group">
        <label class="form-label" for="{{ $prefix }}_sort_order">Orden</label>
        <input type="number" id="{{ $prefix }}_sort_order" name="sort_order" class="form-input"
               min="0" value="{{ $plan->sort_order ?? 0 }}">
    </div>

    <div class="form-group" style="grid-column:1/-1;">
        <label class="form-label" for="{{ $prefix }}_badge_label">Etiqueta destacada <span style="font-weight:400;color:var(--admin-text-muted);">(opcional, ej. "Más popular")</span></label>
        <input type="text" id="{{ $prefix }}_badge_label" name="badge_label" class="form-input"
               value="{{ $plan->badge_label ?? '' }}" placeholder="Más popular">
    </div>
</div>

<div class="form-group" style="margin-top:.85rem;">
    <label class="form-label">Color del plan</label>
    <div class="color-palette">
        @foreach($colors as $c)
        <div class="color-swatch {{ $selectedColor === $c['value'] ? 'selected' : '' }}"
             style="background:{{ $c['bg'] }};"
             title="{{ $c['label'] }}">
            <input type="radio" name="color" value="{{ $c['value'] }}"
                   {{ $selectedColor === $c['value'] ? 'checked' : '' }}>
            <svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="{{ $c['value'] }}" stroke-width="3.5" style="pointer-events:none;">
                <polyline points="20 6 9 17 4 12"/>
            </svg>
        </div>
        @endforeach
    </div>
</div>

<div class="form-group" style="margin-top:.85rem;">
    <label class="form-label" for="{{ $prefix }}_features">
        Características
        <span style="font-weight:400;color:var(--admin-text-muted);">(una por línea)</span>
    </label>
    <textarea id="{{ $prefix }}_features" name="features" class="form-input"
              rows="7"
              placeholder="CV PDF convertido a web profesional&#10;1 plantilla moderna&#10;Subdominio propio&#10;Edición básica del contenido&#10;Enlace para compartir"
              style="resize:vertical;font-family:var(--font-mono);font-size:.82rem;">{{ $plan ? implode("\n", $plan->features ?? []) : '' }}</textarea>
</div>

<div class="form-group" style="margin-top:.85rem;">
    <label class="form-label" for="{{ $prefix }}_template_tier">
        Tier de plantillas
        <span style="font-weight:400;color:var(--admin-text-muted);">(deja vacío si este plan no está relacionado con plantillas)</span>
    </label>
    <select id="{{ $prefix }}_template_tier" name="template_tier" class="form-input">
        <option value="">— Sin tier de plantillas —</option>
        <option value="basic"     {{ ($plan->template_tier ?? '') === 'basic'     ? 'selected' : '' }}>Básico</option>
        <option value="pro"       {{ ($plan->template_tier ?? '') === 'pro'       ? 'selected' : '' }}>Pro</option>
        <option value="super_pro" {{ ($plan->template_tier ?? '') === 'super_pro' ? 'selected' : '' }}>Super Pro</option>
    </select>
    <p style="font-size:.75rem;color:var(--admin-text-muted);margin-top:.3rem;">
        Solo los planes con tier asignado aparecen en el selector de "Plan requerido" de las plantillas.
    </p>
</div>

<div class="form-group" style="margin-top:.75rem;">
    <label style="display:flex;align-items:center;gap:.6rem;cursor:pointer;user-select:none;">
        <input type="checkbox" name="is_active" value="1"
               {{ ($plan->is_active ?? true) ? 'checked' : '' }}
               style="width:16px;height:16px;accent-color:var(--blue-500);cursor:pointer;">
        <span style="font-size:.875rem;font-weight:500;color:var(--admin-text);">Plan activo (visible para usuarios)</span>
    </label>
</div>
