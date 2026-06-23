<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            New Product
        </h2>
    </x-slot>

    <div class="py-8 max-w-xl mx-auto px-4">
        <form action="/admin/products" method="POST" class="space-y-4" enctype="multipart/form-data">
            @csrf

            <div>
                <label class="block text-sm font-medium text-gray-700">Image (optional)</label>
                <div class="flex items-center gap-3 mt-1">
                    <img src="" alt="" id="image-preview" class="w-14 h-14 rounded object-cover border border-gray-200" style="display:none;">
                    <input type="file" name="image" id="image" accept="image/png,image/jpeg,image/webp"
                           class="block w-full border border-gray-300 rounded px-3 py-2">
                </div>
                <p class="text-xs text-gray-400 mt-1">JPG, PNG or WEBP — max 2MB.</p>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Category</label>
                <select name="category_id"
                        class="mt-1 block w-full border border-gray-300 rounded px-3 py-2">
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Name</label>
                <input type="text" name="name" value="{{ old('name') }}"
                       class="mt-1 block w-full border border-gray-300 rounded px-3 py-2">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Description</label>
                <textarea name="description" rows="3"
                          class="mt-1 block w-full border border-gray-300 rounded px-3 py-2">{{ old('description') }}</textarea>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Price</label>
                <input type="number" step="0.01" name="price" value="{{ old('price') }}"
                       class="mt-1 block w-full border border-gray-300 rounded px-3 py-2">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Stock</label>
                <input type="number" name="stock" value="{{ old('stock') }}"
                       class="mt-1 block w-full border border-gray-300 rounded px-3 py-2">
            </div>

            <div class="flex gap-3">
                <button type="submit"
                        class="bg-indigo-600 text-black px-4 py-2 rounded hover:bg-indigo-700">
                    Save
                </button>
                <a href="/admin/products"
                   class="bg-gray-200 text-gray-700 px-4 py-2 rounded hover:bg-gray-300">
                    Cancel
                </a>
            </div>
        </form>
    </div>

    <script>
        document.getElementById('image').addEventListener('change', function (e) {
            const preview = document.getElementById('image-preview');
            const file = e.target.files[0];
            if (file) {
                preview.src = URL.createObjectURL(file);
                preview.style.display = 'block';
            }
        });
    </script>
</x-app-layout>