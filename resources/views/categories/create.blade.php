<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            New Category
        </h2>
    </x-slot>

    <div class="py-8 max-w-xl mx-auto px-4">
        <form action="/admin/categories" method="POST" class="space-y-4">
            @csrf

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

            <div class="flex gap-3">
                <button type="submit"
                        class="bg-indigo-600 text-black px-4 py-2 rounded hover:bg-indigo-700">
                    Save
                </button>
                <a href="/admin/categories"
                   class="bg-gray-200 text-gray-700 px-4 py-2 rounded hover:bg-gray-300">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</x-app-layout>