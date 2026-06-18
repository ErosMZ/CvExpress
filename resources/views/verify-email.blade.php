<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verifica tu correo — CvXpress</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:opsz,wght@9..40,400;9..40,500;9..40,600&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: 'DM Sans', sans-serif;
            background: #0f172a;
            color: #f1f5f9;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .card {
            background: #1e293b;
            border: 1px solid #334155;
            border-radius: 16px;
            padding: 48px 40px;
            max-width: 460px;
            width: 100%;
            text-align: center;
        }
        .icon {
            width: 64px; height: 64px;
            border-radius: 50%;
            background: #1e40af22;
            border: 2px solid #3b82f6;
            display: flex; align-items: center; justify-content: center;
            margin: 0 auto 24px;
        }
        .icon svg { width: 28px; height: 28px; color: #60a5fa; }
        h1 { font-size: 1.4rem; font-weight: 600; margin-bottom: 10px; }
        p { color: #94a3b8; font-size: .95rem; line-height: 1.6; margin-bottom: 28px; }
        .status {
            display: flex; align-items: center; justify-content: center; gap: 8px;
            font-size: .85rem; color: #64748b; margin-bottom: 28px;
        }
        .spinner {
            width: 14px; height: 14px;
            border: 2px solid #334155;
            border-top-color: #3b82f6;
            border-radius: 50%;
            animation: spin .8s linear infinite;
        }
        @keyframes spin { to { transform: rotate(360deg); } }
        .btn-resend {
            background: none;
            border: 1px solid #334155;
            color: #94a3b8;
            padding: 9px 20px;
            border-radius: 8px;
            font-family: inherit;
            font-size: .85rem;
            cursor: pointer;
            transition: border-color .15s, color .15s;
        }
        .btn-resend:hover { border-color: #3b82f6; color: #60a5fa; }
        .btn-resend:disabled { opacity: .5; cursor: default; }
        .msg { font-size: .8rem; color: #22c55e; margin-top: 10px; min-height: 18px; }
    </style>
</head>
<body>
<div class="card">
    <div class="icon">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
            <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/>
            <polyline points="22,6 12,13 2,6"/>
        </svg>
    </div>

    <h1>Revisa tu correo</h1>
    <p>Te hemos enviado un enlace de verificación. Haz clic en él para activar tu cuenta.</p>

    <div class="status">
        <div class="spinner" id="spinner"></div>
        <span id="status-text">Esperando verificación…</span>
    </div>

    <form method="POST" action="{{ route('verification.send') }}" id="resend-form">
        @csrf
        <button type="submit" class="btn-resend" id="resend-btn">Reenviar correo</button>
    </form>
    <p class="msg" id="resend-msg">{{ session('message') }}</p>
</div>

<script>
    (function () {
        let attempts = 0;

        function check() {
            fetch('/email/verification-status', { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
                .then(r => r.json())
                .then(data => {
                    if (data.verified) {
                        document.getElementById('status-text').textContent = '¡Cuenta verificada! Redirigiendo…';
                        document.getElementById('spinner').style.borderTopColor = '#22c55e';
                        setTimeout(() => { window.location.href = '/dashboard'; }, 1000);
                    } else {
                        attempts++;
                        setTimeout(check, 3000);
                    }
                })
                .catch(() => { attempts++; setTimeout(check, 5000); });
        }

        check();

        document.getElementById('resend-form').addEventListener('submit', function () {
            const btn = document.getElementById('resend-btn');
            btn.disabled = true;
            btn.textContent = 'Enviado';
        });
    })();
</script>
</body>
</html>
