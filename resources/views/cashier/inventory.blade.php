@push('portal-styles')
<style>
    .toolbar {
        display: grid;
        grid-template-columns: 1fr 250px;
        gap: 16px;
        margin-bottom: 22px;
    }

    .search-box {
        background: #fff;
        border: 1px solid #e5e5e5;
        border-radius: 12px;
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 0 16px;
        height: 48px;
    }

    .search-box svg {
        width: 18px;
        height: 18px;
        stroke: #999;
        fill: none;
        stroke-width: 2;
    }

    .search-box input {
        width: 100%;
        border: none;
        outline: none;
        font-size: 14px;
        color: #333;
    }

    #category-filter {
        height: 48px;
        background: #fff;
        border: 1px solid #e5e5e5;
        border-radius: 12px;
        padding: 0 16px;
        font-size: 14px;
        color: #333;
        outline: none;
        cursor: pointer;
    }

    #category-filter:focus {
        border-color: #e8192c;
    }
</style>
@endpush
<x-portal-layout
    title="Inventory"
    subtitle="Product and stock management"
    active="inventory"
>
    <div class="toolbar">
      <div class="search-box">
        <svg viewBox="0 0 24 24"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
        <input type="text" id="search-input" placeholder="Search product...">
      </div>
      <select id="category-filter">
    <option value="all">All</option>

     @foreach($mainCategories as $mainCategory)
        <option value="main-{{ $mainCategory->id }}">
            {{ $mainCategory->name }}
        </option>

        @foreach($mainCategory->children as $child)
            <option value="sub-{{ $child->id }}">
               └─ {{ $child->name }}
            </option>
        @endforeach
     @endforeach
    </select>
    </div>

    <div class="table-card">
      <table>
        <thead>
          <tr>
            <th>Code</th>
            <th>Product</th>
            <th>Category</th>
            <th>Price</th>
            <th>Stock</th>
            <th>Status</th>
          </tr>
        </thead>
        <tbody id="products-table">
          @forelse($products as $index => $product)
          <tr 
            data-name="{{ strtolower($product->name) }}"
            data-category-id="{{ $product->category_id }}"
            data-parent-category-id="{{ $product->category?->parent_id ?? $product->category_id }}">
            <td class="prod-code">P{{ str_pad($index + 1, 3, '0', STR_PAD_LEFT) }}</td>
            <td class="prod-name">{{ $product->name }}</td>
            <td>{{ $product->category?->name }}</td>
            <td>S/ {{ number_format($product->price, 2) }}</td>
            <td>{{ $product->stock }}</td>
            <td>
              @if($product->stock <= 0)
                <span class="badge out">Out of stock</span>
              @elseif($product->stock <= 10)
                <span class="badge low">Low stock</span>
              @else
                <span class="badge ok">Available</span>
              @endif
            </td>
          </tr>
          @empty
          <tr class="empty-row"><td colspan="6">No products found</td></tr>
          @endforelse
        </tbody>
      </table>
    </div>

    <script>
document.getElementById('search-input').addEventListener('input', filterTable);
document.getElementById('category-filter').addEventListener('change', filterTable);

function filterTable() {
  const search = document.getElementById('search-input').value.toLowerCase().trim();
  const categoryValue = document.getElementById('category-filter').value;

  document.querySelectorAll('#products-table tr[data-name]').forEach(row => {
    const productName = row.dataset.name || '';
    const categoryId = row.dataset.categoryId;
    const parentCategoryId = row.dataset.parentCategoryId;

    const matchName = productName.includes(search);

    let matchCategory = true;

    if (categoryValue !== 'all') {
      if (categoryValue.startsWith('main-')) {
        const mainId = categoryValue.replace('main-', '');

        matchCategory =
          parentCategoryId === mainId || categoryId === mainId;
      }

      if (categoryValue.startsWith('sub-')) {
        const subId = categoryValue.replace('sub-', '');

        matchCategory = categoryId === subId;
      }
    }

    row.style.display = matchName && matchCategory ? '' : 'none';
  });
}
    </script>

</x-portal-layout>