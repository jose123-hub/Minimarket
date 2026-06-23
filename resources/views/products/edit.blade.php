<x-admin-layout
    title="Edit product"
    subtitle="Update product details and stock"
    active="inventory"
>

    <div class="table-card" style="max-width:560px; padding: 28px 30px;">
      <form action="/admin/products/{{ $product->id }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="form-group">
          <label for="image">Product image (optional)</label>
          <div style="display:flex; align-items:center; gap:14px;">
            @if($product->image)
              <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}"
                   class="prod-thumb" id="image-preview" style="width:56px; height:56px;">
            @else
              <img src="" alt="" class="prod-thumb" id="image-preview" style="width:56px; height:56px; display:none;">
              <div class="prod-thumb-placeholder" id="image-placeholder" style="width:56px; height:56px; font-size:20px;">
                {{ strtoupper(substr($product->name, 0, 1)) }}
              </div>
            @endif
            <input type="file" id="image" name="image" accept="image/png,image/jpeg,image/webp">
          </div>
          <p style="font-size:12px; color:#999; margin-top:6px;">Leave empty to keep the current image. JPG, PNG or WEBP, max 2MB.</p>
          @error('image') <div class="field-error">{{ $message }}</div> @enderror
        </div>

        <div class="form-group">
          <label for="name">Product name</label>
          <input type="text" id="name" name="name" value="{{ old('name', $product->name) }}" required>
          @error('name') <div class="field-error">{{ $message }}</div> @enderror
        </div>

        <div class="form-row">
          <div class="form-group">
            <label for="category_id">Category</label>
            <select id="category_id" name="category_id" required>
              @foreach($categories as $category)
                @php $isParentWithChildren = !$category->parent_id && $category->children_count > 0; @endphp
                <option value="{{ $category->id }}"
                  {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}
                  {{ $isParentWithChildren ? 'disabled' : '' }}>
                  {{ $category->parent_id ? '— ' . $category->name : $category->name }}
                  {{ $isParentWithChildren ? '(choose a subcategory)' : '' }}
                </option>
              @endforeach
            </select>
            @error('category_id') <div class="field-error">{{ $message }}</div> @enderror
          </div>
          <div class="form-group">
            <label for="supplier_id">Supplier (optional)</label>
            <select id="supplier_id" name="supplier_id">
              <option value="">— None —</option>
              @foreach($suppliers as $supplier)
                <option value="{{ $supplier->id }}" {{ old('supplier_id', $product->supplier_id) == $supplier->id ? 'selected' : '' }}>
                  {{ $supplier->company_name }}
                </option>
              @endforeach
            </select>
            @error('supplier_id') <div class="field-error">{{ $message }}</div> @enderror
          </div>
        </div>

        <div class="form-row">
          <div class="form-group">
            <label for="price">Sale price (S/)</label>
            <input type="number" step="0.01" min="0" id="price" name="price" value="{{ old('price', $product->price) }}" required>
            @error('price') <div class="field-error">{{ $message }}</div> @enderror
          </div>
          <div class="form-group">
            <label for="cost">Cost (S/)</label>
            <input type="number" step="0.01" min="0" id="cost" name="cost" value="{{ old('cost', $product->cost) }}">
            @error('cost') <div class="field-error">{{ $message }}</div> @enderror
          </div>
        </div>

        <div class="form-row">
          <div class="form-group">
            <label for="stock">Stock initial</label>
            <input type="number" min="0" id="stock" name="stock" value="{{ old('stock', $product->stock) }}" required>
            @error('stock') <div class="field-error">{{ $message }}</div> @enderror
          </div>
          <div class="form-group">
            <label for="min_stock">Minimum stock</label>
            <input type="number" min="0" id="min_stock" name="min_stock" value="{{ old('min_stock', $product->min_stock) }}">
            @error('min_stock') <div class="field-error">{{ $message }}</div> @enderror
          </div>
        </div>

        <div class="form-group">
          <label for="description">Description (optional)</label>
          <textarea id="description" name="description" rows="3">{{ old('description', $product->description) }}</textarea>
          @error('description') <div class="field-error">{{ $message }}</div> @enderror
        </div>

        <div class="modal-actions" style="margin-top:6px;">
          <a href="/admin/products" class="btn">
            <svg viewBox="0 0 24 24"><line x1="19" y1="12" x2="5" y2="12"/><polyline points="12 19 5 12 12 5"/></svg>
            Cancel
          </a>
          <button type="submit" class="btn btn-primary">
            <svg viewBox="0 0 24 24"><path d="M19 21H5a2 2 0 01-2-2V5a2 2 0 012-2h11l5 5v11a2 2 0 01-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/></svg>
            Update product
          </button>
        </div>
      </form>
    </div>

    <script>
      document.getElementById('image').addEventListener('change', function (e) {
        const preview = document.getElementById('image-preview');
        const placeholder = document.getElementById('image-placeholder');
        const file = e.target.files[0];

        if (file) {
          preview.src = URL.createObjectURL(file);
          preview.style.display = 'block';
          if (placeholder) placeholder.style.display = 'none';
        }
      });
    </script>

</x-admin-layout>