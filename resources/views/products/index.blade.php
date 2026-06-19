<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Express — Inventory</title>
<style>
  * { margin: 0; padding: 0; box-sizing: border-box; }
  body { font-family: 'Inter', sans-serif; background: #f5f5f5; display: flex; min-height: 100vh; }

  .sidebar {
    width: 240px; min-height: 100vh; background: #111; display: flex;
    flex-direction: column; position: fixed; top: 0; left: 0; bottom: 0;
  }
  .sidebar-logo { display: flex; align-items: center; gap: 12px; padding: 24px 20px; border-bottom: 1px solid rgba(255,255,255,0.06); }
  .logo-icon { width: 38px; height: 38px; background: #e8192c; border-radius: 9px; display: flex; align-items: center; justify-content: center; }
  .logo-icon svg { width: 20px; height: 20px; fill: #fff; }
  .logo-text strong { font-size: 15px; font-weight: 700; color: #fff; display: block; }
  .logo-text span { font-size: 11px; color: #666; }

  .sidebar-nav { flex: 1; padding: 16px 12px; }
  .nav-item {
    display: flex; align-items: center; gap: 12px; padding: 11px 14px;
    border-radius: 8px; color: #888; font-size: 14px; font-weight: 500;
    text-decoration: none; margin-bottom: 2px; transition: all 0.15s;
  }
  .nav-item:hover { background: rgba(255,255,255,0.06); color: #fff; }
  .nav-item.active { background: #e8192c; color: #fff; }
  .nav-item svg { width: 18px; height: 18px; stroke: currentColor; fill: none; stroke-width: 1.8; flex-shrink: 0; }

  .sidebar-user {
    padding: 16px 20px; border-top: 1px solid rgba(255,255,255,0.06);
    display: flex; align-items: center; gap: 12px;
  }
  .user-avatar {
    width: 34px; height: 34px; background: #e8192c; border-radius: 50%;
    display: flex; align-items: center; justify-content: center;
    font-size: 13px; font-weight: 700; color: #fff; flex-shrink: 0;
  }
  .user-info strong { font-size: 13px; color: #fff; display: block; }
  .user-info span { font-size: 11px; color: #666; }
  .logout-btn { margin-left: auto; background: none; border: none; cursor: pointer; color: #555; transition: color 0.15s; }
  .logout-btn:hover { color: #e8192c; }
  .logout-btn svg { width: 18px; height: 18px; stroke: currentColor; fill: none; stroke-width: 1.8; }

  .main { margin-left: 240px; flex: 1; display: flex; flex-direction: column; }

  .topbar {
    background: #fff; padding: 16px 28px; display: flex; align-items: center;
    justify-content: space-between; border-bottom: 1px solid #eee; position: sticky; top: 0; z-index: 10;
  }
  .topbar-title h1 { font-size: 22px; font-weight: 800; color: #111; }
  .topbar-title p { font-size: 13px; color: #999; margin-top: 2px; }
  .topbar-right { display: flex; align-items: center; gap: 18px; }
  .topbar-date { font-size: 13px; color: #888; }
  .bell-btn { background: none; border: none; cursor: pointer; color: #555; position: relative; }
  .bell-btn svg { width: 19px; height: 19px; stroke: currentColor; fill: none; stroke-width: 1.8; }

  .content { padding: 24px 28px; flex: 1; }

  .flash-success { background: #ecfdf5; border: 1px solid #a7f3d0; color: #065f46; padding: 12px 16px; border-radius: 10px; margin-bottom: 18px; font-size: 13px; }

  .toolbar { display: flex; align-items: center; gap: 12px; margin-bottom: 18px; }
  .search-box {
    flex: 1; display: flex; align-items: center; gap: 8px; background: #fff;
    border: 1px solid #e5e5e5; border-radius: 10px; padding: 10px 14px;
  }
  .search-box svg { width: 16px; height: 16px; stroke: #aaa; fill: none; stroke-width: 1.8; flex-shrink: 0; }
  .search-box input { border: none; outline: none; font-size: 13px; width: 100%; background: transparent; }

  .filter-select {
    border: 1px solid #e5e5e5; border-radius: 10px; padding: 10px 14px;
    font-size: 13px; color: #333; background: #fff; cursor: pointer;
  }

  .btn {
    display: flex; align-items: center; gap: 8px; border-radius: 10px;
    padding: 10px 16px; font-size: 13px; font-weight: 600; cursor: pointer;
    text-decoration: none; border: 1px solid #e5e5e5; background: #fff; color: #333;
    white-space: nowrap;
  }
  .btn svg { width: 15px; height: 15px; stroke: currentColor; fill: none; stroke-width: 2; }
  .btn-primary { background: #e8192c; border-color: #e8192c; color: #fff; }
  .btn-primary:hover { background: #c41525; }
  .btn:hover:not(.btn-primary) { border-color: #ccc; }

  .table-card { background: #fff; border-radius: 12px; border: 1px solid #eee; overflow: hidden; }
  table { width: 100%; border-collapse: collapse; }
  th { font-size: 11px; text-transform: uppercase; letter-spacing: .03em; color: #999; font-weight: 600; text-align: left; padding: 14px 18px; border-bottom: 1px solid #f0f0f0; background: #fafafa; }
  td { font-size: 13px; color: #333; padding: 14px 18px; border-bottom: 1px solid #f5f5f5; }
  tr:last-child td { border-bottom: none; }
  .prod-code { color: #999; font-weight: 600; }
  .prod-name { font-weight: 700; color: #111; }
  .badge { display: inline-block; font-size: 11px; font-weight: 700; padding: 4px 10px; border-radius: 20px; }
  .badge.ok { background: #ecfdf5; color: #059669; }
  .badge.low { background: #fff7ed; color: #d97706; }
  .badge.out { background: #fef2f2; color: #dc2626; }
  .actions { display: flex; gap: 8px; }
  .icon-btn { width: 30px; height: 30px; border-radius: 7px; border: 1px solid #eee; background: #fff; display: flex; align-items: center; justify-content: center; cursor: pointer; color: #555; }
  .icon-btn:hover { border-color: #ccc; }
  .icon-btn svg { width: 14px; height: 14px; stroke: currentColor; fill: none; stroke-width: 1.8; }
  .icon-btn.danger:hover { color: #e8192c; border-color: #e8192c; }

  .empty-row td { text-align: center; padding: 40px; color: #bbb; font-size: 13px; }

  .modal-overlay {
    display: none; position: fixed; inset: 0; background: rgba(17,17,17,0.55);
    align-items: center; justify-content: center; z-index: 100; padding: 20px;
  }
  .modal-overlay.open { display: flex; }
  .modal-box { background: #fff; border-radius: 16px; padding: 28px 30px; max-width: 480px; width: 100%; max-height: 90vh; overflow-y: auto; }
  .modal-header { display: flex; align-items: flex-start; justify-content: space-between; margin-bottom: 4px; }
  .modal-header-title { display: flex; align-items: center; gap: 10px; }
  .modal-icon { width: 34px; height: 34px; background: #fff0f2; border-radius: 9px; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
  .modal-icon svg { width: 18px; height: 18px; stroke: #e8192c; fill: none; stroke-width: 1.8; }
  .modal-header h2 { font-size: 18px; font-weight: 800; color: #111; }
  .modal-close { background: none; border: none; cursor: pointer; color: #999; }
  .modal-close svg { width: 18px; height: 18px; stroke: currentColor; fill: none; stroke-width: 2; }
  .modal-subtitle { font-size: 13px; color: #999; margin: 4px 0 20px 44px; }
  .form-group { margin-bottom: 16px; }
  .form-row { display: grid; grid-template-columns: 1fr 1fr; gap: 14px; }
  .form-group label { display: block; font-size: 13px; font-weight: 600; color: #333; margin-bottom: 6px; }
  .form-group input, .form-group select, .form-group textarea {
    width: 100%; border: 1px solid #e5e5e5; background: #fafafa; border-radius: 9px;
    padding: 10px 12px; font-size: 13px; font-family: inherit; color: #111;
  }
  .form-group input:focus, .form-group select:focus, .form-group textarea:focus { outline: none; border-color: #e8192c; background: #fff; }
  .field-error { color: #dc2626; font-size: 12px; margin-top: 4px; }
  .modal-actions { display: flex; justify-content: flex-end; gap: 10px; margin-top: 22px; }
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
    <a href="/dashboard" class="nav-item">
      <svg viewBox="0 0 24 24"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/></svg>
      Dashboard
    </a>
    <a href="/admin/products" class="nav-item active">
      <svg viewBox="0 0 24 24"><path d="M21 16V8a2 2 0 00-1-1.73l-7-4a2 2 0 00-2 0l-7 4A2 2 0 003 8v8a2 2 0 001 1.73l7 4a2 2 0 002 0l7-4A2 2 0 0021 16z"/></svg>
      Inventory
    </a>
    <a href="/admin/categories" class="nav-item">
      <svg viewBox="0 0 24 24"><line x1="8" y1="6" x2="21" y2="6"/><line x1="8" y1="12" x2="21" y2="12"/><line x1="8" y1="18" x2="21" y2="18"/><line x1="3" y1="6" x2="3.01" y2="6"/><line x1="3" y1="12" x2="3.01" y2="12"/><line x1="3" y1="18" x2="3.01" y2="18"/></svg>
      Categories
    </a>
    <a href="/admin/rewards" class="nav-item">
      <svg viewBox="0 0 24 24"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
      Rewards
    </a>
    <a href="/admin/purchases" class="nav-item">
      <svg viewBox="0 0 24 24"><circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/><path d="M1 1h4l2.68 13.39a2 2 0 002 1.61h9.72a2 2 0 002-1.61L23 6H6"/></svg>
      Purchases
    </a>
    <a href="/admin/promotions" class="nav-item">
      <svg viewBox="0 0 24 24"><path d="M20.59 13.41l-7.17 7.17a2 2 0 01-2.83 0L2 12V2h10l8.59 8.59a2 2 0 010 2.82z"/><line x1="7" y1="7" x2="7.01" y2="7"/></svg>
      Promotions
    </a>
    <a href="/admin/reports" class="nav-item">
      <svg viewBox="0 0 24 24"><line x1="18" y1="20" x2="18" y2="10"/><line x1="12" y1="20" x2="12" y2="4"/><line x1="6" y1="20" x2="6" y2="14"/></svg>
      Reports
    </a>
  </nav>

  <div class="sidebar-user">
    <div class="user-avatar">{{ strtoupper(substr(Auth::user()->name, 0, 1)) }}</div>
    <div class="user-info">
      <strong>{{ Auth::user()->name }}</strong>
      <span>{{ ucfirst(Auth::user()->role) }}</span>
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
      <span class="topbar-date">{{ now()->isoFormat('dddd, D [de] MMMM [de] YYYY') }}</span>
      <button class="bell-btn">
        <svg viewBox="0 0 24 24"><path d="M18 8a6 6 0 10-12 0c0 7-3 9-3 9h18s-3-2-3-9"/><path d="M13.73 21a2 2 0 01-3.46 0"/></svg>
      </button>
    </div>
  </div>

  <div class="content">

    @if(session('success'))
      <div class="flash-success">{{ session('success') }}</div>
    @endif

    <div class="toolbar">
      <div class="search-box">
        <svg viewBox="0 0 24 24"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
        <input type="text" id="productSearch" placeholder="Buscar producto...">
      </div>

      <select class="filter-select" id="categoryFilter">
        <option value="">Todos</option>
        @foreach($products->pluck('category')->filter()->unique('id') as $cat)
          <option value="{{ $cat->name }}">{{ $cat->name }}</option>
        @endforeach
      </select>

      <button class="btn" id="exportBtn">
        <svg viewBox="0 0 24 24"><path d="M21 15v4a2 2 0 01-2 2H5a2 2 0 01-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
        Exportar
      </button>

      <button class="btn btn-primary" id="openCreateModal" type="button">
        <svg viewBox="0 0 24 24"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
        Agregar producto
      </button>
    </div>

    <div class="table-card">
      <table id="productsTable">
        <thead>
          <tr>
            <th>Código</th>
            <th>Producto</th>
            <th>Categoría</th>
            <th>Precio</th>
            <th>Stock</th>
            <th>Estado</th>
            <th style="text-align:right">Acciones</th>
          </tr>
        </thead>
        <tbody>
          @forelse($products as $product)
            @php
              $stock = $product->stock;
              $minStock = $product->min_stock ?? 5;
              if ($stock <= 0) {
                  $statusClass = 'out';
                  $statusLabel = 'Agotado';
              } elseif ($stock < $minStock) {
                  $statusClass = 'low';
                  $statusLabel = 'Bajo stock';
              } else {
                  $statusClass = 'ok';
                  $statusLabel = 'Disponible';
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
                  <form action="/admin/products/{{ $product->id }}" method="POST" onsubmit="return confirm('¿Eliminar este producto?');">
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
              <td colspan="7">No hay productos registrados todavía.</td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>

  </div>
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
            <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
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

</body>
</html>