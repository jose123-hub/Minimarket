@push('portal-styles')
<style>
  .content { max-width: 800px; }

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
  .form-card .form-group select, .form-card .form-group input { width: 100%; padding: 10px 14px; border: 1px solid #e0e0e0; border-radius: 8px; font-size: 14px; color: #333; outline: none; }
  .form-card .form-group select:focus, .form-card .form-group input:focus { border-color: #e8192c; }

  .btn-open { width: 100%; padding: 13px; background: #16a34a; color: #fff; border: none; border-radius: 8px; font-size: 15px; font-weight: 700; cursor: pointer; transition: background 0.2s; }
  .btn-open:hover { background: #15803d; }
  .btn-close { width: 100%; padding: 13px; background: #e8192c; color: #fff; border: none; border-radius: 8px; font-size: 15px; font-weight: 700; cursor: pointer; transition: background 0.2s; }
  .btn-close:hover { background: #c41525; }

  .history-card { background: #fff; border-radius: 12px; border: 1px solid #eee; padding: 24px; }
  .history-card h3 { font-size: 15px; font-weight: 700; color: #111; margin-bottom: 16px; }
  .badge-open-sm { background: #f0fdf4; color: #16a34a; }
  .badge-closed-sm { background: #f5f5f5; color: #999; }
</style>
@endpush

<x-portal-layout
    title="Cash Register"
    subtitle="Open and close your daily cash register"
    active="cash"
>
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
        <div class="empty-row"><div style="text-align:center; color:#ccc; font-size:13px; padding:24px 0;">No sessions recorded yet</div></div>
      @endif
    </div>

</x-portal-layout>