<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Express — Dashboard Admin</title>
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
  .topbar-date { font-size: 13px; color: #888; }

  .content { padding: 24px 28px; flex: 1; }

  .metrics { display: grid; grid-template-columns: repeat(4, 1fr); gap: 16px; margin-bottom: 24px; }
  .metric-card {
    background: #fff; border-radius: 12px; padding: 20px;
    border: 1px solid #eee; display: flex; justify-content: space-between; align-items: flex-start;
  }
  .metric-label { font-size: 13px; color: #999; margin-bottom: 8px; }
  .metric-value { font-size: 30px; font-weight: 800; color: #111; margin-bottom: 6px; }
  .metric-note { font-size: 12px; color: #bbb; }
  .metric-note.warn { color: #f59e0b; }
  .metric-icon {
    width: 40px; height: 40px; background: #fff0f2; border-radius: 10px;
    display: flex; align-items: center; justify-content: center; flex-shrink: 0;
  }
  .metric-icon svg { width: 20px; height: 20px; stroke: #e8192c; fill: none; stroke-width: 1.8; }
  .metric-icon.dark { background: #f5f5f5; }
  .metric-icon.dark svg { stroke: #333; }

  .quick-access { display: grid; grid-template-columns: repeat(3, 1fr); gap: 12px; margin-bottom: 24px; }
  .quick-btn {
    background: #fff; border: 1px solid #eee; border-radius: 12px;
    padding: 18px 16px; display: flex; flex-direction: column; align-items: center;
    gap: 10px; text-decoration: none; transition: all 0.15s;
    font-size: 13px; font-weight: 600; color: #333;
  }
  .quick-btn:hover { border-color: #e8192c; color: #e8192c; }
  .quick-btn svg { width: 22px; height: 22px; stroke: currentColor; fill: none; stroke-width: 1.8; }
  .quick-btn.red { background: #e8192c; color: #fff; border-color: #e8192c; }
  .quick-btn.red:hover { background: #c41525; }
  .quick-btn.dark { background: #111; color: #fff; border-color: #111; }
  .quick-btn.dark:hover { background: #222; }

  .bottom-row { display: grid; grid-template-columns: 1fr 340px; gap: 16px; }
  .table-card, .stock-card {
    background: #fff; border-radius: 12px; padding: 22px; border: 1px solid #eee;
  }
  .card-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px; }
  .card-header h3 { font-size: 15px; font-weight: 700; color: #111; display: flex; align-items: center; gap: 8px; }
  .card-header h3 svg { width: 16px; height: 16px; stroke: currentColor; fill: none; stroke-width: 2; }
  .ver-todo { font-size: 13px; color: #e8192c; text-decoration: none; font-weight: 500; }

  table { width: 100%; border-collapse: collapse; }
  th { font-size: 12px; color: #999; font-weight: 500; text-align: left; padding: 8px 0; border-bottom: 1px solid #f0f0f0; }
  td { font-size: 13px; color: #333; padding: 10px 0; border-bottom: 1px solid #f9f9f9; }
  .badge { display: inline-block; background: #f5f5f5; color: #555; font-size: 11px; padding: 3px 8px; border-radius: 6px; }
  .badge.warn { background: #fff7ed; color: #f59e0b; }

  .empty-state { text-align: center; padding: 32px 16px; color: #bbb; }
  .empty-state svg { width: 36px; height: 36px; stroke: #ddd; fill: none; stroke-width: 1.5; margin-bottom: 10px; }
  .empty-state p { font-size: 13px; }

  .stock-item { padding: 12px 0; border-bottom: 1px solid #f5f5f5; display: flex; justify-content: space-between; align-items: center; }
  .stock-item:last-child { border-bottom: none; }
  .stock-name { font-size: 13px; font-weight: 600; color: #111; }
  .stock-sub { font-size: 11px; color: #999; margin-top: 2px; }
  .stock-qty strong { font-size: 18px; font-weight: 800; color: #e8192c; display: block; text-align: right; }
  .stock-qty span { font-size: 11px; color: #999; }

  .info-banner {
    background: #fffbeb; border: 1px solid #fde68a; border-radius: 12px;
    padding: 14px 18px; margin-bottom: 24px; display: flex; align-items: center; gap: 12px;
  }
  .info-banner svg { width: 18px; height: 18px; stroke: #f59e0b; fill: none; stroke-width: 2; flex-shrink: 0; }
  .info-banner p { font-size: 13px; color: #78350f; }
  .info-banner strong { font-weight: 700; }
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
  <a href="/dashboard" class="nav-item active">
    <svg viewBox="0 0 24 24"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/></svg>
    Dashboard
  </a>
  <a href="/admin/products" class="nav-item">
    <svg viewBox="0 0 24 24"><path d="M21 16V8a2 2 0 00-1-1.73l-7-4a2 2 0 00-2 0l-7 4A2 2 0 003 8v8a2 2 0 001 1.73l7 4a2 2 0 002 0l7-4A2 2 0 0021 16z"/></svg>
    Inventory
  </a>
  <a href="/admin/suppliers" class="nav-item">
    <svg viewBox="0 0 24 24"><path d="M1 3h15v13H1z"/><path d="M16 8h4l3 3v5h-7V8z"/><circle cx="5.5" cy="18.5" r="2.5"/><circle cx="18.5" cy="18.5" r="2.5"/></svg>
    Suppliers
  </a>
  <a href="/admin/purchases" class="nav-item">
    <svg viewBox="0 0 24 24"><path d="M6 2L3 6v14a2 2 0 002 2h14a2 2 0 002-2V6l-3-4z"/><line x1="3" y1="6" x2="21" y2="6"/><path d="M16 10a4 4 0 01-8 0"/></svg>
    Purchases
  </a>
  <a href="/admin/promotions" class="nav-item">
    <svg viewBox="0 0 24 24"><path d="M20.59 13.41l-7.17 7.17a2 2 0 01-2.83 0L2 12V2h10l8.59 8.59a2 2 0 010 2.82z"/><line x1="7" y1="7" x2="7.01" y2="7"/></svg>
    Promotions
  </a>
  <a href="/admin/rewards" class="nav-item">
    <svg viewBox="0 0 24 24"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
    Rewards
  </a>
  <a href="/admin/loyalty" class="nav-item">
    <svg viewBox="0 0 24 24"><path d="M20.84 4.61a5.5 5.5 0 00-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 10-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 000-7.78z"/></svg>
    Loyalty
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
      <h1>Dashboard</h1>
      <p>Minimarket overview</p>
    </div>
    <span class="topbar-date">{{ now()->isoFormat('dddd, D [de] MMMM [de] YYYY') }}</span>
  </div>

  <div class="content">

    <div class="info-banner">
      <svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
      <p><strong>Sales are recorded by cashiers.</strong> 
    Sales, purchases and promotions are reflected here for supervision and reporting only.</p>
    </div>

    <div class="metrics">
      <div class="metric-card">
        <div>
          <div class="metric-label">Today's Sales</div>
          <div class="metric-value">{{ $totalSales }}</div>
          <div class="metric-note">Sales module pending</div>
        </div>
        <div class="metric-icon">
          <svg viewBox="0 0 24 24"><line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 000 7h5a3.5 3.5 0 010 7H6"/></svg>
        </div>
      </div>
      <div class="metric-card">
        <div>
          <div class="metric-label">Registered Products</div>
          <div class="metric-value">{{ $totalProducts }}</div>
          <div class="metric-note {{ $lowStock->count() > 0 ? 'warn' : '' }}">
            @if($lowStock->count() > 0)
              {{ $lowStock->count() }} with low stock
            @else
              Stock in good condition
            @endif
          </div>
        </div>
        <div class="metric-icon dark">
          <svg viewBox="0 0 24 24"><path d="M21 16V8a2 2 0 00-1-1.73l-7-4a2 2 0 00-2 0l-7 4A2 2 0 003 8v8a2 2 0 001 1.73l7 4a2 2 0 002 0l7-4A2 2 0 0021 16z"/></svg>
        </div>
      </div>
      <div class="metric-card">
        <div>
          <div class="metric-label">Categories</div>
          <div class="metric-value">{{ $totalCategories }}</div>
          <div class="metric-note">Active categories</div>
        </div>
        <div class="metric-icon dark">
          <svg viewBox="0 0 24 24"><line x1="8" y1="6" x2="21" y2="6"/><line x1="8" y1="12" x2="21" y2="12"/><line x1="8" y1="18" x2="21" y2="18"/><line x1="3" y1="6" x2="3.01" y2="6"/><line x1="3" y1="12" x2="3.01" y2="12"/><line x1="3" y1="18" x2="3.01" y2="18"/></svg>
        </div>
      </div>
      <div class="metric-card">
        <div>
          <div class="metric-label">Registered Users</div>
          <div class="metric-value">{{ $totalUsers }}</div>
          <div class="metric-note">Clients</div>
        </div>
        <div class="metric-icon dark">
          <svg viewBox="0 0 24 24"><path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 00-3-3.87"/><path d="M16 3.13a4 4 0 010 7.75"/></svg>
        </div>
      </div>
    </div>

    <div class="quick-access">
      <a href="/admin/products" class="quick-btn red">
        <svg viewBox="0 0 24 24"><path d="M21 16V8a2 2 0 00-1-1.73l-7-4a2 2 0 00-2 0l-7 4A2 2 0 003 8v8a2 2 0 001 1.73l7 4a2 2 0 002 0l7-4A2 2 0 0021 16z"/></svg>
        Inventory
      </a>
      <a href="/admin/rewards" class="quick-btn dark">
        <svg viewBox="0 0 24 24"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
        Rewards
      </a>
      <a href="/admin/purchases/create" class="quick-btn">
        <svg viewBox="0 0 24 24"><circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/><path d="M1 1h4l2.68 13.39a2 2 0 002 1.61h9.72a2 2 0 002-1.61L23 6H6"/></svg>
        New Purchase
      </a>
      <a href="/admin/promotions" class="quick-btn">
        <svg viewBox="0 0 24 24"><path d="M20.59 13.41l-7.17 7.17a2 2 0 01-2.83 0L2 12V2h10l8.59 8.59a2 2 0 010 2.82z"/><line x1="7" y1="7" x2="7.01" y2="7"/></svg>
        Promotions
      </a>
      <a href="/admin/reports" class="quick-btn">
    Sales Reports
       </a>
    </div>

    <div class="bottom-row">
      <div class="table-card">
        <div class="card-header">
          <h3>
            <svg viewBox="0 0 24 24"><path d="M21 16V8a2 2 0 00-1-1.73l-7-4a2 2 0 00-2 0l-7 4A2 2 0 003 8v8a2 2 0 001 1.73l7 4a2 2 0 002 0l7-4A2 2 0 0021 16z"/></svg>
            Last Products Added
          </h3>
          <a href="/admin/products" class="ver-todo">View all</a>
        </div>

        @if($recentProducts->count() > 0)
        <table>
          <thead>
            <tr>
              <th>Product</th>
              <th>Category</th>
              <th>Stock</th>
              <th style="text-align:right">Price</th>
            </tr>
          </thead>
          <tbody>
            @foreach($recentProducts as $p)
            <tr>
              <td>{{ $p->name }}</td>
              <td><span class="badge">{{ $p->category->name ?? '—' }}</span></td>
              <td>
                @if($p->stock < 10)
                  <span class="badge warn">{{ $p->stock }} uds</span>
                @else
                  {{ $p->stock }} uds
                @endif
              </td>
              <td style="text-align:right; font-weight:700">S/ {{ number_format($p->price, 2) }}</td>
            </tr>
            @endforeach
          </tbody>
        </table>
        @else
        <div class="empty-state">
          <svg viewBox="0 0 24 24"><path d="M21 16V8a2 2 0 00-1-1.73l-7-4a2 2 0 00-2 0l-7 4A2 2 0 003 8v8a2 2 0 001 1.73l7 4a2 2 0 002 0l7-4A2 2 0 0021 16z"/></svg>
          <p>Sales module pending<br><a href="/admin/products" style="color:#e8192c">Add the first one →</a></p>
        </div>
        @endif
      </div>

      <div class="stock-card">
        <div class="card-header">
          <h3 style="color:#f59e0b">
            <svg viewBox="0 0 24 24" style="stroke:#f59e0b"><path d="M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
            Low Stock (&lt; 10 uds)
          </h3>
          <a href="/admin/products" class="ver-todo">Manage</a>
        </div>

        @if($lowStock->count() > 0)
          @foreach($lowStock as $p)
          <div class="stock-item">
            <div>
              <div class="stock-name">{{ $p->name }}</div>
              <div class="stock-sub">{{ $p->category->name ?? 'No category' }}</div>
            </div>
            <div class="stock-qty">
              <strong>{{ $p->stock }}</strong>
              <span>units</span>
            </div>
          </div>
          @endforeach
        @else
          <div class="empty-state">
            <svg viewBox="0 0 24 24"><path d="M22 11.08V12a10 10 0 11-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
            <p>All stock is in good condition.</p>
          </div>
        @endif
      </div>
    </div>

  </div>
</div>

</body>
</html>