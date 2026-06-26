<x-portal-layout
    title="Loyalty"
    subtitle="Overview of customer stars and program activity"
    active="loyalty"
>
    <div class="metrics-row" style="display:grid; grid-template-columns: repeat(3, 1fr); gap:16px; margin-bottom:20px;">
      <div class="table-card" style="padding:18px 20px;">
        <div style="font-size:12px; color:#999; font-weight:600;">Stars outstanding</div>
        <div style="font-size:24px; font-weight:800; color:#111; margin-top:4px;">⭐ {{ number_format($totalStarsOutstanding) }}</div>
        <div style="font-size:12px; color:#999; margin-top:2px;">Currently held by all clients</div>
      </div>
      <div class="table-card" style="padding:18px 20px;">
        <div style="font-size:12px; color:#999; font-weight:600;">Total earned</div>
        <div style="font-size:24px; font-weight:800; color:#059669; margin-top:4px;">+{{ number_format($totalEarned) }}</div>
        <div style="font-size:12px; color:#999; margin-top:2px;">All-time, all clients</div>
      </div>
      <div class="table-card" style="padding:18px 20px;">
        <div style="font-size:12px; color:#999; font-weight:600;">Total redeemed</div>
        <div style="font-size:24px; font-weight:800; color:#dc2626; margin-top:4px;">-{{ number_format($totalRedeemed) }}</div>
        <div style="font-size:12px; color:#999; margin-top:2px;">All-time, all clients</div>
      </div>
    </div>

    <form method="GET" action="{{ route('admin.loyalty') }}" class="toolbar">
      <div class="search-box">
        <svg viewBox="0 0 24 24"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by name or email...">
      </div>
      <button type="submit" class="btn">Search</button>
      @if(request('search'))
        <a href="{{ route('admin.loyalty') }}" class="btn">Clear</a>
      @endif
    </form>

    <div class="table-card">
      <table>
        <thead>
          <tr>
            <th>Client</th>
            <th>Email</th>
            <th>Stars balance</th>
            <th>Movements</th>
            <th style="text-align:right">Actions</th>
          </tr>
        </thead>
        <tbody>
          @forelse($clients as $client)
            <tr>
              <td class="prod-name">{{ $client->first_name }} {{ $client->last_name }}</td>
              <td>{{ $client->email ?? '—' }}</td>
              <td>
                <span style="display:inline-flex; align-items:center; gap:5px; font-weight:700; color:#d97706;">
                  <svg width="14" height="14" viewBox="0 0 24 24" fill="#fbbf24" stroke="none"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
                  {{ number_format($client->accumulated_stars) }}
                </span>
              </td>
              <td>{{ $client->star_history_count }}</td>
              <td>
                <div class="actions" style="justify-content:flex-end">
                  <a href="{{ route('admin.loyalty.show', $client->id_cliente) }}" class="btn" style="padding:7px 12px;">
                    View history
                  </a>
                </div>
              </td>
            </tr>
          @empty
            <tr class="empty-row">
              <td colspan="5">No clients found.</td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>

</x-portal-layout>