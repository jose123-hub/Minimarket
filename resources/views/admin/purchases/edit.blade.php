<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Express — Edit Purchase</title>
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

  .content { padding: 24px 28px; max-width: 820px; }
  .error-msg { background: #fff0f0; border: 1px solid #fcc; border-radius: 8px; padding: 10px 14px; font-size: 13px; color: #c00; margin-bottom: 16px; }

  .form-card { background: #fff; border: 1px solid #eee; border-radius: 12px; padding: 20px; margin-bottom: 20px; }
  .form-card h3 { font-size: 14px; font-weight: 700; color: #111; margin-bottom: 14px; }
  .form-row { display: grid; grid-template-columns: 1fr 1fr; gap: 14px; margin-bottom: 14px; }
  .form-group label { display: block; font-size: 12px; font-weight: 600; color: #555; margin-bottom: 6px; }
  .form-group select, .form-group input, .form-group textarea {
    width: 100%; border: 1px solid #e0e0e0; border-radius: 8px; padding: 10px 12px; font-size: 13px; color: #333; outline: none;
  }
  .form-group select:focus, .form-group input:focus, .form-group textarea:focus { border-color: #e8192c; }

  .table-card { background: #fff; border-radius: 12px; border: 1px solid #eee; overflow: hidden; margin-bottom: 20px; }
  table { width: 100%; border-collapse: collapse; }
  thead { background: #fafafa; border-bottom: 1px solid #eee; }
  th { padding: 12px 16px; font-size: 11px; font-weight: 600; color: #999; text-align: left; letter-spacing: 0.05em; text-transform: uppercase; }
  td { padding: 12px 16px; font-size: 13px; color: #333; border-bottom: 1px solid #f5f5f5; }
  tr:last-child td { border-bottom: none; }
  .product-name { font-weight: 700; color: #111; }
  .qty-input, .cost-input { width: 90px; padding: 7px 10px; border: 1px solid #e0e0e0; border-radius: 6px; font-size: 13px; outline: none; }
  .qty-input:focus, .cost-input:focus { border-color: #e8192c; }
  .subtotal { font-weight: 700; color: #111; }

  .form-actions { display: flex; justify-content: flex-end; gap: 12px; }
  .btn-save { padding: 12px 28px; background: #e8192c; color: #fff; border: none; border-radius: 8px; font-size: 14px; font-weight: 600; cursor: pointer; }
  .btn-save:hover { background: #c41525; }
  .btn-cancel { padding: 12px 28px; background: #f5f5f5; color: #555; border: none; border-radius: 8px; font-size: 14px; font-weight: 500; text-decoration: none; }
  .btn-cancel:hover { background: #eee; }
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
      <h1>Edit {{ $purchase->order_number }}</h1>
      <p>Only allowed while the order is still pending (nothing received yet)</p>
    </div>
  </div>

  <div class="content">

    @if(session('success'))
      <div class="toast-message success-toast">
        {{ session('success') }}
      </div>
    @endif
    @if(session('error'))
      <div class="toast-message error-toast">
        {{ session('error') }}
      </div>
    @endif
    @if($errors->any())
      <div class="toast-message error-toast">
        {{ $errors->first() }}
      </div>
    @endif

    <form action="/admin/purchases/{{ $purchase->id }}" method="POST">
      @csrf
      @method('PUT')

      <div class="form-card">
        <h3>Order info</h3>
        <div class="form-row">
          <div class="form-group">
            <label>Supplier</label>
            <select name="supplier_id">
              @foreach($suppliers as $supplier)
                <option value="{{ $supplier->id }}" {{ $purchase->supplier_id == $supplier->id ? 'selected' : '' }}>
                  {{ $supplier->company_name }}
                </option>
              @endforeach
            </select>
          </div>
          <div class="form-group">
            <label>Estimated delivery</label>
            <input type="date" name="estimated_delivery" value="{{ $purchase->estimated_delivery }}">
          </div>
        </div>
        <div class="form-group">
          <label>Notes</label>
          <textarea name="notes" rows="2">{{ $purchase->notes }}</textarea>
        </div>
      </div>

      <div class="table-card">
        <table>
          <thead>
            <tr>
              <th>Product</th>
              <th>Quantity</th>
              <th>Unit cost</th>
              <th>Subtotal</th>
            </tr>
          </thead>
          <tbody>
            @foreach($purchase->details as $i => $detail)
              <tr>
                <td class="product-name">
                  {{ $detail->product->name }}
                  <input type="hidden" name="details[{{ $i }}][id]" value="{{ $detail->id }}">
                </td>
                <td>
                  <input type="number" min="1" class="qty-input"
                         name="details[{{ $i }}][quantity]" value="{{ $detail->quantity_ordered }}">
                </td>
                <td>
                  <input type="number" min="0" step="0.01" class="cost-input"
                         name="details[{{ $i }}][unit_cost]" value="{{ $detail->unit_cost }}">
                </td>
                <td><span class="subtotal">S/ {{ number_format($detail->subtotal, 2) }}</span></td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>

      <div class="form-actions">
        <a href="/admin/purchases/{{ $purchase->id }}" class="btn-cancel">Cancel</a>
        <button type="submit" class="btn-save">Save changes</button>
      </div>
    </form>

  </div>
</div>

</body>
</html>