<h1>Crear categoría</h1>

<form 
    action="{{ route('categories.store') }}"
    method="POST"
>

    @csrf

    <label>
        Nombre
    </label>

    <br>

    <input 
        type="text"
        name="name"
        required
    >

    <br><br>

    <label>

        <input 
            type="checkbox"
            name="is_active"
            checked
        >

        Activa

    </label>

    <br><br>

    <button type="submit">
        Crear categoría
    </button>

</form>

<br>

<a href="{{ route('categories.index') }}">
    Volver
</a>