<x-admin-layout
    title="{{ $client->first_name }} {{ $client->last_name }}"
    subtitle="Star movement history"
    active="loyalty"
>

    <a href="{{ route('admin.loyalty') }}" class="btn" style="margin-bottom:16px; display:inline-flex;">
      <svg viewBox="0 0 24 24"><line x1="19" y1="12" x2="5" y2="12"/><polyline points="12 19 5 12 12 5"/></svg>
      Back to clients
    </a>

    <div class="metrics-row" style="display:grid; grid-template-columns: repeat(3, 1fr); gap:16px; margin-bottom:20px;">
      <div class="table-card" style="padding:18px 20px;">
        <div style="font-size:12px; color:#999; font-weight:600;">Current balance</div>
        <div style="font-size:24px; font-weight:800; color:#111; margin-top:4px;">⭐ {{ number_format($client->accumulated_stars) }}</div>
      </div>
      <div class="table-card" style="padding:18px 20px;">
        <div style="font-size:12px; color:#999; font-weight:600;">Email</div>
        <div style="font-size:15px; font-weight:700; color:#111; margin-top:8px;">{{ $client->email ?? '—' }}</div>
      </div>
      <div class="table-card" style="padding:18px 20px;">
        <div style="font-size:12px; color:#999; font-weight:600;">Phone</div>
        <div style="font-size:15px; font-weight:700; color:#111; margin-top:8px;">{{ $client->phone ?? '—' }}</div>
      </div>
    </div>

    <div class="table-card">
      <table>
        <thead>
          <tr>
            <th>Date</th>
            <th>Type</th>
            <th>Stars</th>
            <th>Reason</th>
            <th>Related sale / reward</th>
          </tr>
        </thead>
        <tbody>
          @forelse($history as $movement)
            <tr>
              <td>{{ \Carbon\Carbon::parse($movement->date)->format('d/m/Y h:i A') }}</td>
              <td>
                <span class="badge {{ $movement->movement_type === 'earned' ? 'ok' : 'out' }}">
                  {{ $movement->movement_type === 'earned' ? 'Earned' : 'Redeemed' }}
                </span>
              </td>
              <td class="font-strong {{ $movement->movement_type === 'earned' ? 'text-success' : 'text-danger' }}">
                {{ $movement->movement_type === 'earned' ? '+' : '-' }}{{ $movement->amount }}
              </td>
              <td>{{ $movement->reason ?? '—' }}</td>
              <td>
                @if($movement->sale)
                  {{ $movement->sale->invoice_number ?? ('B-' . str_pad($movement->sale->id, 5, '0', STR_PAD_LEFT)) }}
                @elseif($movement->redemption && $movement->redemption->reward)
                  {{ $movement->redemption->reward->name }}
                @else
                  —
                @endif
              </td>
            </tr>
          @empty
            <tr class="empty-row">
              <td colspan="5">This client has no star movements yet.</td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>

</x-admin-layout>