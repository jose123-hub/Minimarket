<h1>Edit Category</h1>

<form action="/categories/{{ $category->id }}" method="POST">

    @csrf
    @method('PUT')

    <label>Name</label>
    <input
        type="text"
        name="nombre"
        value="{{ $category->nombre }}"
    >

    <br><br>

    <label>Description</label>

    <textarea name="descripcion">{{ $category->descripcion }}</textarea>

    <br><br>

    <button type="submit">
        Update
    </button>

</form>