<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $template->name }} — CvXpress Plantillas</title>
    <meta name="description" content="{{ $template->description ?? 'Plantilla profesional para portfolio web.' }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:opsz,wght@9..40,300;9..40,400;9..40,500;9..40,600;9..40,700&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        :root {
            --blue: #1A56DB;
            --blue-dark: #1447b8;
            --blue-light: #eff6ff;
            --amber: #d97706;
            --green: #16a34a;
            --text: #111827;
            --muted: #6b7280;
            --border: #e5e7eb;
            --bg: #f8fafc;
            --white: #fff;
        }
        html, body { height: 100%; }
        body {
            font-family: 'DM Sans', system-ui, sans-serif;
            background: var(--bg);
            color: var(--text);
            line-height: 1.6;
            display: flex;
            flex-direction: column;
        }

        /* ─── TOPBAR ─── */
        .sp-topbar {
            background: #0f172a;
            padding: .7rem 1.5rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 1rem;
            flex-shrink: 0;
        }
        .sp-topbar__logo {
            display: inline-flex;
            align-items: center;
            gap: .5rem;
            text-decoration: none;
            color: #fff;
            font-size: .9rem;
            font-weight: 700;
        }
        .sp-topbar__logo strong { color: #60a5fa; }
        .sp-topbar__back {
            display: inline-flex;
            align-items: center;
            gap: .4rem;
            color: rgba(255,255,255,.7);
            font-size: .82rem;
            text-decoration: none;
            padding: .35rem .75rem;
            border: 1px solid rgba(255,255,255,.15);
            border-radius: 8px;
            transition: color .15s, border-color .15s, background .15s;
        }
        .sp-topbar__back:hover {
            color: #fff;
            border-color: rgba(255,255,255,.35);
            background: rgba(255,255,255,.07);
        }

        /* ─── LAYOUT ─── */
        .sp-layout {
            display: grid;
            grid-template-columns: 1fr 340px;
            flex: 1;
            min-height: 0;
        }

        /* ─── PREVIEW PANEL (izquierda) ─── */
        .sp-preview {
            background: #1e293b;
            display: flex;
            flex-direction: column;
            height: 100%;
            min-height: 0;
        }

        /* Barra del "navegador" simulado */
        .sp-preview__bar {
            background: #0f172a;
            padding: .6rem 1rem;
            display: flex;
            align-items: center;
            gap: .75rem;
            border-bottom: 1px solid rgba(255,255,255,.06);
            flex-shrink: 0;
        }
        .sp-preview__dots {
            display: flex;
            gap: 5px;
        }
        .sp-preview__dots span {
            width: 10px; height: 10px;
            border-radius: 50%;
        }
        .sp-preview__dots span:nth-child(1) { background: #ef4444; }
        .sp-preview__dots span:nth-child(2) { background: #f59e0b; }
        .sp-preview__dots span:nth-child(3) { background: #22c55e; }
        .sp-preview__url {
            flex: 1;
            background: rgba(255,255,255,.07);
            border-radius: 6px;
            padding: .28rem .75rem;
            font-size: .75rem;
            color: rgba(255,255,255,.5);
            font-family: monospace;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }
        .sp-preview__url-icon {
            width: 12px; height: 12px;
            color: #22c55e;
            flex-shrink: 0;
        }

        /* Iframe contenedor (ocupa todo el espacio restante) */
        .sp-preview__iframe-wrap {
            flex: 1;
            position: relative;
            min-height: 0;
            background: #fff;
        }
        .sp-preview__iframe-wrap iframe {
            width: 100%;
            height: 100%;
            border: none;
            display: block;
        }
        .sp-preview__placeholder {
            width: 100%;
            height: 100%;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 1rem;
            background: linear-gradient(135deg, #e0e7ff 0%, #dbeafe 100%);
            color: #6b7280;
        }
        .sp-preview__placeholder svg { opacity: .35; }
        .sp-preview__placeholder span { font-size: .9rem; font-style: italic; }

        /* ─── INFO PANEL (derecha) ─── */
        .sp-info {
            background: var(--white);
            border-left: 1px solid var(--border);
            padding: 1.75rem 1.5rem;
            display: flex;
            flex-direction: column;
            gap: 1.4rem;
            overflow-y: auto;
        }

        /* Badges */
        .sp-badges {
            display: flex;
            flex-wrap: wrap;
            gap: .4rem;
        }
        .sp-badge {
            padding: .28rem .72rem;
            border-radius: 999px;
            font-size: .74rem;
            font-weight: 700;
            letter-spacing: .04em;
        }
        .sp-badge--premium {
            background: linear-gradient(135deg, #f59e0b, #d97706);
            color: #fff;
        }
        .sp-badge--free {
            background: #dcfce7;
            color: #16a34a;
            border: 1px solid #bbf7d0;
        }
        .sp-badge--featured {
            background: var(--blue-light);
            color: var(--blue);
            border: 1px solid #bfdbfe;
        }
        .sp-badge--category {
            background: #f3f4f6;
            color: #4b5563;
            border: 1px solid var(--border);
        }

        /* Name & desc */
        .sp-name {
            font-size: 1.5rem;
            font-weight: 800;
            color: var(--text);
            line-height: 1.2;
        }
        .sp-desc {
            font-size: .88rem;
            color: var(--muted);
            line-height: 1.65;
        }

        /* Separator */
        .sp-sep {
            height: 1px;
            background: var(--border);
        }

        /* Price block */
        .sp-price-block {
            background: var(--bg);
            border: 1.5px solid var(--border);
            border-radius: 12px;
            padding: 1.1rem 1.25rem;
        }
        .sp-price-label {
            font-size: .76rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: .07em;
            color: var(--muted);
            margin-bottom: .35rem;
        }
        .sp-price-value {
            font-size: 1.9rem;
            font-weight: 800;
            line-height: 1;
        }
        .sp-price-value--free { color: var(--green); }
        .sp-price-value--paid { color: var(--amber); }
        .sp-price-sub {
            font-size: .76rem;
            color: var(--muted);
            margin-top: .3rem;
        }

        /* CTA */
        .sp-cta {
            display: flex;
            flex-direction: column;
            gap: .65rem;
        }
        .sp-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: .5rem;
            padding: .8rem 1.4rem;
            border-radius: 10px;
            font-size: .92rem;
            font-weight: 700;
            text-decoration: none;
            cursor: pointer;
            border: none;
            transition: background .15s, transform .1s, box-shadow .15s;
        }
        .sp-btn:hover { transform: translateY(-1px); }
        .sp-btn--buy {
            background: var(--blue);
            color: #fff;
            box-shadow: 0 4px 14px rgba(26,86,219,.28);
        }
        .sp-btn--buy:hover { background: var(--blue-dark); }
        .sp-btn--outline {
            background: #fff;
            color: var(--text);
            border: 1.5px solid var(--border);
        }
        .sp-btn--outline:hover { border-color: var(--blue); color: var(--blue); background: var(--blue-light); }
        .sp-cta-note {
            font-size: .74rem;
            color: var(--muted);
            text-align: center;
            line-height: 1.5;
        }

        /* Selector "Solo descargar" vs "Con hosting" */
        .sp-pick {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: .5rem;
            background: var(--bg);
            border: 1.5px solid var(--border);
            border-radius: 12px;
            padding: .35rem;
        }
        .sp-pick__opt {
            display: flex;
            flex-direction: column;
            align-items: flex-start;
            gap: .15rem;
            padding: .65rem .85rem;
            border-radius: 9px;
            border: none;
            background: transparent;
            cursor: pointer;
            font-family: inherit;
            text-align: left;
            transition: background .15s, box-shadow .15s;
        }
        .sp-pick__opt-label { font-size: .78rem; font-weight: 700; color: var(--text); display: flex; align-items: center; gap: 5px; }
        .sp-pick__opt-price { font-size: .82rem; font-weight: 800; color: var(--text); }
        .sp-pick__opt-price small { font-size: .68rem; font-weight: 600; color: var(--muted); }
        .sp-pick__opt--active { background: #fff; box-shadow: 0 2px 8px rgba(15,23,42,.1); }
        .sp-pick__opt--active .sp-pick__opt-label { color: var(--blue); }
        .sp-pick__panel[hidden] { display: none; }

        /* Features list */
        .sp-features__title {
            font-size: .78rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .07em;
            color: var(--muted);
            margin-bottom: .6rem;
        }
        .sp-features {
            display: flex;
            flex-direction: column;
            gap: .55rem;
        }
        .sp-feature {
            display: flex;
            align-items: center;
            gap: .55rem;
            font-size: .85rem;
            color: #374151;
        }
        .sp-feature svg { flex-shrink: 0; color: var(--green); }

        /* ─── MOBILE ─── */
        @media (max-width: 900px) {
            body { display: block; }
            .sp-layout {
                display: flex;
                flex-direction: column;
            }
            .sp-preview {
                height: 65vw;
                min-height: 320px;
                max-height: 520px;
            }
            .sp-info {
                border-left: none;
                border-top: 1px solid var(--border);
            }
        }
    </style>
</head>
<body>

{{-- ── TOPBAR ── --}}
<header class="sp-topbar">
    <a href="{{ route('home') }}" class="sp-topbar__logo">
        <svg width="24" height="24" viewBox="0 0 32 32" fill="none" aria-hidden="true">
            <rect width="32" height="32" rx="8" fill="#1A56DB"/>
            <path d="M8 10h10M8 16h16M8 22h12" stroke="white" stroke-width="2.5" stroke-linecap="round"/>
            <circle cx="24" cy="10" r="3" fill="#60A5FA"/>
        </svg>
        Cv<strong>Xpress</strong>
    </a>
    <a href="{{ route('templates.list') }}" class="sp-topbar__back">
        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M19 12H5M12 5l-7 7 7 7"/></svg>
        Todas las plantillas
    </a>
</header>

{{-- ── LAYOUT ── --}}
<div class="sp-layout">

    {{-- ── PREVIEW (izquierda): iframe con la plantilla real ── --}}
    <div class="sp-preview">
        <div class="sp-preview__bar">
            <div class="sp-preview__dots">
                <span></span><span></span><span></span>
            </div>
            <svg class="sp-preview__url-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
            <div class="sp-preview__url">
                cvxpress.es/mi-portfolio — Plantilla "{{ $template->name }}"
            </div>
        </div>

        <div class="sp-preview__iframe-wrap">
            @php $previewUrl = $template->preview_html_url; @endphp

            @if($previewUrl)
                <iframe
                    src="{{ $previewUrl }}"
                    title="Vista previa de {{ $template->name }}"
                    sandbox="allow-same-origin allow-scripts"
                    loading="lazy"
                    allowfullscreen>
                </iframe>
            @else
                <div class="sp-preview__placeholder">
                    <svg width="72" height="72" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width=".8">
                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                        <polyline points="14 2 14 8 20 8"/>
                        <line x1="16" y1="13" x2="8" y2="13"/>
                        <line x1="16" y1="17" x2="8" y2="17"/>
                    </svg>
                    <span>La plantilla aún no tiene vista previa generada</span>
                </div>
            @endif
        </div>
    </div>

    {{-- ── INFO PANEL (derecha) ── --}}
    <aside class="sp-info" id="comprar">

        {{-- Badges --}}
        <div class="sp-badges">
            @if($template->is_premium)
                <span class="sp-badge sp-badge--premium">
                    <svg width="10" height="10" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
                    Premium
                </span>
            @endif
            @if($template->is_featured)
                <span class="sp-badge sp-badge--featured">Destacada</span>
            @endif
            @if($template->category)
                <span class="sp-badge sp-badge--category">{{ $template->category->name }}</span>
            @endif
        </div>

        {{-- Name --}}
        <h1 class="sp-name">{{ $template->name }}</h1>

        {{-- Description --}}
        @if($template->description)
            <p class="sp-desc">{{ $template->description }}</p>
        @endif

        <div class="sp-sep"></div>

        {{-- Plan info --}}
        @php
            $tierPlan    = $plansByTier[$template->plan_tier] ?? null;
            $canUse      = $activePurchase && $activePurchase->canUseTemplate($template);
            $isActive    = $activePurchase && $activePurchase->selected_template_id === $template->id;
            $canDownload = $template->price > 0;
            $downloadUrl = auth()->check()
                ? route('checkout.download.show', $template->slug)
                : route('register') . '?next=' . urlencode(route('checkout.download.show', $template->slug));
            $hostingUrl  = fn ($plan) => auth()->check()
                ? route('checkout.show', $plan->slug)
                : route('register') . '?next=' . urlencode(route('checkout.show', $plan->slug));
        @endphp

        @if($isActive || $canUse)
            {{-- Ya la tiene / puede usarla con su plan actual: sin selector, un único precio informativo --}}
            <div class="sp-price-block">
                <div class="sp-price-label">{{ $tierPlan ? 'Incluida en' : 'Precio' }}</div>
                @if($tierPlan)
                    <div style="font-size:1.35rem;font-weight:900;color:#0f172a;">Plan {{ $tierPlan->name }}</div>
                @else
                    <div class="sp-price-sub">Gratuita con cualquier plan</div>
                @endif
            </div>
        @else
            {{-- Sin acceso todavía: selector claro entre las dos formas de conseguirla --}}
            <div class="sp-pick" role="tablist" aria-label="Cómo quieres conseguir esta plantilla">
                <button type="button" id="sp-pick-btn-download" class="sp-pick__opt {{ $canDownload ? 'sp-pick__opt--active' : '' }}" onclick="spSelectOption('download')" role="tab" aria-selected="{{ $canDownload ? 'true' : 'false' }}" {{ $canDownload ? '' : 'disabled' }}>
                    <span class="sp-pick__opt-label">
                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
                        Solo descargar
                    </span>
                    <span class="sp-pick__opt-price">
                        @if($canDownload)
                            {{ number_format($template->price, 2, ',', '.') }}€ <small>pago único</small>
                        @else
                            <small>Gratis</small>
                        @endif
                    </span>
                </button>
                <button type="button" id="sp-pick-btn-hosting" class="sp-pick__opt {{ $canDownload ? '' : 'sp-pick__opt--active' }}" onclick="spSelectOption('hosting')" role="tab" aria-selected="{{ $canDownload ? 'false' : 'true' }}" {{ $tierPlan ? '' : 'disabled' }}>
                    <span class="sp-pick__opt-label">
                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"/><line x1="2" y1="12" x2="22" y2="12"/><path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"/></svg>
                        Con hosting propio
                    </span>
                    <span class="sp-pick__opt-price">
                        @if($tierPlan)
                            {{ number_format($tierPlan->price, 2, ',', '.') }}€ <small>/año</small>
                        @else
                            <small>Gratis</small>
                        @endif
                    </span>
                </button>
            </div>
        @endif

        {{-- CTA --}}
        <div class="sp-cta">

            @if($isActive)
                {{-- Ya está usando esta plantilla --}}
                <div style="display:flex;align-items:center;gap:10px;background:#dcfce7;border:1.5px solid #86efac;border-radius:12px;padding:1rem 1.25rem;">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#16a34a" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
                    <div>
                        <div style="font-weight:700;color:#14532d;font-size:.9rem;">Plantilla activa en tu panel</div>
                        <div style="font-size:.78rem;color:#166534;">Esta es tu plantilla actual</div>
                    </div>
                </div>
                <a href="{{ route('dashboard') }}#templates" class="sp-btn sp-btn--outline">
                    Ir a mi panel
                </a>
            @elseif($canUse)
                {{-- Puede usar esta plantilla (plan con hosting suficiente, o plan de solo-descarga) --}}
                <form method="POST" action="{{ route('dashboard.template.select', [$activePurchase->id, $template->id]) }}">
                    @csrf
                    <button type="submit" class="sp-btn sp-btn--buy" style="width:100%;cursor:pointer;">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
                        Usar esta plantilla
                    </button>
                </form>
                <p class="sp-cta-note">
                    Se guardará como tu plantilla activa.
                    @if(!$activePurchase->canHost())
                        Con tu plan podrás descargarla desde el editor CV Web (sin hosting).
                    @endif
                </p>
            @elseif($activePurchase)
                {{-- Tiene un plan con hosting, pero de un nivel insuficiente para esta plantilla --}}
                @if($tierPlan)
                    <a href="{{ route('checkout.show', $tierPlan->slug) }}" class="sp-btn sp-btn--buy" style="background:#f59e0b;box-shadow:0 4px 14px rgba(245,158,11,.28);">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="17 11 12 6 7 11"/><polyline points="17 18 12 13 7 18"/></svg>
                        Mejorar a Plan {{ $tierPlan->name }} · {{ number_format($tierPlan->price, 2, ',', '.') }}€/año
                    </a>
                @endif
                @if($canDownload)
                    <a href="{{ route('checkout.download.show', $template->slug) }}" class="sp-btn sp-btn--outline">
                        O solo descargarla · {{ number_format($template->price, 2, ',', '.') }}€ pago único
                    </a>
                @endif
                <p class="sp-cta-note">Tu plan actual <strong>{{ $activePurchase->plan->name }}</strong> no incluye esta plantilla.</p>
            @else
                {{-- Sin plan: sigue la opción elegida en el selector de arriba --}}
                <div id="sp-pick-panel-download" class="sp-pick__panel" {{ $canDownload ? '' : 'hidden' }}>
                    @if($canDownload)
                        <a href="{{ $downloadUrl }}" class="sp-btn sp-btn--buy" style="width:100%;">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
                            Comprar por {{ number_format($template->price, 2, ',', '.') }}€
                        </a>
                        <p class="sp-cta-note">Pago único · Sin renovaciones · Descarga esta plantilla en ZIP. Sin hosting.</p>
                    @endif
                </div>
                <div id="sp-pick-panel-hosting" class="sp-pick__panel" {{ $canDownload ? 'hidden' : '' }}>
                    @if($tierPlan)
                        <a href="{{ $hostingUrl($tierPlan) }}" class="sp-btn sp-btn--buy">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>
                            Suscribirme · {{ number_format($tierPlan->price, 2, ',', '.') }}€/año
                        </a>
                        <p class="sp-cta-note">Incluye hosting con subdominio propio y todas las plantillas de este nivel.</p>
                    @else
                        <p class="sp-cta-note">Esta plantilla es gratuita con cualquier plan.</p>
                    @endif
                </div>
                @if(!auth()->check())
                    <a href="{{ route('login') }}" class="sp-btn sp-btn--outline">Ya tengo cuenta</a>
                @endif
            @endif
        </div>

        @if(!$isActive && !$canUse && !$activePurchase)
        <script>
        function spSelectOption(opt) {
            ['download', 'hosting'].forEach(function (o) {
                var btn   = document.getElementById('sp-pick-btn-' + o);
                var panel = document.getElementById('sp-pick-panel-' + o);
                if (btn)   btn.classList.toggle('sp-pick__opt--active', o === opt);
                if (btn)   btn.setAttribute('aria-selected', o === opt ? 'true' : 'false');
                if (panel) panel.hidden = (o !== opt);
            });
        }
        </script>
        @endif

        <div class="sp-sep"></div>

        {{-- Features --}}
        <div>
            <div class="sp-features__title">Qué incluye</div>
            <div class="sp-features">
                <div class="sp-feature">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
                    Diseño profesional y responsivo
                </div>
                <div class="sp-feature">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
                    Compatible con todos los dispositivos
                </div>
                <div class="sp-feature">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
                    Hosting incluido en tu plan
                </div>
                <div class="sp-feature">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
                    Personalizable desde tu panel
                </div>
                @if($template->is_premium)
                <div class="sp-feature">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
                    Soporte prioritario incluido
                </div>
                @endif
            </div>
        </div>

    </aside>
</div>

</body>
</html>
