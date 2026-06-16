<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'CVtoPortfolio') — Tu CV, Tu Web</title>
    <meta name="description" content="@yield('meta_description', 'Convierte tu CV en un portfolio web profesional en minutos. Automatización inteligente para destacar en el mercado laboral.')">

    <!-- Preconnect -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,300;0,9..40,400;0,9..40,500;0,9..40,600;1,9..40,300&family=Lora:ital,wght@0,400;0,500;1,400&display=swap" rel="stylesheet">

    <!-- Styles -->
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
        <link rel="stylesheet" href="{{ asset('css/panel.css') }}">

    @stack('styles')
</head>
<body class="antialiased">

    <!-- Navigation -->
    <nav class="nav" id="main-nav">
        <div class="nav__container container">
            <a href="{{ route('home') }}" class="nav__logo">
                <svg width="32" height="32" viewBox="0 0 32 32" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                    <rect width="32" height="32" rx="8" fill="#1A56DB"/>
                    <path d="M8 10h10M8 16h16M8 22h12" stroke="white" stroke-width="2.5" stroke-linecap="round"/>
                    <circle cx="24" cy="10" r="3" fill="#60A5FA"/>
                </svg>
                <span class="nav__logo-text">Cv<strong>Xpress</strong></span>
            </a>

            <button class="nav__toggle" id="nav-toggle" aria-label="Abrir menú" aria-expanded="false" aria-controls="nav-menu">
                <span></span><span></span><span></span>
            </button>

            <ul class="nav__menu" id="nav-menu" role="list">
                <li><a href="{{ route('home') }}#caracteristicas" class="nav__link">¿Que ofrecemos?</a></li>
                <li><a href="{{ route('templates.list') }}" class="nav__link">Plantillas</a></li>
                <li class="nav__dropdown-wrap" id="nav-crear-cv">
                    <button class="nav__link nav__dropdown-btn" aria-haspopup="true" aria-expanded="false" aria-controls="nav-crear-cv-menu">
                        Crear CV
                        <svg class="nav__dropdown-chevron" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" aria-hidden="true"><polyline points="6 9 12 15 18 9"/></svg>
                    </button>
                    <div class="nav__dropdown" id="nav-crear-cv-menu" role="menu">
                        <a href="{{ route('register') }}" class="nav__dropdown-item" role="menuitem">
                            <span class="nav__dropdown-icon nav__dropdown-icon--blue">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><rect x="2" y="3" width="20" height="14" rx="2"/><line x1="8" y1="21" x2="16" y2="21"/><line x1="12" y1="17" x2="12" y2="21"/></svg>
                            </span>
                            <strong>CV Web</strong>
                        </a>
                        <span class="nav__dropdown-sep" aria-hidden="true"></span>
                        <a href="{{ route('cv-pdf.editor') }}" class="nav__dropdown-item" role="menuitem">
                            <span class="nav__dropdown-icon nav__dropdown-icon--red">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="12" y1="12" x2="12" y2="18"/><polyline points="9 15 12 18 15 15"/></svg>
                            </span>
                            <strong>CV PDF</strong>
                        </a>
                    </div>
                </li>
                <li><a href="{{ route('home') }}#precios" class="nav__link">Precios</a></li>
                @guest
                    <li><a href="{{ route('login') }}" class="nav__link">Iniciar sesión</a></li>
                    <li><a href="{{ route('register') }}" class="btn btn--primary btn--sm">Empezar gratis</a></li>
                @else
                    <li><a href="{{ route('dashboard') }}" class="btn btn--primary btn--sm">Mi panel</a></li>
                @endguest
            </ul>
        </div>
    </nav>

    <!-- Flash messages -->
    @if(session('success'))
        <div class="alert alert--success" role="alert">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert--error" role="alert">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
            {{ session('error') }}
        </div>
    @endif

    <!-- Main content -->
    <main id="main-content">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="footer__grid">
                <div class="footer__brand">
                    <a href="{{ route('home') }}" class="nav__logo" style="margin-bottom: 1rem; display: inline-flex;">
                        <svg width="28" height="28" viewBox="0 0 32 32" fill="none" aria-hidden="true">
                            <rect width="32" height="32" rx="8" fill="#1A56DB"/>
                            <path d="M8 10h10M8 16h16M8 22h12" stroke="white" stroke-width="2.5" stroke-linecap="round"/>
                            <circle cx="24" cy="10" r="3" fill="#60A5FA"/>
                        </svg>
                        <span class="nav__logo-text">CV<strong>Portfolio</strong></span>
                    </a>
                    <p class="footer__tagline">Tu currículum merece brillar en la web. Automatizamos el proceso para que tú te centres en lo que importa.</p>
                </div>

                <div class="footer__col">
                    <h3 class="footer__heading">Producto</h3>
                    <ul role="list">
                        <li><a href="#caracteristicas" class="footer__link">Características</a></li>
                        <li><a href="#precios" class="footer__link">Precios</a></li>
                        <li>
                            <a href="#" class="footer__link footer__link--soon">
                                Publicación LinkedIn
                                <span class="badge badge--soon">Próximamente</span>
                            </a>
                        </li>
                    </ul>
                </div>

                <div class="footer__col">
                    <h3 class="footer__heading">Empresa</h3>
                    <ul role="list">
                        <li><a href="{{ route('about') }}" class="footer__link">Sobre nosotros</a></li>
                        <li><a href="{{ route('contact') }}" class="footer__link">Contacto</a></li>
                        <li><a href="{{ route('privacy') }}" class="footer__link">Privacidad</a></li>
                        <li><a href="{{ route('terms') }}" class="footer__link">Términos de uso</a></li>
                    </ul>
                </div>

                <div class="footer__col">
                    <h3 class="footer__heading">Formación</h3>
                    <ul role="list">
                        <li><a href="#" class="footer__link">Guía de portfolios</a></li>
                        <li><a href="#" class="footer__link">Consejos de CV</a></li>
                        <li><a href="#" class="footer__link">Blog</a></li>
                    </ul>
                </div>
            </div>

            <div class="footer__bottom">
                <p>&copy; {{ date('Y') }} CVPortfolio. Todos los derechos reservados.</p>
                <p>Hecho con <span aria-label="amor">♥</span> en España</p>
            </div>
        </div>
    </footer>

    @stack('scripts')

    <style>
        /* ── Nav dropdown ── */
        .nav__dropdown-wrap {
            position: relative;
        }
        .nav__dropdown-btn {
            background: none;
            border: none;
            cursor: pointer;
            font-family: inherit;
            display: inline-flex;
            align-items: center;
            gap: 4px;
            padding: 0;
        }
        .nav__dropdown-chevron {
            transition: transform 200ms ease;
            color: currentColor;
            opacity: .7;
        }
        .nav__dropdown-wrap.is-open .nav__dropdown-chevron {
            transform: rotate(180deg);
        }
        .nav__dropdown {
            position: absolute;
            top: calc(100% + 8px);
            left: 50%;
            transform: translateX(-50%);
            background: #fff;
            border: 1px solid #E5E7EB;
            border-radius: 12px;
            box-shadow: 0 8px 24px rgba(0,0,0,.10), 0 2px 6px rgba(0,0,0,.06);
            padding: 4px;
            display: none;
            z-index: 200;
            flex-direction: row;
            align-items: center;
            white-space: nowrap;
        }
        .nav__dropdown-wrap.is-open .nav__dropdown {
            display: flex;
        }
        .nav__dropdown::before {
            content: '';
            position: absolute;
            top: -5px;
            left: 50%;
            transform: translateX(-50%);
            width: 10px; height: 10px;
            background: #fff;
            border-left: 1px solid #E5E7EB;
            border-top: 1px solid #E5E7EB;
            rotate: 45deg;
        }
        @keyframes dd-in {
            from { opacity: 0; transform: translateX(-50%) translateY(-5px); }
            to   { opacity: 1; transform: translateX(-50%) translateY(0); }
        }
        .nav__dropdown-wrap.is-open .nav__dropdown {
            animation: dd-in 150ms ease forwards;
        }
        .nav__dropdown-item {
            display: flex;
            align-items: center;
            gap: 7px;
            padding: 6px 10px;
            border-radius: 8px;
            text-decoration: none;
            color: #111827;
            transition: background 130ms ease;
        }
        .nav__dropdown-item:hover { background: #F0F7FF; }
        .nav__dropdown-item--soon { opacity: .55; cursor: default; pointer-events: none; }
        .nav__dropdown-icon {
            width: 26px; height: 26px; flex-shrink: 0;
            border-radius: 6px;
            display: flex; align-items: center; justify-content: center;
        }
        .nav__dropdown-icon--blue { background: #EFF6FF; color: #1D4ED8; border: 1px solid #BFDBFE; }
        .nav__dropdown-icon--red  { background: #FEF2F2; color: #DC2626; border: 1px solid #FECACA; }
        .nav__dropdown-icon svg   { width: 13px; height: 13px; }
        .nav__dropdown-item strong { font-size: .82rem; font-weight: 600; }
        .nav__dropdown-sep {
            width: 1px; height: 20px;
            background: #E5E7EB;
            flex-shrink: 0;
            margin: 0 2px;
        }
        /* Mobile: dropdown inline en columna */
        @media (max-width: 768px) {
            .nav__dropdown {
                position: static;
                transform: none;
                box-shadow: none;
                border: none;
                background: #F8FAFF;
                border-radius: 8px;
                margin: 3px 0 0;
                padding: 3px;
                animation: none;
                flex-direction: column;
                align-items: stretch;
            }
            .nav__dropdown::before { display: none; }
            .nav__dropdown-item--soon { pointer-events: auto; opacity: .65; }
            .nav__dropdown-sep { width: 100%; height: 1px; margin: 2px 0; }
        }
    </style>

    <script>
        // Nav scroll effect
        const nav = document.getElementById('main-nav');
        window.addEventListener('scroll', () => {
            nav.classList.toggle('nav--scrolled', window.scrollY > 20);
        }, { passive: true });

        // Mobile toggle
        const toggle = document.getElementById('nav-toggle');
        const menu = document.getElementById('nav-menu');
        toggle.addEventListener('click', () => {
            const open = toggle.getAttribute('aria-expanded') === 'true';
            toggle.setAttribute('aria-expanded', String(!open));
            menu.classList.toggle('nav__menu--open');
            toggle.classList.toggle('nav__toggle--open');
        });

        // Close menu on link click
        menu.querySelectorAll('a').forEach(link => {
            link.addEventListener('click', () => {
                menu.classList.remove('nav__menu--open');
                toggle.classList.remove('nav__toggle--open');
                toggle.setAttribute('aria-expanded', 'false');
            });
        });

        // Crear CV dropdown
        const ddWrap = document.getElementById('nav-crear-cv');
        const ddBtn  = ddWrap && ddWrap.querySelector('.nav__dropdown-btn');
        if (ddBtn) {
            ddBtn.addEventListener('click', (e) => {
                e.stopPropagation();
                const isOpen = ddWrap.classList.toggle('is-open');
                ddBtn.setAttribute('aria-expanded', String(isOpen));
            });
            document.addEventListener('click', () => {
                ddWrap.classList.remove('is-open');
                ddBtn.setAttribute('aria-expanded', 'false');
            });
            ddWrap.addEventListener('click', e => e.stopPropagation());
        }
    </script>
</body>
</html>