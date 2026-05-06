<h1>Editar categoría</h1>

<form 
    action="{{ route('categories.update', $category) }}"
    method="POST"
>

    @csrf
    @method('PUT')

    <label>
        Nombre
    </label>

    <br>

    <input 
        type="text"
        name="name"
        value="{{ $category->name }}"
        required
    >

    <br><br>

    <label>

        <input 
            type="checkbox"
            name="is_active"
            {{ $category->is_active ? 'checked' : '' }}
        >

        Activa

    </label>

    <br><br>

    <button type="submit">
        Guardar cambios
    </button>

</form>

<br>

<a href="{{ route('categories.index') }}">
    Volver
</a>