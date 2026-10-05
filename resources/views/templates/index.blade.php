@extends('layouts.app')

@section('title', 'Plantillas — CvXpress')
@section('meta_description', 'Explora nuestra colección de plantillas profesionales para tu portfolio web. Gratis y premium.')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/templates.css') }}">
@endpush

@section('content')

{{-- ── HERO ── --}}
<section class="tpl-hero">
    <div class="container">
        <div class="tpl-hero__label">
            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" aria-hidden="true"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
            Colección de plantillas
        </div>
        <h1 class="tpl-hero__title">
            Elige tu plantilla<br><em>perfecta</em>
        </h1>
        <p class="tpl-hero__subtitle">
            Diseños profesionales listos para usar. Personaliza con tu información y destaca ante cualquier reclutador.
        </p>
    </div>
</section>

{{-- ── FILTERS ── --}}
<div class="tpl-filters">
    <div class="container">
        <div class="tpl-filters__inner">
            <span class="tpl-filters__label">Filtrar:</span>

            <a href="{{ route('templates.list') }}"
               class="tpl-filter-btn {{ !request('tipo') && !request('category') ? 'active' : '' }}">
                Todas
            </a>

            <a href="{{ route('templates.list', ['tipo' => 'gratis']) }}"
               class="tpl-filter-btn {{ request('tipo') === 'gratis' ? 'active' : '' }}">
                Gratis
            </a>

            <a href="{{ route('templates.list', ['tipo' => 'premium']) }}"
               class="tpl-filter-btn {{ request('tipo') === 'premium' ? 'active' : '' }}">
                Premium
            </a>

            @if($categories->isNotEmpty())
                <div class="tpl-filter-sep" aria-hidden="true"></div>
                @foreach($categories as $cat)
                    <a href="{{ route('templates.list', ['category' => $cat->id]) }}"
                       class="tpl-filter-btn {{ request('category') == $cat->id ? 'active' : '' }}">
                        {{ $cat->name }}
                    </a>
                @endforeach
            @endif

            <div class="tpl-filter-sep" aria-hidden="true"></div>

            {{-- Sort --}}
            @php $baseParams = request()->except('sort'); @endphp
            <select class="tpl-sort-select {{ request('sort') ? 'active' : '' }}" id="tpl-sort">
                <option value="{{ route('templates.list', $baseParams) }}"
                        {{ !request('sort') ? 'selected' : '' }}>Más recientes</option>
                <option value="{{ route('templates.list', array_merge($baseParams, ['sort' => 'price_asc'])) }}"
                        {{ request('sort') === 'price_asc' ? 'selected' : '' }}>Precio: menor a mayor</option>
                <option value="{{ route('templates.list', array_merge($baseParams, ['sort' => 'price_desc'])) }}"
                        {{ request('sort') === 'price_desc' ? 'selected' : '' }}>Precio: mayor a menor</option>
            </select>

            <div class="tpl-filter-sep" aria-hidden="true"></div>

            {{-- Grid toggle --}}
            <div class="tpl-grid-toggle" aria-label="Columnas">
                <button class="tpl-grid-toggle-btn" id="btn-col3" title="3 columnas">
                    <svg width="14" height="14" viewBox="0 0 15 14" fill="currentColor" aria-hidden="true">
                        <rect x="0" y="0" width="4" height="14" rx="1"/>
                        <rect x="5.5" y="0" width="4" height="14" rx="1"/>
                        <rect x="11" y="0" width="4" height="14" rx="1"/>
                    </svg>
                </button>
                <button class="tpl-grid-toggle-btn" id="btn-col4" title="4 columnas">
                    <svg width="14" height="14" viewBox="0 0 19 14" fill="currentColor" aria-hidden="true">
                        <rect x="0" y="0" width="4" height="14" rx="1"/>
                        <rect x="5" y="0" width="4" height="14" rx="1"/>
                        <rect x="10" y="0" width="4" height="14" rx="1"/>
                        <rect x="15" y="0" width="4" height="14" rx="1"/>
                    </svg>
                </button>
            </div>

            <span class="tpl-filters__count">
                {{ $templates->count() }} {{ $templates->count() === 1 ? 'plantilla' : 'plantillas' }}
            </span>
        </div>
    </div>
</div>

{{-- ── GRID ── --}}
<section class="tpl-section">
    <div class="container">
        <div class="tpl-grid">
            @forelse($templates as $template)
            @php $previewHtml = $template->preview_html_url; @endphp
            <article class="tpl-card">

                {{-- Thumbnail --}}
                <div class="tpl-card__thumb">

                    @if($previewHtml)
                        {{-- Iframe escalado como miniatura --}}
                        <div class="tpl-card__iframe-wrap">
                            <iframe
                                src="{{ $previewHtml }}"
                                title="Vista previa {{ $template->name }}"
                                scrolling="no"
                                sandbox="allow-same-origin allow-scripts"
                                loading="lazy">
                            </iframe>
                        </div>
                    @elseif($template->preview_image)
                        <img src="{{ asset('storage/' . $template->preview_image) }}"
                             alt="Vista previa de {{ $template->name }}"
                             loading="lazy">
                    @else
                        <div class="tpl-card__thumb-placeholder">
                            <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" aria-hidden="true"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/></svg>
                        </div>
                    @endif

                    {{-- Badges --}}
                    <div class="tpl-card__badges">
                        @php $tier = $template->plan_tier ?? 'basic'; @endphp
                        @if($tier === 'super_pro')
                            <span class="tpl-badge tpl-badge--super">Super Pro</span>
                        @elseif($tier === 'pro')
                            <span class="tpl-badge tpl-badge--pro">Pro</span>
                        @endif
                        @if($template->is_featured)
                            <span class="tpl-badge tpl-badge--featured">Destacada</span>
                        @endif
                    </div>

                    {{-- Hover overlay --}}
                    <div class="tpl-card__overlay">
                        <a href="{{ route('templates.preview', $template->slug) }}"
                           class="tpl-overlay-btn tpl-overlay-btn--preview">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                            Vista previa
                        </a>
                        @if($activePurchase && $activePurchase->canUseTemplate($template) && $activePurchase->selected_template_id !== $template->id)
                            <form method="POST" action="{{ route('dashboard.template.select', [$activePurchase->id, $template->id]) }}">
                                @csrf
                                <button type="submit" class="tpl-overlay-btn tpl-overlay-btn--buy">
                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
                                    Usar plantilla
                                </button>
                            </form>
                        @elseif($activePurchase && $activePurchase->selected_template_id === $template->id)
                            <span class="tpl-overlay-btn tpl-overlay-btn--buy" style="opacity:.7;cursor:default;">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
                                En uso
                            </span>
                        @else
                            <a href="{{ route('templates.preview', $template->slug) }}#comprar"
                               class="tpl-overlay-btn tpl-overlay-btn--buy">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/></svg>
                                Ver plan
                            </a>
                        @endif
                    </div>
                </div>

                {{-- Body --}}
                <div class="tpl-card__body">
                    <div class="tpl-card__meta">
                        @if($template->category)
                            <span class="tpl-card__category">{{ $template->category->name }}</span>
                        @endif
                    </div>

                    <h2 class="tpl-card__name">{{ $template->name }}</h2>

                    @if($template->description)
                        <p class="tpl-card__desc">{{ $template->description }}</p>
                    @endif

                    @php
                        $canUse      = $activePurchase && $activePurchase->canUseTemplate($template);
                        $isActive    = $activePurchase && $activePurchase->selected_template_id === $template->id;
                        $tierPlan    = $plansByTier[$template->plan_tier] ?? null;
                        $canDownload = $template->price > 0;
                    @endphp

                    <div class="tpl-card__footer">
                        {{-- Plan info --}}
                        <div>
                            @if($canUse)
                                <div style="font-size:.76rem;font-weight:700;color:#16a34a;display:flex;align-items:center;gap:4px;">
                                    <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><polyline points="20 6 9 17 4 12"/></svg>
                                    Incluida en tu plan {{ $activePurchase->plan->name }}
                                </div>
                            @elseif($tierPlan)
                                <div style="font-size:.78rem;font-weight:700;color:var(--color-text-primary,#0f172a);">
                                    Con hosting: {{ $tierPlan->name }}
                                </div>
                            @else
                                <div style="font-size:.78rem;color:#16a34a;font-weight:700;">Gratuita</div>
                            @endif
                        </div>

                        <div class="tpl-card__actions">
                            <a href="{{ route('templates.preview', $template->slug) }}"
                               class="tpl-btn tpl-btn--outline" title="Ver plantilla completa">
                                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                                Ver
                            </a>

                            @if($isActive)
                                <span class="tpl-btn tpl-btn--used">
                                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
                                    En uso
                                </span>
                            @elseif($canUse)
                                <form method="POST" action="{{ route('dashboard.template.select', [$activePurchase->id, $template->id]) }}" style="display:inline;">
                                    @csrf
                                    <button type="submit" class="tpl-btn tpl-btn--primary">
                                        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
                                        Usar plantilla
                                    </button>
                                </form>
                            @elseif($activePurchase)
                                @if($tierPlan)
                                    <a href="{{ route('checkout.show', $tierPlan->slug) }}" class="tpl-btn tpl-btn--upgrade">
                                        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="17 11 12 6 7 11"/><polyline points="17 18 12 13 7 18"/></svg>
                                        Mejorar plan
                                    </a>
                                @endif
                            @else
                                {{-- Sin plan: un único botón "Comprar". El desplegable con los dos
                                     precios es un único elemento compartido al final de la página
                                     (ver #tpl-buy-menu) que JS coloca y rellena al pasar el ratón —
                                     así evitamos que la tarjeta necesite "overflow:visible" (eso
                                     rompía el tamaño de la miniatura). --}}
                                <button type="button" class="tpl-btn tpl-btn--primary tpl-buy__toggle"
                                    data-download-url="{{ $canDownload ? (auth()->check() ? route('checkout.download.show', $template->slug) : route('register')) : '' }}"
                                    data-download-price="{{ $canDownload ? number_format($template->price, 2, ',', '.').'€' : '' }}"
                                    data-hosting-url="{{ $tierPlan ? (auth()->check() ? route('checkout.show', $tierPlan->slug) : route('register')) : '' }}"
                                    data-hosting-name="{{ $tierPlan?->name }}"
                                    data-hosting-price="{{ $tierPlan ? number_format($tierPlan->price, 2, ',', '.').'€' : '' }}">
                                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"/><line x1="3" y1="6" x2="21" y2="6"/><path d="M16 10a4 4 0 0 1-8 0"/></svg>
                                    Comprar
                                    <svg class="chev" width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="6 9 12 15 18 9"/></svg>
                                </button>
                            @endif
                        </div>
                    </div>
                </div>
            </article>
            @empty
            <div class="tpl-empty">
                <svg width="56" height="56" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" aria-hidden="true"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
                <div class="tpl-empty__title">No hay plantillas disponibles</div>
                <div class="tpl-empty__text">Prueba a cambiar los filtros o vuelve pronto.</div>
            </div>
            @endforelse
        </div>
    </div>
</section>

{{-- Menú flotante compartido de "Comprar" (uno solo para todas las tarjetas,
     JS lo coloca con position:fixed junto al botón sobre el que pasa el ratón). --}}
<div class="tpl-buy__menu" id="tpl-buy-menu">
    <a href="#" class="tpl-buy__opt" id="tpl-buy-opt-download">
        <span class="tpl-buy__opt-label">
            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
            Solo descargar
        </span>
        <span class="tpl-buy__opt-price" id="tpl-buy-price-download"><small>pago único</small></span>
    </a>
    <div class="tpl-buy__divider" id="tpl-buy-divider" hidden></div>
    <a href="#" class="tpl-buy__opt" id="tpl-buy-opt-hosting">
        <span class="tpl-buy__opt-label">
            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><circle cx="12" cy="12" r="10"/><line x1="2" y1="12" x2="22" y2="12"/><path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"/></svg>
            <span id="tpl-buy-hosting-name">Con subdominio</span>
        </span>
        <span class="tpl-buy__opt-price" id="tpl-buy-price-hosting"><small>/año</small></span>
    </a>
</div>

@push('scripts')
<script>
// Iframe thumbnail scaling
document.querySelectorAll('.tpl-card__iframe-wrap').forEach(function(wrap) {
    var iframe = wrap.querySelector('iframe');
    if (!iframe) return;
    var scaleX = wrap.offsetWidth  / 1280;
    var scaleY = wrap.offsetHeight / 800;
    var scale  = Math.min(scaleX, scaleY);
    iframe.style.setProperty('--tpl-scale', scale);
    iframe.style.transform = 'scale(' + scale + ')';
    iframe.style.width  = '1280px';
    iframe.style.height = '800px';
});

// Sort dropdown navigation
var sortSel = document.getElementById('tpl-sort');
if (sortSel) {
    sortSel.addEventListener('change', function () { window.location = this.value; });
}

// Grid column toggle
(function () {
    var grid = document.querySelector('.tpl-grid');
    var btn3 = document.getElementById('btn-col3');
    var btn4 = document.getElementById('btn-col4');
    if (!grid || !btn3 || !btn4) return;

    function setGrid(cols) {
        grid.classList.remove('tpl-grid--3', 'tpl-grid--4');
        grid.classList.add('tpl-grid--' + cols);
        btn3.classList.toggle('active', cols === 3);
        btn4.classList.toggle('active', cols === 4);
        try { localStorage.setItem('tpl-grid-cols', cols); } catch (e) {}
    }

    var saved = 3;
    try { saved = parseInt(localStorage.getItem('tpl-grid-cols')) || 3; } catch (e) {}
    setGrid(saved);

    btn3.addEventListener('click', function () { setGrid(3); });
    btn4.addEventListener('click', function () { setGrid(4); });
})();

// Menú flotante "Comprar" — un único elemento compartido, posicionado con
// getBoundingClientRect() junto al botón, para no depender de que las
// tarjetas tengan overflow:visible (eso rompía el tamaño de la miniatura).
(function () {
    var menu = document.getElementById('tpl-buy-menu');
    if (!menu) return;

    var optDownload   = document.getElementById('tpl-buy-opt-download');
    var priceDownload = document.getElementById('tpl-buy-price-download');
    var divider       = document.getElementById('tpl-buy-divider');
    var optHosting    = document.getElementById('tpl-buy-opt-hosting');
    var priceHosting  = document.getElementById('tpl-buy-price-hosting');
    var hostingName   = document.getElementById('tpl-buy-hosting-name');

    var hideTimer = null;
    var activeToggle = null;

    function position(toggle) {
        var r = toggle.getBoundingClientRect();
        var menuWidth = 232;
        var left = Math.min(Math.max(8, r.right - menuWidth), window.innerWidth - menuWidth - 8);
        var top  = r.top - 8; // se ancla por abajo (bottom), ver translateY
        menu.style.left = left + 'px';
        // Por defecto el menú se dibuja hacia ARRIBA del botón (translateY controla el fade,
        // así que fijamos "bottom" real vía top = borde superior del botón).
        menu.style.top = 'auto';
        menu.style.bottom = (window.innerHeight - r.top + 8) + 'px';
    }

    function show(toggle) {
        clearTimeout(hideTimer);
        activeToggle = toggle;

        var dlUrl   = toggle.dataset.downloadUrl;
        var dlPrice = toggle.dataset.downloadPrice;
        var hUrl    = toggle.dataset.hostingUrl;
        var hName   = toggle.dataset.hostingName;
        var hPrice  = toggle.dataset.hostingPrice;

        if (dlUrl) {
            optDownload.style.display = '';
            optDownload.href = dlUrl;
            priceDownload.innerHTML = dlPrice + '<small>pago único</small>';
        } else {
            optDownload.style.display = 'none';
        }

        if (hUrl) {
            optHosting.style.display = '';
            optHosting.href = hUrl;
            hostingName.textContent = hName ? ('Con subdominio: ' + hName) : 'Con subdominio';
            priceHosting.innerHTML = hPrice + '<small>/año</small>';
        } else {
            optHosting.style.display = 'none';
        }

        divider.hidden = !(dlUrl && hUrl);

        document.querySelectorAll('.tpl-buy__toggle.is-open').forEach(function (t) { t.classList.remove('is-open'); });
        toggle.classList.add('is-open');
        position(toggle);
        menu.classList.add('is-visible');
    }

    function scheduleHide() {
        clearTimeout(hideTimer);
        hideTimer = setTimeout(function () {
            menu.classList.remove('is-visible');
            if (activeToggle) activeToggle.classList.remove('is-open');
            activeToggle = null;
        }, 150);
    }

    document.querySelectorAll('.tpl-buy__toggle').forEach(function (toggle) {
        toggle.addEventListener('mouseenter', function () { show(toggle); });
        toggle.addEventListener('focus', function () { show(toggle); });
        toggle.addEventListener('mouseleave', scheduleHide);
        toggle.addEventListener('blur', scheduleHide);
    });
    menu.addEventListener('mouseenter', function () { clearTimeout(hideTimer); });
    menu.addEventListener('mouseleave', scheduleHide);

    window.addEventListener('scroll', function () {
        if (activeToggle && menu.classList.contains('is-visible')) position(activeToggle);
    }, true);
})();
</script>
@endpush

@endsection
