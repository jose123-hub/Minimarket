<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Edit Product
        </h2>
    </x-slot>

    <div class="py-8 max-w-xl mx-auto px-4">
        <form action="/admin/products/{{ $product->id }}" method="POST" class="space-y-4">
            @csrf
            @method('PUT')

            <div>
                <label class="block text-sm font-medium text-gray-700">Category</label>
                <select name="category_id"
                        class="mt-1 block w-full border border-gray-300 rounded px-3 py-2">
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}"
                            {{ $product->category_id == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Name</label>
                <input type="text" name="name" value="{{ $product->name }}"
                       class="mt-1 block w-full border border-gray-300 rounded px-3 py-2">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Description</label>
                <textarea name="description" rows="3"
                          class="mt-1 block w-full border border-gray-300 rounded px-3 py-2">{{ $product->description }}</textarea>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Price</label>
                <input type="number" step="0.01" name="price" value="{{ $product->price }}"
                       class="mt-1 block w-full border border-gray-300 rounded px-3 py-2">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Stock</label>
                <input type="number" name="stock" value="{{ $product->stock }}"
                       class="mt-1 block w-full border border-gray-300 rounded px-3 py-2">
            </div>

            <div class="flex gap-3">
                <button type="submit"
                        class="bg-indigo-600 text-black px-4 py-2 rounded hover:bg-indigo-700">
                    Update
                </button>
                <a href="/admin/products"
                   class="bg-gray-200 text-gray-700 px-4 py-2 rounded hover:bg-gray-300">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</x-app-layout>