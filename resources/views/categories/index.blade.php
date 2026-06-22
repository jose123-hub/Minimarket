<x-admin-layout
    title="Categories"
    subtitle="Organize your product catalog"
    active="categories"
>
    <div class="toolbar">
      <div class="search-box">
        <svg viewBox="0 0 24 24"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
        <input type="text" id="categorySearch" placeholder="Search category...">
      </div>

      <button class="btn btn-primary" id="openCreateModal" type="button">
        <svg viewBox="0 0 24 24"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
        New category
      </button>
    </div>

    <div class="table-card">
      <table id="categoriesTable">
        <thead>
          <tr>
            <th>Name</th>
            <th>Description</th>
            <th>Products</th>
            <th style="text-align:right">Actions</th>
          </tr>
        </thead>
        <tbody>
          @forelse($categories as $category)
            <tr data-name="{{ strtolower($category->name) }}">
              <td class="prod-name">{{ $category->name }}</td>
              <td>{{ $category->description ?: '—' }}</td>
              <td>{{ $category->products_count ?? $category->products()->count() }}</td>
              <td>
                <div class="actions" style="justify-content:flex-end">
                  <a href="{{ route('categories.edit', $category) }}" class="icon-btn">
                    <svg viewBox="0 0 24 24"><path d="M17 3a2.85 2.83 0 114 4L7.5 20.5 2 22l1.5-5.5z"/></svg>
                  </a>
                  <form action="{{ route('categories.destroy', $category) }}" method="POST" onsubmit="return confirm('Delete this category?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="icon-btn danger">
                      <svg viewBox="0 0 24 24"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14a2 2 0 01-2 2H8a2 2 0 01-2-2L5 6"/><path d="M10 11v6"/><path d="M14 11v6"/><path d="M9 6V4a1 1 0 011-1h4a1 1 0 011 1v2"/></svg>
                    </button>
                  </form>
                </div>
              </td>
            </tr>
          @empty
            <tr class="empty-row">
              <td colspan="4">There are no categories registered yet.</td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>

    <div class="modal-overlay{{ $errors->any() ? ' open' : '' }}" id="createModal">
      <div class="modal-box">
        <div class="modal-header">
          <div class="modal-header-title">
            <div class="modal-icon">
              <svg viewBox="0 0 24 24"><line x1="8" y1="6" x2="21" y2="6"/><line x1="8" y1="12" x2="21" y2="12"/><line x1="8" y1="18" x2="21" y2="18"/><line x1="3" y1="6" x2="3.01" y2="6"/><line x1="3" y1="12" x2="3.01" y2="12"/><line x1="3" y1="18" x2="3.01" y2="18"/></svg>
            </div>
            <h2>New category</h2>
          </div>
          <button type="button" class="modal-close" id="closeCreateModal">
            <svg viewBox="0 0 24 24"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
          </button>
        </div>
        <p class="modal-subtitle">Categories help you group and filter products in inventory and reports.</p>

        <form action="{{ route('categories.store') }}" method="POST">
          @csrf

          <div class="form-group">
            <label for="name">Category name</label>
            <input type="text" id="name" name="name" placeholder="E.g. Beverages" value="{{ old('name') }}" required>
            @error('name') <div class="field-error">{{ $message }}</div> @enderror
          </div>

          <div class="form-group">
            <label for="description">Description (optional)</label>
            <textarea id="description" name="description" rows="3">{{ old('description') }}</textarea>
            @error('description') <div class="field-error">{{ $message }}</div> @enderror
          </div>

          <div class="modal-actions">
            <button type="button" class="btn" id="cancelCreateModal">
              <svg viewBox="0 0 24 24"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
              Cancel
            </button>
            <button type="submit" class="btn btn-primary">
              <svg viewBox="0 0 24 24"><path d="M19 21H5a2 2 0 01-2-2V5a2 2 0 012-2h11l5 5v11a2 2 0 01-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/></svg>
              Save category
            </button>
          </div>
        </form>
      </div>
    </div>

    <script>
      const categorySearch = document.getElementById('categorySearch');
      const categoryRows = document.querySelectorAll('#categoriesTable tbody tr[data-name]');

      categorySearch.addEventListener('input', function () {
        const term = this.value.toLowerCase();
        categoryRows.forEach(row => {
          row.style.display = row.dataset.name.includes(term) ? '' : 'none';
        });
      });

      const modal = document.getElementById('createModal');
      document.getElementById('openCreateModal').addEventListener('click', () => modal.classList.add('open'));
      document.getElementById('closeCreateModal').addEventListener('click', () => modal.classList.remove('open'));
      document.getElementById('cancelCreateModal').addEventListener('click', () => modal.classList.remove('open'));
      modal.addEventListener('click', (e) => {
        if (e.target === modal) modal.classList.remove('open');
      });
    </script>

</x-admin-layout>