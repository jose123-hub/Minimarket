<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Express — New Supplier</title>
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
  .topbar { background: #fff; padding: 16px 28px; display: flex; align-items: center; justify-content: space-between; border-bottom: 1px solid #eee; }
  .topbar-title h1 { font-size: 22px; font-weight: 800; color: #111; }
  .topbar-title p { font-size: 13px; color: #999; margin-top: 2px; }

  .content { padding: 24px 28px; }
  .form-card { background: #fff; border-radius: 12px; border: 1px solid #eee; padding: 28px; max-width: 700px; }
  .form-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; }
  .form-group { margin-bottom: 0; }
  .form-group.full { grid-column: 1 / -1; }
  .form-group label { display: block; font-size: 13px; font-weight: 500; color: #444; margin-bottom: 8px; }
  .form-group input, .form-group select {
    width: 100%; padding: 10px 14px; border: 1px solid #e0e0e0;
    border-radius: 8px; font-size: 14px; color: #333; outline: none; transition: border-color 0.2s;
  }
  .form-group input:focus, .form-group select:focus { border-color: #e8192c; }
  .required { color: #e8192c; }

  .error-msg { background: #fff0f0; border: 1px solid #fcc; border-radius: 8px; padding: 10px 14px; font-size: 13px; color: #c00; margin-bottom: 20px; }

  .form-actions { display: flex; gap: 12px; margin-top: 24px; }
  .btn-save { padding: 11px 28px; background: #e8192c; color: #fff; border: none; border-radius: 8px; font-size: 14px; font-weight: 600; cursor: pointer; }
  .btn-save:hover { background: #c41525; }
  .btn-cancel { padding: 11px 28px; background: #f5f5f5; color: #555; border: none; border-radius: 8px; font-size: 14px; font-weight: 500; text-decoration: none; }
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
    <a href="#" class="nav-item"><svg viewBox="0 0 24 24"><circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/><path d="M1 1h4l2.68 13.39a2 2 0 002 1.61h9.72a2 2 0 002-1.61L23 6H6"/></svg>Sales (POS)</a>
    <a href="/admin/products" class="nav-item"><svg viewBox="0 0 24 24"><path d="M21 16V8a2 2 0 00-1-1.73l-7-4a2 2 0 00-2 0l-7 4A2 2 0 003 8v8a2 2 0 001 1.73l7 4a2 2 0 002 0l7-4A2 2 0 0021 16z"/></svg>Inventory</a>
    <a href="/admin/suppliers" class="nav-item active"><svg viewBox="0 0 24 24"><path d="M1 3h15v13H1z"/><path d="M16 8h4l3 3v5h-7V8z"/><circle cx="5.5" cy="18.5" r="2.5"/><circle cx="18.5" cy="18.5" r="2.5"/></svg>Suppliers</a>
    <a href="/admin/purchases" class="nav-item"><svg viewBox="0 0 24 24"><path d="M6 2L3 6v14a2 2 0 002 2h14a2 2 0 002-2V6l-3-4z"/><line x1="3" y1="6" x2="21" y2="6"/><path d="M16 10a4 4 0 01-8 0"/></svg>Purchases</a>
    <a href="#" class="nav-item"><svg viewBox="0 0 24 24"><path d="M20.59 13.41l-7.17 7.17a2 2 0 01-2.83 0L2 12V2h10l8.59 8.59a2 2 0 010 2.82z"/><line x1="7" y1="7" x2="7.01" y2="7"/></svg>Promotions</a>
    <a href="#" class="nav-item"><svg viewBox="0 0 24 24"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>Loyalty</a>
    <a href="#" class="nav-item"><svg viewBox="0 0 24 24"><line x1="18" y1="20" x2="18" y2="10"/><line x1="12" y1="20" x2="12" y2="4"/><line x1="6" y1="20" x2="6" y2="14"/></svg>Reports</a>
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
      <h1>New Supplier</h1>
      <p>Register a new product supplier</p>
    </div>
  </div>

  <div class="content">
    @if($errors->any())
      <div class="error-msg">{{ $errors->first() }}</div>
    @endif

    <div class="form-card">
      <form action="/admin/suppliers" method="POST">
        @csrf
        <div class="form-grid">
          <div class="form-group full">
            <label>Company Name <span class="required">*</span></label>
            <input type="text" name="company_name" value="{{ old('company_name') }}" placeholder="e.g. Distribuidora Lima SAC">
          </div>
          <div class="form-group">
            <label>RUC <span class="required">*</span></label>
            <input type="text" name="ruc" value="{{ old('ruc') }}" placeholder="20123456789" maxlength="11">
          </div>
          <div class="form-group">
            <label>Contact Name</label>
            <input type="text" name="contact_name" value="{{ old('contact_name') }}" placeholder="Contact person">
          </div>
          <div class="form-group">
            <label>Phone</label>
            <input type="text" name="phone" value="{{ old('phone') }}" placeholder="+51 999 000 000">
          </div>
          <div class="form-group">
            <label>Email</label>
            <input type="email" name="email" value="{{ old('email') }}" placeholder="supplier@email.com">
          </div>
          <div class="form-group full">
            <label>Address</label>
            <input type="text" name="address" value="{{ old('address') }}" placeholder="Street, district, city">
          </div>
          <div class="form-group">
            <label>Status</label>
            <select name="status">
              <option value="active">Active</option>
              <option value="inactive">Inactive</option>
            </select>
          </div>
        </div>

        <div class="form-actions">
          <button type="submit" class="btn-save">Save Supplier</button>
          <a href="/admin/suppliers" class="btn-cancel">Cancel</a>
        </div>
      </form>
    </div>
  </div>
</div>

</body>
</html>