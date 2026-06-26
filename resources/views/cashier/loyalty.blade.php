@push('portal-styles')
<style>
  .content { display: grid; grid-template-columns: 320px 1fr; gap: 0; flex: 1; height: calc(100vh - 65px); padding: 0; }

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
  .badge-earned { background: #f0fdf4; color: #16a34a; }
  .badge-redeemed { background: #fff0f0; color: #e8192c; }
  .stars-positive { color: #22c55e; font-weight: 700; }
  .stars-negative { color: #e8192c; font-weight: 700; }
  .empty-history { text-align: center; color: #ccc; font-size: 13px; padding: 24px 0; }

  /* The shared portal topbar doesn't include a search box by default —
     this page adds its own next to the date, like the original design. */
  .topbar-search-box { display: flex; align-items: center; gap: 8px; background: #f5f5f5; border: 1px solid #e8e8e8; border-radius: 8px; padding: 8px 14px; width: 220px; }
  .topbar-search-box svg { width: 15px; height: 15px; stroke: #aaa; fill: none; stroke-width: 1.8; }
  .topbar-search-box input { border: none; background: transparent; font-size: 13px; color: #555; outline: none; width: 100%; }
</style>
@endpush

<x-portal-layout
    title="Loyalty System"
    subtitle="Express Stars Program"
    active="loyalty"
>

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

      @if(!$selected)
        <div class="empty-state">
          <svg viewBox="0 0 24 24"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
          <p>Select a customer to view their stars</p>
        </div>
      @else
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
              <span>Available balance</span>
              <strong>{{ $selected->accumulated_stars }} stars</strong>
            </div>
            <div class="profile-badge">
              <svg viewBox="0 0 24 24"><circle cx="12" cy="8" r="6"/><path d="M15.477 12.89L17 22l-5-3-5 3 1.523-9.11"/></svg>
            </div>
          </div>
        </div>

        <div class="actions-row">
          <div class="action-card">
            <h3>📈 Earn stars</h3>
            <p>1 star per S/5.00 spent</p>
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
            <h3>🎁 Redeem reward</h3>
            <p>Select an active reward according to the customer's stars</p>
            <form method="POST" action="{{ route('cashier.loyalty.redeem') }}">
              @csrf
              <input type="hidden" name="client_id" value="{{ $selected->id_cliente }}">
              <div class="action-input-row">
                <select name="reward_id" class="action-input" required>
                  <option value="">Select reward</option>
                  @foreach($rewards as $reward)
                    <option value="{{ $reward->id }}"
                            {{ $selected->accumulated_stars < $reward->stars_required ? 'disabled' : '' }}>
                      {{ $reward->name }}
                      —
                      {{ $reward->stars_required }} stars
                      —
                      Stock: {{ $reward->available_stock }}
                    </option>
                  @endforeach
                </select>
                <button type="submit" class="btn-redeem">Redeem</button>
              </div>
            </form>
          </div>
        </div>

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

</x-portal-layout>