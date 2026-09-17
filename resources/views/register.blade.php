{{-- resources/views/register.blade.php --}}
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear cuenta — CvXpress</title>
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
                <span class="authsplit-panel__eyebrow">Es gratis</span>
                <h1 class="authsplit-panel__heading">Crea tu cuenta en segundos</h1>
                <p class="authsplit-panel__text">Sube tu CV, elige una plantilla y ten tu portfolio web publicado en minutos. Sin tarjeta, sin complicaciones.</p>
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
                    <a href="/login" class="authsplit-form__toplink">¿Ya tienes cuenta? <strong>Inicia sesión</strong></a>
                </div>

                <h1 class="authsplit-form__heading">Crea tu cuenta</h1>
                <p class="authsplit-form__sub">Empieza a construir tu portfolio profesional.</p>

                @if ($errors->any())
                    <div class="authsplit-alert" role="alert">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="/register">
                    @csrf

                    <div class="authsplit-field">
                        <label for="name">Nombre completo</label>
                        <input type="text" id="name" name="name" placeholder="María García" value="{{ old('name') }}" autocomplete="name" autofocus required>
                    </div>

                    <div class="authsplit-field">
                        <label for="email">Correo electrónico</label>
                        <input type="email" id="email" name="email" placeholder="tu@email.com" value="{{ old('email') }}" autocomplete="email" required>
                    </div>

                    <div class="authsplit-row authsplit-row--2">
                        <div class="authsplit-field">
                            <label for="password">Contraseña</label>
                            <input type="password" id="password" name="password" placeholder="Mínimo 6 caracteres" autocomplete="new-password" required>
                        </div>
                        <div class="authsplit-field">
                            <label for="password_confirmation">Confirmar</label>
                            <input type="password" id="password_confirmation" name="password_confirmation" placeholder="Repite la contraseña" autocomplete="new-password" required>
                        </div>
                    </div>

                    <button type="submit" class="authsplit-btn">
                        Crear cuenta gratis
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
                    </button>
                </form>

                <p class="authsplit-switch">¿Ya tienes cuenta? <a href="/login">Inicia sesión aquí</a></p>
            </div>
        </div>

    </div>

</body>
</html>
