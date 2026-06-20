<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>Express — Purchase {{ $purchase->order_number }}</title>
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
  .nav-item { display: flex; align-items: center; gap: 12px; padding: 11px 14px; border-radius: 8px; color: #888; font-size: 14px; font-weight: 500; text-decoration: none; margin-bottom: 2px; }
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
  .topbar { background: #fff; padding: 16px 28px; display: flex; align-items: center; justify-content: space-between; border-bottom: 1px solid #eee; }
  .topbar-title h1 { font-size: 22px; font-weight: 800; color: #111; }
  .topbar-title p { font-size: 13px; color: #999; margin-top: 2px; }

  .content { padding: 24px 28px; max-width: 880px; }

  .success-msg { background: #f0fff4; border: 1px solid #bbf7d0; border-radius: 8px; padding: 10px 14px; font-size: 13px; color: #16a34a; margin-bottom: 16px; }
  .error-msg { background: #fff0f0; border: 1px solid #fcc; border-radius: 8px; padding: 10px 14px; font-size: 13px; color: #c00; margin-bottom: 16px; }

  .top-grid { display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 16px; margin-bottom: 20px; }
  .info-card { background: #fff; border: 1px solid #eee; border-radius: 12px; padding: 16px 18px; }
  .info-card strong { display: block; font-size: 11px; color: #999; text-transform: uppercase; letter-spacing: .03em; margin-bottom: 6px; }
  .info-card span { font-size: 15px; font-weight: 700; color: #111; }

  .badge { display: inline-flex; padding: 4px 10px; border-radius: 100px; font-size: 11px; font-weight: 600; }
  .badge-pending { background: #fff7ed; color: #ea580c; }
  .badge-partial { background: #eff6ff; color: #2563eb; }
  .badge-received { background: #f0fdf4; color: #16a34a; }
  .badge-cancelled { background: #f5f5f5; color: #999; }

  .table-card { background: #fff; border-radius: 12px; border: 1px solid #eee; overflow: hidden; margin-bottom: 20px; }
  table { width: 100%; border-collapse: collapse; }
  thead { background: #fafafa; border-bottom: 1px solid #eee; }
  th { padding: 12px 16px; font-size: 11px; font-weight: 600; color: #999; text-align: left; letter-spacing: 0.05em; text-transform: uppercase; }
  td { padding: 12px 16px; font-size: 13px; color: #333; border-bottom: 1px solid #f5f5f5; }
  tr:last-child td { border-bottom: none; }
  .product-name { font-weight: 700; color: #111; }
  .total-row td { font-weight: 700; font-size: 14px; }
  .grand-total { color: #e8192c; font-size: 16px; }

  .actions-bar { display: flex; justify-content: flex-end; gap: 10px; }
  .btn { display: flex; align-items: center; gap: 8px; border-radius: 10px; padding: 10px 18px; font-size: 13px; font-weight: 600; cursor: pointer; text-decoration: none; border: 1px solid #e5e5e5; background: #fff; color: #333; }
  .btn-primary { background: #111; border-color: #111; color: #fff; }
  .btn-primary:hover { background: #e8192c; border-color: #e8192c; }
  .btn-danger { background: #fff0f0; border-color: #fcc; color: #c00; }
  .btn-danger:hover { background: #e8192c; border-color: #e8192c; color: #fff; }
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
    <a href="/admin/categories" class="nav-item"><svg viewBox="0 0 24 24"><line x1="8" y1="6" x2="21" y2="6"/><line x1="8" y1="12" x2="21" y2="12"/><line x1="8" y1="18" x2="21" y2="18"/><line x1="3" y1="6" x2="3.01" y2="6"/><line x1="3" y1="12" x2="3.01" y2="12"/><line x1="3" y1="18" x2="3.01" y2="18"/></svg>Categories</a>
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
      <h1>{{ $purchase->order_number }}</h1>
      <p>Purchase order detail</p>
    </div>
  </div>

  <div class="content">

    @if(session('success'))
      <div class="success-msg">{{ session('success') }}</div>
    @endif
    @if(session('error'))
      <div class="error-msg">{{ session('error') }}</div>
    @endif

    <div class="top-grid">
      <div class="info-card">
        <strong>Supplier</strong>
        <span>{{ $purchase->supplier->company_name }}</span>
      </div>
      <div class="info-card">
        <strong>Order date</strong>
        <span>{{ \Carbon\Carbon::parse($purchase->order_date)->format('d/m/Y') }}</span>
      </div>
      <div class="info-card">
        <strong>Status</strong>
        @if($purchase->status === 'pending')
          <span class="badge badge-pending">Pending</span>
        @elseif($purchase->status === 'partial')
          <span class="badge badge-partial">Partial</span>
        @elseif($purchase->status === 'received')
          <span class="badge badge-received">Received</span>
        @else
          <span class="badge badge-cancelled">Cancelled</span>
        @endif
      </div>
      <div class="info-card">
        <strong>Created by</strong>
        <span>{{ $purchase->user->name ?? '—' }}</span>
      </div>
      <div class="info-card">
        <strong>Estimated delivery</strong>
        <span>{{ $purchase->estimated_delivery ? \Carbon\Carbon::parse($purchase->estimated_delivery)->format('d/m/Y') : '—' }}</span>
      </div>
      <div class="info-card">
        <strong>Actual delivery</strong>
        <span>{{ $purchase->actual_delivery ? \Carbon\Carbon::parse($purchase->actual_delivery)->format('d/m/Y') : '—' }}</span>
      </div>
    </div>

    @if($purchase->notes)
      <div class="info-card" style="margin-bottom:20px;">
        <strong>Notes</strong>
        <span style="font-size:13px; font-weight:400;">{{ $purchase->notes }}</span>
      </div>
    @endif

    <div class="table-card">
      <table>
        <thead>
          <tr>
            <th>Product</th>
            <th>Ordered</th>
            <th>Received</th>
            <th>Unit cost</th>
            <th>Subtotal</th>
          </tr>
        </thead>
        <tbody>
          @foreach($purchase->details as $detail)
            <tr>
              <td class="product-name">{{ $detail->product->name }}</td>
              <td>{{ $detail->quantity_ordered }}</td>
              <td>{{ $detail->quantity_received }}</td>
              <td>S/ {{ number_format($detail->unit_cost, 2) }}</td>
              <td>S/ {{ number_format($detail->subtotal, 2) }}</td>
            </tr>
          @endforeach
        </tbody>
        <tfoot>
          <tr class="total-row">
            <td colspan="4" style="text-align:right; color:#999; font-size:13px;">Total purchase</td>
            <td><span class="grand-total">S/ {{ number_format($purchase->total, 2) }}</span></td>
          </tr>
        </tfoot>
      </table>
    </div>

    <div class="actions-bar">
      <a href="/admin/purchases" class="btn">Back to list</a>

      @if(in_array($purchase->status, ['pending', 'partial']))
        <a href="/admin/purchases/{{ $purchase->id }}/receive" class="btn btn-primary">
          <svg viewBox="0 0 24 24" width="15" height="15" stroke="#fff" fill="none" stroke-width="2"><path d="M21 15v4a2 2 0 01-2 2H5a2 2 0 01-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
          Receive
        </a>
      @endif

      @if($purchase->status === 'pending')
        <a href="/admin/purchases/{{ $purchase->id }}/edit" class="btn">Edit</a>
        <form action="/admin/purchases/{{ $purchase->id }}/cancel" method="POST" onsubmit="return confirm('Cancel this purchase order?')">
          @csrf
          <button type="submit" class="btn btn-danger">Cancel order</button>
        </form>
      @endif

      @if(in_array($purchase->status, ['pending', 'cancelled']))
        <form action="/admin/purchases/{{ $purchase->id }}" method="POST" onsubmit="return confirm('Permanently delete this purchase order?')">
          @csrf
          @method('DELETE')
          <button type="submit" class="btn btn-danger">Delete</button>
        </form>
      @endif
    </div>

  </div>
</div>

</body>
</html>