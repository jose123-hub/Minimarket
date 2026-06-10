<h1>Create Product</h1>

<form action="/products" method="POST">

    @csrf

    <label>Category</label>

    <select name="category_id">

        @foreach($categories as $category)

            <option value="{{ $category->id }}">
                {{ $category->nombre }}
            </option>

        @endforeach

    </select>

    <br><br>

    <label>Name</label>
    <input type="text" name="nombre">

    <br><br>

    <label>Description</label>
    <textarea name="descripcion"></textarea>

    <br><br>

    <label>Price</label>
    <input type="number" step="0.01" name="precio">

    <br><br>

    <label>Stock</label>
    <input type="number" name="stock">

    <br><br>

    <button type="submit">
        Save Product
    </button>

</form>