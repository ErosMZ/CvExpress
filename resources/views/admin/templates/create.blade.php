<h1>Crear plantilla</h1>

<form 
    action="{{ route('templates.store') }}"
    method="POST"
    enctype="multipart/form-data"
>

    @csrf

    {{-- Nombre --}}

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

    {{-- Descripción --}}

    <label>
        Descripción
    </label>

    <br>

    <textarea 
        name="description"
    ></textarea>

    <br><br>

    {{-- Categoría --}}

    <label>
        Categoría
    </label>

    <br>

    <select name="category_id">

        <option value="">
            Sin categoría
        </option>

        @foreach($categories as $category)

            <option value="{{ $category->id }}">

                {{ $category->name }}

            </option>

        @endforeach

    </select>

    <br><br>

    {{-- Precio --}}

    <label>
        Precio (€)
    </label>

    <br>

    <input 
        type="number"
        step="0.01"
        name="price"
        value="0"
    >

    <br><br>

    {{-- Preview --}}

    <label>
        Imagen preview
    </label>

    <br>

    <input 
        type="file"
        name="preview_image"
        accept="image/*"
    >

    <br><br>

    {{-- ZIP --}}

    <label>
        ZIP plantilla
    </label>

    <br>

    <input 
        type="file"
        name="template_zip"
        accept=".zip"
        required
    >

    <br><br>

    {{-- Premium --}}

    <label>

        <input 
            type="checkbox"
            name="is_premium"
        >

        Premium

    </label>

    <br><br>

    {{-- Destacada --}}

    <label>

        <input 
            type="checkbox"
            name="is_featured"
        >

        Destacada

    </label>

    <br><br>

    {{-- Activa --}}

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
        Guardar plantilla
    </button>

</form>

<br><br>

<a href="{{ route('templates.index') }}">
    Volver
</a>