{{-- resources/views/login.blade.php --}}
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar sesión — CvXpress</title>
    <link rel="icon" href="/images/logo2Web.png" type="image/png">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <link rel="stylesheet" href="{{ asset('css/auth.css') }}">
</head>
<body class="authsplit-page">

    <div class="authsplit-shell">

        {{-- ── PANEL IZQUIERDO (azul) ── --}}
        <aside class="authsplit-panel">
            <div class="authsplit-panel__glow" aria-hidden="true"></div>

            <a href="/" class="authsplit-panel__logo">
                <img src="/images/logo2Web.webp" alt="CvXpress">
            </a>

            <div class="authsplit-panel__body">
                <span class="authsplit-panel__eyebrow">Acceso seguro</span>
                <h1 class="authsplit-panel__heading">Bienvenido de nuevo</h1>
                <p class="authsplit-panel__text">Gestiona tu CV, elige tu plantilla y publica tu portfolio web desde un único sitio.</p>
            </div>

            <p class="authsplit-panel__footer">
                &copy; {{ date('Y') }} CvXpress · <a href="/privacidad">Privacidad</a> · <a href="/terminos">Términos</a>
            </p>
        </aside>

        {{-- ── LADO DEL FORMULARIO (blanco) ── --}}
        <div class="authsplit-formside">
            <div class="authsplit-form">

                <div class="authsplit-form__top">
                    <a href="/" class="authsplit-form__wordmark">CvXpress</a>
                    <a href="/register" class="authsplit-form__toplink">¿No tienes cuenta? <strong>Crear una</strong></a>
                </div>

                <h1 class="authsplit-form__heading">Bienvenido de nuevo</h1>
                <p class="authsplit-form__sub">Inicia sesión para gestionar tu portfolio.</p>

                @if(session('error'))
                    <div class="authsplit-alert" role="alert">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                        {{ session('error') }}
                    </div>
                @endif

                <form method="POST" action="/login">
                    @csrf
                    <div class="authsplit-field">
                        <label for="email">Correo electrónico</label>
                        <input type="email" id="email" name="email" placeholder="tu@email.com" autocomplete="email" autofocus required>
                    </div>
                    <div class="authsplit-field" style="margin-bottom:.5rem;">
                        <label for="password">Contraseña</label>
                        <input type="password" id="password" name="password" placeholder="••••••••" autocomplete="current-password" required>
                    </div>

                    <button type="submit" class="authsplit-btn">
                        Iniciar sesión
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
                    </button>
                </form>

                <p class="authsplit-switch">¿No tienes cuenta? <a href="/register">Regístrate gratis</a></p>
            </div>
        </div>

    </div>

</body>
</html>
