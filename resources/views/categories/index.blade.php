<h1>Categories List</h1>

<a href="/categories/create">
    New Category
</a>

<hr>

<table border="1">
    <tr>
        <th>Name</th>
        <th>Description</th>
        <th>Actions</th>
    </tr>

    @foreach($categories as $category)
    <tr>
        <td>{{ $category->nombre }}</td>
        <td>{{ $category->descripcion }}</td>
        <td>
            <a href="/categories/{{ $category->id }}/edit">
                Edit
            </a>
        </td>
    </tr>
    @endforeach

</table>