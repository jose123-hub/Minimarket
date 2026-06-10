<h1>Products List</h1>

<a href="/products/create">
    New Product
</a>

<hr>

<table border="1">

    <tr>
        <th>Category</th>
        <th>Name</th>
        <th>Description</th>
        <th>Price</th>
        <th>Stock</th>
    </tr>

    @foreach($products as $product)

    <tr>

        <td>{{ $product->category->nombre }}</td>

        <td>{{ $product->nombre }}</td>

        <td>{{ $product->descripcion }}</td>

        <td>{{ $product->precio }}</td>

        <td>{{ $product->stock }}</td>

    </tr>

    @endforeach

</table>