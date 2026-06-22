<x-admin-layout
    title="Edit category"
    subtitle="Update the category details"
    active="categories"
>

    <div class="table-card" style="max-width:520px; padding: 28px 30px;">
      <form action="{{ route('categories.update', $category) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="form-group">
          <label for="name">Category name</label>
          <input type="text" id="name" name="name" value="{{ old('name', $category->name) }}" required>
          @error('name') <div class="field-error">{{ $message }}</div> @enderror
        </div>

        <div class="form-group">
          <label for="description">Description (optional)</label>
          <textarea id="description" name="description" rows="3">{{ old('description', $category->description) }}</textarea>
          @error('description') <div class="field-error">{{ $message }}</div> @enderror
        </div>

        <div class="modal-actions" style="margin-top:6px;">
          <a href="{{ route('categories.index') }}" class="btn">
            <svg viewBox="0 0 24 24"><line x1="19" y1="12" x2="5" y2="12"/><polyline points="12 19 5 12 12 5"/></svg>
            Cancel
          </a>
          <button type="submit" class="btn btn-primary">
            <svg viewBox="0 0 24 24"><path d="M19 21H5a2 2 0 01-2-2V5a2 2 0 012-2h11l5 5v11a2 2 0 01-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/></svg>
            Update category
          </button>
        </div>
      </form>
    </div>

</x-admin-layout>