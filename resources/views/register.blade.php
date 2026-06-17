{{-- resources/views/auth/register.blade.php --}}
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear cuenta — CvXpres</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:opsz,wght@9..40,300;9..40,400;9..40,500;9..40,600;9..40,700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <link rel="stylesheet" href="{{ asset('css/auth.css') }}">
</head>
<body class="auth-page">

    {{-- Tarjetas flotantes --}}
    <div class="auth-bg-cards" aria-hidden="true">
        <div class="auth-float-card fc-1">
            <div class="auth-float-card__line auth-float-card__line--name"></div>
            <div class="auth-float-card__line auth-float-card__line--role"></div>
            <div class="auth-float-card__section"></div>
            <div class="auth-float-card__line auth-float-card__line--med"></div>
            <div class="auth-float-card__line auth-float-card__line--short"></div>
        </div>
        <div class="auth-float-card fc-2">
            <div class="auth-float-card__avatar"></div>
            <div class="auth-float-card__line auth-float-card__line--name"></div>
            <div class="auth-float-card__line auth-float-card__line--role"></div>
            <div class="auth-float-card__tags">
                <div class="auth-float-card__tag"></div>
                <div class="auth-float-card__tag"></div>
            </div>
        </div>
        <div class="auth-float-card fc-3 auth-float-card--bright">
            <div class="auth-float-card__line auth-float-card__line--name"></div>
            <div class="auth-float-card__line auth-float-card__line--role"></div>
            <div class="auth-float-card__section"></div>
            <div class="auth-float-card__line auth-float-card__line--med"></div>
            <div class="auth-float-card__line auth-float-card__line--short"></div>
        </div>
        <div class="auth-float-card fc-4 auth-float-card--bright">
            <div class="auth-float-card__avatar"></div>
            <div class="auth-float-card__line auth-float-card__line--name"></div>
            <div class="auth-float-card__line auth-float-card__line--role"></div>
            <div class="auth-float-card__tags">
                <div class="auth-float-card__tag"></div>
                <div class="auth-float-card__tag"></div>
            </div>
        </div>
        <div class="auth-float-card fc-5">
            <div class="auth-float-card__line auth-float-card__line--name"></div>
            <div class="auth-float-card__line auth-float-card__line--role"></div>
            <div class="auth-float-card__section"></div>
            <div class="auth-float-card__line"></div>
        </div>
        <div class="auth-float-card fc-6">
            <div class="auth-float-card__avatar"></div>
            <div class="auth-float-card__line auth-float-card__line--name"></div>
            <div class="auth-float-card__line auth-float-card__line--role"></div>
        </div>
        <div class="auth-float-card fc-7">
            <div class="auth-float-card__line auth-float-card__line--name"></div>
            <div class="auth-float-card__line auth-float-card__line--role"></div>
        </div>
    </div>

    {{-- Botón volver --}}
    <a href="/" class="auth-back">
        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M19 12H5M12 5l-7 7 7 7"/>
        </svg>
        Volver al inicio
    </a>

    <div class="auth-wrapper">

        <a href="/" class="auth-logo">
            <div style="width:200px;height:56px;overflow:hidden;">
                <img src="/images/logo2Web.webp" alt="CVX" style="width:455px;height:auto;margin-left:-123px;margin-top:-124px;display:block;max-width:none;">
            </div>
        </a>

        <div class="auth-card">
            <span class="auth-card__eyebrow">
                <span class="auth-card__eyebrow-dot"></span>
                Es gratis
            </span>
            <h1 class="auth-card__heading">Crea tu cuenta</h1>
            <p class="auth-card__subheading">Empieza a construir tu portfolio profesional</p>

            @if ($errors->any())
                <div class="auth-alert" role="alert">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="flex-shrink:0;margin-top:2px">
                        <circle cx="12" cy="12" r="10"/>
                        <line x1="12" y1="8" x2="12" y2="12"/>
                        <line x1="12" y1="16" x2="12.01" y2="16"/>
                    </svg>
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="/register">
                @csrf

                {{-- Nombre + Email en fila en pantallas grandes --}}
                <div class="auth-form-row">
                    <div class="auth-form-group">
                        <label for="name" class="auth-label">Nombre completo</label>
                        <input type="text" id="name" name="name"
                            class="auth-input" placeholder="María García"
                            value="{{ old('name') }}"
                            autocomplete="name" required>
                    </div>
                    <div class="auth-form-group">
                        <label for="email" class="auth-label">Correo electrónico</label>
                        <input type="email" id="email" name="email"
                            class="auth-input" placeholder="tu@email.com"
                            value="{{ old('email') }}"
                            autocomplete="email" required>
                    </div>
                </div>

                <div class="auth-form-group">
                    <label for="password" class="auth-label">Contraseña</label>
                    <input type="password" id="password" name="password"
                        class="auth-input" placeholder="Mínimo 8 caracteres"
                        autocomplete="new-password" required>
                </div>

                <div class="auth-form-group">
                    <label for="password_confirmation" class="auth-label">Confirmar contraseña</label>
                    <input type="password" id="password_confirmation" name="password_confirmation"
                        class="auth-input" placeholder="Repite la contraseña"
                        autocomplete="new-password" required>
                </div>

                <button type="submit" class="auth-btn">
                    Crear cuenta gratis
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M5 12h14M12 5l7 7-7 7"/>
                    </svg>
                </button>
            </form>

            <div class="auth-card__footer">
                ¿Ya tienes cuenta? <a href="/login">Inicia sesión aquí</a>
            </div>
        </div>

        <p class="auth-page-footer">
            &copy; {{ date('Y') }} CvXpress &nbsp;·&nbsp;
            <a href="/privacidad">Privacidad</a> &nbsp;·&nbsp;
            <a href="/terminos">Términos</a>
        </p>

    </div>

</body>
</html>