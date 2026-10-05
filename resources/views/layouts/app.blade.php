<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'CvXpress') — Tu CV, Tu Web</title>
    <link rel="icon" href="/images/logo2Web.png" type="image/png">
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
    <nav class="nav {{ request()->routeIs('home') ? '' : 'nav--scrolled' }}" id="main-nav">
        <div class="nav__container container">
            <a href="{{ route('home') }}" class="nav__logo">
                <div style="width:140px;height:53px;overflow:hidden;flex-shrink:0;">
                    <img src="{{ asset('images/logo2Web.webp') }}" alt="CVX" style="width:244px;height:auto;margin-left:-54px;margin-top:-52px;display:block;max-width:none;">
                </div>
            </a>

            <button class="nav__toggle" id="nav-toggle" aria-label="Abrir menú" aria-expanded="false" aria-controls="nav-menu">
                <span></span><span></span><span></span>
            </button>

            <ul class="nav__menu" id="nav-menu" role="list">
                <li><a href="{{ route('home') }}" id="nav-inicio" class="nav__link">Inicio</a></li>
                <li><a href="{{ route('templates.list') }}" class="nav__link {{ request()->routeIs('templates.list','templates.preview') ? 'nav__link--active' : '' }}">Plantillas</a></li>
                <li><a href="{{ auth()->check() ? route('cv-web.editor') : route('register') }}" class="nav__link {{ request()->routeIs('cv-web.*') ? 'nav__link--active' : '' }}">CV Web</a></li>
                <li><a href="{{ route('cv-pdf.editor') }}" class="nav__link {{ request()->routeIs('cv-pdf.*') ? 'nav__link--active' : '' }}">CV PDF</a></li>
                <li><a href="{{ route('home') }}#precios" id="nav-precios" class="nav__link">Precios</a></li>
                @guest
                    <li><a href="{{ route('login') }}" class="nav__link">Iniciar sesión</a></li>
                    <li><a href="{{ route('register') }}" class="btn btn--primary btn--sm">Empezar gratis</a></li>
                @else
                    <li><a href="{{ route('dashboard') }}" class="btn btn--primary btn--sm {{ request()->routeIs('dashboard') ? 'btn--active-panel' : '' }}">Perfil</a></li>
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
                        <div style="width:110px;height:42px;overflow:hidden;flex-shrink:0;">
                            <img src="{{ asset('images/logo2Web.webp') }}" alt="CVX" style="width:190px;height:auto;margin-left:-42px;margin-top:-44px;display:block;max-width:none;">
                        </div>
                    </a>
                    <p class="footer__tagline">Tu currículum merece brillar en la web. Automatizamos el proceso para que tú te centres en lo que importa.</p>
                </div>

                <div class="footer__col">
                    <h3 class="footer__heading">Producto</h3>
                    <ul role="list">
                        <li><a href="{{ route('app-landing') }}" class="footer__link">Vista del producto</a></li>
                        <li><a href="{{ route('templates.list') }}" class="footer__link">Plantillas</a></li>
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
                <p>&copy; {{ date('Y') }} CvXpress. Todos los derechos reservados.</p>
                <p>Hecho con <span aria-label="amor">♥</span> en España</p>
            </div>
        </div>
    </footer>

    @stack('scripts')

    <style>
        /* ── Nav active indicator ── */
        .nav__link--active {
            color: #2563eb !important;
            position: relative;
        }
        .nav__link--active::before,
        .nav__link--active::after {
            content: '';
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            width: 1.5px;
            height: 13px;
            border-radius: 1px;
            pointer-events: none;
            background: linear-gradient(to bottom, transparent, #93c5fd, transparent);
        }
        .nav__link--active::before { left: 0; }
        .nav__link--active::after  { right: 0; }
        @media (max-width: 768px) {
            .nav__link--active::before,
            .nav__link--active::after { display: none; }
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
        const menu   = document.getElementById('nav-menu');
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

        // ── Scroll-based active for home sections ──
        (function () {
            const secInicio    = document.getElementById('nav-inicio');
            const secOfrecemos = document.getElementById('nav-ofrecemos');
            const secPrecios   = document.getElementById('nav-precios');

            if (!secInicio && !secOfrecemos && !secPrecios) return; // not in layout

            const sectionMap = [
                { sectionId: 'caracteristicas', link: secOfrecemos },
                { sectionId: 'precios',         link: secPrecios   },
            ];

            // Only activate scroll logic on home page (sections exist)
            const hasHomeSections = sectionMap.some(m => document.getElementById(m.sectionId));
            if (!hasHomeSections) return;

            const visible = new Set();

            function updateActive() {
                // Deactivate all three
                [secInicio, secOfrecemos, secPrecios].forEach(l => l && l.classList.remove('nav__link--active'));

                if (visible.has('precios')) {
                    secPrecios && secPrecios.classList.add('nav__link--active');
                } else if (visible.has('caracteristicas')) {
                    secOfrecemos && secOfrecemos.classList.add('nav__link--active');
                } else {
                    secInicio && secInicio.classList.add('nav__link--active');
                }
            }

            const observer = new IntersectionObserver((entries) => {
                entries.forEach(e => {
                    if (e.isIntersecting) visible.add(e.target.id);
                    else                  visible.delete(e.target.id);
                });
                updateActive();
            }, { threshold: 0.25 });

            sectionMap.forEach(m => {
                const el = document.getElementById(m.sectionId);
                if (el) observer.observe(el);
            });

            // Set initial state
            updateActive();
        })();
    </script>
</body>
</html>