@extends('layouts.app')

@section('title', 'CVPortfolio — Convierte tu CV en un portfolio web profesional')
@section('meta_description', 'Sube tu CV en PDF y en minutos tendrás un portfolio web profesional alojado, editable y listo para compartir. Próximamente: publicación automática en LinkedIn.')

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
                Automatización inteligente de portfolios
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
                <p><strong>+500 profesionales</strong> ya tienen su portfolio web</p>
            </div>
        </div>

        <div class="hero__visual" aria-hidden="true">
            <!-- Mockup CV → Portfolio -->
            <div class="mockup">
                <div class="mockup__cv">
                    <div class="mockup__doc-header">
                        <div class="mockup__line mockup__line--name"></div>
                        <div class="mockup__line mockup__line--role"></div>
                    </div>
                    <div class="mockup__doc-body">
                        <div class="mockup__section-title"></div>
                        <div class="mockup__line"></div>
                        <div class="mockup__line mockup__line--short"></div>
                        <div class="mockup__section-title"></div>
                        <div class="mockup__line"></div>
                        <div class="mockup__line"></div>
                        <div class="mockup__line mockup__line--short"></div>
                    </div>
                    <div class="mockup__cv-label">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
                        curriculum.pdf
                    </div>
                </div>

                <div class="mockup__arrow">
                    <div class="mockup__arrow-line"></div>
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
                    <span>Automágico</span>
                </div>

                <div class="mockup__portfolio">
                    <div class="mockup__browser">
                        <div class="mockup__browser-bar">
                            <div class="mockup__dots">
                                <span></span><span></span><span></span>
                            </div>
                            <div class="mockup__url">miportfolio.cvportfolio.es</div>
                        </div>
                        <div class="mockup__browser-content">
                            <div class="mockup__avatar-circle"></div>
                            <div class="mockup__portfolio-name"></div>
                            <div class="mockup__portfolio-role"></div>
                            <div class="mockup__tags">
                                <span></span><span></span><span></span>
                            </div>
                            <div class="mockup__portfolio-section"></div>
                            <div class="mockup__portfolio-line"></div>
                            <div class="mockup__portfolio-line mockup__portfolio-line--short"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
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

<!-- ===================== CÓMO FUNCIONA ===================== -->
<section class="section" id="como-funciona" aria-labelledby="steps-title">
    <div class="container">
        <div class="section__header">
            <div class="label">Proceso simple</div>
            <h2 class="section__title" id="steps-title">De PDF a portfolio web<br>en tres pasos</h2>
            <p class="section__subtitle">Sin conocimientos técnicos. Sin horas de diseño. Solo sube tu CV y deja que la automatización trabaje por ti.</p>
        </div>

        <ol class="steps" role="list">
            <li class="step">
                <div class="step__number" aria-hidden="true">01</div>
                <div class="step__icon" aria-hidden="true">
                    <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" y1="3" x2="12" y2="15"/></svg>
                </div>
                <h3 class="step__title">Sube tu CV en PDF</h3>
                <p class="step__desc">Arrastra o selecciona tu currículum. Nuestra IA analiza y extrae toda la información: experiencia, formación, habilidades y logros.</p>
            </li>

            <li class="step" aria-hidden="true">
                <div class="step__connector" aria-hidden="true"></div>
            </li>

            <li class="step">
                <div class="step__number" aria-hidden="true">02</div>
                <div class="step__icon" aria-hidden="true">
                    <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><rect x="2" y="3" width="20" height="14" rx="2"/><line x1="8" y1="21" x2="16" y2="21"/><line x1="12" y1="17" x2="12" y2="21"/></svg>
                </div>
                <h3 class="step__title">Tu portfolio se genera automáticamente</h3>
                <p class="step__desc">En segundos tendrás un portfolio web profesional y responsivo, con tu dominio personalizado, listo para compartir.</p>
            </li>

            <li class="step" aria-hidden="true">
                <div class="step__connector" aria-hidden="true"></div>
            </li>

            <li class="step">
                <div class="step__number" aria-hidden="true">03</div>
                <div class="step__icon" aria-hidden="true">
                    <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                </div>
                <h3 class="step__title">Edita y mantén actualizado</h3>
                <p class="step__desc">Desde tu panel de control puedes añadir nuevas experiencias, actualizar proyectos y personalizar el diseño en cualquier momento.</p>
            </li>
        </ol>
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

    <div class="marquee-outer" aria-hidden="true">
        <div class="marquee-track">
            {{-- Dos copias para loop infinito seamless --}}
            @foreach([1,2] as $copy)
                @foreach($featuredTemplates as $tpl)
                <a href="{{ route('templates.preview', $tpl->slug) }}"
                   target="_blank" rel="noopener"
                   class="mq-card">
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
            @endforeach
        </div>
    </div>
</section>
@endif

<!-- ===================== CARACTERÍSTICAS ===================== -->
<section class="section section--alt" id="caracteristicas" aria-labelledby="features-title">
    <div class="container">
        <div class="section__header">
            <div class="label">Todo lo que necesitas</div>
            <h2 class="section__title" id="features-title">Una plataforma completa<br>para tu presencia digital</h2>
        </div>

        <div class="features">
            <article class="feature feature--large">
                <div class="feature__icon feature__icon--blue" aria-hidden="true">
                    <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><circle cx="12" cy="12" r="3"/><path d="M12 1v2M12 21v2M4.22 4.22l1.42 1.42M18.36 18.36l1.42 1.42M1 12h2M21 12h2M4.22 19.78l1.42-1.42M18.36 5.64l1.42-1.42"/></svg>
                </div>
                <h3 class="feature__title">Extracción inteligente con IA</h3>
                <p class="feature__desc">Nuestra IA lee y comprende tu CV en cualquier formato, estructura y idioma. Extrae y organiza automáticamente toda la información relevante sin que tengas que tocar nada.</p>
            </article>

            <article class="feature">
                <div class="feature__icon feature__icon--teal" aria-hidden="true">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M12 2L2 7l10 5 10-5-10-5z"/><path d="M2 17l10 5 10-5"/><path d="M2 12l10 5 10-5"/></svg>
                </div>
                <h3 class="feature__title">Hosting incluido</h3>
                <p class="feature__desc">Tu portfolio vive en nuestra infraestructura. URL personalizada, HTTPS incluido y accesible desde cualquier dispositivo.</p>
            </article>

            <article class="feature">
                <div class="feature__icon feature__icon--blue" aria-hidden="true">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><rect x="2" y="3" width="20" height="14" rx="2"/><line x1="8" y1="21" x2="16" y2="21"/><line x1="12" y1="17" x2="12" y2="21"/></svg>
                </div>
                <h3 class="feature__title">100% responsivo</h3>
                <p class="feature__desc">Tu portfolio se adapta perfectamente a móvil, tablet y escritorio. Siempre impecable, sin importar el dispositivo del reclutador.</p>
            </article>

            <article class="feature">
                <div class="feature__icon feature__icon--teal" aria-hidden="true">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                </div>
                <h3 class="feature__title">Panel de edición</h3>
                <p class="feature__desc">Añade nuevas experiencias, proyectos o formación directamente desde tu panel. Sin necesidad de volver a subir el CV.</p>
            </article>

            <article class="feature feature--soon">
                <div class="feature__soon-badge">Próximamente</div>
                <div class="feature__icon feature__icon--linkedin" aria-hidden="true">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M16 8a6 6 0 0 1 6 6v7h-4v-7a2 2 0 0 0-2-2 2 2 0 0 0-2 2v7h-4v-7a6 6 0 0 1 6-6z"/><rect x="2" y="9" width="4" height="12"/><circle cx="4" cy="4" r="2"/></svg>
                </div>
                <h3 class="feature__title">Publicación automática en LinkedIn</h3>
                <p class="feature__desc">Pronto podrás automatizar publicaciones sobre tu portfolio y tus logros directamente en LinkedIn. Aumenta tu visibilidad sin esfuerzo.</p>
            </article>

            <article class="feature">
                <div class="feature__icon feature__icon--blue" aria-hidden="true">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
                </div>
                <h3 class="feature__title">Seguridad y privacidad</h3>
                <p class="feature__desc">Tus datos están cifrados y protegidos. Tú decides qué información es pública y qué queda privada en tu perfil.</p>
            </article>
        </div>
    </div>
</section>

<!-- ===================== PRECIOS ===================== -->
<section class="section" id="precios" aria-labelledby="pricing-title">
    <div class="container">
        <div class="section__header">
            <div class="label">Planes de pago único</div>
            <h2 class="section__title" id="pricing-title">Sin suscripciones.<br><em>Pagas una vez, es tuyo.</em></h2>
            <p class="section__subtitle">Elige el plan que mejor se adapte a ti. Pago único, sin renovaciones, sin sorpresas.</p>
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
                        <div class="pricing-card__tagline">Pago único, sin renovaciones</div>
                    </div>
                </div>
                <div class="pricing-card__price">
                    <span class="pricing-card__amount">{{ number_format($plan->price, 2, ',', '.') }}€</span>
                    <span class="pricing-card__once">pago único</span>
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
                        Empezar por {{ number_format($plan->price, 2, ',', '.') }}€
                    </a>
                @else
                    <a href="{{ route('login') }}?redirect={{ urlencode(route('checkout.show', $plan->slug)) }}" class="pricing-card__cta pricing-card__cta--{{ $tc['cta'] }}">
                        Empezar por {{ number_format($plan->price, 2, ',', '.') }}€
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
                    Próximamente
                </div>
                <h2 class="no-pdf__title" id="no-pdf-title">
                    ¿Aún no tienes<br><em>tu CV en PDF?</em>
                </h2>
                <p class="no-pdf__desc">
                    No te preocupes. Pronto podrás crear tu currículum profesional directamente en CvExpress, con plantillas guiadas paso a paso y exportación instantánea a PDF.
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
                <a href="{{ route('register') }}" class="btn btn--primary btn--lg">
                    Crear mi cuenta
                    <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
                </a>
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
                        Auto-publicado por CVPortfolio
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

@push('scripts')
@if($featuredTemplates->isNotEmpty())
<script>
(function () {
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

    // Pausa al hover
    var track = document.querySelector('.marquee-track');
    if (track) {
        track.addEventListener('mouseenter', function () { track.style.animationPlayState = 'paused'; });
        track.addEventListener('mouseleave', function () { track.style.animationPlayState = 'running'; });
    }
})();
</script>
@endif
@endpush

@endsection
