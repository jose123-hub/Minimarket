<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Express — Promotions</title>
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

  .content { padding: 24px 28px; display: grid; grid-template-columns: 420px 1fr; gap: 24px; align-items: start; }
  .form-card { background: #fff; border-radius: 12px; border: 1px solid #eee; padding: 24px; position: sticky; top: 80px; }
  .form-card-title { display: flex; align-items: center; gap: 8px; font-size: 15px; font-weight: 700; color: #e8192c; margin-bottom: 20px; }
  .form-card-title svg { width: 16px; height: 16px; stroke: #e8192c; fill: none; stroke-width: 2.5; }

  .form-group { margin-bottom: 16px; }
  .form-group label { display: block; font-size: 12px; font-weight: 500; color: #888; margin-bottom: 8px; }
  .form-group select, .form-group input { width: 100%; padding: 10px 14px; border: 1px solid #e8e8e8; border-radius: 8px; font-size: 14px; color: #333; outline: none; transition: border-color 0.2s; background: #fff; }
  .form-group select:focus, .form-group input:focus { border-color: #e8192c; }

  .input-prefix { position: relative; }
  .input-prefix span { position: absolute; left: 12px; top: 50%; transform: translateY(-50%); font-size: 13px; color: #999; }
  .input-prefix input { padding-left: 28px; }

  .date-row { display: grid; grid-template-columns: 1fr 1fr; gap: 12px; }
  .date-row input[type="date"] { 
  width: 100%; 
  max-width: 100%;
  min-width: 0;
  box-sizing: border-box;
  }

  .checkbox-label { display: flex; align-items: center; gap: 8px; font-size: 13px; color: #555; cursor: pointer; margin-bottom: 20px; }
  .checkbox-label input[type="checkbox"] { accent-color: #e8192c; width: 16px; height: 16px; }

  .btn-save { width: 100%; padding: 13px; background: #e8192c; color: #fff; border: none; border-radius: 8px; font-size: 14px; font-weight: 700; cursor: pointer; transition: background 0.2s; }
  .btn-save:hover { background: #c41525; }

  .list-panel {}
  .list-title { font-size: 15px; font-weight: 700; color: #111; margin-bottom: 16px; }

  .promo-card { background: #fff; border-radius: 12px; border: 1px solid #eee; padding: 18px 20px; margin-bottom: 12px; display: flex; align-items: center; gap: 16px; transition: border-color 0.15s; }
  .promo-card:hover { border-color: #e8192c; }
  .promo-badge { min-width: 60px; height: 60px; background: #fff0f2; border-radius: 10px; display: flex; align-items: center; justify-content: center; font-size: 16px; font-weight: 800; color: #e8192c; flex-shrink: 0; }
  .promo-info { flex: 1; }
  .promo-name { font-size: 15px; font-weight: 700; color: #111; margin-bottom: 4px; }
  .promo-meta { display: flex; align-items: center; gap: 12px; font-size: 12px; color: #999; }
  .promo-meta svg { width: 12px; height: 12px; stroke: currentColor; fill: none; stroke-width: 1.8; }
  .promo-code { background: #f5f5f5; color: #888; font-size: 11px; font-weight: 600; padding: 2px 8px; border-radius: 4px; font-family: monospace; }
  .promo-actions { display: flex; align-items: center; gap: 10px; flex-shrink: 0; }
  .status-badge { font-size: 11px; font-weight: 600; padding: 4px 10px; border-radius: 100px; }
  .status-active { background: #f0fdf4; color: #16a34a; }
  .status-inactive { background: #f5f5f5; color: #999; }
  .status-expired { background: #fff7ed; color: #ea580c; }
  .btn-edit { font-size: 13px; font-weight: 600; color: #e8192c; background: none; border: none; cursor: pointer; text-decoration: none; }
  .btn-edit:hover { text-decoration: underline; }

  .empty { text-align: center; padding: 48px; color: #ccc; font-size: 14px; background: #fff; border-radius: 12px; border: 1px solid #eee; }
  .success-msg { background: #f0fff4; border: 1px solid #bbf7d0; border-radius: 8px; padding: 10px 14px; font-size: 13px; color: #16a34a; margin-bottom: 16px; }
  .error-msg { background: #fff0f0; border: 1px solid #fcc; border-radius: 8px; padding: 10px 14px; font-size: 13px; color: #c00; margin-bottom: 16px; }

  .modal-overlay { display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.4); z-index: 100; align-items: center; justify-content: center; }
  .modal-overlay.show { display: flex; }
  .modal { background: #fff; border-radius: 14px; padding: 28px; width: 400px; }
  .modal h3 { font-size: 16px; font-weight: 700; margin-bottom: 20px; }
  .modal-actions { display: flex; gap: 10px; margin-top: 20px; }
  .btn-update { flex: 1; padding: 11px; background: #e8192c; color: #fff; border: none; border-radius: 8px; font-size: 14px; font-weight: 600; cursor: pointer; }
  .btn-cancel-modal { flex: 1; padding: 11px; background: #f5f5f5; color: #555; border: none; border-radius: 8px; font-size: 14px; cursor: pointer; }
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
    <a href="#" class="nav-item"><svg viewBox="0 0 24 24"><circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/><path d="M1 1h4l2.68 13.39a2 2 0 002 1.61h9.72a2 2 0 002-1.61L23 6H6"/></svg>Sales (POS)</a>
    <a href="/admin/products" class="nav-item"><svg viewBox="0 0 24 24"><path d="M21 16V8a2 2 0 00-1-1.73l-7-4a2 2 0 00-2 0l-7 4A2 2 0 003 8v8a2 2 0 001 1.73l7 4a2 2 0 002 0l7-4A2 2 0 0021 16z"/></svg>Inventory</a>
    <a href="/admin/suppliers" class="nav-item"><svg viewBox="0 0 24 24"><path d="M1 3h15v13H1z"/><path d="M16 8h4l3 3v5h-7V8z"/><circle cx="5.5" cy="18.5" r="2.5"/><circle cx="18.5" cy="18.5" r="2.5"/></svg>Suppliers</a>
    <a href="/admin/purchases" class="nav-item"><svg viewBox="0 0 24 24"><path d="M6 2L3 6v14a2 2 0 002 2h14a2 2 0 002-2V6l-3-4z"/><line x1="3" y1="6" x2="21" y2="6"/><path d="M16 10a4 4 0 01-8 0"/></svg>Purchases</a>
    <a href="/admin/promotions" class="nav-item active"><svg viewBox="0 0 24 24"><path d="M20.59 13.41l-7.17 7.17a2 2 0 01-2.83 0L2 12V2h10l8.59 8.59a2 2 0 010 2.82z"/><line x1="7" y1="7" x2="7.01" y2="7"/></svg>Promotions</a>
    <a href="#" class="nav-item"><svg viewBox="0 0 24 24"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>Loyalty</a>
    <a href="/admin/reports" class="nav-item"><svg viewBox="0 0 24 24"><line x1="18" y1="20" x2="18" y2="10"/><line x1="12" y1="20" x2="12" y2="4"/><line x1="6" y1="20" x2="6" y2="14"/></svg>Reports</a>
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
      <h1>Promotions</h1>
      <p>Create and manage discounts</p>
    </div>
    <span class="topbar-date">{{ now()->isoFormat('dddd, D [of] MMMM [of] YYYY') }}</span>
  </div>

  <div class="content">

    <div>
      @if(session('success'))
        <div class="success-msg">{{ session('success') }}</div>
      @endif
      @if(session('error'))
        <div class="error-msg">{{ session('error') }}</div>
      @endif
      @if($errors->any())
        <div class="error-msg">{{ $errors->first() }}</div>
      @endif

      <div class="form-card">
        <div class="form-card-title">
          <svg viewBox="0 0 24 24"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
          New Promotion
        </div>

        <form method="POST" action="{{ route('admin.promotions.store') }}">
          @csrf
          <div class="form-group">
            <label>Product</label>
            <select name="product_id">
              <option value="">— Select product —</option>
              @foreach($products as $product)
                <option value="{{ $product->id }}">{{ $product->name }}</option>
              @endforeach
            </select>
          </div>

          <div class="form-group">
            <label>Discount (%)</label>
            <div class="input-prefix">
              <span>%</span>
              <input type="number" name="value" min="1" max="100" placeholder="15">
            </div>
          </div>

          <div class="form-group">
  <label>Date Range</label>
  <div class="date-row">
    <div>
      <label style="font-size:11px; color:#bbb; display:block; margin-bottom:4px;">Start</label>
      <input type="date" name="start_date" value="{{ date('Y-m-d') }}" style="width:100%">
        </div>
          <div>
           <label style="font-size:11px; color:#bbb; display:block; margin-bottom:4px;">End</label>
           <input type="date" name="end_date" value="{{ date('Y-m-d', strtotime('+15 days')) }}" style="width:100%">
          </div>
         </div>
        </div>
          <label class="checkbox-label">
            <input type="checkbox" name="activate_now" checked>
            Activate immediately
          </label>

          <button type="submit" class="btn-save">Save promotion</button>
        </form>
      </div>
    </div>

    <div class="list-panel">
      <div class="list-title">Registered promotions</div>

      @forelse($discounts as $index => $pd)
      @php
        $d = $pd->discount;
        $now = now();
        if ($d->status === 'inactive') {
          $statusLabel = 'Inactive';
          $statusClass = 'status-inactive';
        } elseif ($now->lt(\Carbon\Carbon::parse($d->start_date))) {
          $statusLabel = 'Scheduled';
          $statusClass = 'status-inactive';
        } elseif ($now->gt(\Carbon\Carbon::parse($d->end_date))) {
          $statusLabel = 'Expired';
          $statusClass = 'status-expired';
        } else {
          $statusLabel = 'Active';
          $statusClass = 'status-active';
        }
      @endphp
      <div class="promo-card">
        <div class="promo-badge">-{{ $d->value }}%</div>
        <div class="promo-info">
          <div class="promo-name">{{ $pd->product->name }}</div>
          <div class="promo-meta">
            <svg viewBox="0 0 24 24"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
            {{ \Carbon\Carbon::parse($d->start_date)->format('Y-m-d') }} → {{ \Carbon\Carbon::parse($d->end_date)->format('Y-m-d') }}
            <span class="promo-code">PR{{ str_pad($index + 1, 2, '0', STR_PAD_LEFT) }}</span>
          </div>
        </div>
        <div class="promo-actions">
          <span class="status-badge {{ $statusClass }}">{{ $statusLabel }}</span>
          <button
          class="btn-edit"
          data-id="{{ $d->id }}"
          data-value="{{ $d->value }}"
          data-start="{{ $d->start_date }}"
          data-end="{{ $d->end_date }}"
          data-status="{{ $d->status }}"
          onclick="openEdit(this)">
          Edit
          </button>
        </div>
      </div>
      @empty
      <div class="empty">No promotions registered yet</div>
      @endforelse
    </div>
  </div>
</div>

<div class="modal-overlay" id="edit-modal">
  <div class="modal">
    <h3>Edit Promotion</h3>
    <form id="edit-form" method="POST">
      @csrf
      @method('PUT')
      <div class="form-group">
        <label>Discount (%)</label>
        <div class="input-prefix">
          <span>%</span>
          <input type="number" name="value" id="edit-value" min="1" max="100">
        </div>
      </div>
      <div class="form-group">
        <label>Start Date</label>
        <input type="date" name="start_date" id="edit-start">
      </div>
      <div class="form-group">
        <label>End Date</label>
        <input type="date" name="end_date" id="edit-end">
      </div>
      <div class="form-group">
        <label>Status</label>
        <select name="status" id="edit-status">
          <option value="active">Active</option>
          <option value="inactive">Inactive</option>
        </select>
      </div>
      <div class="modal-actions">
        <button type="submit" class="btn-update">Update</button>
        <button type="button" class="btn-cancel-modal" onclick="closeEdit()">Cancel</button>
      </div>
    </form>
  </div>
</div>

<script>
function openEdit(button) {
  const id = button.getAttribute('data-id');
  const value = button.getAttribute('data-value');
  const start = button.getAttribute('data-start');
  const end = button.getAttribute('data-end');
  const status = button.getAttribute('data-status');

  document.getElementById('edit-form').action = '/admin/promotions/' + id;
  document.getElementById('edit-value').value = value;
  document.getElementById('edit-start').value = start;
  document.getElementById('edit-end').value = end;
  document.getElementById('edit-status').value = status;
  document.getElementById('edit-modal').classList.add('show');
}

function closeEdit() {
  document.getElementById('edit-modal').classList.remove('show');
}

document.getElementById('edit-modal').addEventListener('click', function(e) {
  if (e.target === this) closeEdit();
});
</script>

</body>
</html>