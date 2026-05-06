<h1>Categorías</h1>

<a href="{{ route('categories.create') }}">
    Crear categoría
</a>

<br><br>

@foreach($categories as $category)

    <div>

        <h2>
            {{ $category->name }}
        </h2>

        <p>
            Slug: {{ $category->slug }}
        </p>

        <p>
            Activa:
            {{ $category->is_active ? 'Sí' : 'No' }}
        </p>

        <a href="{{ route('categories.edit', $category) }}">
            Editar
        </a>

        <br><br>

        <form 
            action="{{ route('categories.destroy', $category) }}"
            method="POST"
        >
dsdsd
            @csrf
            @method('DELETE')

            <button type="submit">
                Eliminar
            </button>

        </form>

    </div>

    <hr>

@endforeach

<br>

<a href="{{ route('admin') }}">
    Volver al panel
</a>