<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Express — Inventory</title>
<style>
  * { margin: 0; padding: 0; box-sizing: border-box; }
  body { font-family: 'Inter', sans-serif; background: #f5f5f5; display: flex; min-height: 100vh; }

  .sidebar { width: 240px; min-height: 100vh; background: #111; display: flex; flex-direction: column; position: fixed; top: 0; left: 0; bottom: 0; }
  .sidebar-logo { display: flex; align-items: center; gap: 12px; padding: 24px 20px; border-bottom: 1px solid rgba(255,255,255,0.06); }
  .logo-icon { width: 38px; height: 38px; background: #e8192c; border-radius: 9px; display: flex; align-items: center; justify-content: center; }
  .logo-icon svg { width: 20px; height: 20px; fill: #fff; }
  .logo-text strong { font-size: 15px; font-weight: 700; color: #fff; display: block; }
  .logo-text span { font-size: 11px; color: #666; }
  .sidebar-nav { flex: 1; padding: 16px 12px; }
  .nav-item { display: flex; align-items: center; gap: 12px; padding: 11px 14px; border-radius: 8px; color: #888; font-size: 14px; font-weight: 500; text-decoration: none; margin-bottom: 2px; transition: all 0.15s; }
  .nav-item:hover { background: rgba(255,255,255,0.06); color: #fff; }
  .nav-item.active { background: #e8192c; color: #fff; }
  .nav-item svg { width: 18px; height: 18px; stroke: currentColor; fill: none; stroke-width: 1.8; flex-shrink: 0; }
  .sidebar-user { padding: 16px 20px; border-top: 1px solid rgba(255,255,255,0.06); display: flex; align-items: center; gap: 12px; }
  .user-avatar { width: 34px; height: 34px; background: #e8192c; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 13px; font-weight: 700; color: #fff; }
  .user-info strong { font-size: 13px; color: #fff; display: block; }
  .user-info span { font-size: 11px; color: #666; }
  .logout-btn { margin-left: auto; background: none; border: none; cursor: pointer; color: #555; }
  .logout-btn:hover { color: #e8192c; }
  .logout-btn svg { width: 18px; height: 18px; stroke: currentColor; fill: none; stroke-width: 1.8; }

  .main { margin-left: 240px; flex: 1; display: flex; flex-direction: column; }
  .topbar { background: #fff; padding: 16px 28px; display: flex; align-items: center; justify-content: space-between; border-bottom: 1px solid #eee; position: sticky; top: 0; z-index: 10; }
  .topbar-title h1 { font-size: 22px; font-weight: 800; color: #111; }
  .topbar-title p { font-size: 13px; color: #999; margin-top: 2px; }
  .topbar-right { display: flex; align-items: center; gap: 20px; }
  .topbar-date { font-size: 13px; color: #888; }

  .content { padding: 24px 28px; }

  .toolbar { display: flex; align-items: center; gap: 12px; margin-bottom: 20px; }
  .search-box { display: flex; align-items: center; gap: 8px; background: #fff; border: 1px solid #e8e8e8; border-radius: 8px; padding: 9px 14px; flex: 1; max-width: 360px; }
  .search-box svg { width: 15px; height: 15px; stroke: #aaa; fill: none; stroke-width: 1.8; flex-shrink: 0; }
  .search-box input { border: none; background: transparent; font-size: 13px; color: #555; outline: none; width: 100%; }

  .filter-select { padding: 9px 14px; border: 1px solid #e8e8e8; border-radius: 8px; font-size: 13px; color: #555; background: #fff; outline: none; cursor: pointer; }
  .filter-select:focus { border-color: #e8192c; }

  .btn-filter { display: flex; align-items: center; gap: 6px; padding: 9px 16px; border: 1px solid #e8e8e8; border-radius: 8px; background: #fff; font-size: 13px; color: #555; cursor: pointer; }
  .btn-filter svg { width: 14px; height: 14px; stroke: currentColor; fill: none; stroke-width: 1.8; }
  .btn-filter:hover { border-color: #e8192c; color: #e8192c; }

  .table-card { background: #fff; border-radius: 12px; border: 1px solid #eee; overflow: hidden; }
  table { width: 100%; border-collapse: collapse; }
  thead { background: #fafafa; border-bottom: 1px solid #eee; }
  th { padding: 12px 16px; font-size: 11px; font-weight: 600; color: #999; text-align: left; letter-spacing: 0.05em; text-transform: uppercase; }
  td { padding: 14px 16px; font-size: 13px; color: #333; border-bottom: 1px solid #f5f5f5; }
  tr:last-child td { border-bottom: none; }
  tr:hover td { background: #fafafa; }

  .code { font-size: 12px; color: #999; font-family: monospace; }
  .product-name { font-weight: 600; color: #111; }
  .category { color: #e8192c; font-size: 12px; font-weight: 500; }
  .price { font-weight: 700; color: #111; }
  .stock-num { font-weight: 600; }

  .badge { display: inline-flex; align-items: center; gap: 5px; padding: 4px 10px; border-radius: 100px; font-size: 11px; font-weight: 600; }
  .badge-available { background: #f0fdf4; color: #16a34a; }
  .badge-low { background: #fff7ed; color: #ea580c; }
  .badge-dot { width: 6px; height: 6px; border-radius: 50%; background: currentColor; }

  .empty { text-align: center; padding: 48px; color: #aaa; font-size: 14px; }
</style>
</head>
<body>

<aside class="sidebar">
  <div class="sidebar-logo">
    <div class="logo-icon">
      <svg viewBox="0 0 24 24"><path d="M3 9l9-7 9 7v11a2 2 0 01-2 2H5a2 2 0 01-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
    </div>
    <div class="logo-text">
      <strong>Express</strong>
      <span>Minimarket POS</span>
    </div>
  </div>
  <nav class="sidebar-nav">
    <a href="/cashier/dashboard" class="nav-item">
      <svg viewBox="0 0 24 24"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/></svg>
      Dashboard
    </a>
    <a href="/cashier/sales/create" class="nav-item">
      <svg viewBox="0 0 24 24"><circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/><path d="M1 1h4l2.68 13.39a2 2 0 002 1.61h9.72a2 2 0 002-1.61L23 6H6"/></svg>
      Sales (POS)
    </a>
    <a href="/cashier/inventory" class="nav-item active">
      <svg viewBox="0 0 24 24"><path d="M21 16V8a2 2 0 00-1-1.73l-7-4a2 2 0 00-2 0l-7 4A2 2 0 003 8v8a2 2 0 001 1.73l7 4a2 2 0 002 0l7-4A2 2 0 0021 16z"/></svg>
      Inventory
    </a>
    <a href="/cashier/loyalty" class="nav-item">
      <svg viewBox="0 0 24 24"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
      Loyalty
    </a>
  </nav>
  <div class="sidebar-user">
    <div class="user-avatar">{{ strtoupper(substr(Auth::user()->name, 0, 1)) }}</div>
    <div class="user-info">
      <strong>{{ Auth::user()->name }}</strong>
      <span>Cashier</span>
    </div>
    <form method="POST" action="{{ route('logout') }}" style="margin-left:auto">
      @csrf
      <button type="submit" class="logout-btn">
        <svg viewBox="0 0 24 24"><path d="M9 21H5a2 2 0 01-2-2V5a2 2 0 012-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg>
      </button>
    </form>
  </div>
</aside>

<div class="main">
  <div class="topbar">
    <div class="topbar-title">
      <h1>Inventory</h1>
      <p>Product and stock management</p>
    </div>
    <div class="topbar-right">
      <span class="topbar-date">{{ now()->isoFormat('dddd, D [of] MMMM [of] YYYY') }}</span>
    </div>
  </div>

  <div class="content">
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
      <button class="btn-filter">
        <svg viewBox="0 0 24 24"><polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3"/></svg>
        Filters
      </button>
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
            <td><span class="code">P{{ str_pad($index + 1, 3, '0', STR_PAD_LEFT) }}</span></td>
            <td><span class="product-name">{{ $product->name }}</span></td>
            <td><span class="category">{{ $product->category?->name }}</span></td>
            <td><span class="price">S/ {{ number_format($product->price, 2) }}</span></td>
            <td><span class="stock-num">{{ $product->stock }}</span></td>
            <td>
              @if($product->stock <= 0)
                <span class="badge badge-low"><span class="badge-dot"></span> Out of stock</span>
              @elseif($product->stock <= 10)
                <span class="badge badge-low"><span class="badge-dot"></span> Low stock</span>
              @else
                <span class="badge badge-available"><span class="badge-dot"></span> Available</span>
              @endif
            </td>
          </tr>
          @empty
          <tr><td colspan="6" class="empty">No products found</td></tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>
</div>

<script>
document.getElementById('search-input').addEventListener('input', function() {
  const q = this.value.toLowerCase();
  filterTable();
});

document.getElementById('category-filter').addEventListener('change', function() {
  filterTable();
});

function filterTable() {
  const q = document.getElementById('search-input').value.toLowerCase();
  const cat = document.getElementById('category-filter').value;
  document.querySelectorAll('#products-table tr').forEach(row => {
    const name = row.dataset.name || '';
    const category = row.dataset.category || '';
    const matchName = name.includes(q);
    const matchCat = cat === 'all' || category === cat;
    row.style.display = matchName && matchCat ? '' : 'none';
  });
}
</script>

</body>
</html>