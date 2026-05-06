<h1>Plantillas</h1>

<a href="{{ route('templates.create') }}">
    Crear plantilla
</a>

<hr>

@foreach($templates as $template)

    <div>

        <h2>{{ $template->name }}</h2>

        <p>{{ $template->description }}</p>

        @if($template->preview_image)

            <img 
                src="{{ asset('storage/' . $template->preview_image) }}"
                width="200"
            >

        @endif

        <br><br>

        <a href="{{ route('templates.edit', $template) }}">
            Editar
        </a>

        <form 
            action="{{ route('templates.destroy', $template) }}"
            method="POST"
        >
            @csrf
            @method('DELETE')

            <button type="submit">
                Eliminar
            </button>

        </form>

    </div>

    <hr>

@endforeach