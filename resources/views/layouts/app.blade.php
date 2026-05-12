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
                <li><a href="{{ route('home') }}#como-funciona" class="nav__link">Cómo funciona</a></li>
                <li><a href="{{ route('home') }}#caracteristicas" class="nav__link">Características</a></li>
                <li><a href="{{ route('templates.list') }}" class="nav__link">Plantillas</a></li>
                <li><a href="{{ route('home') }}#crear-cv" class="nav__link">Crear CV</a></li>
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
                        <li><a href="#como-funciona" class="footer__link">Cómo funciona</a></li>
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
    </script>
</body>
</html>