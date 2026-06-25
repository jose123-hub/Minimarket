<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Express — Returns</title>
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
  .topbar { background: #fff; padding: 18px 28px; display: flex; align-items: center; justify-content: space-between; border-bottom: 1px solid #eee; }
  .topbar-title h1 { font-size: 20px; font-weight: 800; color: #111; }
  .topbar-title p { font-size: 13px; color: #999; margin-top: 2px; }
  .content { padding: 24px 28px; flex: 1; }

  .new-return-btn { display: inline-flex; align-items: center; gap: 8px; background: #e8192c; color: #fff; border-radius: 10px; padding: 12px 18px; font-size: 14px; font-weight: 700; text-decoration: none; margin-bottom: 20px; }
  .new-return-btn:hover { background: #c41525; }
  .new-return-btn svg { width: 18px; height: 18px; stroke: #fff; fill: none; stroke-width: 2; }

  .table-card { background: #fff; border-radius: 12px; padding: 22px; border: 1px solid #eee; }
  table { width: 100%; border-collapse: collapse; }
  th { font-size: 12px; color: #999; font-weight: 600; text-align: left; padding: 10px 8px; border-bottom: 1px solid #f0f0f0; }
  td { font-size: 13px; color: #333; padding: 12px 8px; border-bottom: 1px solid #f9f9f9; }
  .badge { display: inline-block; font-size: 11px; font-weight: 700; padding: 4px 10px; border-radius: 20px; }
  .badge.pending { background: #fff7ed; color: #d97706; }
  .badge.approved { background: #ecfdf5; color: #059669; }
  .badge.rejected { background: #fef2f2; color: #dc2626; }
  .empty { text-align: center; color: #aaa; font-size: 13px; padding: 30px 0; }
  .flash-success { background: #ecfdf5; border: 1px solid #a7f3d0; color: #065f46; padding: 12px 16px; border-radius: 10px; margin-bottom: 18px; font-size: 13px; }
  .flash-error { background: #fef2f2; border: 1px solid #fecaca; color: #b91c1c; padding: 12px 16px; border-radius: 10px; margin-bottom: 18px; font-size: 13px; }
  .toast-message {position: fixed;top: 82px;right: 28px;z-index: 9999;min-width: 280px;max-width: 380px;padding: 14px 18px;border-radius: 12px;font-size: 14px;font-weight: 700;box-shadow: 0 12px 30px rgba(0, 0, 0, 0.12);animation: slideInToast 0.25s ease;}
  .success-toast {background: #dcfce7;color: #166534;border: 1px solid #86efac;}
  .error-toast {background: #fee2e2;color: #991b1b;border: 1px solid #fecaca;}
  .toast-message.hide {opacity: 0;transform: translateX(20px);transition: all 0.3s ease;}
  @keyframes slideInToast {from {opacity: 0;transform: translateX(20px);}to {opacity: 1;transform: translateX(0);}}
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
    <a href="/cashier/cash" class="nav-item">
      <svg viewBox="0 0 24 24"><rect x="2" y="5" width="20" height="14" rx="2"/><line x1="2" y1="10" x2="22" y2="10"/></svg>
      Cash Register
    </a>
    <a href="/cashier/loyalty" class="nav-item">
      <svg viewBox="0 0 24 24"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
      Loyalty
    </a>
    <a href="/cashier/returns" class="nav-item active">
      <svg viewBox="0 0 24 24"><polyline points="1 4 1 10 7 10"/><path d="M3.51 15a9 9 0 102.13-9.36L1 10"/></svg>
      Returns
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
      <h1>Returns</h1>
      <p>Your return requests and their approval status</p>
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

    <a href="{{ route('cashier.returns.create') }}" class="new-return-btn">
      <svg viewBox="0 0 24 24"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
      New return request
    </a>

    <div class="table-card">
      @if($returns->count() > 0)
        <table>
          <thead>
            <tr>
              <th>Sale</th>
              <th>Products</th>
              <th>Amount</th>
              <th>Reason</th>
              <th>Status</th>
              <th>Date</th>
            </tr>
          </thead>
          <tbody>
            @foreach($returns as $return)
              <tr>
                <td>{{ $return->sale->invoice_number ?? ('B-' . str_pad($return->sale_id, 5, '0', STR_PAD_LEFT)) }}</td>
                <td>
                  @foreach($return->details as $detail)
                    {{ $detail->quantity }}x {{ $detail->product->name ?? 'Unknown' }}@if(!$loop->last), @endif
                  @endforeach
                </td>
                <td>S/ {{ number_format($return->amount_returned, 2) }}</td>
                <td>{{ $return->reason }}</td>
                <td><span class="badge {{ $return->status }}">{{ ucfirst($return->status) }}</span></td>
                <td>{{ $return->return_date ? \Carbon\Carbon::parse($return->return_date)->format('d/m/Y h:i A') : '-' }}</td>
              </tr>
            @endforeach
          </tbody>
        </table>
      @else
        <div class="empty">You haven't submitted any return requests yet.</div>
      @endif
    </div>

  </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const toastMessages = document.querySelectorAll('.toast-message');

    toastMessages.forEach(function (toast) {
        setTimeout(function () {
            toast.classList.add('hide');

            setTimeout(function () {
                toast.remove();
            }, 300);
        }, 3000);
    });
});
</script>

</body>
</html>