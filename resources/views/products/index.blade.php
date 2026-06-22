<x-admin-layout
    title="Inventory"
    subtitle="Product and stock management"
    active="inventory"
>
    <div class="toolbar">
      <div class="search-box">
        <svg viewBox="0 0 24 24"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
        <input type="text" id="productSearch" placeholder="Search product...">
      </div>

      <select class="filter-select" id="categoryFilter">
        <option value="">All</option>
        @foreach($products->pluck('category')->filter()->unique('id') as $cat)
          <option value="{{ $cat->name }}">{{ $cat->name }}</option>
        @endforeach
      </select>

      <button class="btn" id="exportBtn">
        <svg viewBox="0 0 24 24"><path d="M21 15v4a2 2 0 01-2 2H5a2 2 0 01-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
        Export
      </button>

      <button class="btn" id="openCategoriesModal" type="button">
        <svg viewBox="0 0 24 24"><line x1="8" y1="6" x2="21" y2="6"/><line x1="8" y1="12" x2="21" y2="12"/><line x1="8" y1="18" x2="21" y2="18"/><line x1="3" y1="6" x2="3.01" y2="6"/><line x1="3" y1="12" x2="3.01" y2="12"/><line x1="3" y1="18" x2="3.01" y2="18"/></svg>
        Manage categories
      </button>

      <button class="btn btn-primary" id="openCreateModal" type="button">
        <svg viewBox="0 0 24 24"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
        Add product
      </button>
    </div>

    <div class="table-card">
      <table id="productsTable">
        <thead>
          <tr>
            <th>Code</th>
            <th>Product</th>
            <th>Category</th>
            <th>Price</th>
            <th>Stock</th>
            <th>Status</th>
            <th style="text-align:right">Actions</th>
          </tr>
        </thead>
        <tbody>
          @forelse($products as $product)
            @php
              $stock = $product->stock;
              $minStock = $product->min_stock ?? 5;
              if ($stock <= 0) {
                  $statusClass = 'out';
                  $statusLabel = 'Out of stock';
              } elseif ($stock < $minStock) {
                  $statusClass = 'low';
                  $statusLabel = 'Low stock';
              } else {
                  $statusClass = 'ok';
                  $statusLabel = 'Available';
              }
            @endphp
            <tr data-name="{{ strtolower($product->name) }}" data-category="{{ $product->category->name ?? '' }}">
              <td class="prod-code">P{{ str_pad($product->id, 3, '0', STR_PAD_LEFT) }}</td>
              <td class="prod-name">{{ $product->name }}</td>
              <td>{{ $product->category->name ?? '—' }}</td>
              <td>S/ {{ number_format($product->price, 2) }}</td>
              <td>{{ $product->stock }}</td>
              <td><span class="badge {{ $statusClass }}">{{ $statusLabel }}</span></td>
              <td>
                <div class="actions" style="justify-content:flex-end">
                  <a href="/admin/products/{{ $product->id }}/edit" class="icon-btn">
                    <svg viewBox="0 0 24 24"><path d="M17 3a2.85 2.83 0 114 4L7.5 20.5 2 22l1.5-5.5z"/></svg>
                  </a>
                  <form action="/admin/products/{{ $product->id }}" method="POST" onsubmit="return confirm('¿Deleted this product?');">
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
              <td colspan="7">There are no products registered yet.</td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>

    <script>
      const searchInput = document.getElementById('productSearch');
      const categoryFilter = document.getElementById('categoryFilter');
      const rows = document.querySelectorAll('#productsTable tbody tr[data-name]');

      function applyFilters() {
        const term = searchInput.value.toLowerCase();
        const cat = categoryFilter.value;
        rows.forEach(row => {
          const matchesName = row.dataset.name.includes(term);
          const matchesCategory = !cat || row.dataset.category === cat;
          row.style.display = (matchesName && matchesCategory) ? '' : 'none';
        });
      }

      searchInput.addEventListener('input', applyFilters);
      categoryFilter.addEventListener('change', applyFilters);

      document.getElementById('exportBtn').addEventListener('click', () => {
        const visibleRows = Array.from(rows).filter(r => r.style.display !== 'none');
        let csv = 'Codigo,Producto,Categoria,Precio,Stock,Estado\n';
        visibleRows.forEach(row => {
          const cells = row.querySelectorAll('td');
          const values = Array.from(cells).slice(0, 6).map(c => '"' + c.innerText.trim().replace(/\n/g, ' ') + '"');
          csv += values.join(',') + '\n';
        });
        const blob = new Blob([csv], { type: 'text/csv' });
        const url = URL.createObjectURL(blob);
        const a = document.createElement('a');
        a.href = url;
        a.download = 'inventario.csv';
        a.click();
        URL.revokeObjectURL(url);
      });
    </script>

    <div class="modal-overlay{{ $errors->any() ? ' open' : '' }}" id="createModal">
      <div class="modal-box">
        <div class="modal-header">
          <div class="modal-header-title">
            <div class="modal-icon">
              <svg viewBox="0 0 24 24"><path d="M21 16V8a2 2 0 00-1-1.73l-7-4a2 2 0 00-2 0l-7 4A2 2 0 003 8v8a2 2 0 001 1.73l7 4a2 2 0 002 0l7-4A2 2 0 0021 16z"/></svg>
            </div>
            <h2>Agregar nuevo producto</h2>
          </div>
          <button type="button" class="modal-close" id="closeCreateModal">
            <svg viewBox="0 0 24 24"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
          </button>
        </div>
        <p class="modal-subtitle">Completa los datos del producto para registrarlo en el inventario.</p>

        <form action="/admin/products" method="POST">
          @csrf

          <div class="form-group">
            <label for="name">Nombre del producto</label>
            <input type="text" id="name" name="name" placeholder="Ej. Coca Cola 500ml" value="{{ old('name') }}" required>
            @error('name') <div class="field-error">{{ $message }}</div> @enderror
          </div>

          <div class="form-group">
            <label for="category_id">Categoría</label>
            <select id="category_id" name="category_id" required>
              <option value="" disabled selected>Selecciona una categoría</option>
              @foreach($categories as $category)
                <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>{{ $category->parent_id ? '— ' . $category->name : $category->name }}</option>
              @endforeach
            </select>
            @error('category_id') <div class="field-error">{{ $message }}</div> @enderror
          </div>

          <div class="form-row">
            <div class="form-group">
              <label for="price">Precio venta (S/)</label>
              <input type="number" step="0.01" min="0" id="price" name="price" placeholder="0.00" value="{{ old('price') }}" required>
              @error('price') <div class="field-error">{{ $message }}</div> @enderror
            </div>
            <div class="form-group">
              <label for="cost">Costo (S/)</label>
              <input type="number" step="0.01" min="0" id="cost" name="cost" placeholder="0.00" value="{{ old('cost') }}">
              @error('cost') <div class="field-error">{{ $message }}</div> @enderror
            </div>
          </div>

          <div class="form-row">
            <div class="form-group">
              <label for="stock">Stock inicial</label>
              <input type="number" min="0" id="stock" name="stock" placeholder="0" value="{{ old('stock', 0) }}" required>
              @error('stock') <div class="field-error">{{ $message }}</div> @enderror
            </div>
            <div class="form-group">
              <label for="min_stock">Stock mínimo</label>
              <input type="number" min="0" id="min_stock" name="min_stock" placeholder="5" value="{{ old('min_stock', 5) }}">
              @error('min_stock') <div class="field-error">{{ $message }}</div> @enderror
            </div>
          </div>

          <div class="form-group">
            <label for="description">Descripción (opcional)</label>
            <textarea id="description" name="description" rows="2">{{ old('description') }}</textarea>
          </div>

          <div class="modal-actions">
            <button type="button" class="btn" id="cancelCreateModal">
              <svg viewBox="0 0 24 24"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
              Cancelar
            </button>
            <button type="submit" class="btn btn-primary">
              <svg viewBox="0 0 24 24"><path d="M19 21H5a2 2 0 01-2-2V5a2 2 0 012-2h11l5 5v11a2 2 0 01-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/></svg>
              Guardar producto
            </button>
          </div>
        </form>
      </div>
    </div>

    <script>
      const modal = document.getElementById('createModal');

      document.getElementById('openCreateModal').addEventListener('click', () => modal.classList.add('open'));
      document.getElementById('closeCreateModal').addEventListener('click', () => modal.classList.remove('open'));
      document.getElementById('cancelCreateModal').addEventListener('click', () => modal.classList.remove('open'));

      modal.addEventListener('click', (e) => {
          if (e.target === modal) {
              modal.classList.remove('open');
          }
      });
    </script>

    <div class="modal-overlay" id="categoriesModal">
      <div class="modal-box" style="max-width:560px;">
        <div class="modal-header">
          <div class="modal-header-title">
            <div class="modal-icon">
              <svg viewBox="0 0 24 24"><line x1="8" y1="6" x2="21" y2="6"/><line x1="8" y1="12" x2="21" y2="12"/><line x1="8" y1="18" x2="21" y2="18"/><line x1="3" y1="6" x2="3.01" y2="6"/><line x1="3" y1="12" x2="3.01" y2="12"/><line x1="3" y1="18" x2="3.01" y2="18"/></svg>
            </div>
            <h2>Manage categories</h2>
          </div>
          <button type="button" class="modal-close" id="closeCategoriesModal">
            <svg viewBox="0 0 24 24"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
          </button>
        </div>
        <p class="modal-subtitle">Categories organize your products in inventory, the POS and reports.</p>

        <form id="categoryForm">
          <input type="hidden" id="categoryId" value="">
          <div class="form-row" style="grid-template-columns: 1fr 1fr; align-items:start;">
            <div class="form-group" style="margin-bottom:0;">
              <label for="categoryName">Name</label>
              <input type="text" id="categoryName" placeholder="E.g. Beverages" required>
              <div class="field-error" id="categoryNameError" style="display:none;"></div>
            </div>
            <div class="form-group" style="margin-bottom:0;">
              <label for="categoryDescription">Description (optional)</label>
              <input type="text" id="categoryDescription" placeholder="Optional">
            </div>
          </div>
          <div class="form-group" style="margin-top:14px;">
            <label for="categoryParent">Parent category (optional)</label>
            <select id="categoryParent">
              <option value="">— None (top-level category) —</option>
            </select>
            <div class="field-error" id="categoryParentError" style="display:none;"></div>
          </div>
          <div class="modal-actions" style="margin-top:14px; justify-content:flex-start;">
            <button type="submit" class="btn btn-primary" id="categorySubmitBtn">
              <svg viewBox="0 0 24 24"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
              Add category
            </button>
            <button type="button" class="btn" id="categoryCancelEdit" style="display:none;">
              Cancel edit
            </button>
          </div>
        </form>

        <div id="categoriesListError" class="field-error" style="display:none; margin-top:14px;"></div>

        <div class="table-card" style="margin-top:18px; box-shadow:none;">
          <table id="categoriesModalTable">
            <thead>
              <tr>
                <th>Name</th>
                <th>Description</th>
                <th>Products</th>
                <th style="text-align:right">Actions</th>
              </tr>
            </thead>
            <tbody id="categoriesModalBody">
              <tr class="empty-row"><td colspan="4">Loading categories…</td></tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>

    <script>
      const categoriesModal = document.getElementById('categoriesModal');
      const categoriesModalBody = document.getElementById('categoriesModalBody');
      const categoryForm = document.getElementById('categoryForm');
      const categoryIdInput = document.getElementById('categoryId');
      const categoryNameInput = document.getElementById('categoryName');
      const categoryDescriptionInput = document.getElementById('categoryDescription');
      const categoryParentSelect = document.getElementById('categoryParent');
      const categoryNameError = document.getElementById('categoryNameError');
      const categoryParentError = document.getElementById('categoryParentError');
      const categoriesListError = document.getElementById('categoriesListError');
      const categorySubmitBtn = document.getElementById('categorySubmitBtn');
      const categoryCancelEdit = document.getElementById('categoryCancelEdit');
      let latestCategoriesTree = [];

      function openCategoriesModal() {
        categoriesModal.classList.add('open');
        loadCategories();
      }
      function closeCategoriesModal() {
        categoriesModal.classList.remove('open');
        resetCategoryForm();
      }

      document.getElementById('openCategoriesModal').addEventListener('click', openCategoriesModal);
      document.getElementById('closeCategoriesModal').addEventListener('click', closeCategoriesModal);
      categoriesModal.addEventListener('click', (e) => {
        if (e.target === categoriesModal) closeCategoriesModal();
      });

      function resetCategoryForm() {
        categoryIdInput.value = '';
        categoryForm.reset();
        categoryNameError.style.display = 'none';
        categoryParentError.style.display = 'none';
        categoryParentSelect.disabled = false;
        renderParentOptions(latestCategoriesTree);
        categorySubmitBtn.innerHTML = `
          <svg viewBox="0 0 24 24"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
          Add category`;
        categoryCancelEdit.style.display = 'none';
      }

      categoryCancelEdit.addEventListener('click', resetCategoryForm);

      async function loadCategories() {
        categoriesListError.style.display = 'none';
        try {
          const res = await fetch("{{ route('categories.index') }}", {
            headers: { 'Accept': 'application/json' },
          });
          if (!res.ok) throw new Error('Failed to load categories.');
          const categories = await res.json();
          latestCategoriesTree = categories;
          renderCategoriesModal(categories);
          renderParentOptions(categories);
        } catch (err) {
          categoriesModalBody.innerHTML = '<tr class="empty-row"><td colspan="4">Could not load categories.</td></tr>';
        }
      }

      function renderCategoriesModal(categories) {
        if (!categories.length) {
          categoriesModalBody.innerHTML = '<tr class="empty-row"><td colspan="4">No categories yet. Add one above.</td></tr>';
          return;
        }

        const rowsHtml = [];

        categories.forEach(parent => {
          rowsHtml.push(categoryRowHtml(parent, false));
          (parent.children || []).forEach(child => {
            rowsHtml.push(categoryRowHtml(child, true));
          });
        });

        categoriesModalBody.innerHTML = rowsHtml.join('');
      }

      function categoryRowHtml(cat, isChild) {
        const nameCell = isChild
          ? `<span style="display:inline-flex; align-items:center; gap:6px; padding-left:18px; color:#666;">
               <svg width="12" height="12" viewBox="0 0 24 24" stroke="#ccc" fill="none" stroke-width="2"><path d="M9 18l6-6-6-6"/></svg>
               ${escapeHtml(cat.name)}
             </span>`
          : `<span class="prod-name">${escapeHtml(cat.name)}</span>`;

        return `
          <tr>
            <td>${nameCell}</td>
            <td>${cat.description ? escapeHtml(cat.description) : '—'}</td>
            <td>${cat.products_count}</td>
            <td>
              <div class="actions" style="justify-content:flex-end">
                <button type="button" class="icon-btn" onclick='startEditCategory(${cat.id}, ${JSON.stringify(cat.name)}, ${JSON.stringify(cat.description || '')}, ${cat.parent_id ?? 'null'})'>
                  <svg viewBox="0 0 24 24"><path d="M17 3a2.85 2.83 0 114 4L7.5 20.5 2 22l1.5-5.5z"/></svg>
                </button>
                <button type="button" class="icon-btn danger" onclick="deleteCategory(${cat.id})">
                  <svg viewBox="0 0 24 24"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14a2 2 0 01-2 2H8a2 2 0 01-2-2L5 6"/><path d="M10 11v6"/><path d="M14 11v6"/><path d="M9 6V4a1 1 0 011-1h4a1 1 0 011 1v2"/></svg>
                </button>
              </div>
            </td>
          </tr>`;
      }

      function renderParentOptions(categories) {
        const editingId = categoryIdInput.value ? parseInt(categoryIdInput.value, 10) : null;
        const currentValue = categoryParentSelect.value;

        const options = categories
          .filter(cat => cat.id !== editingId) 
          .map(cat => `<option value="${cat.id}">${escapeHtml(cat.name)}</option>`)
          .join('');

        categoryParentSelect.innerHTML = '<option value="">— None (top-level category) —</option>' + options;
        categoryParentSelect.value = currentValue;
      }

      function escapeHtml(str) {
        const div = document.createElement('div');
        div.textContent = str ?? '';
        return div.innerHTML;
      }

      function startEditCategory(id, name, description, parentId) {
        categoryIdInput.value = id;
        categoryNameInput.value = name;
        categoryDescriptionInput.value = description;
        categoryNameError.style.display = 'none';
        categoryParentError.style.display = 'none';

        renderParentOptions(latestCategoriesTree);
        categoryParentSelect.value = parentId ?? '';

        const isParentOfOthers = latestCategoriesTree.some(c => c.id === id && (c.children || []).length > 0);
        categoryParentSelect.disabled = isParentOfOthers;

        categorySubmitBtn.innerHTML = `
          <svg viewBox="0 0 24 24"><path d="M19 21H5a2 2 0 01-2-2V5a2 2 0 012-2h11l5 5v11a2 2 0 01-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/></svg>
          Update category`;
        categoryCancelEdit.style.display = '';
        categoryNameInput.focus();
      }

      categoryForm.addEventListener('submit', async (e) => {
        e.preventDefault();
        categoryNameError.style.display = 'none';
        categoryParentError.style.display = 'none';

        const id = categoryIdInput.value;
        const url = id ? `/admin/categories/${id}` : "{{ route('categories.store') }}";
        const payload = {
          name: categoryNameInput.value,
          description: categoryDescriptionInput.value,
          parent_id: categoryParentSelect.value || null,
        };

        try {
          const res = await fetch(url, {
            method: id ? 'PUT' : 'POST',
            headers: {
              'Content-Type': 'application/json',
              'Accept': 'application/json',
              'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content
                ?? document.querySelector('input[name="_token"]')?.value,
            },
            body: JSON.stringify(payload),
          });

          const data = await res.json();

          if (!res.ok) {
            if (data.errors?.name) {
              categoryNameError.textContent = data.errors.name[0];
              categoryNameError.style.display = 'block';
            } else if (data.errors?.parent_id) {
              categoryParentError.textContent = data.errors.parent_id[0];
              categoryParentError.style.display = 'block';
            } else {
              categoriesListError.textContent = data.message ?? 'Something went wrong.';
              categoriesListError.style.display = 'block';
            }
            return;
          }

          resetCategoryForm();
          await loadCategories();
          refreshCategoryFilterOptions();
        } catch (err) {
          categoriesListError.textContent = 'Could not reach the server.';
          categoriesListError.style.display = 'block';
        }
      });

      async function deleteCategory(id) {
        if (!confirm('Delete this category?')) return;

        try {
          const res = await fetch(`/admin/categories/${id}`, {
            method: 'DELETE',
            headers: {
              'Content-Type': 'application/json',
              'Accept': 'application/json',
              'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content
                ?? document.querySelector('input[name="_token"]')?.value,
            },
          });

          const data = await res.json();

          if (!res.ok) {
            categoriesListError.textContent = data.message ?? 'Could not delete this category.';
            categoriesListError.style.display = 'block';
            return;
          }

          await loadCategories();
          refreshCategoryFilterOptions();
        } catch (err) {
          categoriesListError.textContent = 'Could not reach the server.';
          categoriesListError.style.display = 'block';
        }
      }
      async function refreshCategoryFilterOptions() {
        try {
          const res = await fetch("{{ route('categories.index') }}", {
            headers: { 'Accept': 'application/json' },
          });
          if (!res.ok) return;
          const tree = await res.json();
          const flatCategories = flattenCategoryTree(tree);

          const current = categoryFilter.value;
          categoryFilter.innerHTML = '<option value="">All</option>' +
            flatCategories.map(c => `<option value="${escapeHtml(c.name)}">${escapeHtml(c.name)}</option>`).join('');
          categoryFilter.value = current;

          const productCategorySelect = document.getElementById('category_id');
          if (productCategorySelect) {
            const currentSelected = productCategorySelect.value;
            productCategorySelect.innerHTML = '<option value="" disabled>Selecciona una categoría</option>' +
              flatCategories.map(c => `<option value="${c.id}">${escapeHtml(c.parent_id ? '— ' + c.name : c.name)}</option>`).join('');
            productCategorySelect.value = currentSelected;
          }
        } catch (err) {
          categoriesListError.textContent = 'Could not reach the server.';
          categoriesListError.style.display = 'block';
        }
      }
      function flattenCategoryTree(tree) {
        const flat = [];
        tree.forEach(parent => {
          flat.push(parent);
          (parent.children || []).forEach(child => flat.push(child));
        });
        return flat;
      }
    </script>

</x-admin-layout>