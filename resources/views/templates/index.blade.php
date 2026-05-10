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
                        @if($template->is_premium)
                            <span class="tpl-badge tpl-badge--premium">Premium</span>
                        @else
                            <span class="tpl-badge tpl-badge--free">Gratis</span>
                        @endif
                        @if($template->is_featured)
                            <span class="tpl-badge tpl-badge--featured">Destacada</span>
                        @endif
                    </div>

                    {{-- Hover overlay --}}
                    <div class="tpl-card__overlay">
                        <a href="{{ route('templates.preview', $template->slug) }}"
                           target="_blank"
                           rel="noopener"
                           class="tpl-overlay-btn tpl-overlay-btn--preview">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                            Vista previa
                        </a>
                        <a href="{{ route('templates.preview', $template->slug) }}#comprar"
                           target="_blank"
                           rel="noopener"
                           class="tpl-overlay-btn tpl-overlay-btn--buy">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/></svg>
                            Comprar
                        </a>
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

                    <div class="tpl-card__footer">
                        <div class="tpl-card__price {{ $template->price > 0 ? 'tpl-card__price--paid' : 'tpl-card__price--free' }}">
                            @if($template->price > 0)
                                €{{ number_format($template->price, 2) }}
                            @else
                                Gratis
                            @endif
                        </div>

                        <div class="tpl-card__actions">
                            <a href="{{ route('templates.preview', $template->slug) }}"
                               target="_blank"
                               rel="noopener"
                               class="tpl-btn tpl-btn--outline"
                               title="Ver plantilla completa en nueva pestaña">
                                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"/><polyline points="15 3 21 3 21 9"/><line x1="10" y1="14" x2="21" y2="3"/></svg>
                                Ver
                            </a>
                            <a href="{{ route('templates.preview', $template->slug) }}#comprar"
                               target="_blank"
                               rel="noopener"
                               class="tpl-btn tpl-btn--primary">
                                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/></svg>
                                Comprar
                            </a>
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

@push('scripts')
<script>
// Escala los iframes para que encajen en el thumbnail
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
</script>
@endpush

@endsection
