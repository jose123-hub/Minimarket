<x-portal-layout
    title="Inventory"
    subtitle="Product and stock management"
    active="inventory"
>
    <style>
  .custom-select { position: relative; }
  .custom-select-trigger {
    width: 100%; display: flex; align-items: center; justify-content: space-between;
    border: 1px solid #e5e5e5; background: #fafafa; border-radius: 9px;
    padding: 10px 12px; font-size: 13px; color: #111; cursor: pointer; text-align: left;
  }
  .custom-select-trigger:focus { outline: none; border-color: #e8192c; }
  .custom-select-trigger.placeholder span { color: #999; }
  .custom-select-panel {
    display: none; position: absolute; top: calc(100% + 6px); left: 0; right: 0;
    max-height: 220px; overflow-y: auto; background: #fff; border: 1px solid #e5e5e5;
    border-radius: 9px; box-shadow: 0 10px 28px rgba(0,0,0,0.10); z-index: 50;
  }
  .custom-select-panel.open { display: block; }
  .custom-select-option { padding: 9px 12px; font-size: 13px; color: #333; cursor: pointer; }
  .custom-select-option:hover { background: #fff5f5; }
  .custom-select-option.is-sub { padding-left: 26px; color: #666; }
  .custom-select-option.is-disabled { color: #ccc; cursor: not-allowed; background: #fafafa; }
  .custom-select-option.is-disabled:hover { background: #fafafa; }
  .custom-select-option.is-selected { background: #fff0f2; color: #e8192c; font-weight: 600; }
  .custom-select-empty { padding: 14px 12px; font-size: 12px; color: #aaa; text-align: center; }
</style>

<div class="toolbar">
      <div class="search-box">
        <svg viewBox="0 0 24 24"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
        <input type="text" id="productSearch" placeholder="Search product...">
      </div>

      <select class="filter-select" id="categoryFilter">
        <option value="">All</option>
        @foreach($categories->where('parent_id', null) as $parent)
          @php $children = $categories->where('parent_id', $parent->id); @endphp
          @if($children->count() > 0)
            <optgroup label="{{ $parent->name }}">
              <option value="{{ $parent->name }}">All {{ $parent->name }}</option>
              @foreach($children as $child)
                <option value="{{ $child->name }}">{{ $child->name }}</option>
              @endforeach
            </optgroup>
          @else
            <option value="{{ $parent->name }}">{{ $parent->name }}</option>
          @endif
        @endforeach
      </select>

      <button class="btn" id="exportBtn">
        <svg viewBox="0 0 24 24"><path d="M21 15v4a2 2 0 01-2 2H5a2 2 0 01-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
        Export
      </button>

      <button class="btn" id="sortByPriceBtn" type="button">
        <svg viewBox="0 0 24 24"><line x1="12" y1="20" x2="12" y2="4"/><polyline points="6 10 12 4 18 10"/></svg>
        Sort by price
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

    <p id="sort-status" style="font-size:12px; color:#999; margin: -6px 0 14px 2px;">Showing products in default order</p>

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
        <tbody id="products-table-body"></tbody>
      </table>
    </div>
    <script id="categories-data" type="application/json">{!! json_encode($categoriesForJs->toArray(), JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) !!}</script>
    <script id="products-data" type="application/json">{!! json_encode($products->map(function ($p) {
        return [
            'id'             => $p->id,
            'code'           => 'P' . str_pad($p->id, 3, '0', STR_PAD_LEFT),
            'name'           => $p->name,
            'description'    => $p->description,
            'image_url'      => $p->image ? asset('storage/' . $p->image) : null,
            'category'       => $p->category->name ?? '—',
            'category_parent_name' => $p->category->parent->name ?? null,
            'category_id'    => $p->category_id,
            'price'          => (float) $p->price,
            'cost'           => (float) ($p->cost ?? 0),
            'stock'          => $p->stock,
            'min_stock'      => $p->min_stock ?? 5,
            'status_class'   => $p->stock <= 0 ? 'out' : ($p->stock < ($p->min_stock ?? 5) ? 'low' : 'ok'),
            'status_label'   => $p->stock <= 0 ? 'Out of stock' : ($p->stock < ($p->min_stock ?? 5) ? 'Low stock' : 'Available'),
        ];
    })->values(), JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) !!}</script>
    <script>
      const originalProducts = JSON.parse(document.getElementById('products-data').textContent || '[]');
      const tbody = document.getElementById('products-table-body');
      const searchInput = document.getElementById('productSearch');
      const categoryFilter = document.getElementById('categoryFilter');
      let rows = [];

      function escapeHtmlAttr(str) {
        return str
          .replace(/&/g, '&amp;')
          .replace(/"/g, '&quot;')
          .replace(/</g, '&lt;')
          .replace(/>/g, '&gt;');
      }

      function productRowHtml(p) {
        const thumb = p.image_url
          ? `<img src="${p.image_url}" alt="${p.name}" style="width:34px; height:34px; border-radius:7px; object-fit:cover; border:1px solid #eee;">`
          : `<div style="width:34px; height:34px; border-radius:7px; background:#f5f5f5; display:flex; align-items:center; justify-content:center; color:#bbb; font-size:11px;">N/A</div>`;

        return `
          <tr data-name="${p.name.toLowerCase()}" data-category="${p.category}" data-category-parent="${p.category_parent_name ?? ''}">
            <td class="prod-code">${p.code}</td>
            <td class="prod-name" style="display:flex; align-items:center; gap:10px;">${thumb}${p.name}</td>
            <td>${p.category}</td>
            <td>S/ ${p.price.toFixed(2)}</td>
            <td>${p.stock}</td>
            <td><span class="badge ${p.status_class}">${p.status_label}</span></td>
            <td>
              <div class="actions" style="justify-content:flex-end">
                <button type="button" class="icon-btn" data-product="${escapeHtmlAttr(JSON.stringify(p))}" onclick="openEditProduct(JSON.parse(this.dataset.product))">
                  <svg viewBox="0 0 24 24"><path d="M17 3a2.85 2.83 0 114 4L7.5 20.5 2 22l1.5-5.5z"/></svg>
                </button>
                <form action="/admin/products/${p.id}" method="POST" onsubmit="return confirm('¿Deleted this product?');">
                  <input type="hidden" name="_token" value="${document.querySelector('meta[name=csrf-token]')?.content ?? ''}">
                  <input type="hidden" name="_method" value="DELETE">
                  <button type="submit" class="icon-btn danger">
                    <svg viewBox="0 0 24 24"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14a2 2 0 01-2 2H8a2 2 0 01-2-2L5 6"/><path d="M10 11v6"/><path d="M14 11v6"/><path d="M9 6V4a1 1 0 011-1h4a1 1 0 011 1v2"/></svg>
                  </button>
                </form>
              </div>
            </td>
          </tr>`;
      }

      function renderProducts(products) {
        if (!products.length) {
          tbody.innerHTML = '<tr class="empty-row"><td colspan="7">There are no products registered yet.</td></tr>';
          rows = [];
          return;
        }
        tbody.innerHTML = products.map(productRowHtml).join('');
        rows = Array.from(document.querySelectorAll('#productsTable tbody tr[data-name]'));
        applyFilters();
      }

      function applyFilters() {
        const term = searchInput.value.toLowerCase();
        const cat = categoryFilter.value;
        rows.forEach(row => {
          const matchesName = row.dataset.name.includes(term);
          const matchesCategory = !cat || row.dataset.category === cat || row.dataset.categoryParent === cat;
          row.style.display = (matchesName && matchesCategory) ? '' : 'none';
        });
      }

      searchInput.addEventListener('input', applyFilters);
      categoryFilter.addEventListener('change', applyFilters);

      document.getElementById('exportBtn').addEventListener('click', () => {
        const visibleRows = rows.filter(r => r.style.display !== 'none');
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

let quickComparisons = 0;
let quickSwaps = 0;

function copyArray(items) {
  const copy = [];

  for (let i = 0; i < items.length; i++) {
    copy[copy.length] = items[i];
  }

  return copy;
}

function quickSort(items) {
  const arr = copyArray(items);
  quickSortInPlace(arr, 0, arr.length - 1);
  return arr;
}

function quickSortInPlace(arr, low, high) {
  if (low < high) {
    const pivotIndex = partition(arr, low, high);

    quickSortInPlace(arr, low, pivotIndex - 1);
    quickSortInPlace(arr, pivotIndex + 1, high);
  }
}

function partition(arr, low, high) {
  const pivot = arr[high].price;
  let i = low - 1;

  for (let j = low; j < high; j++) {
    quickComparisons++;

    if (arr[j].price <= pivot) {
      i++;
      swap(arr, i, j);
    }
  }

  swap(arr, i + 1, high);

  return i + 1;
}

function swap(arr, a, b) {
  if (a === b) return;

  const temp = arr[a];
  arr[a] = arr[b];
  arr[b] = temp;

  quickSwaps++;
}

      const sortBtn = document.getElementById('sortByPriceBtn');
      const sortStatus = document.getElementById('sort-status');
      let isPriceSorted = false;

      sortBtn.addEventListener('click', () => {
        if (!isPriceSorted) {
          quickComparisons = 0;
          quickSwaps = 0;
          const sorted = quickSort(originalProducts);
          renderProducts(sorted);
          sortStatus.textContent = `Sorted by price (lowest first) ${quickComparisons} comparisons, ${quickSwaps} swaps over ${originalProducts.length} products`;
          sortBtn.textContent = 'Reset to default order';
          isPriceSorted = true;
        } else {
          renderProducts(originalProducts);
          sortStatus.textContent = 'Showing products in default order';
          sortBtn.textContent = 'Sort by price';
          isPriceSorted = false;
        }
      });

      renderProducts(originalProducts);
    </script>

    <div class="modal-overlay{{ $errors->any() ? ' open' : '' }}" id="createModal">
      <div class="modal-box">
        <div class="modal-header">
          <div class="modal-header-title">
            <div class="modal-icon">
              <svg viewBox="0 0 24 24"><path d="M21 16V8a2 2 0 00-1-1.73l-7-4a2 2 0 00-2 0l-7 4A2 2 0 003 8v8a2 2 0 001 1.73l7 4a2 2 0 002 0l7-4A2 2 0 0021 16z"/></svg>
            </div>
            <h2 id="product-modal-title">Add new product</h2>
          </div>
          <button type="button" class="modal-close" id="closeCreateModal">
            <svg viewBox="0 0 24 24"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
          </button>
        </div>
        <p class="modal-subtitle" id="product-modal-subtitle">Complete the product details to register it in the inventory.</p>

        <form id="productForm" action="/admin/products" method="POST" enctype="multipart/form-data">
          @csrf
          <input type="hidden" name="_method" id="product-method-field" value="PUT" disabled>

          <div class="form-group">
            <label for="name">Product name</label>
            <input type="text" id="name" name="name" placeholder="Ej. Coca Cola 500ml" value="{{ old('name') }}" required>
            @error('name') <div class="field-error">{{ $message }}</div> @enderror
          </div>

          <div class="form-group">
            <label for="image">Product image (optional)</label>
            <div style="display:flex; align-items:center; gap:12px;">
              <img id="image-preview" src="" alt=""
                   style="width:56px; height:56px; border-radius:8px; object-fit:cover; border:1px solid #e5e5e5; display:none;">
              <input type="file" id="image" name="image" accept="image/png,image/jpeg,image/webp" style="flex:1;">
            </div>
            <small style="color:#999; font-size:11px;" id="image-hint">JPG, PNG or WEBP — max. 2MB</small>
            @error('image') <div class="field-error">{{ $message }}</div> @enderror
          </div>

          <div class="form-group">
            <label for="category-trigger">Category</label>
            <div class="custom-select" id="category-select-wrapper">
              <button type="button" class="custom-select-trigger" id="category-trigger">
                <span id="category-trigger-label">Select a category</span>
                <svg viewBox="0 0 24 24" style="width:14px;height:14px;stroke:#999;fill:none;stroke-width:2;"><polyline points="6 9 12 15 18 9"/></svg>
              </button>
              <input type="hidden" id="category_id" name="category_id" value="{{ old('category_id') }}">
              <div class="custom-select-panel" id="category-panel"></div>
            </div>
            @error('category_id') <div class="field-error">{{ $message }}</div> @enderror
          </div>

          <div class="form-row">
            <div class="form-group">
              <label for="price">Sale price (S/)</label>
              <input type="number" step="0.01" min="0" id="price" name="price" placeholder="0.00" value="{{ old('price') }}" required>
              @error('price') <div class="field-error">{{ $message }}</div> @enderror
            </div>
            <div class="form-group">
              <label for="cost">Cost (S/)</label>
              <input type="number" step="0.01" min="0" id="cost" name="cost" placeholder="0.00" value="{{ old('cost') }}">
              @error('cost') <div class="field-error">{{ $message }}</div> @enderror
            </div>
          </div>

          <div class="form-row">
            <div class="form-group">
              <label for="stock">Stock initial</label>
              <input type="number" min="0" id="stock" name="stock" placeholder="0" value="{{ old('stock', 0) }}" required>
              @error('stock') <div class="field-error">{{ $message }}</div> @enderror
            </div>
            <div class="form-group">
              <label for="min_stock">Minimum stock</label>
              <input type="number" min="0" id="min_stock" name="min_stock" placeholder="5" value="{{ old('min_stock', 5) }}">
              @error('min_stock') <div class="field-error">{{ $message }}</div> @enderror
            </div>
          </div>

          <div class="form-group">
            <label for="supplier_id">Supplier (optional)</label>
            <select id="supplier_id" name="supplier_id">
              <option value="">— None —</option>
              @foreach($suppliers as $supplier)
                <option value="{{ $supplier->id }}" {{ old('supplier_id') == $supplier->id ? 'selected' : '' }}>{{ $supplier->company_name }}</option>
              @endforeach
            </select>
            @error('supplier_id') <div class="field-error">{{ $message }}</div> @enderror
          </div>

          <div class="form-group">
            <label for="description">Description (optional)</label>
            <textarea id="description" name="description" rows="2">{{ old('description') }}</textarea>
          </div>

          <div class="modal-actions">
            <button type="button" class="btn" id="cancelCreateModal">
              <svg viewBox="0 0 24 24"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
              Cancel
            </button>
            <button type="submit" class="btn btn-primary">
              <svg viewBox="0 0 24 24"><path d="M19 21H5a2 2 0 01-2-2V5a2 2 0 012-2h11l5 5v11a2 2 0 01-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/></svg>
              <span id="product-submit-text">Save product</span>
            </button>
          </div>
        </form>
      </div>
    </div>

    <script>
      const modal = document.getElementById('createModal');
      const productForm = document.getElementById('productForm');
      const productMethodField = document.getElementById('product-method-field');
      const imagePreview = document.getElementById('image-preview');
      const imageHint = document.getElementById('image-hint');

      document.getElementById('openCreateModal').addEventListener('click', () => {
        resetProductModalToCreate();
        modal.classList.add('open');
      });
      document.getElementById('closeCreateModal').addEventListener('click', () => modal.classList.remove('open'));
      document.getElementById('cancelCreateModal').addEventListener('click', () => modal.classList.remove('open'));

      modal.addEventListener('click', (e) => {
          if (e.target === modal) {
              modal.classList.remove('open');
          }
      });

      document.getElementById('image').addEventListener('change', function(e) {
          const file = e.target.files[0];
          if (file) {
              imagePreview.src = URL.createObjectURL(file);
              imagePreview.style.display = 'block';
          } else if (!imagePreview.dataset.existing) {
              imagePreview.style.display = 'none';
          }
      });

      const categoryTrigger = document.getElementById('category-trigger');
      const categoryTriggerLabel = document.getElementById('category-trigger-label');
      const categoryPanel = document.getElementById('category-panel');
      const categoryHiddenInput = document.getElementById('category_id');

      function renderCategoryOptions(categories, selectedId = null) {
        if (!categories.length) {
          categoryPanel.innerHTML = '<div class="custom-select-empty">No categories yet</div>';
          return;
        }

        categoryPanel.innerHTML = categories.map(c => {
          const isDisabled = !c.parent_id && c.has_children && String(c.id) !== String(selectedId);
          const classes = [
            'custom-select-option',
            c.parent_id ? 'is-sub' : '',
            String(c.id) === String(selectedId) ? 'is-selected' : '',
            isDisabled ? 'is-disabled' : '',
          ].filter(Boolean).join(' ');

          const label = isDisabled
            ? `${c.name} <span style="font-size:11px; color:#bbb;">(choose a subcategory)</span>`
            : `${c.parent_id ? '— ' : ''}${c.name}`;

          return `
            <div class="${classes}"
                 data-id="${c.id}" data-label="${(c.parent_id ? '— ' : '') + c.name.replace(/"/g, '&quot;')}"
                 ${isDisabled ? 'data-disabled="true"' : ''}>
              ${label}
            </div>
          `;
        }).join('');

        categoryPanel.querySelectorAll('.custom-select-option').forEach(opt => {
          if (opt.dataset.disabled) return;

          opt.addEventListener('click', () => {
            categoryHiddenInput.value = opt.dataset.id;
            categoryTriggerLabel.textContent = opt.dataset.label;
            categoryTrigger.classList.remove('placeholder');
            categoryPanel.querySelectorAll('.custom-select-option').forEach(o => o.classList.remove('is-selected'));
            opt.classList.add('is-selected');
            categoryPanel.classList.remove('open');
          });
        });

        const selected = categories.find(c => String(c.id) === String(selectedId));
        if (selected) {
          categoryTriggerLabel.textContent = (selected.parent_id ? '— ' : '') + selected.name;
          categoryTrigger.classList.remove('placeholder');
        } else {
          categoryTriggerLabel.textContent = 'Select a category';
          categoryTrigger.classList.add('placeholder');
        }
      }

      categoryTrigger.addEventListener('click', (e) => {
        e.stopPropagation();
        categoryPanel.classList.toggle('open');
      });
      document.addEventListener('click', (e) => {
        if (!document.getElementById('category-select-wrapper').contains(e.target)) {
          categoryPanel.classList.remove('open');
        }
      });

      let currentCategoriesForDropdown = JSON.parse(document.getElementById('categories-data').textContent);
      const oldCategoryId = "{{ old('category_id') }}";
      renderCategoryOptions(currentCategoriesForDropdown, oldCategoryId || null);

      function resetProductModalToCreate() {
        document.getElementById('product-modal-title').textContent = 'Add new product';
        document.getElementById('product-modal-subtitle').textContent = 'Complete the product details to register it in the inventory.';
        document.getElementById('product-submit-text').textContent = 'Save product';
        imageHint.textContent = 'JPG, PNG or WEBP — max. 2MB';
        productForm.action = '/admin/products';
        productMethodField.disabled = true;
        document.getElementById('image').required = false;

        productForm.reset();
        imagePreview.style.display = 'none';
        imagePreview.removeAttribute('data-existing');
        categoryHiddenInput.value = '';
        renderCategoryOptions(currentCategoriesForDropdown, null);
      }

      function openEditProduct(product) {
        document.getElementById('product-modal-title').textContent = 'Edit product';
        document.getElementById('product-modal-subtitle').textContent = 'Update the product details below.';
        document.getElementById('product-submit-text').textContent = 'Save changes';
        imageHint.textContent = 'Leave empty to keep the current image. JPG, PNG or WEBP — max. 2MB';
        productForm.action = '/admin/products/' + product.id;
        productMethodField.disabled = false;

        document.getElementById('name').value = product.name ?? '';
        document.getElementById('description').value = product.description ?? '';
        document.getElementById('price').value = product.price ?? '';
        document.getElementById('cost').value = product.cost ?? '';
        document.getElementById('stock').value = product.stock ?? 0;
        document.getElementById('min_stock').value = product.min_stock ?? 5;
        document.getElementById('supplier_id').value = product.supplier_id ?? '';

        if (product.image_url) {
          imagePreview.src = product.image_url;
          imagePreview.style.display = 'block';
          imagePreview.dataset.existing = '1';
        } else {
          imagePreview.style.display = 'none';
          imagePreview.removeAttribute('data-existing');
        }

        categoryHiddenInput.value = product.category_id ?? '';
        renderCategoryOptions(currentCategoriesForDropdown, product.category_id ?? null);

        modal.classList.add('open');
      }
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
          categoryFilter.innerHTML = '<option value="">All</option>' + tree.map(parent => {
            if (parent.children && parent.children.length > 0) {
              const childOptions = parent.children
                .map(child => `<option value="${escapeHtml(child.name)}">${escapeHtml(child.name)}</option>`)
                .join('');
              return `<optgroup label="${escapeHtml(parent.name)}">
                <option value="${escapeHtml(parent.name)}">All ${escapeHtml(parent.name)}</option>
                ${childOptions}
              </optgroup>`;
            }
            return `<option value="${escapeHtml(parent.name)}">${escapeHtml(parent.name)}</option>`;
          }).join('');
          categoryFilter.value = current;

          currentCategoriesForDropdown = flatCategories;

          const currentSelected = categoryHiddenInput.value;
          renderCategoryOptions(flatCategories, currentSelected);
        } catch (err) {
          categoriesListError.textContent = 'Could not reach the server.';
          categoriesListError.style.display = 'block';
        }
      }
      function flattenCategoryTree(tree) {
        const flat = [];
        tree.forEach(parent => {
          flat.push({ ...parent, has_children: (parent.children_count ?? 0) > 0 });
          (parent.children || []).forEach(child => {
            flat.push({ ...child, has_children: (child.children_count ?? 0) > 0 });
          });
        });
        return flat;
      }
    </script>

</x-portal-layout>