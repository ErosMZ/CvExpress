{{-- Página producto — standalone, sin layouts.app --}}
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CvXpress — Tu portfolio web profesional</title>
    <meta name="description" content="Crea tu portfolio web desde tu CV en minutos. Plantillas profesionales, subdominio propio, editor visual.">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:opsz,wght@9..40,300;9..40,400;9..40,500;9..40,600;9..40,700;9..40,800&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet">
    <link rel="icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">
    <style>
    /* ══════════════════════════════════════════════
       RESET + BASE
    ══════════════════════════════════════════════ */
    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
    html { font-size: 16px; scroll-behavior: smooth; }
    body {
        font-family: 'DM Sans', -apple-system, sans-serif;
        background: #f0f4fb;
        color: #0f172a;
        -webkit-font-smoothing: antialiased;
        overflow-x: hidden;
    }
    a { text-decoration: none; color: inherit; }
    ul { list-style: none; }
    button { font-family: inherit; cursor: pointer; border: none; background: none; }
    img { display: block; max-width: 100%; }

    /* ══════════════════════════════════════════════
       NAVBAR
    ══════════════════════════════════════════════ */
    .ap-nav {
        position: fixed;
        top: 0; left: 0; right: 0;
        height: 56px;
        background: #0d1b3e;
        display: flex;
        align-items: center;
        padding: 0 1.25rem;
        z-index: 200;
        box-shadow: 0 1px 0 rgba(255,255,255,.06), 0 4px 24px rgba(0,0,0,.25);
        gap: 0;
    }

    /* Logo */
    .ap-nav__logo {
        display: flex; align-items: center; gap: .55rem;
        flex-shrink: 0; margin-right: 1.5rem;
    }
    .ap-nav__logo-mark {
        width: 30px; height: 30px;
        background: linear-gradient(135deg, #3b82f6, #1d4ed8);
        border-radius: 8px;
        display: flex; align-items: center; justify-content: center;
        color: #fff;
    }
    .ap-nav__logo-text {
        font-size: .95rem; font-weight: 700; color: #fff;
        letter-spacing: -.01em;
    }
    .ap-nav__logo-text strong { color: #60a5fa; }

    /* Center tabs */
    .ap-nav__tabs {
        display: flex;
        align-items: center;
        height: 100%;
        flex: 1;
        overflow-x: auto;
        scrollbar-width: none;
        gap: 0;
    }
    .ap-nav__tabs::-webkit-scrollbar { display: none; }

    .ap-tab {
        display: inline-flex;
        align-items: center;
        gap: .4rem;
        padding: 0 .9rem;
        height: 56px;
        font-size: .83rem;
        font-weight: 600;
        color: rgba(255,255,255,.52);
        white-space: nowrap;
        flex-shrink: 0;
        border-bottom: 2px solid transparent;
        transition: color .18s, border-color .18s;
    }
    .ap-tab svg { opacity: .65; transition: opacity .18s; }
    .ap-tab:hover { color: rgba(255,255,255,.9); }
    .ap-tab:hover svg { opacity: 1; }
    .ap-tab--active {
        color: #fff;
        border-bottom-color: #3b82f6;
    }
    .ap-tab--active svg { opacity: 1; }

    /* Right: auth */
    .ap-nav__right {
        display: flex;
        align-items: center;
        gap: .625rem;
        flex-shrink: 0;
        margin-left: .75rem;
    }
    .ap-btn-ghost {
        font-size: .8rem; font-weight: 600;
        color: rgba(255,255,255,.65);
        padding: .4rem .875rem;
        border-radius: 7px;
        border: 1px solid rgba(255,255,255,.15);
        transition: all .18s;
    }
    .ap-btn-ghost:hover { color: #fff; border-color: rgba(255,255,255,.35); }

    .ap-btn-primary {
        font-size: .8rem; font-weight: 700;
        background: #2563eb;
        color: #fff;
        padding: .4rem 1rem;
        border-radius: 7px;
        transition: background .18s;
    }
    .ap-btn-primary:hover { background: #1d4ed8; }

    .ap-nav__user {
        display: flex; align-items: center; gap: .5rem;
    }
    .ap-nav__avatar {
        width: 30px; height: 30px; border-radius: 50%;
        background: linear-gradient(135deg, #3b82f6, #1e40af);
        display: flex; align-items: center; justify-content: center;
        color: #fff; font-size: .65rem; font-weight: 700;
    }
    .ap-nav__username { font-size: .8rem; font-weight: 600; color: rgba(255,255,255,.8); }

    /* Hamburger mobile */
    .ap-hamburger {
        display: none;
        flex-direction: column; gap: 5px;
        padding: .5rem; margin-left: .5rem;
    }
    .ap-hamburger span {
        display: block; width: 20px; height: 2px;
        background: rgba(255,255,255,.7); border-radius: 2px;
        transition: all .2s;
    }

    /* Mobile nav drawer */
    .ap-nav__drawer {
        display: none;
        position: fixed;
        top: 56px; left: 0; right: 0;
        background: #0d1b3e;
        border-bottom: 1px solid rgba(255,255,255,.08);
        flex-direction: column;
        z-index: 190;
        padding: .5rem 0;
    }
    .ap-nav__drawer.open { display: flex; }
    .ap-nav__drawer .ap-tab {
        height: 44px; padding: 0 1.25rem; width: 100%;
        border-bottom: none;
        border-left: 2px solid transparent;
    }
    .ap-nav__drawer .ap-tab--active { border-left-color: #3b82f6; }

    /* ══════════════════════════════════════════════
       MAIN AREA
    ══════════════════════════════════════════════ */
    .ap-main {
        margin-top: 56px;
        min-height: calc(100vh - 56px);
    }

    .ap-panel { display: none; }
    .ap-panel--active { display: block; }

    /* Inner wrapper */
    .ap-inner {
        max-width: 1280px;
        margin: 0 auto;
        padding: 2rem 1.5rem 4rem;
    }

    /* Page header row */
    .ap-page-title {
        margin-bottom: 2rem;
    }
    .ap-page-title h2 {
        font-size: 1.5rem; font-weight: 800;
        letter-spacing: -.03em; color: #0f172a;
        margin-bottom: .2rem;
    }
    .ap-page-title p {
        font-size: .875rem; color: #64748b;
    }

    /* Cards */
    .ap-card {
        background: #fff;
        border: 1px solid #e2e8f0;
        border-radius: 14px;
        padding: 1.5rem;
    }

    /* ══════════════════════════════════════════════
       PANEL: INICIO
    ══════════════════════════════════════════════ */

    /* Hero layout */
    .ap-hero {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 2.5rem;
        align-items: center;
        margin-bottom: 2.5rem;
    }

    .ap-eyebrow {
        display: inline-flex; align-items: center; gap: .4rem;
        font-size: .72rem; font-weight: 700; color: #2563eb;
        letter-spacing: .07em; text-transform: uppercase;
        margin-bottom: 1.1rem;
    }
    .ap-eyebrow-dot {
        width: 7px; height: 7px; border-radius: 50%; background: #2563eb;
        animation: apdot 2s ease infinite;
    }
    @keyframes apdot {
        0%,100% { box-shadow: 0 0 0 0 rgba(37,99,235,.5); }
        50%      { box-shadow: 0 0 0 6px rgba(37,99,235,0); }
    }

    .ap-h1 {
        font-size: clamp(2.25rem, 4.5vw, 3.5rem);
        font-weight: 800; line-height: 1.1;
        letter-spacing: -.04em; color: #0f172a;
        margin-bottom: .875rem;
    }
    .ap-h1 em { font-style: normal; color: #2563eb; }

    .ap-hero-sub {
        font-size: .975rem; color: #475569;
        line-height: 1.65; max-width: 400px;
        margin-bottom: 1.75rem;
    }

    .ap-hero__btns {
        display: flex; align-items: center; gap: 1rem; flex-wrap: wrap;
        margin-bottom: 1.5rem;
    }

    .btn-lg-primary {
        display: inline-flex; align-items: center; gap: .5rem;
        background: #2563eb; color: #fff;
        font-size: .9rem; font-weight: 700;
        padding: .75rem 1.75rem; border-radius: 10px;
        transition: background .2s, transform .15s;
    }
    .btn-lg-primary:hover { background: #1d4ed8; transform: translateY(-1px); }

    .btn-lg-ghost {
        display: inline-flex; align-items: center; gap: .4rem;
        font-size: .875rem; font-weight: 600; color: #475569;
        padding: .75rem 1.25rem; border-radius: 10px;
        border: 1.5px solid #e2e8f0;
        background: #fff;
        transition: all .2s;
    }
    .btn-lg-ghost:hover { border-color: #2563eb; color: #2563eb; }

    .ap-proof {
        display: flex; align-items: center; gap: .6rem;
        font-size: .75rem; color: #94a3b8; font-weight: 500;
    }
    .ap-proof-avs { display: flex; }
    .ap-proof-avs span {
        width: 24px; height: 24px; border-radius: 50%;
        background: linear-gradient(135deg, #3b82f6, #1e40af);
        color: #fff; font-size: .55rem; font-weight: 700;
        display: flex; align-items: center; justify-content: center;
        border: 2px solid #f0f4fb;
        margin-left: -5px;
    }
    .ap-proof-avs span:first-child { margin-left: 0; }

    /* Browser mockup */
    .ap-browser {
        background: #fff;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        box-shadow: 0 20px 60px rgba(15,23,42,.13), 0 4px 16px rgba(0,0,0,.06);
        overflow: hidden;
    }
    .ap-browser__bar {
        height: 36px; background: #f8fafc;
        border-bottom: 1px solid #e2e8f0;
        display: flex; align-items: center; gap: .625rem;
        padding: 0 .875rem;
    }
    .ap-browser__dots { display: flex; gap: 4px; flex-shrink: 0; }
    .ap-browser__dots span { width: 10px; height: 10px; border-radius: 50%; }
    .ap-browser__url {
        flex: 1; font-size: .68rem; color: #94a3b8;
        background: #f1f5f9; border-radius: 4px;
        padding: 2px 8px; overflow: hidden;
        text-overflow: ellipsis; white-space: nowrap;
        font-family: 'JetBrains Mono', monospace;
    }
    .ap-browser__screen {
        aspect-ratio: 4/3; overflow: hidden;
        position: relative; background: #e2e8f0;
    }

    /* Hero iframe */
    .ap-hero-iframe {
        position: absolute; inset: 0;
        overflow: hidden; pointer-events: none;
    }
    .ap-hero-iframe iframe {
        position: absolute; top: 0; left: 0;
        width: 1280px; height: 800px;
        border: none; transform-origin: top left;
    }

    .ap-browser-caption {
        text-align: center; margin-top: .5rem;
        font-size: .73rem; color: #94a3b8;
    }
    .ap-browser-caption a { color: #64748b; transition: color .2s; }
    .ap-browser-caption a:hover { color: #2563eb; }

    /* Feature cards (Inicio) */
    .ap-feat-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 1.125rem;
    }

    .ap-feat-card {
        background: #fff;
        border: 1.5px solid #e2e8f0;
        border-radius: 14px;
        padding: 1.375rem 1.25rem 1.25rem;
        cursor: pointer;
        transition: border-color .2s, box-shadow .2s, transform .2s;
        display: flex; flex-direction: column; gap: .7rem;
    }
    .ap-feat-card:hover {
        border-color: #2563eb;
        box-shadow: 0 8px 28px rgba(37,99,235,.12);
        transform: translateY(-2px);
    }

    .ap-feat-icon {
        width: 44px; height: 44px;
        background: #eff6ff; border: 1px solid #bfdbfe;
        border-radius: 11px;
        display: flex; align-items: center; justify-content: center;
        color: #1d4ed8;
    }
    .ap-feat-card h3 {
        font-size: .975rem; font-weight: 700; color: #0f172a;
    }
    .ap-feat-card p {
        font-size: .82rem; color: #64748b; line-height: 1.55; flex: 1;
    }
    .ap-feat-card__link {
        font-size: .78rem; font-weight: 700; color: #2563eb;
    }

    /* ══════════════════════════════════════════════
       PANEL: CV WEB & CV PDF  (product panels)
    ══════════════════════════════════════════════ */
    .ap-product-layout {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 2.5rem;
        align-items: start;
        margin-bottom: 2.5rem;
    }

    .ap-product-heading {
        font-size: 1.875rem; font-weight: 800;
        letter-spacing: -.035em; color: #0f172a;
        margin-bottom: .75rem;
    }
    .ap-product-desc {
        font-size: .9375rem; color: #475569;
        line-height: 1.7; margin-bottom: 1.75rem;
    }

    .ap-feature-list {
        display: flex; flex-direction: column; gap: .7rem;
        margin-bottom: 1.75rem;
    }
    .ap-feature-list li {
        display: flex; align-items: flex-start; gap: .6rem;
        font-size: .855rem; color: #374151; line-height: 1.5;
    }
    .ap-feature-list li .ap-check {
        width: 18px; height: 18px; flex-shrink: 0;
        background: #dcfce7; border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
        margin-top: 1px;
    }
    .ap-feature-list li .ap-check svg { color: #16a34a; }

    /* Mini template grid (3 cols, inside CV Web) */
    .ap-mini-tpl-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: .875rem;
    }

    /* ══════════════════════════════════════════════
       PANEL: PLANTILLAS
    ══════════════════════════════════════════════ */
    .ap-filters {
        display: flex; gap: .5rem; flex-wrap: wrap;
        margin-bottom: 1.5rem;
    }
    .ap-filter {
        display: inline-flex; align-items: center; gap: .35rem;
        padding: .35rem .875rem;
        border: 1.5px solid #e2e8f0;
        border-radius: 99px;
        font-size: .775rem; font-weight: 600; color: #475569;
        background: #fff; cursor: pointer;
        transition: all .18s;
    }
    .ap-filter span {
        font-size: .62rem; font-weight: 700;
        background: #f1f5f9; color: #64748b;
        padding: 0 5px; border-radius: 99px;
    }
    .ap-filter:hover { border-color: #2563eb; color: #2563eb; }
    .ap-filter--active {
        background: #2563eb; border-color: #2563eb; color: #fff;
    }
    .ap-filter--active span { background: rgba(255,255,255,.2); color: #fff; }

    .ap-tpl-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 1.25rem;
    }

    /* Template card */
    .ap-tpl-card {
        display: block; text-decoration: none;
        border-radius: 12px; overflow: visible;
        transition: transform .3s cubic-bezier(.34,1.5,.64,1), box-shadow .25s;
        position: relative; z-index: 0;
    }
    .ap-tpl-card:hover {
        transform: translateY(-5px) scale(1.025);
        z-index: 5;
        box-shadow: 0 18px 48px rgba(15,23,42,.15), 0 4px 14px rgba(0,0,0,.08);
    }

    .ap-tpl-thumb {
        aspect-ratio: 16/10;
        border-radius: 10px 10px 0 0;
        overflow: hidden; position: relative;
        background: #e2e8f0;
    }
    .ap-tpl-thumb img {
        width: 100%; height: 100%;
        object-fit: cover; object-position: top; display: block;
    }

    .ap-tpl-iframe-wrap {
        position: absolute; inset: 0;
        overflow: hidden; pointer-events: none;
        background: #f8fafc;
    }
    .ap-tpl-iframe-wrap iframe {
        position: absolute; top: 0; left: 0;
        width: 1280px; height: 800px;
        border: none; transform-origin: top left; display: block;
    }

    .ap-tpl-overlay {
        position: absolute; inset: 0;
        background: linear-gradient(to bottom, transparent 35%, rgba(15,23,42,.82) 100%);
        display: flex; align-items: flex-end; justify-content: center;
        padding: .8rem; opacity: 0; transition: opacity .22s;
        border-radius: 10px 10px 0 0;
    }
    .ap-tpl-card:hover .ap-tpl-overlay { opacity: 1; }
    .ap-tpl-overlay span {
        color: #fff; font-size: .72rem; font-weight: 700;
        background: rgba(255,255,255,.18);
        border: 1px solid rgba(255,255,255,.3);
        padding: .28rem .8rem; border-radius: 99px;
        backdrop-filter: blur(6px);
    }

    .ap-tpl-tier {
        position: absolute; top: 7px; right: 7px;
        color: #fff; font-size: .56rem; font-weight: 800;
        letter-spacing: .07em; text-transform: uppercase;
        padding: .18rem .55rem; border-radius: 99px;
    }
    .ap-tpl-star {
        position: absolute; top: 7px; left: 7px;
        background: #f59e0b; color: #fff;
        font-size: .6rem; font-weight: 800;
        padding: .18rem .55rem; border-radius: 99px;
    }

    .ap-tpl-foot {
        background: #fff; border: 1px solid #e5e7eb;
        border-top: none; border-radius: 0 0 10px 10px;
        padding: .5rem .8rem .6rem;
    }
    .ap-tpl-name {
        font-size: .79rem; font-weight: 600; color: #0f172a;
        white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
        display: block;
    }
    .ap-tpl-cat {
        font-size: .62rem; font-weight: 700;
        text-transform: uppercase; letter-spacing: .07em; color: #2563eb;
        display: block;
    }

    /* ══════════════════════════════════════════════
       PANEL: PLANES
    ══════════════════════════════════════════════ */
    .ap-plans-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 1.375rem;
        max-width: 980px;
        margin: 0 auto;
    }

    .ap-plan {
        background: #fff;
        border: 1.5px solid #e2e8f0;
        border-radius: 18px;
        padding: 1.875rem;
        position: relative;
        transition: box-shadow .25s, transform .25s;
    }
    .ap-plan:hover {
        box-shadow: 0 16px 40px rgba(0,0,0,.09);
        transform: translateY(-3px);
    }
    .ap-plan--featured {
        border-color: var(--pclr, #2563eb);
        box-shadow: 0 0 0 3px color-mix(in srgb, var(--pclr, #2563eb) 18%, transparent);
    }

    .ap-plan__badge {
        position: absolute; top: -13px; left: 50%;
        transform: translateX(-50%);
        background: var(--pclr, #2563eb); color: #fff;
        font-size: .63rem; font-weight: 800;
        letter-spacing: .09em; text-transform: uppercase;
        padding: .25rem 1.1rem; border-radius: 99px;
        white-space: nowrap;
        box-shadow: 0 2px 8px rgba(0,0,0,.15);
    }

    .ap-plan__name { font-size: 1.0625rem; font-weight: 700; color: #0f172a; margin-bottom: .875rem; }

    .ap-plan__price {
        display: flex; align-items: baseline; gap: .35rem;
        margin-bottom: 1.25rem;
    }
    .ap-plan__price strong { font-size: 2.5rem; font-weight: 800; color: #0f172a; letter-spacing: -.04em; }
    .ap-plan__price span { font-size: .85rem; color: #64748b; }

    .ap-plan__features {
        display: flex; flex-direction: column; gap: .55rem;
        margin-bottom: 1.625rem;
    }
    .ap-plan__features li {
        display: flex; align-items: flex-start; gap: .45rem;
        font-size: .83rem; color: #374151; line-height: 1.5;
    }
    .ap-plan__features li svg { flex-shrink: 0; margin-top: 2px; color: #16a34a; }

    .ap-plan__cta {
        display: block; width: 100%;
        padding: .7rem 1rem; border-radius: 10px;
        text-align: center; font-size: .875rem; font-weight: 700;
        color: #374151; background: #f8fafc;
        border: 1.5px solid #e2e8f0;
        text-decoration: none; transition: all .2s;
    }
    .ap-plan__cta:hover { border-color: #cbd5e1; background: #f1f5f9; }
    .ap-plan__cta--hi {
        background: var(--pclr, #2563eb);
        border-color: var(--pclr, #2563eb); color: #fff;
    }
    .ap-plan__cta--hi:hover { filter: brightness(1.08); }

    /* ══════════════════════════════════════════════
       FOOTER MINI
    ══════════════════════════════════════════════ */
    .ap-footer {
        background: #0d1b3e;
        padding: 1.25rem 1.5rem;
        display: flex; align-items: center; justify-content: space-between;
        flex-wrap: wrap; gap: .75rem;
    }
    .ap-footer p {
        font-size: .75rem; color: rgba(255,255,255,.4);
    }
    .ap-footer a {
        font-size: .75rem; color: rgba(255,255,255,.5);
        transition: color .2s;
    }
    .ap-footer a:hover { color: rgba(255,255,255,.9); }
    .ap-footer__links { display: flex; gap: 1.25rem; flex-wrap: wrap; }

    /* ══════════════════════════════════════════════
       SECTION DIVIDER LABEL
    ══════════════════════════════════════════════ */
    .ap-section-label {
        font-size: .68rem; font-weight: 700;
        letter-spacing: .1em; text-transform: uppercase;
        color: #94a3b8; margin-bottom: .6rem;
    }

    /* ══════════════════════════════════════════════
       RESPONSIVE
    ══════════════════════════════════════════════ */
    @media (max-width: 1023px) {
        .ap-nav__tabs { display: none; }
        .ap-hamburger { display: flex; }
        .ap-hero { grid-template-columns: 1fr; }
        .ap-hero .ap-browser-wrap { display: none; }
        .ap-tpl-grid { grid-template-columns: repeat(2, 1fr); }
        .ap-feat-grid { grid-template-columns: 1fr 1fr; }
        .ap-product-layout { grid-template-columns: 1fr; }
    }

    @media (max-width: 639px) {
        .ap-inner { padding: 1.5rem 1rem 3rem; }
        .ap-tpl-grid { grid-template-columns: 1fr; }
        .ap-feat-grid { grid-template-columns: 1fr; }
        .ap-plans-grid { grid-template-columns: 1fr; }
        .ap-nav__right .ap-btn-ghost { display: none; }
    }
    </style>
</head>
<body>

{{-- ══════════════════ NAVBAR ══════════════════ --}}
<header class="ap-nav">
    {{-- Logo --}}
    <a href="{{ route('home') }}" class="ap-nav__logo">
        <div class="ap-nav__logo-mark">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><rect x="3" y="3" width="18" height="18" rx="3"/><path d="M8 7h8M8 11h5M8 15h6"/></svg>
        </div>
        <span class="ap-nav__logo-text"><strong>CV</strong>Xpress</span>
    </a>

    {{-- Tabs --}}
    <div class="ap-nav__tabs">
        <button class="ap-tab ap-tab--active" data-panel="inicio">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/></svg>
            Inicio
        </button>
        <button class="ap-tab" data-panel="cvweb">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="3" width="20" height="14" rx="2"/><line x1="8" y1="21" x2="16" y2="21"/><line x1="12" y1="17" x2="12" y2="21"/></svg>
            CV Web
        </button>
        <button class="ap-tab" data-panel="cvpdf">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
            CV PDF
        </button>
        <button class="ap-tab" data-panel="plantillas">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="18" height="18" rx="2"/><path d="M3 9h18M9 21V9"/></svg>
            Plantillas
            <span style="font-size:.6rem;background:rgba(255,255,255,.15);color:rgba(255,255,255,.7);padding:1px 6px;border-radius:99px;font-weight:700;">{{ $templates->count() }}</span>
        </button>
        <button class="ap-tab" data-panel="planes">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>
            Planes
        </button>
    </div>

    {{-- Auth --}}
    <div class="ap-nav__right">
        @auth
            <div class="ap-nav__user">
                <div class="ap-nav__avatar">{{ strtoupper(substr(auth()->user()->name, 0, 2)) }}</div>
                <span class="ap-nav__username">{{ auth()->user()->name }}</span>
            </div>
            <a href="{{ route('dashboard') }}" class="ap-btn-primary">Mi panel</a>
        @else
            <a href="{{ route('login') }}" class="ap-btn-ghost">Entrar</a>
            <a href="{{ route('register') }}" class="ap-btn-primary">Empezar gratis</a>
        @endauth
    </div>

    {{-- Hamburger --}}
    <button class="ap-hamburger" id="apHamburger" aria-label="Menú">
        <span></span><span></span><span></span>
    </button>
</header>

{{-- Mobile drawer --}}
<div class="ap-nav__drawer" id="apDrawer">
    <button class="ap-tab" data-panel="inicio">Inicio</button>
    <button class="ap-tab" data-panel="cvweb">CV Web</button>
    <button class="ap-tab" data-panel="cvpdf">CV PDF</button>
    <button class="ap-tab" data-panel="plantillas">Plantillas</button>
    <button class="ap-tab" data-panel="planes">Planes</button>
</div>

{{-- ══════════════════ MAIN ══════════════════ --}}
<main class="ap-main">

    {{-- ─────────── INICIO ─────────── --}}
    <div class="ap-panel ap-panel--active" id="panel-inicio">
        <div class="ap-inner">

            {{-- Hero --}}
            <div class="ap-hero">
                <div>
                    <div class="ap-eyebrow">
                        <span class="ap-eyebrow-dot"></span>
                        Portfolio web profesional
                    </div>
                    <h1 class="ap-h1">Tu CV.<br>Tu web.<br><em>Tu marca.</em></h1>
                    <p class="ap-hero-sub">
                        Sube un PDF. Elige una plantilla. En minutos tienes un portfolio web alojado, editable y con tu propio dominio.
                    </p>
                    <div class="ap-hero__btns">
                        <a href="{{ auth()->check() ? route('templates.list') : route('register') }}" class="btn-lg-primary">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
                            Empezar gratis
                        </a>
                        <button class="btn-lg-ghost" onclick="apSwitch('plantillas')">
                            Ver plantillas →
                        </button>
                    </div>
                    <div class="ap-proof">
                        <div class="ap-proof-avs">
                            <span>JM</span><span>SR</span><span>AL</span>
                        </div>
                        +500 portfolios creados
                    </div>
                </div>

                {{-- Browser mockup --}}
                @php $heroTpl = $featured ?? $templates->first(); @endphp
                @if($heroTpl)
                <div class="ap-browser-wrap">
                    <div class="ap-browser">
                        <div class="ap-browser__bar">
                            <div class="ap-browser__dots">
                                <span style="background:#fc5c57"></span>
                                <span style="background:#fdbc40"></span>
                                <span style="background:#34c749"></span>
                            </div>
                            <div class="ap-browser__url">cvxpress.es/{{ \Illuminate\Support\Str::slug(auth()->user()?->name ?? 'juan-garcia') }}</div>
                        </div>
                        <div class="ap-browser__screen">
                            @if($heroTpl->preview_html_url)
                                <div class="ap-hero-iframe" id="apHeroIframe">
                                    <iframe src="{{ $heroTpl->preview_html_url }}" scrolling="no" sandbox="allow-same-origin allow-scripts" title="{{ $heroTpl->name }}"></iframe>
                                </div>
                            @elseif($heroTpl->preview_image)
                                <img src="{{ asset('storage/'.$heroTpl->preview_image) }}" alt="{{ $heroTpl->name }}" style="width:100%;height:100%;object-fit:cover;object-position:top">
                            @else
                                <div style="width:100%;height:100%;background:linear-gradient(135deg,#eff6ff,#dbeafe)"></div>
                            @endif
                        </div>
                    </div>
                    <div class="ap-browser-caption">
                        Plantilla: <a href="{{ route('templates.preview', $heroTpl->slug) }}">{{ $heroTpl->name }} →</a>
                    </div>
                </div>
                @endif
            </div>

            {{-- Feature cards --}}
            <div class="ap-section-label">Qué puedes hacer</div>
            <div class="ap-feat-grid">
                <div class="ap-feat-card" onclick="apSwitch('cvweb')">
                    <div class="ap-feat-icon">
                        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><rect x="2" y="3" width="20" height="14" rx="2"/><line x1="8" y1="21" x2="16" y2="21"/><line x1="12" y1="17" x2="12" y2="21"/></svg>
                    </div>
                    <h3>CV Web</h3>
                    <p>Convierte tu CV en un portfolio web profesional con subdominio propio, editable desde el panel.</p>
                    <span class="ap-feat-card__link">Explorar →</span>
                </div>

                <div class="ap-feat-card" onclick="apSwitch('cvpdf')">
                    <div class="ap-feat-icon" style="background:#f0fdf4;border-color:#bbf7d0">
                        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#16a34a" stroke-width="1.8"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/></svg>
                    </div>
                    <h3>CV PDF</h3>
                    <p>Genera y descarga tu CV en PDF de forma profesional. Con IA que extrae y mejora tu información.</p>
                    <span class="ap-feat-card__link" style="color:#16a34a">Explorar →</span>
                </div>

                <div class="ap-feat-card" onclick="apSwitch('plantillas')">
                    <div class="ap-feat-icon" style="background:#fdf4ff;border-color:#e9d5ff">
                        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#9333ea" stroke-width="1.8"><rect x="3" y="3" width="18" height="18" rx="2"/><path d="M3 9h18M9 21V9"/></svg>
                    </div>
                    <h3>Plantillas</h3>
                    <p>{{ $templates->count() }} diseños profesionales para tu portfolio web. Desde minimalistas hasta creativos.</p>
                    <span class="ap-feat-card__link" style="color:#9333ea">Ver todas →</span>
                </div>
            </div>

        </div>
    </div>

    {{-- ─────────── CV WEB ─────────── --}}
    <div class="ap-panel" id="panel-cvweb">
        <div class="ap-inner">
            <div class="ap-page-title">
                <h2>CV Web</h2>
                <p>Tu portfolio web profesional, alojado y listo en minutos</p>
            </div>

            <div class="ap-product-layout">
                <div>
                    <p class="ap-product-desc">
                        Sube tu CV en PDF y nuestra IA extrae toda tu información. Elige una plantilla y en menos de 2 minutos tienes tu portfolio web online, con tu propio subdominio y editor visual completo.
                    </p>

                    <ul class="ap-feature-list">
                        <li>
                            <span class="ap-check"><svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><polyline points="20 6 9 17 4 12"/></svg></span>
                            Subdominio propio incluido: <strong>tudominio.cvxpress.es</strong>
                        </li>
                        <li>
                            <span class="ap-check"><svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><polyline points="20 6 9 17 4 12"/></svg></span>
                            Editor visual por secciones (presentación, experiencia, habilidades…)
                        </li>
                        <li>
                            <span class="ap-check"><svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><polyline points="20 6 9 17 4 12"/></svg></span>
                            {{ $templates->count() }} plantillas diseñadas por profesionales
                        </li>
                        <li>
                            <span class="ap-check"><svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><polyline points="20 6 9 17 4 12"/></svg></span>
                            100% responsive — perfecto en móvil, tablet y escritorio
                        </li>
                        <li>
                            <span class="ap-check"><svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><polyline points="20 6 9 17 4 12"/></svg></span>
                            Foto de perfil, habilidades, colores personalizables
                        </li>
                    </ul>

                    <a href="{{ auth()->check() ? route('templates.list') : route('register') }}" class="btn-lg-primary" style="display:inline-flex">
                        Crear mi CV Web
                    </a>
                </div>

                {{-- Template preview mini-grid --}}
                <div>
                    <div class="ap-section-label" style="margin-bottom:.875rem">Plantillas disponibles</div>
                    <div class="ap-mini-tpl-grid">
                        @foreach($templates->where('is_featured', true)->take(3) as $tpl)
                        <a href="{{ route('templates.preview', $tpl->slug) }}" class="ap-tpl-card">
                            <div class="ap-tpl-thumb">
                                @if($tpl->preview_html_url)
                                    <div class="ap-tpl-iframe-wrap ap-mini-iframe">
                                        <iframe src="{{ $tpl->preview_html_url }}" scrolling="no" sandbox="allow-same-origin allow-scripts" title="{{ $tpl->name }}"></iframe>
                                    </div>
                                @elseif($tpl->preview_image)
                                    <img src="{{ asset('storage/'.$tpl->preview_image) }}" alt="{{ $tpl->name }}">
                                @endif
                                <div class="ap-tpl-overlay"><span>Ver →</span></div>
                            </div>
                            <div class="ap-tpl-foot">
                                <span class="ap-tpl-name">{{ $tpl->name }}</span>
                            </div>
                        </a>
                        @endforeach
                    </div>
                    <div style="text-align:center;margin-top:1rem">
                        <button onclick="apSwitch('plantillas')" class="btn-lg-ghost" style="font-size:.8rem;padding:.5rem 1.25rem">
                            Ver todas las plantillas →
                        </button>
                    </div>
                </div>
            </div>

            {{-- Steps --}}
            <div class="ap-section-label">Cómo funciona</div>
            <div style="display:grid;grid-template-columns:1fr auto 1fr auto 1fr;gap:1rem;align-items:center">
                @foreach([
                    ['01', 'Sube tu CV', 'Carga tu PDF y la IA extrae toda tu información automáticamente.', 'M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4 M17 8 12 3 7 8 M12 3v12'],
                    ['02', 'Elige plantilla', 'Selecciona el diseño que mejor represente tu perfil.', 'M3 3h18v18H3z M3 9h18 M9 21V9'],
                    ['03', 'Publica', 'Tu portfolio queda alojado online y listo para compartir.', 'M18 5a3 3 0 1 0 0-6 3 3 0 0 0 0 6z M6 12a3 3 0 1 0 0-6 3 3 0 0 0 0 6z M18 19a3 3 0 1 0 0-6 3 3 0 0 0 0 6z'],
                ] as $step)
                <div class="ap-card" style="text-align:center;padding:1.5rem 1.25rem">
                    <div style="font-size:.6rem;font-weight:800;letter-spacing:.15em;color:#2563eb;text-transform:uppercase;margin-bottom:.75rem">{{ $step[0] }}</div>
                    <div style="width:44px;height:44px;background:#eff6ff;border:1px solid #bfdbfe;border-radius:12px;display:flex;align-items:center;justify-content:center;color:#1d4ed8;margin:0 auto .75rem">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><rect x="3" y="3" width="18" height="18" rx="2"/></svg>
                    </div>
                    <h3 style="font-size:.9rem;font-weight:700;margin-bottom:.4rem">{{ $step[1] }}</h3>
                    <p style="font-size:.78rem;color:#64748b;line-height:1.5">{{ $step[2] }}</p>
                </div>
                @if(!$loop->last)
                <div style="color:#cbd5e1;display:flex;align-items:center">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
                </div>
                @endif
                @endforeach
            </div>

        </div>
    </div>

    {{-- ─────────── CV PDF ─────────── --}}
    <div class="ap-panel" id="panel-cvpdf">
        <div class="ap-inner">
            <div class="ap-page-title">
                <h2>CV PDF</h2>
                <p>Genera tu currículum en PDF con IA y plantillas profesionales</p>
            </div>

            <div class="ap-product-layout">
                <div>
                    <p class="ap-product-desc">
                        Nuestro editor de CV con IA te permite crear un currículum en PDF profesional en minutos. Sube tu CV actual, la IA lo analiza y mejora, y lo genera en el formato que necesites.
                    </p>

                    <ul class="ap-feature-list">
                        <li>
                            <span class="ap-check" style="background:#dcfce7"><svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="#16a34a" stroke-width="3"><polyline points="20 6 9 17 4 12"/></svg></span>
                            Extracción automática de datos desde tu PDF con IA
                        </li>
                        <li>
                            <span class="ap-check" style="background:#dcfce7"><svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="#16a34a" stroke-width="3"><polyline points="20 6 9 17 4 12"/></svg></span>
                            Mejora de perfil profesional con IA
                        </li>
                        <li>
                            <span class="ap-check" style="background:#dcfce7"><svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="#16a34a" stroke-width="3"><polyline points="20 6 9 17 4 12"/></svg></span>
                            Editor visual completo por secciones
                        </li>
                        <li>
                            <span class="ap-check" style="background:#dcfce7"><svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="#16a34a" stroke-width="3"><polyline points="20 6 9 17 4 12"/></svg></span>
                            Descarga directa en PDF listo para enviar
                        </li>
                        <li>
                            <span class="ap-check" style="background:#dcfce7"><svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="#16a34a" stroke-width="3"><polyline points="20 6 9 17 4 12"/></svg></span>
                            Compatible con todos los sistemas de selección (ATS)
                        </li>
                    </ul>

                    <a href="{{ auth()->check() ? route('cv-pdf.editor') : route('register') }}" class="btn-lg-primary" style="display:inline-flex;background:#16a34a">
                        Abrir editor CV PDF
                    </a>
                </div>

                <div class="ap-card" style="display:flex;flex-direction:column;gap:1rem;padding:2rem">
                    <div style="text-align:center;padding:2rem;background:#f0fdf4;border-radius:12px;border:1px solid #bbf7d0">
                        <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="#16a34a" stroke-width="1.5" style="margin:0 auto 1rem"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/><polyline points="10 9 9 9 8 9"/></svg>
                        <p style="font-size:.875rem;font-weight:600;color:#15803d;margin-bottom:.5rem">Editor de CV con IA</p>
                        <p style="font-size:.78rem;color:#166534;line-height:1.55">Sube tu PDF o empieza desde cero. La IA extrae y organiza toda tu información automáticamente.</p>
                    </div>
                    <a href="{{ auth()->check() ? route('cv-pdf.editor') : route('register') }}" class="ap-plan__cta ap-plan__cta--hi" style="--pclr:#16a34a;text-align:center;display:block">
                        Probar ahora →
                    </a>
                </div>
            </div>
        </div>
    </div>

    {{-- ─────────── PLANTILLAS ─────────── --}}
    <div class="ap-panel" id="panel-plantillas">
        <div class="ap-inner">
            <div class="ap-page-title" style="display:flex;align-items:flex-start;justify-content:space-between;gap:1rem;flex-wrap:wrap">
                <div>
                    <h2>Plantillas</h2>
                    <p>{{ $templates->count() }} diseños disponibles para tu portfolio web</p>
                </div>
                <a href="{{ route('templates.list') }}" class="btn-lg-ghost" style="font-size:.8rem;padding:.5rem 1.125rem">Ver en pantalla completa →</a>
            </div>

            @if($categories->count() > 0)
            <div class="ap-filters" id="apFilters">
                <button class="ap-filter ap-filter--active" data-filter="*">
                    Todas <span>{{ $templates->count() }}</span>
                </button>
                @foreach($categories as $cat)
                    @php $n = $templates->where('category_id', $cat->id)->count(); @endphp
                    @if($n > 0)
                    <button class="ap-filter" data-filter="{{ $cat->id }}">
                        {{ $cat->name }} <span>{{ $n }}</span>
                    </button>
                    @endif
                @endforeach
            </div>
            @endif

            <div class="ap-tpl-grid" id="apTplGrid">
                @forelse($templates as $tpl)
                <a href="{{ route('templates.preview', $tpl->slug) }}"
                   class="ap-tpl-card"
                   data-cat="{{ $tpl->category_id ?? '' }}">
                    <div class="ap-tpl-thumb">
                        @if($tpl->preview_html_url)
                            <div class="ap-tpl-iframe-wrap">
                                <iframe src="{{ $tpl->preview_html_url }}"
                                        scrolling="no"
                                        sandbox="allow-same-origin allow-scripts"
                                        title="{{ $tpl->name }}"></iframe>
                            </div>
                        @elseif($tpl->preview_image)
                            <img src="{{ asset('storage/'.$tpl->preview_image) }}" alt="{{ $tpl->name }}">
                        @else
                            <div style="width:100%;height:100%;display:flex;align-items:center;justify-content:center;background:#e2e8f0">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#94a3b8" stroke-width="1.2"><rect x="3" y="3" width="18" height="18" rx="2"/><path d="M3 9h18M9 21V9"/></svg>
                            </div>
                        @endif
                        <div class="ap-tpl-overlay"><span>Ver plantilla →</span></div>
                        @php $ti = \App\Models\Template::PLAN_TIERS[$tpl->plan_tier] ?? null; @endphp
                        @if($tpl->is_premium && $ti)
                            <div class="ap-tpl-tier" style="background:{{ $ti['color'] }}">{{ $ti['label'] }}</div>
                        @endif
                        @if($tpl->is_featured)
                            <div class="ap-tpl-star">★</div>
                        @endif
                    </div>
                    <div class="ap-tpl-foot">
                        <span class="ap-tpl-name">{{ $tpl->name }}</span>
                        @if($tpl->category)
                            <span class="ap-tpl-cat">{{ $tpl->category->name }}</span>
                        @endif
                    </div>
                </a>
                @empty
                <p style="grid-column:1/-1;text-align:center;color:#94a3b8;padding:3rem 0">No hay plantillas activas aún.</p>
                @endforelse
            </div>
        </div>
    </div>

    {{-- ─────────── PLANES ─────────── --}}
    <div class="ap-panel" id="panel-planes">
        <div class="ap-inner">
            <div class="ap-page-title" style="text-align:center;max-width:520px;margin:0 auto 2.5rem">
                <h2>Planes y precios</h2>
                <p>Sin sorpresas. Sin letra pequeña. Cancela cuando quieras.</p>
            </div>

            <div class="ap-plans-grid">
                @forelse($plans as $plan)
                <div class="ap-plan {{ $plan->badge_label ? 'ap-plan--featured' : '' }}"
                     style="--pclr:{{ $plan->color }}">
                    @if($plan->badge_label)
                        <div class="ap-plan__badge">{{ $plan->badge_label }}</div>
                    @endif
                    <div class="ap-plan__name">{{ $plan->name }}</div>
                    <div class="ap-plan__price">
                        <strong>€{{ number_format($plan->price, 2, ',', '.') }}</strong>
                        <span>{{ $plan->billing_cycle === 'once' ? 'pago único' : '/ año' }}</span>
                    </div>
                    @if($plan->features)
                    <ul class="ap-plan__features">
                        @foreach($plan->features as $feat)
                        <li>
                            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><polyline points="20 6 9 17 4 12"/></svg>
                            {{ $feat }}
                        </li>
                        @endforeach
                    </ul>
                    @endif
                    <a href="{{ auth()->check() ? route('checkout.show', $plan->slug) : route('register') }}"
                       class="ap-plan__cta {{ $plan->badge_label ? 'ap-plan__cta--hi' : '' }}">
                        Empezar con {{ $plan->name }}
                    </a>
                </div>
                @empty
                <p style="grid-column:1/-1;text-align:center;color:#94a3b8;padding:3rem 0">Próximamente.</p>
                @endforelse
            </div>
        </div>
    </div>

</main>

{{-- ══════════════════ FOOTER MINI ══════════════════ --}}
<footer class="ap-footer">
    <p>© {{ date('Y') }} CvXpress. Todos los derechos reservados.</p>
    <div class="ap-footer__links">
        <a href="{{ route('home') }}">Inicio</a>
        <a href="{{ route('about') }}">Sobre nosotros</a>
        <a href="{{ route('privacy') }}">Privacidad</a>
        <a href="{{ route('terms') }}">Términos</a>
        <a href="{{ route('contact') }}">Contacto</a>
    </div>
</footer>

<script>
(function () {

    /* ── Panel switching ── */
    var panels  = document.querySelectorAll('.ap-panel');
    var allTabs = document.querySelectorAll('.ap-tab');

    window.apSwitch = function (name) {
        panels.forEach(function (p) { p.classList.remove('ap-panel--active'); });
        allTabs.forEach(function (t) { t.classList.remove('ap-tab--active'); });

        var panel = document.getElementById('panel-' + name);
        if (panel) panel.classList.add('ap-panel--active');

        allTabs.forEach(function (t) {
            if (t.dataset.panel === name) t.classList.add('ap-tab--active');
        });

        /* Scale iframes in the newly visible panel */
        if (panel) {
            requestAnimationFrame(function () {
                scaleWraps(panel.querySelectorAll('.ap-tpl-iframe-wrap'));
                scaleWraps(panel.querySelectorAll('.ap-mini-iframe'));
            });
        }

        /* Close mobile drawer */
        var drawer = document.getElementById('apDrawer');
        if (drawer) drawer.classList.remove('open');
    };

    allTabs.forEach(function (tab) {
        tab.addEventListener('click', function () {
            apSwitch(tab.dataset.panel);
        });
    });

    /* ── Hamburger ── */
    var ham = document.getElementById('apHamburger');
    var drawer = document.getElementById('apDrawer');
    if (ham && drawer) {
        ham.addEventListener('click', function () {
            drawer.classList.toggle('open');
        });
    }

    /* ── Scale iframes ── */
    function scaleWraps(wraps) {
        wraps.forEach(function (wrap) {
            var iframe = wrap.querySelector('iframe');
            if (!iframe) return;
            var w = wrap.offsetWidth;
            var h = wrap.offsetHeight;
            if (!w || !h) return;
            var scale = w / 1280;
            iframe.style.transform = 'scale(' + scale + ')';
            iframe.style.height    = Math.ceil(h / scale) + 'px';
        });
    }

    function scaleAll() {
        var active = document.querySelector('.ap-panel--active');
        if (active) scaleWraps(active.querySelectorAll('.ap-tpl-iframe-wrap, .ap-mini-iframe'));
        /* Hero iframe */
        var heroWrap = document.getElementById('apHeroIframe');
        if (heroWrap) {
            var iframe = heroWrap.querySelector('iframe');
            if (iframe) {
                var w = heroWrap.offsetWidth;
                var h = heroWrap.offsetHeight;
                if (w && h) {
                    iframe.style.transform = 'scale(' + (w / 1280) + ')';
                    iframe.style.height    = Math.ceil(h / (w / 1280)) + 'px';
                }
            }
        }
    }

    scaleAll();
    window.addEventListener('resize', scaleAll);

    /* ── Category filter (plantillas panel) ── */
    var filters = document.querySelectorAll('#apFilters .ap-filter');
    var cards   = document.querySelectorAll('#apTplGrid .ap-tpl-card');

    filters.forEach(function (btn) {
        btn.addEventListener('click', function () {
            filters.forEach(function (b) { b.classList.remove('ap-filter--active'); });
            btn.classList.add('ap-filter--active');
            var f = btn.dataset.filter;
            cards.forEach(function (card) {
                var show = f === '*' || String(card.dataset.cat) === String(f);
                card.style.display = show ? '' : 'none';
            });
            /* Re-scale visible iframes after filter */
            requestAnimationFrame(scaleAll);
        });
    });

})();
</script>
</body>
</html>
