<!-- resources/views/register.blade.php -->

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registro</title>
</head>
<body>

<div class="form-container">
    <h2>Registro</h2>

    <!-- Errores -->
    @if ($errors->any())
        <div class="error">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="/register">
        @csrf

        <input type="text" name="name" placeholder="Nombre" value="{{ old('name') }}" required>

        <input type="email" name="email" placeholder="Email" value="{{ old('email') }}" required>

        <input type="password" name="password" placeholder="Contraseña" required>

        <input type="password" name="password_confirmation" placeholder="Confirmar contraseña" required>

        <button type="submit">Registrarse</button>
    </form>
</div>

</body>
</html>