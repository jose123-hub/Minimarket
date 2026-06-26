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
      <select class="filter-select" id="category-filter">
        <option value="all">All</option>
        @foreach($categories as $cat)
          <option value="{{ $cat->name }}">{{ $cat->name }}</option>
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
          <tr data-name="{{ strtolower($product->name) }}" data-category="{{ $product->category?->name }}">
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
        const q = document.getElementById('search-input').value.toLowerCase();
        const cat = document.getElementById('category-filter').value;
        document.querySelectorAll('#products-table tr[data-name]').forEach(row => {
          const matchName = row.dataset.name.includes(q);
          const matchCat = cat === 'all' || row.dataset.category === cat;
          row.style.display = matchName && matchCat ? '' : 'none';
        });
      }
    </script>

</x-portal-layout>