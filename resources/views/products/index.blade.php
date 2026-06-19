<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Products
        </h2>
    </x-slot>

    <div class="py-8 max-w-6xl mx-auto px-4">

        @if(session('success'))
            <div class="mb-4 p-3 bg-green-100 text-green-700 rounded">
                {{ session('success') }}
            </div>
        @endif

        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-medium">Products List</h3>
            <a href="/admin/products/create"
               class="bg-indigo-600 text-black px-4 py-2 rounded hover:bg-indigo-700">
                + New Product
            </a>
        </div>

        <table class="w-full border border-gray-200 rounded">
            <thead class="bg-gray-100">
                <tr>
                    <th class="text-left p-3">Category</th>
                    <th class="text-left p-3">Name</th>
                    <th class="text-left p-3">Description</th>
                    <th class="text-left p-3">Price</th>
                    <th class="text-left p-3">Stock</th>
                    <th class="text-center p-3">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($products as $product)
                <tr class="border-t border-gray-200">
                    <td class="p-3">{{ $product->category?->name }}</td>
                    <td class="p-3">{{ $product->name }}</td>
                    <td class="p-3">{{ $product->description }}</td>
                    <td class="p-3">S/ {{ number_format($product->price, 2) }}</td>
                    <td class="p-3">{{ $product->stock }}</td>
                    <td class="p-3 text-center space-x-2">
                        <a href="/admin/products/{{ $product->id }}/edit"
                           class="bg-yellow-400 text-black px-3 py-1 rounded hover:bg-yellow-500">
                            Edit
                        </a>
                        <form action="/admin/products/{{ $product->id }}" method="POST" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                    onclick="return confirm('Delete this product?')"
                                    class="bg-red-500 text-black px-3 py-1 rounded hover:bg-red-600">
                                Delete
                            </button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</x-app-layout>