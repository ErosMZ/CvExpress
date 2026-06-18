<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cuenta verificada — CvXpress</title>
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
            max-width: 420px;
            width: 100%;
            text-align: center;
        }
        .icon {
            width: 64px; height: 64px;
            border-radius: 50%;
            background: #16a34a22;
            border: 2px solid #22c55e;
            display: flex; align-items: center; justify-content: center;
            margin: 0 auto 24px;
            animation: pop .4s ease;
        }
        @keyframes pop {
            from { transform: scale(.6); opacity: 0; }
            to   { transform: scale(1);  opacity: 1; }
        }
        .icon svg { width: 28px; height: 28px; color: #22c55e; }
        h1 { font-size: 1.4rem; font-weight: 600; margin-bottom: 10px; }
        p { color: #94a3b8; font-size: .95rem; line-height: 1.6; }
        .hint { margin-top: 28px; font-size: .8rem; color: #475569; }
    </style>
</head>
<body>
<div class="card">
    <div class="icon">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
            <polyline points="20 6 9 17 4 12"/>
        </svg>
    </div>

    <h1>¡Cuenta verificada!</h1>
    <p>Tu cuenta ha sido activada correctamente.</p>
    <p class="hint">Puedes cerrar esta pestaña y volver a la ventana donde te registraste.</p>
</div>
</body>
</html>
