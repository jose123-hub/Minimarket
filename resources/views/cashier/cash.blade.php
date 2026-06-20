<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Express — Cash Register</title>
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

  .content { padding: 24px 28px; max-width: 800px; }

  .status-banner { border-radius: 14px; padding: 24px 28px; margin-bottom: 24px; display: flex; align-items: center; justify-content: space-between; }
  .status-banner.open { background: linear-gradient(135deg, #f0fdf4, #dcfce7); border: 1px solid #bbf7d0; }
  .status-banner.closed { background: linear-gradient(135deg, #fff7ed, #ffedd5); border: 1px solid #fed7aa; }
  .status-left h2 { font-size: 20px; font-weight: 800; color: #111; margin-bottom: 4px; }
  .status-left p { font-size: 13px; color: #666; }
  .status-badge { display: inline-flex; align-items: center; gap: 6px; padding: 6px 14px; border-radius: 100px; font-size: 12px; font-weight: 700; }
  .badge-open { background: #16a34a; color: #fff; }
  .badge-closed { background: #ea580c; color: #fff; }
  .badge-dot { width: 6px; height: 6px; border-radius: 50%; background: currentColor; }

  .cards-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 16px; margin-bottom: 24px; }
  .info-card { background: #fff; border-radius: 12px; border: 1px solid #eee; padding: 18px; }
  .info-card .label { font-size: 12px; color: #999; margin-bottom: 6px; }
  .info-card .value { font-size: 22px; font-weight: 800; color: #111; }
  .info-card .value.red { color: #e8192c; }
  .info-card .value.green { color: #16a34a; }

  .form-card { background: #fff; border-radius: 12px; border: 1px solid #eee; padding: 24px; margin-bottom: 16px; }
  .form-card h3 { font-size: 15px; font-weight: 700; color: #111; margin-bottom: 16px; display: flex; align-items: center; gap: 8px; }
  .form-card h3 svg { width: 16px; height: 16px; stroke: #e8192c; fill: none; stroke-width: 1.8; }
  .form-group { margin-bottom: 16px; }
  .form-group label { display: block; font-size: 13px; font-weight: 500; color: #444; margin-bottom: 8px; }
  .form-group select, .form-group input { width: 100%; padding: 10px 14px; border: 1px solid #e0e0e0; border-radius: 8px; font-size: 14px; color: #333; outline: none; }
  .form-group select:focus, .form-group input:focus { border-color: #e8192c; }

  .btn-open { width: 100%; padding: 13px; background: #16a34a; color: #fff; border: none; border-radius: 8px; font-size: 15px; font-weight: 700; cursor: pointer; transition: background 0.2s; }
  .btn-open:hover { background: #15803d; }
  .btn-close { width: 100%; padding: 13px; background: #e8192c; color: #fff; border: none; border-radius: 8px; font-size: 15px; font-weight: 700; cursor: pointer; transition: background 0.2s; }
  .btn-close:hover { background: #c41525; }

  .history-card { background: #fff; border-radius: 12px; border: 1px solid #eee; padding: 24px; }
  .history-card h3 { font-size: 15px; font-weight: 700; color: #111; margin-bottom: 16px; }
  table { width: 100%; border-collapse: collapse; }
  th { font-size: 11px; color: #999; font-weight: 500; text-align: left; padding: 8px 0; border-bottom: 1px solid #f0f0f0; text-transform: uppercase; letter-spacing: 0.05em; }
  td { font-size: 13px; color: #333; padding: 12px 0; border-bottom: 1px solid #f9f9f9; }
  .badge { display: inline-flex; padding: 3px 10px; border-radius: 100px; font-size: 11px; font-weight: 600; }
  .badge-open-sm { background: #f0fdf4; color: #16a34a; }
  .badge-closed-sm { background: #f5f5f5; color: #999; }
  .empty { text-align: center; color: #ccc; font-size: 13px; padding: 24px 0; }

  .alert-success { background: #f0fff4; border: 1px solid #bbf7d0; border-radius: 8px; padding: 10px 14px; font-size: 13px; color: #16a34a; margin-bottom: 16px; }
  .alert-error { background: #fff0f0; border: 1px solid #fcc; border-radius: 8px; padding: 10px 14px; font-size: 13px; color: #c00; margin-bottom: 16px; }
</style>
</head>
<body>

<aside class="sidebar">
  <div class="sidebar-logo">
    <div class="logo-icon"><svg viewBox="0 0 24 24"><path d="M3 9l9-7 9 7v11a2 2 0 01-2 2H5a2 2 0 01-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg></div>
    <div class="logo-text"><strong>Express</strong><span>Minimarket POS</span></div>
  </div>
  <nav class="sidebar-nav">
    <a href="/cashier/dashboard" class="nav-item">
      <svg viewBox="0 0 24 24"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/></svg>Dashboard
    </a>
    <a href="/cashier/sales/create" class="nav-item">
      <svg viewBox="0 0 24 24"><circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/><path d="M1 1h4l2.68 13.39a2 2 0 002 1.61h9.72a2 2 0 002-1.61L23 6H6"/></svg>Sales (POS)
    </a>
    <a href="/cashier/inventory" class="nav-item">
      <svg viewBox="0 0 24 24"><path d="M21 16V8a2 2 0 00-1-1.73l-7-4a2 2 0 00-2 0l-7 4A2 2 0 003 8v8a2 2 0 001 1.73l7 4a2 2 0 002 0l7-4A2 2 0 0021 16z"/></svg>Inventory
    </a>
    <a href="/cashier/cash" class="nav-item active">
      <svg viewBox="0 0 24 24"><rect x="2" y="5" width="20" height="14" rx="2"/><line x1="2" y1="10" x2="22" y2="10"/></svg>Cash Register
    </a>
    <a href="/cashier/loyalty" class="nav-item">
      <svg viewBox="0 0 24 24"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>Loyalty
    </a>
  </nav>
  <div class="sidebar-user">
    <div class="user-avatar">{{ strtoupper(substr(Auth::user()->name, 0, 1)) }}</div>
    <div class="user-info"><strong>{{ Auth::user()->name }}</strong><span>Cashier</span></div>
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
      <h1>Cash Register</h1>
      <p>Open and close your daily cash register</p>
    </div>
    <span class="topbar-date">{{ now()->isoFormat('dddd, D [of] MMMM [of] YYYY') }}</span>
  </div>

  <div class="content">

    @if(session('success'))
      <div class="alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
      <div class="alert-error">{{ session('error') }}</div>
    @endif

    <div class="status-banner {{ $opening ? 'open' : 'closed' }}">
      <div class="status-left">
        <h2>{{ $opening ? 'Cash Register Open' : 'Cash Register Closed' }}</h2>
        <p>{{ $opening ? 'Opened at ' . \Carbon\Carbon::parse($opening->opening_date)->format('h:i A') . ' — ' . $opening->cashRegister->name : 'No active cash register session' }}</p>
      </div>
      <span class="status-badge {{ $opening ? 'badge-open' : 'badge-closed' }}">
        <span class="badge-dot"></span>
        {{ $opening ? 'Open' : 'Closed' }}
      </span>
    </div>

    @if($opening)
      <div class="cards-grid">
        <div class="info-card">
          <div class="label">Initial Amount</div>
          <div class="value">S/ {{ number_format($opening->initial_amount, 2) }}</div>
        </div>
        <div class="info-card">
          <div class="label">Sales Today</div>
          <div class="value green">S/ {{ number_format($opening->sales()->sum('total'), 2) }}</div>
        </div>
        <div class="info-card">
          <div class="label">Transactions</div>
          <div class="value">{{ $opening->sales()->count() }}</div>
        </div>
      </div>

      <div class="form-card">
        <h3>
          <svg viewBox="0 0 24 24"><rect x="2" y="5" width="20" height="14" rx="2"/><line x1="2" y1="10" x2="22" y2="10"/></svg>
          Close Cash Register
        </h3>
        <form method="POST" action="{{ route('cashier.cash.close') }}">
          @csrf
          <div class="form-group">
            <label>Counted Amount (S/)</label>
            <input type="number" name="counted_amount" step="0.01" min="0" placeholder="Enter the amount counted in the register">
          </div>
          <button type="submit" class="btn-close" onclick="return confirm('Are you sure you want to close the cash register?')">
            Close Cash Register
          </button>
        </form>
      </div>

    @else
      <div class="form-card">
        <h3>
          <svg viewBox="0 0 24 24"><rect x="2" y="5" width="20" height="14" rx="2"/><line x1="2" y1="10" x2="22" y2="10"/></svg>
          Open Cash Register
        </h3>
        <form method="POST" action="{{ route('cashier.cash.open') }}">
          @csrf
          <div class="form-group">
            <label>Cash Register</label>
            <select name="cash_register_id">
              <option value="">— Select register —</option>
              @foreach($registers as $register)
                <option value="{{ $register->id }}">{{ $register->name }} — {{ $register->location }}</option>
              @endforeach
            </select>
          </div>
          <div class="form-group">
            <label>Initial Amount (S/)</label>
            <input type="number" name="initial_amount" step="0.01" min="0" placeholder="e.g. 100.00">
          </div>
          <button type="submit" class="btn-open">Open Cash Register</button>
        </form>
      </div>
    @endif

    <div class="history-card">
      <h3>Recent Sessions</h3>
      @php
        $history = \App\Models\CashOpening::where('user_id', Auth::id())
            ->with('cashRegister')
            ->latest()
            ->take(5)
            ->get();
      @endphp
      @if($history->count() > 0)
      <table>
        <thead>
          <tr>
            <th>Register</th>
            <th>Opening</th>
            <th>Closing</th>
            <th>Sales</th>
            <th>Status</th>
          </tr>
        </thead>
        <tbody>
          @foreach($history as $h)
          <tr>
            <td>{{ $h->cashRegister->name }}</td>
            <td>{{ \Carbon\Carbon::parse($h->opening_date)->format('d/m/Y h:i A') }}</td>
            <td>{{ $h->closing_date ? \Carbon\Carbon::parse($h->closing_date)->format('d/m/Y h:i A') : '—' }}</td>
            <td>S/ {{ number_format($h->total_sales, 2) }}</td>
            <td>
              @if($h->status === 'open')
                <span class="badge badge-open-sm">Open</span>
              @else
                <span class="badge badge-closed-sm">Closed</span>
              @endif
            </td>
          </tr>
          @endforeach
        </tbody>
      </table>
      @else
        <div class="empty">No sessions recorded yet</div>
      @endif
    </div>

  </div>
</div>

</body>
</html>