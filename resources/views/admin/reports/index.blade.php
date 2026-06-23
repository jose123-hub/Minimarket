<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Express — Reports</title>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.0/chart.umd.min.js"></script>
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
  .topbar-date { font-size: 13px; color: #888; }

  .content { padding: 24px 28px; }

  .toolbar { background: #fff; border-radius: 12px; border: 1px solid #eee; padding: 16px 20px; margin-bottom: 20px; display: flex; align-items: center; gap: 12px; flex-wrap: wrap; }
  .tab-btn { padding: 8px 18px; border-radius: 8px; border: none; font-size: 13px; font-weight: 600; cursor: pointer; transition: all 0.15s; background: #f5f5f5; color: #555; }
  .tab-btn.active { background: #e8192c; color: #fff; }
  .tab-btn:hover:not(.active) { background: #eee; }
  .date-filter { display: flex; align-items: center; gap: 8px; margin-left: auto; }
  .date-filter input { padding: 8px 12px; border: 1px solid #e8e8e8; border-radius: 8px; font-size: 13px; color: #333; outline: none; }
  .date-filter input:focus { border-color: #e8192c; }
  .date-sep { font-size: 13px; color: #999; }
  .download-label { font-size: 12px; color: #999; font-weight: 500; }
  .btn-download { display: flex; align-items: center; gap: 6px; padding: 8px 14px; border-radius: 8px; border: 1px solid #eee; background: #fff; font-size: 13px; font-weight: 600; cursor: pointer; text-decoration: none; transition: all 0.15s; }
  .btn-download:hover { border-color: #e8192c; color: #e8192c; }
  .btn-download svg { width: 15px; height: 15px; stroke: currentColor; fill: none; stroke-width: 1.8; }
  .btn-pdf { color: #e8192c; border-color: #fecaca; }
  .btn-excel { color: #16a34a; border-color: #bbf7d0;}

  .metrics { display: grid; grid-template-columns: repeat(3, 1fr); gap: 16px; margin-bottom: 20px; }
  .metric-card { background: #fff; border-radius: 12px; border: 1px solid #eee; padding: 20px; }
  .metric-label { font-size: 13px; color: #999; margin-bottom: 8px; }
  .metric-value { font-size: 28px; font-weight: 800; color: #111; margin-bottom: 6px; }
  .metric-change { font-size: 12px; color: #22c55e; font-weight: 500; }
  .metric-change.negative { color: #e8192c; }

  .charts-row { display: grid; grid-template-columns: 1fr 340px; gap: 16px; margin-bottom: 20px; }
  .chart-card { background: #fff; border-radius: 12px; border: 1px solid #eee; padding: 22px; }
  .chart-header { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 16px; }
  .chart-header h3 { font-size: 15px; font-weight: 700; color: #111; }
  .chart-header p { font-size: 12px; color: #999; margin-top: 2px; }
  .chart-wrap { height: 220px; }

  .table-card { background: #fff; border-radius: 12px; border: 1px solid #eee; padding: 22px; }
  .table-header { margin-bottom: 16px; }
  .table-header h3 { font-size: 15px; font-weight: 700; color: #111; }
  table { width: 100%; border-collapse: collapse; }
  th { font-size: 11px; color: #999; font-weight: 600; text-align: left; padding: 8px 0; border-bottom: 1px solid #f0f0f0; text-transform: uppercase; letter-spacing: 0.05em; }
  td { font-size: 13px; color: #333; padding: 12px 0; border-bottom: 1px solid #f9f9f9; }
  tr:last-child td { border-bottom: none; }
  .invoice { font-family: monospace; font-weight: 600; color: #111; }
  .method-badge { display: inline-flex; padding: 3px 8px; background: #f5f5f5; color: #555; border-radius: 6px; font-size: 11px; }
  .total-amount { font-weight: 700; color: #111; }
  .empty { text-align: center; color: #ccc; font-size: 13px; padding: 32px 0; }
</style>
</head>
<body>

<aside class="sidebar">
  <div class="sidebar-logo">
    <div class="logo-icon"><svg viewBox="0 0 24 24"><path d="M3 9l9-7 9 7v11a2 2 0 01-2 2H5a2 2 0 01-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg></div>
    <div class="logo-text"><strong>Express</strong><span>Minimarket POS</span></div>
  </div>
  <nav class="sidebar-nav">
    <a href="/dashboard" class="nav-item"><svg viewBox="0 0 24 24"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/></svg>Dashboard</a>
    <a href="/admin/products" class="nav-item"><svg viewBox="0 0 24 24"><path d="M21 16V8a2 2 0 00-1-1.73l-7-4a2 2 0 00-2 0l-7 4A2 2 0 003 8v8a2 2 0 001 1.73l7 4a2 2 0 002 0l7-4A2 2 0 0021 16z"/></svg>Inventory</a>
    <a href="/admin/suppliers" class="nav-item"><svg viewBox="0 0 24 24"><path d="M1 3h15v13H1z"/><path d="M16 8h4l3 3v5h-7V8z"/><circle cx="5.5" cy="18.5" r="2.5"/><circle cx="18.5" cy="18.5" r="2.5"/></svg>Suppliers</a>
    <a href="/admin/purchases" class="nav-item"><svg viewBox="0 0 24 24"><path d="M6 2L3 6v14a2 2 0 002 2h14a2 2 0 002-2V6l-3-4z"/><line x1="3" y1="6" x2="21" y2="6"/><path d="M16 10a4 4 0 01-8 0"/></svg>Purchases</a>
    <a href="/admin/promotions" class="nav-item"><svg viewBox="0 0 24 24"><path d="M20.59 13.41l-7.17 7.17a2 2 0 01-2.83 0L2 12V2h10l8.59 8.59a2 2 0 010 2.82z"/><line x1="7" y1="7" x2="7.01" y2="7"/></svg>Promotions</a>
    <a href="/admin/rewards" class="nav-item"><svg viewBox="0 0 24 24"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>Rewards</a>
    <a href="/admin/reports" class="nav-item active"><svg viewBox="0 0 24 24"><line x1="18" y1="20" x2="18" y2="10"/><line x1="12" y1="20" x2="12" y2="4"/><line x1="6" y1="20" x2="6" y2="14"/></svg>Reports</a>
  </nav>
  <div class="sidebar-user">
    <div class="user-avatar">{{ strtoupper(substr(Auth::user()->name, 0, 1)) }}</div>
    <div class="user-info"><strong>{{ Auth::user()->name }}</strong><span>{{ ucfirst(Auth::user()->role) }}</span></div>
    <form method="POST" action="{{ route('logout') }}" style="margin-left:auto">
      @csrf
      <button type="submit" class="logout-btn"><svg viewBox="0 0 24 24"><path d="M9 21H5a2 2 0 01-2-2V5a2 2 0 012-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg></button>
    </form>
  </div>
</aside>

<div class="main">
  <div class="topbar">
    <div class="topbar-title">
      <h1>Reports</h1>
      <p>Sales, purchases and inventory analysis</p>
    </div>
    <span class="topbar-date">{{ now()->isoFormat('dddd, D [of] MMMM [of] YYYY') }}</span>
  </div>

  <div class="content">

    <div class="toolbar">
      <button class="tab-btn active">Sales</button>
      <button class="tab-btn">Purchases</button>
      <button class="tab-btn">Inventory</button>
      <button class="tab-btn">Customers</button>

      <div class="date-filter">
        <form method="GET" action="{{ route('admin.reports') }}" style="display:flex; align-items:center; gap:8px;">
          <input type="date" name="start_date" value="{{ $startDate }}">
          <span class="date-sep">→</span>
          <input type="date" name="end_date" value="{{ $endDate }}">
          <button type="submit" style="padding:8px 14px; background:#111; color:#fff; border:none; border-radius:8px; font-size:13px; font-weight:600; cursor:pointer;">Apply</button>
        </form>
      </div>

      <span class="download-label">Download</span>
      <a href="#" class="btn-download btn-pdf">
        <svg viewBox="0 0 24 24"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
        PDF
      </a>
      <a href="#" class="btn-download btn-excel">
        <svg viewBox="0 0 24 24"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
        Excel
      </a>
    </div>

    <div class="metrics">
      <div class="metric-card">
        <div class="metric-label">Total sales</div>
        <div class="metric-value">S/ {{ number_format($totalSales, 2) }}</div>
        <div class="metric-change">{{ $salesCount }} transactions</div>
      </div>
      <div class="metric-card">
        <div class="metric-label">Total purchases</div>
        <div class="metric-value">S/ {{ number_format($totalPurchases, 2) }}</div>
        <div class="metric-change">Purchase orders</div>
      </div>
      <div class="metric-card">
        <div class="metric-label">Inventory value</div>
        <div class="metric-value">S/ {{ number_format($inventoryValue, 2) }}</div>
        <div class="metric-change">Current stock</div>
      </div>
    </div>

    <div class="charts-row">
      <div class="chart-card">
        <div class="chart-header">
          <div>
            <h3>Sales vs Purchases (week)</h3>
            <p>Last 7 days</p>
          </div>
        </div>
        <div class="chart-wrap">
          <canvas id="lineChart"></canvas>
        </div>
      </div>
      <div class="chart-card">
        <div class="chart-header">
          <div>
            <h3>Sales by category</h3>
          </div>
        </div>
        <div class="chart-wrap">
          <canvas id="barChart"></canvas>
        </div>
      </div>
    </div>

    <div class="table-card">
      <div class="table-header" style="display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:10px;">
        <div>
          <h3>Sales detail for period</h3>
          <p id="sort-status" style="font-size:12px; color:#999; margin-top:2px;">Showing sales in default order (most recent first)</p>
        </div>
        @if($recentSales->count() > 0)
        <button type="button" id="sortByTotalBtn" class="tab-btn" style="background:#e8192c; color:#fff;">
          Sort by total (Merge Sort)
        </button>
        @endif
      </div>
      @if($recentSales->count() > 0)
      <table>
        <thead>
          <tr>
            <th>Invoice</th>
            <th>Time</th>
            <th>Items</th>
            <th>Method</th>
            <th>Cashier</th>
            <th style="text-align:right">Total</th>
          </tr>
        </thead>
        <tbody id="sales-detail-body">
          @foreach($recentSales as $sale)
          <tr>
            <td><span class="invoice">{{ $sale->invoice_number ?? 'B-' . str_pad($sale->id, 5, '0', STR_PAD_LEFT) }}</span></td>
            <td>{{ $sale->created_at->format('h:i A') }}</td>
            <td>{{ $sale->details->count() }} items</td>
            <td><span class="method-badge">{{ ucfirst($sale->payment_method ?? 'Cash') }}</span></td>
            <td>{{ $sale->cashier?->name ?? '-' }}</td>
            <td style="text-align:right"><span class="total-amount">S/ {{ number_format($sale->total, 2) }}</span></td>
          </tr>
          @endforeach
        </tbody>
      </table>
      @else
        <div class="empty">No sales recorded for this period</div>
      @endif
    </div>

  </div>
</div>

<script id="weekly-data" type="application/json">{!! $weeklySalesJson !!}</script>
<script>
const weeklyData = JSON.parse(document.getElementById('weekly-data').textContent || '[]');

const lineCtx = document.getElementById('lineChart').getContext('2d');
new Chart(lineCtx, {
  type: 'line',
  data: {
    labels: weeklyData.map(d => d.day),
    datasets: [
      {
        label: 'Sales',
        data: weeklyData.map(d => d.sales),
        borderColor: '#e8192c',
        backgroundColor: 'rgba(232,25,44,0.08)',
        borderWidth: 2.5, fill: true, tension: 0.4, pointRadius: 4,
        pointBackgroundColor: '#e8192c',
      },
      {
        label: 'Purchases',
        data: weeklyData.map(d => d.purchases),
        borderColor: '#111',
        backgroundColor: 'rgba(0,0,0,0.03)',
        borderWidth: 2, fill: true, tension: 0.4, pointRadius: 4,
        pointBackgroundColor: '#111',
      }
    ]
  },
  options: {
    responsive: true, maintainAspectRatio: false,
    plugins: { legend: { position: 'bottom', labels: { font: { size: 11 } } } },
    scales: {
      x: { grid: { color: '#f0f0f0' }, ticks: { font: { size: 11 }, color: '#aaa' } },
      y: { grid: { color: '#f0f0f0' }, ticks: { font: { size: 11 }, color: '#aaa' } }
    }
  }
});

</script>

<script id="sales-by-category-labels" type="application/json">{!! $salesByCategoryLabelsJson !!}</script>
<script id="sales-by-category-counts" type="application/json">{!! $salesByCategoryCountsJson !!}</script>
<script>
const salesByCategoryLabels = JSON.parse(document.getElementById('sales-by-category-labels').textContent || '[]');
const salesByCategoryCounts = JSON.parse(document.getElementById('sales-by-category-counts').textContent || '[]');

const barCtx = document.getElementById('barChart').getContext('2d');
new Chart(barCtx, {
  type: 'bar',
  data: {
    labels: salesByCategoryLabels,
    datasets: [{
      data: salesByCategoryCounts,
      backgroundColor: '#e8192c',
      borderRadius: 6,
    }]
  },
  options: {
    responsive: true, maintainAspectRatio: false,
    plugins: { legend: { display: false } },
    scales: {
      x: { grid: { display: false }, ticks: { font: { size: 11 }, color: '#aaa' } },
      y: { grid: { color: '#f0f0f0' }, ticks: { font: { size: 11 }, color: '#aaa' } }
    }
  }
});
</script>
<script id="sales-detail-data" type="application/json">{!! $salesDetailJson !!}</script>
<script>
  const originalSalesDetail = JSON.parse(document.getElementById('sales-detail-data').textContent || '[]');
  let mergeComparisons = 0;
  let mergeOperations = 0;

  function mergeSort(items) {
    if (items.length <= 1) return items;

    const middle = Math.floor(items.length / 2);
    const left = mergeSort(items.slice(0, middle));
    const right = mergeSort(items.slice(middle));

    return merge(left, right);
  }

  function merge(left, right) {
    const result = [];
    let i = 0, j = 0;

    while (i < left.length && j < right.length) {
      mergeComparisons++;
      if (left[i].total >= right[j].total) {
        result.push(left[i]);
        i++;
      } else {
        result.push(right[j]);
        j++;
      }
      mergeOperations++;
    }

    while (i < left.length) { result.push(left[i]); i++; mergeOperations++; }
    while (j < right.length) { result.push(right[j]); j++; mergeOperations++; }

    return result;
  }

  function renderSalesDetail(sales) {
    const tbody = document.getElementById('sales-detail-body');
    tbody.innerHTML = sales.map(s => `
      <tr>
        <td><span class="invoice">${s.invoice}</span></td>
        <td>${s.time}</td>
        <td>${s.items}</td>
        <td><span class="method-badge">${s.method}</span></td>
        <td>${s.cashier}</td>
        <td style="text-align:right"><span class="total-amount">S/ ${s.total.toFixed(2)}</span></td>
      </tr>
    `).join('');
  }

  const sortBtn = document.getElementById('sortByTotalBtn');
  if (sortBtn) {
    let isSorted = false;

    sortBtn.addEventListener('click', () => {
      const status = document.getElementById('sort-status');

      if (!isSorted) {
        mergeComparisons = 0;
        mergeOperations = 0;
        const sorted = mergeSort(originalSalesDetail);
        renderSalesDetail(sorted);
        status.textContent = `Sorted by total (highest first) — Merge Sort: ${mergeComparisons} comparisons, ${mergeOperations} merge operations over ${originalSalesDetail.length} sales`;
        sortBtn.textContent = 'Reset to default order';
        isSorted = true;
      } else {
        renderSalesDetail(originalSalesDetail);
        status.textContent = 'Showing sales in default order (most recent first)';
        sortBtn.textContent = 'Sort by total (Merge Sort)';
        isSorted = false;
      }
    });
  }
</script>

</body>
</html>