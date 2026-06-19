<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Express — Loyalty System</title>
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
  .search-box { display: flex; align-items: center; gap: 8px; background: #f5f5f5; border: 1px solid #e8e8e8; border-radius: 8px; padding: 8px 14px; width: 220px; }
  .search-box svg { width: 15px; height: 15px; stroke: #aaa; fill: none; stroke-width: 1.8; }
  .search-box input { border: none; background: transparent; font-size: 13px; color: #555; outline: none; width: 100%; }
  .topbar-date { font-size: 13px; color: #888; }

  .content { display: grid; grid-template-columns: 320px 1fr; gap: 0; flex: 1; height: calc(100vh - 65px); }

  .clients-panel { background: #fff; border-right: 1px solid #eee; display: flex; flex-direction: column; overflow: hidden; }
  .clients-search { padding: 16px; border-bottom: 1px solid #eee; }
  .clients-search input { width: 100%; padding: 10px 14px; border: 1px solid #e8e8e8; border-radius: 8px; font-size: 13px; color: #333; outline: none; }
  .clients-search input:focus { border-color: #e8192c; }
  .clients-list { flex: 1; overflow-y: auto; }
  .client-item { display: flex; justify-content: space-between; align-items: center; padding: 14px 16px; border-bottom: 1px solid #f5f5f5; cursor: pointer; text-decoration: none; transition: background 0.15s; }
  .client-item:hover { background: #fafafa; }
  .client-item.active { background: #fff5f5; border-left: 3px solid #e8192c; }
  .client-name { font-size: 14px; font-weight: 600; color: #111; }
  .client-email { font-size: 12px; color: #999; margin-top: 2px; }
  .client-stars { display: flex; align-items: center; gap: 4px; font-size: 13px; font-weight: 600; color: #f59e0b; }

  .loyalty-panel { padding: 24px; overflow-y: auto; }

  .empty-state { display: flex; flex-direction: column; align-items: center; justify-content: center; height: 100%; color: #ccc; }
  .empty-state svg { width: 48px; height: 48px; stroke: #ddd; fill: none; stroke-width: 1.5; margin-bottom: 12px; }
  .empty-state p { font-size: 14px; }

  .client-profile { background: linear-gradient(135deg, #fff5f5 0%, #fff 100%); border: 1px solid #fecaca; border-radius: 16px; padding: 28px; margin-bottom: 20px; display: flex; align-items: center; justify-content: space-between; }
  .profile-left h2 { font-size: 28px; font-weight: 800; color: #111; margin-bottom: 4px; }
  .profile-left p { font-size: 13px; color: #999; }
  .stars-display { display: flex; align-items: center; gap: 12px; margin-top: 16px; }
  .stars-display .star-icon { font-size: 36px; }
  .stars-display .star-count { font-size: 48px; font-weight: 800; color: #111; }
  .stars-equiv { text-align: right; }
  .stars-equiv span { font-size: 12px; color: #999; display: block; }
  .stars-equiv strong { font-size: 18px; font-weight: 700; color: #e8192c; }
  .profile-badge svg { width: 60px; height: 60px; stroke: #fca5a5; fill: none; stroke-width: 1; }

  .actions-row { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-bottom: 20px; }
  .action-card { background: #fff; border: 1px solid #eee; border-radius: 12px; padding: 20px; }
  .action-card h3 { font-size: 14px; font-weight: 700; color: #111; margin-bottom: 4px; display: flex; align-items: center; gap: 8px; }
  .action-card p { font-size: 12px; color: #999; margin-bottom: 14px; }
  .action-input-row { display: flex; gap: 8px; }
  .action-input { flex: 1; padding: 10px 12px; border: 1px solid #e8e8e8; border-radius: 8px; font-size: 13px; color: #333; outline: none; }
  .action-input:focus { border-color: #e8192c; }
  .btn-earn { padding: 10px 16px; background: #22c55e; color: #fff; border: none; border-radius: 8px; font-size: 13px; font-weight: 600; cursor: pointer; white-space: nowrap; }
  .btn-earn:hover { background: #16a34a; }
  .btn-redeem { padding: 10px 16px; background: #e8192c; color: #fff; border: none; border-radius: 8px; font-size: 13px; font-weight: 600; cursor: pointer; white-space: nowrap; }
  .btn-redeem:hover { background: #c41525; }

  .history-card { background: #fff; border: 1px solid #eee; border-radius: 12px; padding: 20px; }
  .history-card h3 { font-size: 15px; font-weight: 700; color: #111; margin-bottom: 16px; }
  table { width: 100%; border-collapse: collapse; }
  th { font-size: 11px; color: #999; font-weight: 500; text-align: left; padding: 8px 0; border-bottom: 1px solid #f0f0f0; text-transform: uppercase; letter-spacing: 0.05em; }
  td { font-size: 13px; color: #333; padding: 12px 0; border-bottom: 1px solid #f9f9f9; }
  .badge { display: inline-flex; align-items: center; padding: 3px 10px; border-radius: 100px; font-size: 11px; font-weight: 600; }
  .badge-earned { background: #f0fdf4; color: #16a34a; }
  .badge-redeemed { background: #fff0f0; color: #e8192c; }
  .stars-positive { color: #22c55e; font-weight: 700; }
  .stars-negative { color: #e8192c; font-weight: 700; }
  .empty-history { text-align: center; color: #ccc; font-size: 13px; padding: 24px 0; }

  .alert-success { background: #f0fff4; border: 1px solid #bbf7d0; border-radius: 8px; padding: 10px 14px; font-size: 13px; color: #16a34a; margin-bottom: 16px; }
  .alert-error { background: #fff0f0; border: 1px solid #fcc; border-radius: 8px; padding: 10px 14px; font-size: 13px; color: #c00; margin-bottom: 16px; }
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
    <a href="/cashier/dashboard" class="nav-item">
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
    <a href="/cashier/loyalty" class="nav-item active">
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
      <h1>Loyalty System</h1>
      <p>Express Stars Program</p>
    </div>
    <div class="topbar-right">
      <div class="search-box">
        <svg viewBox="0 0 24 24"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
        <input type="text" placeholder="Search in the system...">
      </div>
      <span class="topbar-date">{{ now()->isoFormat('dddd, D [of] MMMM [of] YYYY') }}</span>
    </div>
  </div>

  <div class="content">

    <div class="clients-panel">
      <div class="clients-search">
        <input type="text" id="client-search" placeholder="Search by name or email...">
      </div>
      <div class="clients-list">
        @foreach($clients as $client)
        <a href="{{ route('cashier.loyalty', ['client_id' => $client->id_cliente]) }}"
           class="client-item {{ isset($selected) && $selected->id_cliente == $client->id_cliente ? 'active' : '' }}">
          <div>
            <div class="client-name">{{ $client->first_name }} {{ $client->last_name }}</div>
            <div class="client-email">{{ $client->email ?? $client->user?->email }}</div>
          </div>
          <div class="client-stars">
            ⭐ {{ $client->accumulated_stars }}
          </div>
        </a>
        @endforeach
      </div>
    </div>

    <div class="loyalty-panel">

      @if(session('success'))
        <div class="alert-success">{{ session('success') }}</div>
      @endif
      @if(session('error'))
        <div class="alert-error">{{ session('error') }}</div>
      @endif

      @if(!$selected)
        <div class="empty-state">
          <svg viewBox="0 0 24 24"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
          <p>Select a customer to view their stars</p>
        </div>
      @else
        <!-- Profile -->
        <div class="client-profile">
          <div class="profile-left">
            <h2>{{ $selected->first_name }} {{ $selected->last_name }}</h2>
            <p>{{ $selected->email ?? $selected->user?->email }}</p>
            <div class="stars-display">
              <span class="star-icon">⭐</span>
              <span class="star-count">{{ $selected->accumulated_stars }}</span>
            </div>
          </div>
          <div>
            <div class="stars-equiv">
              <span>Equivalent to</span>
              <strong>S/ {{ number_format($selected->accumulated_stars / 20, 2) }}</strong>
            </div>
            <div class="profile-badge">
              <svg viewBox="0 0 24 24"><circle cx="12" cy="8" r="6"/><path d="M15.477 12.89L17 22l-5-3-5 3 1.523-9.11"/></svg>
            </div>
          </div>
        </div>

        <!-- Actions -->
        <div class="actions-row">
          <div class="action-card">
            <h3>📈 Earn stars</h3>
            <p>1 star per S/1.00 spent</p>
            <form method="POST" action="{{ route('cashier.loyalty.earn') }}">
              @csrf
              <input type="hidden" name="client_id" value="{{ $selected->id_cliente }}">
              <div class="action-input-row">
                <input type="number" name="amount" step="0.01" min="1" placeholder="Amount S/" class="action-input">
                <button type="submit" class="btn-earn">Earn</button>
              </div>
            </form>
          </div>
          <div class="action-card">
            <h3>🎁 Redeem stars</h3>
            <p>20 stars = S/1.00 discount</p>
            <form method="POST" action="{{ route('cashier.loyalty.redeem') }}">
              @csrf
              <input type="hidden" name="client_id" value="{{ $selected->id_cliente }}">
              <div class="action-input-row">
                <input type="number" name="stars" min="1" max="{{ $selected->accumulated_stars }}" placeholder="Stars" class="action-input">
                <button type="submit" class="btn-redeem">Redeem</button>
              </div>
            </form>
          </div>
        </div>

        <!-- History -->
        <div class="history-card">
          <h3>Points history</h3>
          @if($history->count() > 0)
          <table>
            <thead>
              <tr>
                <th>Date</th>
                <th>Action</th>
                <th>Detail</th>
                <th style="text-align:right">Stars</th>
              </tr>
            </thead>
            <tbody>
              @foreach($history as $item)
              <tr>
                <td>{{ \Carbon\Carbon::parse($item->date)->format('d/m/Y') }}</td>
                <td>
                  @if($item->movement_type === 'earned')
                    <span class="badge badge-earned">Earned</span>
                  @else
                    <span class="badge badge-redeemed">Redeemed</span>
                  @endif
                </td>
                <td>{{ $item->reason }}</td>
                <td style="text-align:right">
                  @if($item->movement_type === 'earned')
                    <span class="stars-positive">+{{ $item->amount }} ⭐</span>
                  @else
                    <span class="stars-negative">-{{ $item->amount }} ⭐</span>
                  @endif
                </td>
              </tr>
              @endforeach
            </tbody>
          </table>
          @else
            <div class="empty-history">No history yet</div>
          @endif
        </div>
      @endif
    </div>
  </div>
</div>

<script>
document.getElementById('client-search').addEventListener('input', function() {
  const q = this.value.toLowerCase();
  document.querySelectorAll('.client-item').forEach(item => {
    const name = item.querySelector('.client-name').textContent.toLowerCase();
    const email = item.querySelector('.client-email').textContent.toLowerCase();
    item.style.display = name.includes(q) || email.includes(q) ? '' : 'none';
  });
});
</script>

</body>
</html>