<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Express — Dashboard</title>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.0/chart.umd.min.js"></script>
<style>
  * { margin: 0; padding: 0; box-sizing: border-box; }
  body { font-family: 'Inter', sans-serif; background: #f5f5f5; display: flex; min-height: 100vh; }

  /* SIDEBAR */
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
    text-decoration: none; margin-bottom: 2px; transition: all 0.15s; cursor: pointer;
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
  .logout-btn {
    margin-left: auto; background: none; border: none; cursor: pointer;
    color: #555; transition: color 0.15s;
  }
  .logout-btn:hover { color: #e8192c; }
  .logout-btn svg { width: 18px; height: 18px; stroke: currentColor; fill: none; stroke-width: 1.8; }

  /* MAIN */
  .main { margin-left: 240px; flex: 1; display: flex; flex-direction: column; }

  .topbar {
    background: #fff; padding: 16px 28px; display: flex; align-items: center;
    justify-content: space-between; border-bottom: 1px solid #eee; position: sticky; top: 0; z-index: 10;
  }
  .topbar-title h1 { font-size: 22px; font-weight: 800; color: #111; }
  .topbar-title p { font-size: 13px; color: #999; margin-top: 2px; }
  .topbar-right { display: flex; align-items: center; gap: 20px; }
  .search-box {
    display: flex; align-items: center; gap: 8px; background: #f5f5f5;
    border: 1px solid #e8e8e8; border-radius: 8px; padding: 8px 14px; width: 220px;
  }
  .search-box svg { width: 15px; height: 15px; stroke: #aaa; fill: none; stroke-width: 1.8; }
  .search-box input { border: none; background: transparent; font-size: 13px; color: #555; outline: none; width: 100%; }
  .topbar-date { font-size: 13px; color: #888; }
  .notif-btn { background: none; border: none; cursor: pointer; position: relative; }
  .notif-btn svg { width: 20px; height: 20px; stroke: #555; fill: none; stroke-width: 1.8; }
  .notif-dot { width: 8px; height: 8px; background: #e8192c; border-radius: 50%; position: absolute; top: 0; right: 0; }

  .content { padding: 24px 28px; flex: 1; }

  /* METRIC CARDS */
  .metrics { display: grid; grid-template-columns: repeat(4, 1fr); gap: 16px; margin-bottom: 20px; }
  .metric-card {
    background: #fff; border-radius: 12px; padding: 20px;
    border: 1px solid #eee; display: flex; justify-content: space-between; align-items: flex-start;
  }
  .metric-label { font-size: 13px; color: #999; margin-bottom: 8px; }
  .metric-value { font-size: 26px; font-weight: 800; color: #111; margin-bottom: 6px; }
  .metric-change { font-size: 12px; color: #22c55e; display: flex; align-items: center; gap: 4px; }
  .metric-change svg { width: 12px; height: 12px; stroke: currentColor; fill: none; stroke-width: 2; }
  .metric-icon {
    width: 40px; height: 40px; background: #fff0f2; border-radius: 10px;
    display: flex; align-items: center; justify-content: center; flex-shrink: 0;
  }
  .metric-icon svg { width: 20px; height: 20px; stroke: #e8192c; fill: none; stroke-width: 1.8; }

  /* QUICK ACCESS */
  .quick-access { display: grid; grid-template-columns: repeat(5, 1fr); gap: 12px; margin-bottom: 20px; }
  .quick-btn {
    background: #fff; border: 1px solid #eee; border-radius: 12px;
    padding: 18px 16px; display: flex; flex-direction: column; align-items: center;
    gap: 10px; cursor: pointer; text-decoration: none; transition: all 0.15s;
    font-size: 13px; font-weight: 600; color: #333;
  }
  .quick-btn:hover { border-color: #e8192c; color: #e8192c; }
  .quick-btn svg { width: 22px; height: 22px; stroke: currentColor; fill: none; stroke-width: 1.8; }
  .quick-btn.red { background: #e8192c; color: #fff; border-color: #e8192c; }
  .quick-btn.red:hover { background: #c41525; }
  .quick-btn.dark { background: #111; color: #fff; border-color: #111; }
  .quick-btn.dark:hover { background: #222; }

  /* CHARTS ROW */
  .charts-row { display: grid; grid-template-columns: 1fr 340px; gap: 16px; margin-bottom: 20px; }
  .chart-card { background: #fff; border-radius: 12px; padding: 22px; border: 1px solid #eee; }
  .chart-header { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 4px; }
  .chart-header h3 { font-size: 15px; font-weight: 700; color: #111; }
  .chart-header p { font-size: 12px; color: #999; margin-top: 2px; }
  .chart-badge { background: #f0fff4; color: #22c55e; font-size: 12px; font-weight: 600; padding: 4px 10px; border-radius: 100px; }
  .chart-wrap { height: 220px; margin-top: 16px; }
  .donut-wrap { height: 160px; display: flex; align-items: center; justify-content: center; margin-top: 12px; }
  .legend { margin-top: 12px; }
  .legend-item { display: flex; align-items: center; justify-content: space-between; font-size: 12px; color: #555; padding: 3px 0; }
  .legend-dot { width: 8px; height: 8px; border-radius: 50%; margin-right: 8px; flex-shrink: 0; }

  /* BOTTOM ROW */
  .bottom-row { display: grid; grid-template-columns: 1fr 340px; gap: 16px; }
  .table-card { background: #fff; border-radius: 12px; padding: 22px; border: 1px solid #eee; }
  .table-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px; }
  .table-header h3 { font-size: 15px; font-weight: 700; color: #111; }
  .ver-todo { font-size: 13px; color: #e8192c; text-decoration: none; font-weight: 500; }
  table { width: 100%; border-collapse: collapse; }
  th { font-size: 12px; color: #999; font-weight: 500; text-align: left; padding: 8px 0; border-bottom: 1px solid #f0f0f0; }
  td { font-size: 13px; color: #333; padding: 10px 0; border-bottom: 1px solid #f9f9f9; }
  .badge { display: inline-block; background: #f5f5f5; color: #555; font-size: 11px; padding: 3px 8px; border-radius: 6px; }

  .stock-card { background: #fff; border-radius: 12px; padding: 22px; border: 1px solid #eee; }
  .stock-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px; }
  .stock-header h3 { font-size: 15px; font-weight: 700; color: #111; display: flex; align-items: center; gap: 8px; }
  .stock-header h3 svg { width: 16px; height: 16px; stroke: #f59e0b; fill: none; stroke-width: 2; }
  .stock-item { padding: 12px 0; border-bottom: 1px solid #f5f5f5; display: flex; justify-content: space-between; align-items: flex-start; }
  .stock-item:last-child { border-bottom: none; }
  .stock-name { font-size: 13px; font-weight: 600; color: #111; }
  .stock-sub { font-size: 11px; color: #999; margin-top: 2px; }
  .stock-qty { text-align: right; }
  .stock-qty strong { font-size: 16px; font-weight: 800; color: #e8192c; display: block; }
  .stock-qty span { font-size: 11px; color: #999; }
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
    <a href="#" class="nav-item">
      <svg viewBox="0 0 24 24"><circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/><path d="M1 1h4l2.68 13.39a2 2 0 002 1.61h9.72a2 2 0 002-1.61L23 6H6"/></svg>
      Sales (POS)
    </a>
    <a href="/admin/products" class="nav-item">
      <svg viewBox="0 0 24 24"><path d="M21 16V8a2 2 0 00-1-1.73l-7-4a2 2 0 00-2 0l-7 4A2 2 0 003 8v8a2 2 0 001 1.73l7 4a2 2 0 002 0l7-4A2 2 0 0021 16z"/></svg>
      Inventory
    </a>
    <a href="#" class="nav-item">
      <svg viewBox="0 0 24 24"><path d="M6 2L3 6v14a2 2 0 002 2h14a2 2 0 002-2V6l-3-4z"/><line x1="3" y1="6" x2="21" y2="6"/><path d="M16 10a4 4 0 01-8 0"/></svg>
      Purchases
    </a>
    <a href="#" class="nav-item">
      <svg viewBox="0 0 24 24"><path d="M20.59 13.41l-7.17 7.17a2 2 0 01-2.83 0L2 12V2h10l8.59 8.59a2 2 0 010 2.82z"/><line x1="7" y1="7" x2="7.01" y2="7"/></svg>
      Promotions
    </a>
    <a href="#" class="nav-item">
      <svg viewBox="0 0 24 24"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
      Loyalty
    </a>
    <a href="#" class="nav-item">
      <svg viewBox="0 0 24 24"><line x1="18" y1="20" x2="18" y2="10"/><line x1="12" y1="20" x2="12" y2="4"/><line x1="6" y1="20" x2="6" y2="14"/></svg>
      Reports
    </a>
    <a href="/admin/categories" class="nav-item">
      <svg viewBox="0 0 24 24"><line x1="8" y1="6" x2="21" y2="6"/><line x1="8" y1="12" x2="21" y2="12"/><line x1="8" y1="18" x2="21" y2="18"/><line x1="3" y1="6" x2="3.01" y2="6"/><line x1="3" y1="12" x2="3.01" y2="12"/><line x1="3" y1="18" x2="3.01" y2="18"/></svg>
      Categories
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
      <p>General summary of your minimarket</p>
    </div>
    <div class="topbar-right">
      <div class="search-box">
        <svg viewBox="0 0 24 24"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
        <input type="text" placeholder="Search in the system...">
      </div>
      <span class="topbar-date">{{ now()->isoFormat('dddd, D [of] MMMM [of] YYYY') }}</span>
      <button class="notif-btn">
        <svg viewBox="0 0 24 24"><path d="M18 8A6 6 0 006 8c0 7-3 9-3 9h18s-3-2-3-9"/><path d="M13.73 21a2 2 0 01-3.46 0"/></svg>
        <span class="notif-dot"></span>
      </button>
    </div>
  </div>

  <div class="content">

    <!-- METRICS -->
    <div class="metrics">
      <div class="metric-card">
        <div>
          <div class="metric-label">Sales today</div>
          <div class="metric-value">S/ 326.30</div>
          <div class="metric-change"><svg viewBox="0 0 24 24"><polyline points="18 15 12 9 6 15"/></svg> +12.4%</div>
        </div>
        <div class="metric-icon"><svg viewBox="0 0 24 24"><line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 000 7h5a3.5 3.5 0 010 7H6"/></svg></div>
      </div>
      <div class="metric-card">
        <div>
          <div class="metric-label">Transactions</div>
          <div class="metric-value">6</div>
          <div class="metric-change"><svg viewBox="0 0 24 24"><polyline points="18 15 12 9 6 15"/></svg> +3</div>
        </div>
        <div class="metric-icon"><svg viewBox="0 0 24 24"><circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/><path d="M1 1h4l2.68 13.39a2 2 0 002 1.61h9.72a2 2 0 002-1.61L23 6H6"/></svg></div>
      </div>
      <div class="metric-card">
        <div>
          <div class="metric-label">Products in stock</div>
          <div class="metric-value">12</div>
          <div class="metric-change" style="color:#f59e0b"><svg viewBox="0 0 24 24"><polyline points="18 15 12 9 6 15"/></svg> 3 low</div>
        </div>
        <div class="metric-icon"><svg viewBox="0 0 24 24"><path d="M21 16V8a2 2 0 00-1-1.73l-7-4a2 2 0 00-2 0l-7 4A2 2 0 003 8v8a2 2 0 001 1.73l7 4a2 2 0 002 0l7-4A2 2 0 0021 16z"/></svg></div>
      </div>
      <div class="metric-card">
        <div>
          <div class="metric-label">Active customers</div>
          <div class="metric-value">124</div>
          <div class="metric-change"><svg viewBox="0 0 24 24"><polyline points="18 15 12 9 6 15"/></svg> +8 today</div>
        </div>
        <div class="metric-icon"><svg viewBox="0 0 24 24"><path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 00-3-3.87"/><path d="M16 3.13a4 4 0 010 7.75"/></svg></div>
      </div>
    </div>

    <!-- QUICK ACCESS -->
    <div class="quick-access">
      <a href="#" class="quick-btn red">
        <svg viewBox="0 0 24 24"><circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/><path d="M1 1h4l2.68 13.39a2 2 0 002 1.61h9.72a2 2 0 002-1.61L23 6H6"/></svg>
        New sale
      </a>
      <a href="/admin/products" class="quick-btn dark">
        <svg viewBox="0 0 24 24"><path d="M21 16V8a2 2 0 00-1-1.73l-7-4a2 2 0 00-2 0l-7 4A2 2 0 003 8v8a2 2 0 001 1.73l7 4a2 2 0 002 0l7-4A2 2 0 0021 16z"/></svg>
        Inventory
      </a>
      <a href="#" class="quick-btn">
        <svg viewBox="0 0 24 24"><path d="M20.59 13.41l-7.17 7.17a2 2 0 01-2.83 0L2 12V2h10l8.59 8.59a2 2 0 010 2.82z"/><line x1="7" y1="7" x2="7.01" y2="7"/></svg>
        Promotions
      </a>
      <a href="#" class="quick-btn">
        <svg viewBox="0 0 24 24"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
        Loyalty
      </a>
      <a href="#" class="quick-btn">
        <svg viewBox="0 0 24 24"><line x1="18" y1="20" x2="18" y2="10"/><line x1="12" y1="20" x2="12" y2="4"/><line x1="6" y1="20" x2="6" y2="14"/></svg>
        Reports
      </a>
    </div>

    <!-- CHARTS -->
    <div class="charts-row">
      <div class="chart-card">
        <div class="chart-header">
          <div>
            <h3>Weekly sales</h3>
            <p>Last 7 days</p>
          </div>
          <span class="chart-badge">+18.2%</span>
        </div>
        <div class="chart-wrap">
          <canvas id="salesChart"></canvas>
        </div>
      </div>
      <div class="chart-card">
        <div class="chart-header">
          <div>
            <h3>By category</h3>
            <p>Sales distribution</p>
          </div>
        </div>
        <div class="donut-wrap">
          <canvas id="donutChart"></canvas>
        </div>
        <div class="legend">
          <div class="legend-item"><div style="display:flex;align-items:center"><span class="legend-dot" style="background:#e8192c"></span>Beverages</div><span>35%</span></div>
          <div class="legend-item"><div style="display:flex;align-items:center"><span class="legend-dot" style="background:#111"></span>Snacks</div><span>22%</span></div>
          <div class="legend-item"><div style="display:flex;align-items:center"><span class="legend-dot" style="background:#f87171"></span>Groceries</div><span>20%</span></div>
          <div class="legend-item"><div style="display:flex;align-items:center"><span class="legend-dot" style="background:#d4a5a5"></span>Dairy</div><span>15%</span></div>
          <div class="legend-item"><div style="display:flex;align-items:center"><span class="legend-dot" style="background:#555"></span>Others</div><span>8%</span></div>
        </div>
      </div>
    </div>

    <!-- BOTTOM ROW -->
    <div class="bottom-row">
      <div class="table-card">
        <div class="table-header">
          <h3>Latest sales</h3>
          <a href="#" class="ver-todo">View all</a>
        </div>
        <table>
          <thead>
            <tr>
              <th>Receipt</th>
              <th>Time</th>
              <th>Method</th>
              <th style="text-align:right">Total</th>
            </tr>
          </thead>
          <tbody>
            <tr><td>B-00121</td><td>10:24</td><td><span class="badge">Yape</span></td><td style="text-align:right">S/ 45.50</td></tr>
            <tr><td>B-00122</td><td>10:51</td><td><span class="badge">Cash</span></td><td style="text-align:right">S/ 18.00</td></tr>
            <tr><td>B-00123</td><td>11:15</td><td><span class="badge">Card</span></td><td style="text-align:right">S/ 92.30</td></tr>
            <tr><td>B-00124</td><td>11:42</td><td><span class="badge">Yape</span></td><td style="text-align:right">S/ 27.80</td></tr>
            <tr><td>B-00125</td><td>12:08</td><td><span class="badge">Card</span></td><td style="text-align:right">S/ 134.20</td></tr>
            <tr><td>B-00126</td><td>12:35</td><td><span class="badge">Cash</span></td><td style="text-align:right">S/ 8.50</td></tr>
          </tbody>
        </table>
      </div>

      <div class="stock-card">
        <div class="stock-header">
          <h3><svg viewBox="0 0 24 24"><path d="M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg> Low stock products</h3>
          <a href="/admin/products" class="ver-todo">Manage</a>
        </div>
        <div class="stock-item">
          <div>
            <div class="stock-name">Inca Kola 500ml</div>
            <div class="stock-sub">Beverages · Minimum 20</div>
          </div>
          <div class="stock-qty"><strong>12</strong><span>units</span></div>
        </div>
        <div class="stock-item">
          <div>
            <div class="stock-name">Papas Lays Classic</div>
            <div class="stock-sub">Snacks · Minimum 15</div>
          </div>
          <div class="stock-qty"><strong>8</strong><span>units</span></div>
        </div>
        <div class="stock-item">
          <div>
            <div class="stock-name">Yogurt Laive Fresa</div>
            <div class="stock-sub">Dairy · Minimum 12</div>
          </div>
          <div class="stock-qty"><strong>5</strong><span>units</span></div>
        </div>
      </div>
    </div>

  </div>
</div>

<script>
const salesCtx = document.getElementById('salesChart').getContext('2d');
new Chart(salesCtx, {
  type: 'line',
  data: {
    labels: ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'],
    datasets: [{
      data: [1200, 1450, 1300, 1800, 2100, 2750, 2100],
      borderColor: '#e8192c',
      backgroundColor: 'rgba(232,25,44,0.08)',
      borderWidth: 2.5,
      fill: true,
      tension: 0.4,
      pointRadius: 0,
    }]
  },
  options: {
    responsive: true, maintainAspectRatio: false,
    plugins: { legend: { display: false } },
    scales: {
      x: { grid: { color: '#f0f0f0' }, ticks: { font: { size: 11 }, color: '#aaa' } },
      y: { grid: { color: '#f0f0f0' }, ticks: { font: { size: 11 }, color: '#aaa' } }
    }
  }
});

const donutCtx = document.getElementById('donutChart').getContext('2d');
new Chart(donutCtx, {
  type: 'doughnut',
  data: {
    datasets: [{
      data: [35, 22, 20, 15, 8],
      backgroundColor: ['#e8192c', '#111', '#f87171', '#d4a5a5', '#555'],
      borderWidth: 0,
    }]
  },
  options: {
    responsive: true, maintainAspectRatio: false,
    plugins: { legend: { display: false } },
    cutout: '65%'
  }
});
</script>

</body>
</html>