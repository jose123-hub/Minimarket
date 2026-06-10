<h1>Create Category</h1>

<form action="/categories" method="POST">
    @csrf

    <label>Name</label>
    <input type="text" name="nombre">

    <br><br>

    <label>Description</label>
    <textarea name="descripcion"></textarea>

    <br><br>

    <button type="submit">
        Save
    </button>
</form>