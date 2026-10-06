@extends('layouts.app')

@section('title', 'CvXpress — Convierte tu Currículum en un portfolio web profesional')
@section('meta_description', 'Sube tu Currículum en PDF y en minutos tendrás un portfolio web profesional alojado, editable y listo para compartir. Próximamente: publicación automática en LinkedIn.')

@section('content')

<!-- ===================== HERO ===================== -->
<section class="hero" aria-labelledby="hero-title">
    <div class="hero__bg-grid" aria-hidden="true"></div>
    <div class="hero__blob hero__blob--1" aria-hidden="true"></div>
    <div class="hero__blob hero__blob--2" aria-hidden="true"></div>

    <div class="container hero__container">
        <div class="hero__text">
            <div class="badge badge--feature" role="status">
                <span class="badge__dot" aria-hidden="true"></span>
                Tu CV convertido en portfolio web
            </div>

            <h1 class="hero__title" id="hero-title">
                Tu Currículum se merece  <em>una  web propia</em><br>
            </h1>

            <p class="hero__subtitle">
                Sube tu PDF, nosotros hacemos el resto. En minutos tendrás un portfolio web profesional alojado, editable y listo para destacar en el mercado laboral.
            </p>
 
            <div class="hero__actions">
                <a href="{{ auth()->check() ? route('templates.list') : route('register') }}" class="btn btn--primary btn--lg">
                    Crear mi CV Web
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
                </a>
                <a href="#crear-cv" class="btn btn--ghost btn--lg">
                    Crear CV PDF
                </a>
            </div>

            <div class="hero__social-proof">
                <div class="hero__avatars" aria-hidden="true">
                    <span class="avatar">JM</span>
                    <span class="avatar">SR</span>
                    <span class="avatar">AL</span>
                    <span class="avatar">PG</span>
                </div>
                <p><strong>+500 Estudiantes y profesionales</strong> ya tienen su portfolio web</p>
            </div>
        </div>

    </div>
</section>

<!-- ===================== VÍDEO LIGADO AL SCROLL ===================== -->
<section class="scrollvid" aria-label="Cómo funciona CvXpress">
    <div class="scrollvid__sticky">
        <video class="scrollvid__video" id="scrollVideo"
               src="{{ route('video.indice') }}?v={{ filemtime(resource_path('videos/videoIndice_smooth.mp4')) }}"
               muted playsinline preload="auto" aria-hidden="true"></video>
        <div class="scrollvid__veil" aria-hidden="true"></div>

        <div class="scrollvid__steps" aria-hidden="true">
            <div class="scrollvid__step" data-from="0" data-to="0.34">
                <span class="scrollvid__num">01</span> Sube tu CV en PDF
            </div>
            <div class="scrollvid__step" data-from="0.34" data-to="0.67">
                <span class="scrollvid__num">02</span> Elige tu plantilla
            </div>
            <div class="scrollvid__step" data-from="0.67" data-to="1">
                <span class="scrollvid__num">03</span> Publica tu web
            </div>
        </div>

        <div class="scrollvid__progress" aria-hidden="true"><span id="scrollVideoBar"></span></div>
    </div>
</section>

<!-- ===================== TRUST BAR ===================== -->
<section class="trust-bar" aria-label="Garantías del servicio">
    <div class="container">
        <ul class="trust-bar__list" role="list">
            <li class="trust-bar__item">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
                Datos seguros y protegidos
            </li>
            <li class="trust-bar__item">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                Portfolio listo en menos de 2 minutos
            </li>
            <li class="trust-bar__item">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                Panel de edición completo
            </li>
            <li class="trust-bar__item">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><rect x="2" y="3" width="20" height="14" rx="2"/><line x1="8" y1="21" x2="16" y2="21"/><line x1="12" y1="17" x2="12" y2="21"/></svg>
                Hosting incluido en tu plan
            </li>
        </ul>
    </div>
</section>


<!-- ===================== MARQUEE PLANTILLAS DESTACADAS ===================== -->
@if($featuredTemplates->isNotEmpty())
<section class="marquee-section" aria-labelledby="marquee-title">
    <div class="container">
        <div class="marquee-section__header">
            <div class="label">Plantillas destacadas</div>
            <h2 class="section__title" id="marquee-title">Diseños que marcan<br><em>la diferencia</em></h2>
            <a href="{{ route('templates.list') }}" class="btn btn--ghost btn--sm" style="margin-top:.75rem;">
                Ver todas las plantillas →
            </a>
        </div>
    </div>

    {{-- Tarjetas en parejas que suben o bajan en cascada (orden desordenado).
         Cada pareja tiene su retardo y su sentido; la animación es solo CSS. --}}
    {{-- Siempre 8 huecos: con 8 o más plantillas salen todas distintas; con menos,
         se repiten en orden para que el carrusel nunca se vea vacío. --}}
    @php
        $pool  = $featuredTemplates->shuffle()->values();
        $slots = collect(range(0, 7))->map(fn ($n) => $pool[$n % $pool->count()]);
    @endphp
    {{-- 4 columnas: 1.ª y 3.ª suben, 2.ª y 4.ª bajan. Cada columna lleva sus 2 tarjetas
         duplicadas para que el bucle sea continuo sin saltos. --}}
    <div class="casc" aria-hidden="true">
        @foreach([0, 1, 2, 3] as $col)
        <div class="casc__col casc__col--{{ $col % 2 ? 'down' : 'up' }}"
             style="--dur: {{ [22, 26, 24, 28][$col] }}s; --offset: {{ [-6, -13, -3, -18][$col] }}s">
        <div class="casc__track">
        @for($copy = 0; $copy < 4; $copy++)
        @foreach($slots->slice($col * 2, 2) as $tpl)
        <a href="{{ route('templates.preview', $tpl->slug) }}"
           target="_blank" rel="noopener"
           class="mq-card casc__card">
            <div class="mq-card__thumb">
                @if($tpl->preview_html_url)
                    <div class="mq-card__iframe-wrap">
                        <iframe
                            src="{{ $tpl->preview_html_url }}"
                            scrolling="no"
                            sandbox="allow-same-origin allow-scripts"
                            loading="lazy"
                            title="{{ $tpl->name }}">
                        </iframe>
                    </div>
                @elseif($tpl->preview_image)
                    <img src="{{ asset('storage/' . $tpl->preview_image) }}"
                         alt="{{ $tpl->name }}"
                         loading="lazy">
                @else
                    <div class="mq-card__placeholder">
                        <svg width="36" height="36" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" opacity=".3"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
                    </div>
                @endif

                @if($tpl->is_premium)
                <div class="mq-card__badge mq-card__badge--premium">Premium</div>
                @endif
            </div>

            <div class="mq-card__body">
                @if($tpl->category)
                    <span class="mq-card__cat">{{ $tpl->category->name }}</span>
                @endif
                <div class="mq-card__name">{{ $tpl->name }}</div>
                @if($tpl->price > 0)
                    <div class="mq-card__price">€{{ number_format($tpl->price, 2) }}</div>
                @endif
            </div>
        </a>
        @endforeach
        @endfor
        </div>
        </div>
        @endforeach
    </div>
</section>
@endif

<!-- ===================== CARACTERÍSTICAS ===================== -->
<section class="section section--alt" id="caracteristicas" aria-labelledby="features-title">
    <div class="container">
        <div class="section__header">
            <div class="label">¿Por qué CvXpress?</div>
            <h2 class="section__title" id="features-title">Una plataforma completa para tu presencia digital</h2>
            <p class="section__subtitle">Transforma tu CV en un portfolio web profesional que trabaja por ti las 24h. Más visibilidad, más oportunidades de trabajo y una imagen que deja huella en cada reclutador que te busque online.</p>
        </div>

        <div class="features features--four-col">
            <article class="feature">
                <div class="feature__icon feature__icon--blue" aria-hidden="true">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><circle cx="11" cy="11" r="8"/><path d="M21 21l-4.35-4.35"/></svg>
                </div>
                <h3 class="feature__title">Más visibilidad online</h3>
                <p class="feature__desc">Tu portfolio aparece en Google, LinkedIn y buscadores. Los reclutadores te encuentran aunque no estés buscando activamente.</p>
            </article>

            <article class="feature">
                <div class="feature__icon feature__icon--teal" aria-hidden="true">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                </div>
                <h3 class="feature__title">Más oportunidades de trabajo</h3>
                <p class="feature__desc">Comparte tu URL en cada candidatura y destaca entre cientos de CV en PDF. Una imagen profesional que genera confianza desde el primer clic.</p>
            </article>

            <article class="feature">
                <div class="feature__icon feature__icon--blue" aria-hidden="true">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><circle cx="12" cy="12" r="3"/><path d="M12 1v2M12 21v2M4.22 4.22l1.42 1.42M18.36 18.36l1.42 1.42M1 12h2M21 12h2M4.22 19.78l1.42-1.42M18.36 5.64l1.42-1.42"/></svg>
                </div>
                <h3 class="feature__title">Listo en minutos con IA</h3>
                <p class="feature__desc">Sube tu CV y nuestra IA extrae toda la información automáticamente. Hosting, HTTPS y dominio incluidos. Sin instalar nada ni tocar código.</p>
            </article>

            <article class="feature">
                <div class="feature__icon feature__icon--teal" aria-hidden="true">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                </div>
                <h3 class="feature__title">Actualízalo cuando quieras</h3>
                <p class="feature__desc">Panel de edición para añadir proyectos, cambiar experiencia o elegir una plantilla nueva. Tu portfolio siempre al día sin volver a subir el CV.</p>
            </article>
        </div>
    </div>
</section>

<!-- ===================== PRECIOS ===================== -->
<section class="section" id="precios" aria-labelledby="pricing-title">
    <div class="container">
        <div class="section__header">
            <h2 class="section__title" id="pricing-title">Planes anuales.<br><em>Todo incluido, sin sorpresas.</em></h2>
            <p class="section__subtitle">Elige el plan que mejor se adapte a ti. Facturación anual, cancela cuando quieras.</p>
        </div>

        <div class="pricing-grid">

            @foreach($plans as $plan)
            @php
                $slugIcons = [
                    'basic'     => '<path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/>',
                    'pro'       => '<polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/>',
                    'super_pro' => '<path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>',
                ];
                $colorMap = [
                    '#16a34a' => ['bg' => '#dcfce7', 'fg' => '#16a34a', 'cta' => 'basic'],
                    '#1A56DB' => ['bg' => '#dbeafe', 'fg' => '#1A56DB', 'cta' => 'pro'],
                    '#7c3aed' => ['bg' => '#ede9fe', 'fg' => '#7c3aed', 'cta' => 'super'],
                    '#d97706' => ['bg' => '#fef3c7', 'fg' => '#d97706', 'cta' => 'basic'],
                    '#db2777' => ['bg' => '#fce7f3', 'fg' => '#db2777', 'cta' => 'basic'],
                    '#0891b2' => ['bg' => '#cffafe', 'fg' => '#0891b2', 'cta' => 'basic'],
                    '#475569' => ['bg' => '#f1f5f9', 'fg' => '#475569', 'cta' => 'basic'],
                ];
                $tc   = $colorMap[$plan->color] ?? ['bg' => '#f1f5f9', 'fg' => '#475569', 'cta' => 'basic'];
                $icon = $slugIcons[$plan->slug] ?? $slugIcons['basic'];
                $isFeatured = $plan->badge_label !== null;
            @endphp
            <div class="pricing-card {{ $isFeatured ? 'pricing-card--featured' : '' }}">
                @if($isFeatured)
                    <div class="pricing-card__badge">{{ $plan->badge_label }}</div>
                @endif
                <div class="pricing-card__header">
                    <div class="pricing-card__icon" style="background:{{ $tc['bg'] }};color:{{ $tc['fg'] }}">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">{!! $icon !!}</svg>
                    </div>
                    <div>
                        <div class="pricing-card__name">{{ $plan->name }}</div>
                        <div class="pricing-card__tagline">Facturado anualmente</div>
                    </div>
                </div>
                <div class="pricing-card__price">
                    <span class="pricing-card__amount">{{ number_format($plan->price, 2, ',', '.') }}€</span>
                    <span class="pricing-card__once">/año</span>
                </div>
                <ul class="pricing-card__features">
                    @foreach($plan->features as $feat)
                    <li>
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="{{ $tc['fg'] }}" stroke-width="2.5" aria-hidden="true"><polyline points="20 6 9 17 4 12"/></svg>
                        {{ $feat }}
                    </li>
                    @endforeach
                </ul>
                @auth
                    <a href="{{ route('checkout.show', $plan->slug) }}" class="pricing-card__cta pricing-card__cta--{{ $tc['cta'] }}">
                        Empezar por {{ number_format($plan->price, 2, ',', '.') }}€/año
                    </a>
                @else
                    <a href="{{ route('login') }}?redirect={{ urlencode(route('checkout.show', $plan->slug)) }}" class="pricing-card__cta pricing-card__cta--{{ $tc['cta'] }}">
                        Empezar por {{ number_format($plan->price, 2, ',', '.') }}€/año
                    </a>
                @endauth
            </div>
            @endforeach

        </div>

        {{-- Hosting opcional --}}
        <div class="pricing-hosting">
            <div class="pricing-hosting__inner">
                <div class="pricing-hosting__icon">
                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><rect x="2" y="3" width="20" height="14" rx="2"/><line x1="8" y1="21" x2="16" y2="21"/><line x1="12" y1="17" x2="12" y2="21"/></svg>
                </div>
                <div class="pricing-hosting__content">
                    <div class="pricing-hosting__title">Hosting opcional — 2-3€/mes</div>
                    <p class="pricing-hosting__desc">Para quienes prefieren comodidad total. Hosting, backups, SSL, CDN y dominio conectado. <strong>Sin obligación</strong> — también puedes usar tu subdominio gratis o tu propio hosting.</p>
                    <div class="pricing-hosting__chips">
                        <span>✓ Hosting gestionado</span>
                        <span>✓ Backups automáticos</span>
                        <span>✓ SSL incluido</span>
                        <span>✓ CDN global</span>
                        <span>✓ Dominio conectado</span>
                    </div>
                </div>
                <div class="pricing-hosting__price">
                    <span>2-3€</span>
                    <small>/mes</small>
                </div>
            </div>
        </div>

    </div>
</section>

<!-- ===================== ¿AÚN NO TIENES CV EN PDF? ===================== -->
<section class="no-pdf-section section--alt" id="crear-cv" aria-labelledby="no-pdf-title">
    <div class="container">
        <div class="no-pdf__inner">

            {{-- Visual: form mockup --}}
            <div class="no-pdf__visual" aria-hidden="true">
                <div class="no-pdf__card">
                    <div class="no-pdf__card-bar">
                        <span class="no-pdf__dot no-pdf__dot--r"></span>
                        <span class="no-pdf__dot no-pdf__dot--y"></span>
                        <span class="no-pdf__dot no-pdf__dot--g"></span>
                        <span>Crear mi CV — CvExpress</span>
                    </div>
                    <div class="no-pdf__fields">
                        <div class="no-pdf__field">
                            <div class="no-pdf__field-lbl"></div>
                            <div class="no-pdf__field-inp"></div>
                        </div>
                        <div class="no-pdf__field">
                            <div class="no-pdf__field-lbl"></div>
                            <div class="no-pdf__field-inp"></div>
                        </div>
                        <div class="no-pdf__field-row">
                            <div class="no-pdf__field">
                                <div class="no-pdf__field-lbl"></div>
                                <div class="no-pdf__field-inp"></div>
                            </div>
                            <div class="no-pdf__field">
                                <div class="no-pdf__field-lbl"></div>
                                <div class="no-pdf__field-inp"></div>
                            </div>
                        </div>
                        <div class="no-pdf__field no-pdf__field--section">
                            <div class="no-pdf__field-lbl no-pdf__field-lbl--section"></div>
                            <div class="no-pdf__field-inp no-pdf__field-inp--area"></div>
                        </div>
                        <div class="no-pdf__card-footer">
                            <div class="no-pdf__card-btn"></div>
                        </div>
                    </div>
                </div>
                <div class="no-pdf__export-chip">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" aria-hidden="true">
                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                        <polyline points="14 2 14 8 20 8"/>
                        <line x1="12" y1="12" x2="12" y2="18"/>
                        <polyline points="9 15 12 18 15 15"/>
                    </svg>
                    Exportar a PDF
                </div>
            </div>

            {{-- Content --}}
            <div class="no-pdf__content">
                <div class="badge badge--feature" role="status">
                    <span class="badge__dot" aria-hidden="true"></span>
                    Disponible ahora
                </div>
                <h2 class="no-pdf__title" id="no-pdf-title">
                    Crea tu CV<br><em>en PDF gratis</em>
                </h2>
                <p class="no-pdf__desc">
                    Rellena tus datos, ve los cambios en tiempo real y descarga tu currículum profesional en PDF con un solo clic.
                </p>
                <ul class="no-pdf__feats" role="list">
                    <li>
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" aria-hidden="true"><polyline points="20 6 9 17 4 12"/></svg>
                        Plantillas de CV profesionales incluidas
                    </li>
                    <li>
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" aria-hidden="true"><polyline points="20 6 9 17 4 12"/></svg>
                        Exportación a PDF en un clic
                    </li>
                    <li>
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" aria-hidden="true"><polyline points="20 6 9 17 4 12"/></svg>
                        Integrado con tu portfolio web
                    </li>
                </ul>
                <div style="display:flex;gap:.75rem;flex-wrap:wrap;align-items:center;">
                    @auth
                        <a href="{{ route('cv-pdf.editor') }}" class="btn btn--primary btn--lg">
                            Crear CV PDF
                            <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
                        </a>
                    @else
                        <a href="{{ route('cv-pdf.editor') }}" class="btn btn--primary btn--lg">
                            Crear CV PDF
                            <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
                        </a>
                        <a href="{{ route('register') }}" class="btn btn--ghost btn--lg">
                            Crear cuenta gratis
                        </a>
                    @endauth
                </div>
            </div>

        </div>
    </div>
</section>

<!-- ===================== LINKEDIN COMING SOON ===================== -->
<section class="linkedin-preview" aria-labelledby="linkedin-title">
    <div class="container">
        <div class="linkedin-preview__inner">
            <div class="linkedin-preview__content">
                <div class="badge badge--soon-white">Próximamente</div>
                <h2 class="linkedin-preview__title" id="linkedin-title">Automatiza tu presencia en LinkedIn</h2>
                <p class="linkedin-preview__desc">
                    Estamos trabajando en una integración con LinkedIn para que puedas publicar actualizaciones de tu portfolio, logros y novedades de forma automática. Tu red sabrá siempre lo que estás haciendo sin que tengas que hacerlo tú.
                </p>
                <ul class="linkedin-preview__list" role="list">
                    <li>
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" aria-hidden="true"><polyline points="20 6 9 17 4 12"/></svg>
                        Publicaciones automáticas cuando actualizas tu perfil
                    </li>
                    <li>
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" aria-hidden="true"><polyline points="20 6 9 17 4 12"/></svg>
                        Comparte proyectos y logros con un clic
                    </li>
                    <li>
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" aria-hidden="true"><polyline points="20 6 9 17 4 12"/></svg>
                        Programación de publicaciones inteligente
                    </li>
                </ul>
                <a href="#" class="btn btn--white">Avísame cuando esté disponible</a>
            </div>
            <div class="linkedin-preview__visual" aria-hidden="true">
                <div class="li-card">
                    <div class="li-card__header">
                        <div class="li-card__avatar"></div>
                        <div>
                            <div class="li-card__name"></div>
                            <div class="li-card__meta"></div>
                        </div>
                    </div>
                    <div class="li-card__body">
                        <div class="li-card__line"></div>
                        <div class="li-card__line li-card__line--short"></div>
                        <div class="li-card__portfolio-preview">
                            <div class="li-card__preview-img"></div>
                            <div class="li-card__preview-text">
                                <div class="li-card__preview-title"></div>
                                <div class="li-card__preview-url"></div>
                            </div>
                        </div>
                    </div>
                    <div class="li-card__badge">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="currentColor"><path d="M16 8a6 6 0 0 1 6 6v7h-4v-7a2 2 0 0 0-2-2 2 2 0 0 0-2 2v7h-4v-7a6 6 0 0 1 6-6z"/><rect x="2" y="9" width="4" height="12"/><circle cx="4" cy="4" r="2"/></svg>
                        Auto-publicado por CvXpress
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ===================== CTA FINAL ===================== -->
<section class="cta-section" aria-labelledby="cta-title">
    <div class="container">
        <div class="cta-section__inner">
            <h2 class="cta-section__title" id="cta-title">¿Listo para dar el salto?</h2>
            <p class="cta-section__subtitle">Únete a los profesionales que ya tienen su portfolio web y destacan donde importa.</p>
            <a href="{{ route('register') }}" class="btn btn--primary btn--xl">
                Crear mi portfolio ahora
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
            </a>
            <p class="cta-section__note">Gratis para empezar. Sin tarjeta de crédito.</p>
        </div>
    </div>
</section>

@push('styles')
<style>
/* ── Hero flow card ── */
.hero-flow {
    background: #fff;
    border: 1px solid var(--color-border, #E5E7EB);
    border-radius: 1.25rem;
    box-shadow: 0 8px 32px rgba(15,40,100,.10), 0 1px 4px rgba(0,0,0,.06);
    overflow: hidden;
    width: 100%;
}

/* TOP: transformación visual */
.hero-flow__top {
    background: linear-gradient(135deg, #EFF6FF 0%, #DBEAFE 100%);
    padding: 1.5rem 1.5rem 1.25rem;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 1rem;
    border-bottom: 1px solid #BFDBFE;
}

/* Mini documento CV */
.hero-flow__cv {
    background: #fff;
    border: 1px solid #E5E7EB;
    border-radius: .75rem;
    box-shadow: 0 4px 12px rgba(0,0,0,.1);
    padding: .875rem;
    width: 120px;
    flex-shrink: 0;
}
.hero-flow__cv-header { margin-bottom: .625rem; }
.hero-flow__cv-line {
    height: 7px; background: #E5E7EB;
    border-radius: 4px; margin-bottom: .3rem;
}
.hero-flow__cv-line--name { width: 75%; height: 10px; background: #D1D5DB; }
.hero-flow__cv-line--role { width: 55%; background: #BFDBFE; }
.hero-flow__cv-line--short { width: 60%; }
.hero-flow__cv-body { padding-top: .25rem; }
.hero-flow__cv-section {
    height: 6px; width: 40%; background: #93C5FD;
    border-radius: 4px; margin: .5rem 0 .3rem;
}
.hero-flow__cv-footer {
    display: flex; align-items: center; gap: .3rem;
    font-size: 9px; color: #9CA3AF;
    margin-top: .625rem; padding-top: .5rem;
    border-top: 1px solid #F3F4F6;
}

/* Flecha central */
.hero-flow__arrow {
    display: flex; flex-direction: column;
    align-items: center; gap: .375rem;
    flex-shrink: 0; color: #2563EB;
}
.hero-flow__arrow-track {
    display: flex; flex-direction: column; gap: 4px; align-items: center;
}
.hero-flow__arrow-dot {
    width: 5px; height: 5px; border-radius: 50%;
    background: #93C5FD;
    animation: flow-dot 1.4s ease-in-out infinite;
}
.hero-flow__arrow-dot:nth-child(2) { animation-delay: .2s; }
.hero-flow__arrow-dot:nth-child(3) { animation-delay: .4s; }
@keyframes flow-dot {
    0%,100% { opacity: .3; transform: scaleY(1); }
    50%      { opacity: 1;  transform: scaleY(1.3); }
}
.hero-flow__arrow-badge {
    font-size: .6rem; font-weight: 700;
    background: #1D4ED8; color: #fff;
    padding: .2rem .5rem; border-radius: 100px;
    letter-spacing: .05em;
}

/* Mini navegador */
.hero-flow__browser {
    background: #fff;
    border: 1px solid #E5E7EB;
    border-radius: .75rem;
    box-shadow: 0 4px 12px rgba(0,0,0,.1);
    overflow: hidden;
    width: 170px;
    flex-shrink: 0;
}
.hero-flow__browser-bar {
    background: #F9FAFB;
    border-bottom: 1px solid #F3F4F6;
    padding: .375rem .625rem;
    display: flex; align-items: center; gap: .375rem;
}
.hero-flow__browser-dots { display: flex; gap: 3px; }
.hero-flow__browser-dots span {
    width: 6px; height: 6px; border-radius: 50%;
}
.hero-flow__browser-dots span:nth-child(1) { background: #FC5C57; }
.hero-flow__browser-dots span:nth-child(2) { background: #FDBC40; }
.hero-flow__browser-dots span:nth-child(3) { background: #34C749; }
.hero-flow__browser-url {
    font-size: 8.5px; color: #9CA3AF;
    background: #F3F4F6; border-radius: 4px;
    padding: 1px 6px; flex: 1;
    white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
}
.hero-flow__browser-body { padding: .75rem; }
.hero-flow__browser-avatar {
    width: 30px; height: 30px; border-radius: 50%;
    background: linear-gradient(135deg, #3B82F6, #1E40AF);
    margin-bottom: .5rem;
}
.hero-flow__browser-name {
    height: 8px; width: 65%; background: #1F2937;
    border-radius: 4px; margin-bottom: .3rem;
}
.hero-flow__browser-role {
    height: 6px; width: 50%; background: #93C5FD;
    border-radius: 4px; margin-bottom: .5rem;
}
.hero-flow__browser-tags { display: flex; gap: .25rem; margin-bottom: .625rem; }
.hero-flow__browser-tags span {
    height: 14px; width: 32px;
    background: #DBEAFE; border-radius: 100px;
}
.hero-flow__browser-line {
    height: 5px; background: #E5E7EB;
    border-radius: 4px; margin-bottom: .25rem;
}
.hero-flow__browser-line--short { width: 70%; }

/* Responsive fixes */
@media (max-width: 640px) {
    /* El visual no añade padding extra — el .container ya lo da */
    .hero__visual { padding: 0; }

    /* Top: permitir que las tarjetas encojan en pantallas pequeñas */
    .hero-flow__top  { gap: .375rem; padding: .875rem .625rem; }
    .hero-flow__cv   { flex-shrink: 1; width: clamp(76px, 24vw, 110px); }
    .hero-flow__browser { flex-shrink: 1; width: clamp(108px, 34vw, 160px); }
    .hero-flow__arrow { flex-shrink: 1; min-width: 0; }
    .hero-flow__arrow-badge { font-size: .55rem; padding: .15rem .4rem; }

    /* Steps: ancho completo, padding simétrico */
    .hero-flow__steps { padding: .75rem 1rem 1rem; align-items: stretch; }
    .hero-flow__step  { gap: .625rem; padding: .625rem .5rem; width: 100%; justify-content: flex-start; }
    .hero-flow__step-body { flex: 1; min-width: 0; }
    .hero-flow__step-body strong { font-size: .8rem; }
    .hero-flow__step-body span   { font-size: .7rem; line-height: 1.4; }

    /* Separador */
    .hero-flow__sep { white-space: normal; text-align: center; }
}

/* Separador */
.hero-flow__sep {
    padding: .75rem 1.5rem;
    text-align: center;
    font-size: .7rem;
    font-weight: 600;
    letter-spacing: .08em;
    color: #6B7280;
    text-transform: uppercase;
    background: #FAFAFA;
    border-bottom: 1px solid #F3F4F6;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

/* Steps */
.hero-flow__steps {
    padding: 1rem 1.25rem 1.25rem;
    display: flex;
    flex-direction: column;
    gap: 0;
}
.hero-flow__step {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: .875rem;
    padding: .875rem .75rem;
    border-radius: .875rem;
    transition: background .18s, transform .18s;
    cursor: default;
}
.hero-flow__step:hover {
    background: #EFF6FF;
    transform: translateX(3px);
}
.hero-flow__step-num {
    font-size: .6rem; font-weight: 700;
    letter-spacing: .1em; color: #2563EB;
    flex-shrink: 0; width: 18px;
}
.hero-flow__step-icon {
    width: 44px; height: 44px; flex-shrink: 0;
    background: #EFF6FF; border: 1px solid #BFDBFE;
    border-radius: .75rem;
    display: flex; align-items: center; justify-content: center;
    color: #1D4ED8;
}
.hero-flow__step-body {
    min-width: 0;
}
.hero-flow__step-body strong {
    display: block;
    font-size: .875rem; font-weight: 600;
    color: #111827; margin-bottom: .15rem;
}
.hero-flow__step-body span {
    font-size: .75rem; color: #6B7280; line-height: 1.5;
}
.hero-flow__connector {
    display: flex; align-items: center; justify-content: center;
    color: #93C5FD;
}

/* ════════════════════════════════════════
   CARRUSEL VERTICAL DE PLANTILLAS (hero)
   ════════════════════════════════════════ */
.hero-tpl-section {
    overflow: hidden;
    /* alinear verticalmente con el texto del hero */
    align-self: stretch;
    display: flex;
    align-items: center;
}

.hero-tpl-carousel {
    position: relative;
    width: 100%;
    height: 560px;
    display: flex;
    gap: 14px;
    overflow: hidden;
    /* degradado top/bottom para fundir las tarjetas */
    -webkit-mask-image: linear-gradient(to bottom, transparent 0%, #000 14%, #000 86%, transparent 100%);
    mask-image: linear-gradient(to bottom, transparent 0%, #000 14%, #000 86%, transparent 100%);
}

/* Pausa TODA la animación al pasar el ratón por el carrusel */
.hero-tpl-carousel:hover .hero-tpl-track {
    animation-play-state: paused;
}

.hero-tpl-col {
    flex: 1;
    min-width: 0;
    display: flex;
    flex-direction: column;
}

/* Segunda columna: empieza más abajo para crear escalonado */
.hero-tpl-col--offset {
    margin-top: -90px;
}

.hero-tpl-track {
    display: flex;
    flex-direction: column;
    gap: 14px;
    animation: hero-scroll 20s linear infinite;
    will-change: transform;
}

.hero-tpl-track--slow {
    animation-duration: 28s;
}

@keyframes hero-scroll {
    from { transform: translateY(0); }
    to   { transform: translateY(-50%); }
}

/* ── Tarjeta de plantilla ── */
.hero-tpl-card {
    display: block;
    text-decoration: none;
    border-radius: 14px;
    overflow: visible; /* para que el scale no se corte */
    flex-shrink: 0;
    transition: transform .35s cubic-bezier(.34,1.4,.64,1), box-shadow .3s ease;
    position: relative;
    z-index: 0;
}

.hero-tpl-card:hover {
    transform: scale(1.06) translateY(-5px);
    z-index: 10;
    box-shadow: 0 24px 56px rgba(30,58,138,.22), 0 6px 20px rgba(0,0,0,.12);
}

.hero-tpl-card__thumb {
    aspect-ratio: 3 / 4;
    border-radius: 14px 14px 0 0;
    overflow: hidden;
    background: linear-gradient(135deg, #e0e7ff 0%, #dbeafe 100%);
    position: relative;
}

.hero-tpl-card__thumb img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    display: block;
    transition: transform .4s ease;
}

.hero-tpl-card:hover .hero-tpl-card__thumb img {
    transform: scale(1.04);
}

/* iframe preview — mismo patrón que /plantillas */
.hero-tpl-iframe-wrap {
    position: absolute;
    inset: 0;
    overflow: hidden;
    pointer-events: none;
    background: #f8fafc;
}

.hero-tpl-iframe-wrap iframe {
    position: absolute;
    top: 0;
    left: 0;
    width: 1280px;
    height: 800px;
    border: none;
    transform-origin: top left;
    display: block;
}

.hero-tpl-card__placeholder {
    width: 100%;
    height: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
}

/* Overlay "Ver plantilla" que aparece al hover */
.hero-tpl-card__overlay {
    position: absolute;
    inset: 0;
    background: linear-gradient(to bottom, transparent 40%, rgba(15,30,80,.82) 100%);
    display: flex;
    align-items: flex-end;
    justify-content: center;
    padding: 1rem;
    opacity: 0;
    transition: opacity .25s ease;
    border-radius: 14px 14px 0 0;
}

.hero-tpl-card:hover .hero-tpl-card__overlay {
    opacity: 1;
}

.hero-tpl-card__overlay span {
    color: #fff;
    font-size: .78rem;
    font-weight: 700;
    letter-spacing: .03em;
    background: rgba(255,255,255,.18);
    border: 1px solid rgba(255,255,255,.35);
    padding: .38rem .9rem;
    border-radius: 99px;
    backdrop-filter: blur(6px);
    -webkit-backdrop-filter: blur(6px);
    white-space: nowrap;
}

.hero-tpl-card__badge {
    position: absolute;
    top: 10px;
    right: 10px;
    background: linear-gradient(135deg, #f59e0b, #d97706);
    color: #fff;
    font-size: .6rem;
    font-weight: 800;
    letter-spacing: .06em;
    text-transform: uppercase;
    padding: .25rem .65rem;
    border-radius: 99px;
    box-shadow: 0 2px 8px rgba(217,119,6,.4);
}

.hero-tpl-card__info {
    background: #fff;
    border: 1px solid #e5e7eb;
    border-top: none;
    border-radius: 0 0 14px 14px;
    padding: .6rem .875rem .7rem;
    display: flex;
    flex-direction: column;
    gap: .1rem;
}

.hero-tpl-card__cat {
    font-size: .62rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: .08em;
    color: #1A56DB;
}

.hero-tpl-card__name {
    font-size: .8rem;
    font-weight: 600;
    color: #111827;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

/* ══════════ REDISEÑO: hero oscuro + vídeo ligado al scroll + filas en sentidos opuestos ══════════ */
:root { --cvx-ink: #050a1c; --cvx-ink-2: #0b1430; --cvx-blue: #3b82f6; --cvx-blue-soft: #93c5fd; }

.hero {
    background: radial-gradient(120% 80% at 80% 0%, #0f2a66 0%, var(--cvx-ink) 55%, #03060f 100%);
    color: #fff;
}
.hero__title { color: #fff; }
.hero__title em { color: var(--cvx-blue-soft); font-style: normal; }
.hero__subtitle { color: rgba(255,255,255,.72); }
.hero__social-proof { color: rgba(255,255,255,.7); }
.hero__social-proof strong { color: #fff; }
.hero__container { grid-template-columns: 1fr; }
.hero__text { max-width: 720px; }
.hero .badge--feature {
    background: rgba(59,130,246,.14); border: 1px solid rgba(147,197,253,.35); color: var(--cvx-blue-soft);
}
.hero .btn--ghost { color: #fff; border-color: rgba(255,255,255,.28); }
.hero .btn--ghost:hover { background: rgba(255,255,255,.08); border-color: rgba(255,255,255,.5); }
.hero .avatar { border-color: var(--cvx-ink); }

/* Cabecera adaptada cuando va sobre las secciones oscuras (hero y vídeo) */
.nav.nav--dark { background: rgba(5,10,28,.55) !important; box-shadow: none !important; border-bottom-color: transparent !important; }
.nav.nav--dark .nav__link { color: rgba(255,255,255,.88) !important; }
.nav.nav--dark .nav__link--active { color: var(--cvx-blue-soft) !important; }
.nav.nav--dark .nav__logo img { filter: brightness(0) invert(1); }
.nav.nav--dark .nav__toggle span { background: #fff; }

/* Vídeo ligado al scroll: la sección es alta, y dentro hay un bloque pegado a la pantalla */
.scrollvid { position: relative; height: 320vh; background: var(--cvx-ink); }
.scrollvid__sticky {
    position: sticky; top: 0; height: 100vh; overflow: hidden;
    display: flex; align-items: center; justify-content: center;
}
.scrollvid__video {
    position: absolute; inset: 0; width: 100%; height: 100%;
    object-fit: cover; background: var(--cvx-ink);
}
.scrollvid__veil {
    position: absolute; inset: 0;
    background: linear-gradient(180deg, rgba(5,10,28,.55) 0%, rgba(5,10,28,.1) 40%, rgba(5,10,28,.85) 100%);
    pointer-events: none;
}
.scrollvid__steps {
    position: absolute; left: 0; right: 0; bottom: 12vh; text-align: center; z-index: 2;
}
.scrollvid__step {
    position: absolute; left: 0; right: 0; bottom: 0;
    font-size: clamp(1.6rem, 4vw, 3rem); font-weight: 800; letter-spacing: -.03em; color: #fff;
    opacity: 0; transform: translateY(16px); transition: opacity .4s ease, transform .4s ease;
}
.scrollvid__step.is-active { opacity: 1; transform: translateY(0); }
.scrollvid__num {
    display: block; font-size: .9rem; letter-spacing: .25em; color: var(--cvx-blue-soft); margin-bottom: .4rem;
}
.scrollvid__progress {
    position: absolute; left: 50%; bottom: 4vh; transform: translateX(-50%); z-index: 2;
    width: min(360px, 70vw); height: 3px; background: rgba(255,255,255,.18); border-radius: 99px; overflow: hidden;
}
.scrollvid__progress span {
    display: block; height: 100%; width: 0%; background: linear-gradient(90deg, #3b82f6, #93c5fd);
}

/* Plantillas destacadas: fondo oscuro a juego con el final del vídeo */
.marquee-section {
    padding: var(--space-20) 0 var(--space-16);
    background: radial-gradient(90% 60% at 50% 0%, #0f2a66 0%, var(--cvx-ink) 70%);
    color: #fff;
}
.marquee-section .label { color: var(--cvx-blue-soft); }
.marquee-section .section__title { color: #fff; }
.marquee-section .section__title em { color: var(--cvx-blue-soft); font-style: normal; }
.marquee-section .btn--ghost { color: #fff; border-color: rgba(255,255,255,.28); }
.marquee-section .btn--ghost:hover { background: rgba(255,255,255,.08); border-color: rgba(255,255,255,.5); }

/* Cascada: parejas de tarjetas que suben o bajan y desaparecen en cascada */
/* Cuatro columnas en movimiento continuo: impares suben, pares bajan */
.casc {
    display: grid; grid-template-columns: repeat(4, 1fr); gap: 1.5rem;
    height: 720px; overflow: hidden;
    max-width: 1180px; margin: 0 auto; padding: 0 1.25rem;
    mask-image: linear-gradient(transparent 0%, #000 8%, #000 92%, transparent 100%);
    -webkit-mask-image: linear-gradient(transparent 0%, #000 8%, #000 92%, transparent 100%);
}
.casc__col { overflow: hidden; height: 100%; }
.casc__track {
    display: flex; flex-direction: column;
    animation: casc-up var(--dur, 24s) linear infinite;
    animation-delay: var(--offset, 0s);
}
.casc__col--down .casc__track { animation-name: casc-down; }
.casc__col .casc__card { width: 100%; margin-bottom: 1.5rem; flex-shrink: 0; }
.casc .mq-card { transition: box-shadow .3s ease; }
.casc .mq-card:hover { transform: none; box-shadow: 0 0 0 1px rgba(147,197,253,.6), 0 16px 40px rgba(59,130,246,.35); }
@keyframes casc-up   { from { transform: translateY(0); }     to { transform: translateY(-50%); } }
@keyframes casc-down { from { transform: translateY(-50%); }  to { transform: translateY(0); } }
/* Al pasar el cursor por encima, todo se queda parado */
.casc:hover .casc__track { animation-play-state: paused; }
@media (max-width: 1023px) {
    .casc { grid-template-columns: repeat(2, 1fr); }
    .casc__col:nth-child(n+3) { display: none; }
}
@media (prefers-reduced-motion: reduce) {
    .casc__track { animation: none; }
}

/* Responsive: en móvil el carrusel se oculta (ya hay marquee abajo) */
@media (max-width: 1023px) {
    .hero-tpl-section { display: none; }
}
</style>
@endpush

@push('scripts')
<script>
(function () {
    var items = document.querySelectorAll('.cv-acc__item');
    items.forEach(function (item) {
        var btn = item.querySelector('.cv-acc__trigger');
        if (!btn) return;
        btn.addEventListener('click', function () {
            var isOpen = item.classList.contains('cv-acc__item--open');
            // close all
            items.forEach(function (i) {
                i.classList.remove('cv-acc__item--open');
                i.querySelector('.cv-acc__trigger').setAttribute('aria-expanded', 'false');
            });
            // open clicked (toggle)
            if (!isOpen) {
                item.classList.add('cv-acc__item--open');
                btn.setAttribute('aria-expanded', 'true');
            }
        });
    });
})();
</script>
@if($featuredTemplates->isNotEmpty())
<script>
(function () {
    /* ── Escalar iframes del hero carousel ── */
    function scaleHeroIframes() {
        document.querySelectorAll('.hero-tpl-iframe-wrap').forEach(function (wrap) {
            var iframe = wrap.querySelector('iframe');
            if (!iframe) return;
            var w = wrap.offsetWidth;
            var h = wrap.offsetHeight;
            if (!w || !h) return;
            var scale = w / 1280;
            iframe.style.transform = 'scale(' + scale + ')';
            /* Hacer que el iframe sea tan alto como para rellenar el contenedor */
            iframe.style.height = Math.ceil(h / scale) + 'px';
        });
    }
    scaleHeroIframes();
    window.addEventListener('resize', scaleHeroIframes);

    /* ── Escalar iframes del marquee ── */
    function scaleMarqueeIframes() {
        document.querySelectorAll('.mq-card__iframe-wrap').forEach(function (wrap) {
            var iframe = wrap.querySelector('iframe');
            if (!iframe) return;
            var scaleX = wrap.offsetWidth  / 1280;
            var scaleY = wrap.offsetHeight / 800;
            var scale  = Math.min(scaleX, scaleY);
            iframe.style.transformOrigin = 'top left';
            iframe.style.transform       = 'scale(' + scale + ')';
            iframe.style.width           = '1280px';
            iframe.style.height          = '800px';
            iframe.style.pointerEvents   = 'none';
            iframe.style.border          = 'none';
        });
    }

    scaleMarqueeIframes();
    window.addEventListener('resize', scaleMarqueeIframes);
})();

/* ── Vídeo ligado al scroll ──
   El scroll fija una posición objetivo; el vídeo la persigue con una
   interpolación suave en cada frame. No se encadenan búsquedas mientras el
   navegador todavía busca (eso es lo que daba el efecto a saltos). */
(function () {
    var sec   = document.querySelector('.scrollvid');
    var video = document.getElementById('scrollVideo');
    if (!sec || !video) return;

    var bar   = document.getElementById('scrollVideoBar');
    var steps = Array.prototype.slice.call(document.querySelectorAll('.scrollvid__step'));
    var ready   = false;
    var target  = 0;   // segundo al que queremos llegar (según el scroll)
    var current = 0;   // segundo que mostramos (se acerca a target suavemente)
    var looping = false;

    video.pause();
    function markReady() { ready = true; kick(); }
    // Si el vídeo ya está en la caché del navegador (p. ej. al volver de otra
    // página), el evento "loadedmetadata" puede dispararse antes de que este
    // script llegue a escucharlo. readyState >= 1 cubre ese caso.
    if (video.readyState >= 1) {
        markReady();
    } else {
        video.addEventListener('loadedmetadata', markReady);
    }

    function loop() {
        looping = false;
        if (!ready) return;

        current += (target - current) * 0.18;
        if (!video.seeking && Math.abs(video.currentTime - current) > 0.04) {
            video.currentTime = current;
        }
        if (Math.abs(target - current) > 0.01) kick();
    }

    function kick() {
        if (looping) return;
        looping = true;
        requestAnimationFrame(loop);
    }

    function update() {
        var rect  = sec.getBoundingClientRect();
        var range = sec.offsetHeight - window.innerHeight;
        var p     = Math.min(1, Math.max(0, -rect.top / range));

        if (ready && isFinite(video.duration)) {
            target = p * video.duration;
            kick();
        }
        if (bar) bar.style.width = (p * 100) + '%';

        steps.forEach(function (s, i) {
            var from = parseFloat(s.dataset.from);
            var to   = parseFloat(s.dataset.to);
            var last = i === steps.length - 1;
            s.classList.toggle('is-active', p >= from && (p < to || (last && p <= 1)));
        });
    }

    window.addEventListener('scroll', update, { passive: true });
    window.addEventListener('resize', update);
    update();
})();

/* ── Cabecera oscura mientras se superpone al hero o al vídeo ── */
(function () {
    var nav   = document.getElementById('main-nav');
    var dark  = Array.prototype.slice.call(document.querySelectorAll('.hero, .scrollvid'));
    if (!nav || !dark.length) return;

    function update() {
        var y = nav.offsetHeight / 2;
        var onDark = dark.some(function (sec) {
            var r = sec.getBoundingClientRect();
            return r.top <= y && r.bottom >= y;
        });
        nav.classList.toggle('nav--dark', onDark);
    }

    window.addEventListener('scroll', update, { passive: true });
    window.addEventListener('resize', update);
    update();
})();
</script>
@endif
@endpush

@endsection
