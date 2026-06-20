<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Express — Cashier Dashboard</title>
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
  .user-avatar { width: 34px; height: 34px; background: #e8192c; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 13px; font-weight: 700; color: #fff; flex-shrink: 0; }
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

  .content { padding: 24px 28px; flex: 1; }

  .metrics { display: grid; grid-template-columns: repeat(3, 1fr); gap: 16px; margin-bottom: 20px; }
  .metric-card { background: #fff; border-radius: 12px; padding: 20px; border: 1px solid #eee; display: flex; justify-content: space-between; align-items: flex-start; }
  .metric-label { font-size: 13px; color: #999; margin-bottom: 8px; }
  .metric-value { font-size: 26px; font-weight: 800; color: #111; margin-bottom: 6px; }
  .metric-change { font-size: 12px; color: #22c55e; }
  .metric-icon { width: 40px; height: 40px; background: #fff0f2; border-radius: 10px; display: flex; align-items: center; justify-content: center; }
  .metric-icon svg { width: 20px; height: 20px; stroke: #e8192c; fill: none; stroke-width: 1.8; }

  .new-sale-btn { display: flex; align-items: center; justify-content: center; gap: 12px; background: #e8192c; color: #fff; border-radius: 12px; padding: 22px; font-size: 18px; font-weight: 700; text-decoration: none; margin-bottom: 20px; transition: background 0.2s; }
  .new-sale-btn:hover { background: #c41525; color: #fff; }
  .new-sale-btn svg { width: 24px; height: 24px; stroke: #fff; fill: none; stroke-width: 2; }

  .bottom-row { display: grid; grid-template-columns: 1fr 340px; gap: 16px; }
  .table-card { background: #fff; border-radius: 12px; padding: 22px; border: 1px solid #eee; }
  .table-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px; }
  .table-header h3 { font-size: 15px; font-weight: 700; color: #111; }
  .ver-todo { font-size: 13px; color: #e8192c; text-decoration: none; font-weight: 500; }
  table { width: 100%; border-collapse: collapse; }
  th { font-size: 12px; color: #999; font-weight: 500; text-align: left; padding: 8px 0; border-bottom: 1px solid #f0f0f0; }
  td { font-size: 13px; color: #333; padding: 10px 0; border-bottom: 1px solid #f9f9f9; }
  .badge { display: inline-block; background: #f5f5f5; color: #555; font-size: 11px; padding: 3px 8px; border-radius: 6px; }

  .quick-card { background: #fff; border-radius: 12px; padding: 22px; border: 1px solid #eee; }
  .quick-card h3 { font-size: 15px; font-weight: 700; color: #111; margin-bottom: 16px; }
  .quick-link { display: flex; align-items: center; gap: 12px; padding: 12px; border-radius: 8px; border: 1px solid #eee; text-decoration: none; color: #333; font-size: 14px; font-weight: 500; margin-bottom: 10px; transition: all 0.15s; }
  .quick-link:hover { border-color: #e8192c; color: #e8192c; }
  .quick-link svg { width: 18px; height: 18px; stroke: currentColor; fill: none; stroke-width: 1.8; }
  .empty { text-align: center; color: #aaa; font-size: 13px; padding: 24px 0; }
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
    <a href="/cashier/dashboard" class="nav-item active">
      <svg viewBox="0 0 24 24"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/></svg>
      Dashboard
    </a>
    <a href="/cashier/sales/create" class="nav-item">
      <svg viewBox="0 0 24 24"><circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/><path d="M1 1h4l2.68 13.39a2 2 0 002 1.61h9.72a2 2 0 002-1.61L23 6H6"/></svg>
      Sales (POS)
    </a>
    <a href="/cashier/inventory" class="nav-item">
      <svg viewBox="0 0 24 24"><path d="M21 16V8a2 2 0 00-1-1.73l-7-4a2 2 0 00-2 0l-7 4A2 2 0 003 8v8a2 2 0 001 1.73l7 4a2 2 0 002 0l7-4A2 2 0 0021 16z"/></svg>
      Inventory
    </a>
    <a href="/cashier/cash" class="nav-item">
      <svg viewBox="0 0 24 24"><rect x="2" y="5" width="20" height="14" rx="2"/><line x1="2" y1="10" x2="22" y2="10"/></svg>
      Cash Register
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
      <h1>Dashboard</h1>
      <p>Your activity summary for today</p>
    </div>
    <div class="topbar-right">
      <span class="topbar-date">{{ now()->isoFormat('dddd, D [of] MMMM [of] YYYY') }}</span>
    </div>
  </div>

  <div class="content">

    <div class="metrics">
      <div class="metric-card">
        <div>
          <div class="metric-label">Sales today</div>
          <div class="metric-value">S/ {{ number_format($totalToday, 2) }}</div>
          <div class="metric-change">Your sales today</div>
        </div>
        <div class="metric-icon"><svg viewBox="0 0 24 24"><line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 000 7h5a3.5 3.5 0 010 7H6"/></svg></div>
      </div>
      <div class="metric-card">
        <div>
          <div class="metric-label">Transactions</div>
          <div class="metric-value">{{ $transactionsToday }}</div>
          <div class="metric-change">Sales registered</div>
        </div>
        <div class="metric-icon"><svg viewBox="0 0 24 24"><circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/><path d="M1 1h4l2.68 13.39a2 2 0 002 1.61h9.72a2 2 0 002-1.61L23 6H6"/></svg></div>
      </div>
      <div class="metric-card">
        <div>
          <div class="metric-label">Average ticket</div>
          <div class="metric-value">S/ {{ $transactionsToday > 0 ? number_format($totalToday / $transactionsToday, 2) : '0.00' }}</div>
          <div class="metric-change">Per transaction</div>
        </div>
        <div class="metric-icon"><svg viewBox="0 0 24 24"><line x1="18" y1="20" x2="18" y2="10"/><line x1="12" y1="20" x2="12" y2="4"/><line x1="6" y1="20" x2="6" y2="14"/></svg></div>
      </div>
    </div>

    <a href="{{ route('sales.create') }}" class="new-sale-btn">
      <svg viewBox="0 0 24 24"><circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/><path d="M1 1h4l2.68 13.39a2 2 0 002 1.61h9.72a2 2 0 002-1.61L23 6H6"/></svg>
      New Sale
    </a>

    <div class="bottom-row">
      <div class="table-card">
        <div class="table-header">
          <h3>My latest sales</h3>
          <a href="#" class="ver-todo">View all</a>
        </div>
        @if($recentSales->count() > 0)
        <table>
          <thead>
            <tr>
              <th>#</th>
              <th>Customer</th>
              <th>Date</th>
              <th style="text-align:right">Total</th>
            </tr>
          </thead>
          <tbody>
            @foreach($recentSales as $sale)
            <tr>
              <td>#{{ $sale->id }}</td>
              <td>{{ $sale->customer->name ?? 'N/A' }}</td>
              <td>{{ $sale->created_at->format('h:i A') }}</td>
              <td style="text-align:right">S/ {{ number_format($sale->total, 2) }}</td>
            </tr>
            @endforeach
          </tbody>
        </table>
        @else
        <div class="empty">No sales registered today</div>
        @endif
      </div>

      <div class="quick-card">
        <h3>Quick access</h3>
        <a href="{{ route('sales.create') }}" class="quick-link">
          <svg viewBox="0 0 24 24"><circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/><path d="M1 1h4l2.68 13.39a2 2 0 002 1.61h9.72a2 2 0 002-1.61L23 6H6"/></svg>
          Register new sale
        </a>
        <a href="{{ route('cashier.inventory') }}" class="quick-link">
          <svg viewBox="0 0 24 24"><path d="M21 16V8a2 2 0 00-1-1.73l-7-4a2 2 0 00-2 0l-7 4A2 2 0 003 8v8a2 2 0 001 1.73l7 4a2 2 0 002 0l7-4A2 2 0 0021 16z"/></svg>
          View products
        </a>
        <a href="{{ route('cashier.loyalty') }}" class="quick-link">
          <svg viewBox="0 0 24 24"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
          Loyalty program
        </a>
      </div>
    </div>

  </div>
</div>

</body>
</html>