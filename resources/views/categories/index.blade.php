<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Categories
        </h2>
    </x-slot>

    <div class="py-8 max-w-5xl mx-auto px-4">

        @if(session('success'))
            <div class="mb-4 p-3 bg-green-100 text-green-700 rounded">
                {{ session('success') }}
            </div>
        @endif

        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-medium">Categories List</h3>
            <a href="/admin/categories/create"
               class="bg-indigo-600 text-gray px-4 py-2 rounded hover:bg-indigo-700">
                + New Category
            </a>
        </div>

        <table class="w-full border border-gray-200 rounded">
            <thead class="bg-gray-100">
                <tr>
                    <th class="text-left p-3">Name</th>
                    <th class="text-left p-3">Description</th>
                    <th class="text-center p-3">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($categories as $category)
                <tr class="border-t border-gray-200">
                    <td class="p-3">{{ $category->nombre }}</td>
                    <td class="p-3">{{ $category->descripcion }}</td>
                    <td class="p-3 text-center space-x-2">
                        <a href="/admin/categories/{{ $category->id }}/edit"
                           class="bg-yellow-400 text-black px-3 py-1 rounded hover:bg-yellow-500">
                            Edit
                        </a>
                        <form action="/admin/categories/{{ $category->id }}" method="POST" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                    onclick="return confirm('Delete this category?')"
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