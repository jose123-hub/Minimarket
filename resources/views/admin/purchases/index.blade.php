<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Express — Purchases</title>
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
  .toolbar { display: flex; align-items: center; justify-content: space-between; margin-bottom: 20px; }
  .search-box { display: flex; align-items: center; gap: 8px; background: #fff; border: 1px solid #e8e8e8; border-radius: 8px; padding: 9px 14px; width: 300px; }
  .search-box svg { width: 15px; height: 15px; stroke: #aaa; fill: none; stroke-width: 1.8; }
  .search-box input { border: none; background: transparent; font-size: 13px; color: #555; outline: none; width: 100%; }
  .btn-add { display: flex; align-items: center; gap: 8px; padding: 10px 20px; background: #e8192c; color: #fff; border: none; border-radius: 8px; font-size: 13px; font-weight: 600; text-decoration: none; }
  .btn-add:hover { background: #c41525; color: #fff; }
  .btn-add svg { width: 16px; height: 16px; stroke: #fff; fill: none; stroke-width: 2.5; }

  .table-card { background: #fff; border-radius: 12px; border: 1px solid #eee; overflow: hidden; }
  table { width: 100%; border-collapse: collapse; }
  thead { background: #fafafa; border-bottom: 1px solid #eee; }
  th { padding: 12px 16px; font-size: 11px; font-weight: 600; color: #999; text-align: left; letter-spacing: 0.05em; text-transform: uppercase; }
  td { padding: 14px 16px; font-size: 13px; color: #333; border-bottom: 1px solid #f5f5f5; }
  tr:last-child td { border-bottom: none; }
  tr:hover td { background: #fafafa; }

  .order-number { font-family: monospace; font-weight: 600; color: #111; text-decoration: none; }
  .order-number:hover { color: #e8192c; text-decoration: underline; }
  .supplier-name { font-weight: 600; color: #111; }
  .total { font-weight: 700; color: #111; }
  .badge { display: inline-flex; padding: 4px 10px; border-radius: 100px; font-size: 11px; font-weight: 600; }
  .badge-pending { background: #fff7ed; color: #ea580c; }
  .badge-partial { background: #eff6ff; color: #2563eb; }
  .badge-received { background: #f0fdf4; color: #16a34a; }
  .badge-cancelled { background: #f5f5f5; color: #999; }
  .btn-receive { display: inline-flex; align-items: center; gap: 6px; padding: 6px 12px; background: #111; color: #fff; border-radius: 7px; font-size: 12px; font-weight: 600; text-decoration: none; }
  .btn-receive:hover { background: #e8192c; }
  .btn-receive svg { width: 13px; height: 13px; stroke: #fff; fill: none; stroke-width: 2; }
  .empty { text-align: center; padding: 48px; color: #aaa; font-size: 14px; }
  .success-msg { background: #f0fff4; border: 1px solid #bbf7d0; border-radius: 8px; padding: 10px 14px; font-size: 13px; color: #16a34a; margin-bottom: 16px; }
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
    <a href="/admin/purchases" class="nav-item active"><svg viewBox="0 0 24 24"><path d="M6 2L3 6v14a2 2 0 002 2h14a2 2 0 002-2V6l-3-4z"/><line x1="3" y1="6" x2="21" y2="6"/><path d="M16 10a4 4 0 01-8 0"/></svg>Purchases</a>
    <a href="/admin/promotions" class="nav-item"><svg viewBox="0 0 24 24"><path d="M20.59 13.41l-7.17 7.17a2 2 0 01-2.83 0L2 12V2h10l8.59 8.59a2 2 0 010 2.82z"/><line x1="7" y1="7" x2="7.01" y2="7"/></svg>Promotions</a>
    <a href="/admin/rewards" class="nav-item"><svg viewBox="0 0 24 24"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>Rewards</a>
    <a href="/admin/reports" class="nav-item"><svg viewBox="0 0 24 24"><line x1="18" y1="20" x2="18" y2="10"/><line x1="12" y1="20" x2="1２" y２="4"/><line x１="6" y１="２０" x２="6" y２="１４"/></svg>Reports</a>
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
      <h1>Purchases</h1>
      <p>Purchase orders to suppliers</p>
    </div>
    <div class="topbar-right">
      <span class="topbar-date">{{ now()->isoFormat('dddd, D [of] MMMM [of] YYYY') }}</span>
    </div>
  </div>

  <div class="content">

    @if(session('success'))
      <div class="success-msg">{{ session('success') }}</div>
    @endif

    <div class="toolbar">
      <div class="search-box">
        <svg viewBox="0 0 24 24"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
        <input type="text" id="search-input" placeholder="Search order...">
      </div>
      <a href="/admin/purchases/create" class="btn-add">
        <svg viewBox="0 0 24 24"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
        New Purchase
      </a>
    </div>

    <div class="table-card">
      <table>
        <thead>
          <tr>
            <th>Order #</th>
            <th>Supplier</th>
            <th>Date</th>
            <th>Items</th>
            <th>Total</th>
            <th>Status</th>
            <th style="text-align:right">Actions</th>
          </tr>
        </thead>
        <tbody id="purchases-table">
          @forelse($purchases as $purchase)
          <tr data-name="{{ strtolower($purchase->order_number) }}">
            <td><a href="/admin/purchases/{{ $purchase->id }}" class="order-number">{{ $purchase->order_number }}</a></td>
            <td><span class="supplier-name">{{ $purchase->supplier->company_name }}</span></td>
            <td>{{ \Carbon\Carbon::parse($purchase->order_date)->format('d/m/Y') }}</td>
            <td>{{ $purchase->details->count() }} products</td>
            <td><span class="total">S/ {{ number_format($purchase->total, 2) }}</span></td>
            <td>
              @if($purchase->status === 'pending')
                <span class="badge badge-pending">Pending</span>
              @elseif($purchase->status === 'partial')
                <span class="badge badge-partial">Partial</span>
              @elseif($purchase->status === 'received')
                <span class="badge badge-received">Received</span>
              @else
                <span class="badge badge-cancelled">Cancelled</span>
              @endif
            </td>
            <td style="text-align:right">
              @if($purchase->status === 'pending' || $purchase->status === 'partial')
                <a href="/admin/purchases/{{ $purchase->id }}/receive" class="btn-receive">
                  <svg viewBox="0 0 24 24"><path d="M21 15v4a2 2 0 01-2 2H5a2 2 0 01-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
                  Receive
                </a>
              @endif
            </td>
          </tr>
          @empty
          <tr><td colspan="7" class="empty">No purchase orders yet</td></tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>
</div>

<script>
document.getElementById('search-input').addEventListener('input', function() {
  const q = this.value.toLowerCase();
  document.querySelectorAll('#purchases-table tr').forEach(row => {
    row.style.display = (row.dataset.name || '').includes(q) ? '' : 'none';
  });
});
</script>

</body>
</html>