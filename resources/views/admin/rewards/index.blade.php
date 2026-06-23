<x-admin-layout
    title="Rewards"
    subtitle="Loyalty rewards catalog management"
    active="rewards"
>
<style>
  .toolbar { display: flex; align-items: center; justify-content: flex-end; margin-bottom: 18px; gap: 12px; }
  .btn { display: flex; align-items: center; gap: 8px; border-radius: 10px; padding: 10px 16px; font-size: 13px; font-weight: 600; cursor: pointer; text-decoration: none; border: 1px solid #e5e5e5; background: #fff; color: #333; white-space: nowrap; }
  .btn svg { width: 15px; height: 15px; stroke: currentColor; fill: none; stroke-width: 2; }
  .btn-primary { background: #e8192c; border-color: #e8192c; color: #fff; }
  .btn-primary:hover { background: #c41525; }
  .btn:hover:not(.btn-primary) { border-color: #ccc; }

  .table-card { background: #fff; border-radius: 12px; border: 1px solid #eee; overflow: hidden; }
  table { width: 100%; border-collapse: collapse; }
  th { font-size: 11px; text-transform: uppercase; letter-spacing: .03em; color: #999; font-weight: 600; text-align: left; padding: 14px 18px; border-bottom: 1px solid #f0f0f0; background: #fafafa; }
  td { font-size: 13px; color: #333; padding: 14px 18px; border-bottom: 1px solid #f5f5f5; vertical-align: middle; }
  tr:last-child td { border-bottom: none; }
  .reward-name { font-weight: 700; color: #111; }
  .reward-desc { font-size: 12px; color: #999; margin-top: 2px; }
  .badge { display: inline-block; font-size: 11px; font-weight: 700; padding: 4px 10px; border-radius: 20px; }
  .badge.active { background: #ecfdf5; color: #059669; }
  .badge.inactive { background: #f5f5f5; color: #999; }
  .badge.discount { background: #eff6ff; color: #2563eb; }
  .badge.gift { background: #fff7ed; color: #d97706; }
  .stars-cell { display: flex; align-items: center; gap: 5px; font-weight: 700; color: #d97706; }
  .stars-cell svg { width: 14px; height: 14px; fill: #fbbf24; stroke: none; }
  .actions { display: flex; gap: 8px; justify-content: flex-end; }
  .icon-btn { width: 30px; height: 30px; border-radius: 7px; border: 1px solid #eee; background: #fff; display: flex; align-items: center; justify-content: center; cursor: pointer; color: #555; }
  .icon-btn:hover { border-color: #ccc; }
  .icon-btn svg { width: 14px; height: 14px; stroke: currentColor; fill: none; stroke-width: 1.8; }
  .icon-btn.danger:hover { color: #e8192c; border-color: #e8192c; }
  .empty-row td { text-align: center; padding: 40px; color: #bbb; font-size: 13px; }
  .success-msg { background: #f0fff4; border: 1px solid #bbf7d0; border-radius: 8px; padding: 10px 14px; font-size: 13px; color: #16a34a; margin-bottom: 16px; }

  .modal-overlay { display: none; position: fixed; inset: 0; background: rgba(17,17,17,0.55); align-items: center; justify-content: center; z-index: 100; padding: 20px; }
  .modal-overlay.open { display: flex; }
  .modal-box { background: #fff; border-radius: 16px; padding: 28px 30px; max-width: 520px; width: 100%; max-height: 90vh; overflow-y: auto; }
  .modal-header { display: flex; align-items: flex-start; justify-content: space-between; margin-bottom: 4px; }
  .modal-header-title { display: flex; align-items: center; gap: 10px; }
  .modal-icon { width: 34px; height: 34px; background: #fff0f2; border-radius: 9px; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
  .modal-icon svg { width: 18px; height: 18px; stroke: #e8192c; fill: none; stroke-width: 1.8; }
  .modal-header h2 { font-size: 18px; font-weight: 800; color: #111; }
  .modal-close { background: none; border: none; cursor: pointer; color: #999; }
  .modal-close svg { width: 18px; height: 18px; stroke: currentColor; fill: none; stroke-width: 2; }
  .modal-subtitle { font-size: 13px; color: #999; margin: 4px 0 20px 44px; }
  .form-group { margin-bottom: 16px; }
  .form-row { display: grid; grid-template-columns: 1fr 1fr; gap: 14px; }
  .form-group label { display: block; font-size: 13px; font-weight: 600; color: #333; margin-bottom: 6px; }
  .form-group input, .form-group select, .form-group textarea {
    width: 100%; border: 1px solid #e5e5e5; background: #fafafa; border-radius: 9px;
    padding: 10px 12px; font-size: 13px; font-family: inherit; color: #111;
  }
  .form-group input:focus, .form-group select:focus, .form-group textarea:focus { outline: none; border-color: #e8192c; background: #fff; }
  .field-error { color: #dc2626; font-size: 12px; margin-top: 4px; }
  .modal-actions { display: flex; justify-content: flex-end; gap: 10px; margin-top: 22px; }
</style>

@if(session('success'))
  <div class="success-msg">{{ session('success') }}</div>
@endif

<aside class="sidebar">
  <div class="sidebar-logo">
    <div class="logo-icon"><svg viewBox="0 0 24 24"><path d="M3 9l9-7 9 7v11a2 2 0 01-2 2H5a2 2 0 01-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg></div>
    <div class="logo-text"><strong>Express</strong><span>Minimarket POS</span></div>
  </div>
  <nav class="sidebar-nav">
    <a href="/dashboard" class="nav-item"><svg viewBox="0 0 24 24"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/></svg>Dashboard</a>
    <a href="/admin/products" class="nav-item"><svg viewBox="0 0 24 24"><path d="M21 16V8a2 2 0 00-1-1.73l-7-4a2 2 0 00-2 0l-7 4A2 2 0 003 8v8a2 2 0 001 1.73l7 4a2 2 0 002 0l7-4A2 2 0 0021 16z"/></svg>Inventory</a>
    <a href="/admin/suppliers" class="nav-item"><svg viewBox="0 0 24 24"><path d="M1 3h15v13H1z"/><path d="M16 8h4l3 3v5h-7V8z"/><circle cx="5.5" cy="18.5" r="2.5"/><circle cx="18.5" cy="18.5" r="2.5"/></svg>Suppliers</a>
    <a href="/admin/purchases" class="nav-item"><svg viewBox="0 0 24 24"><path d="M6 2L3 6v14a2 2 0 002 2h14a2 2 0 002-2V6l-3-4z"/><line x1="3" y1="6" x2="21" y2="6"/><path d="M16 10a4 4 0 01-8 0"/></svg>Purchases</a>
    <a href="/admin/promotions" class="nav-item"><svg viewBox="0 0 24 24"><path d="M20.59 13.41l-7.17 7.17a2 2 0 01-2.83 0L2 12V2h10l8.59 8.59a2 2 0 010 2.82z"/><line x1="7" y1="7" x2="7.01" y2="7"/></svg>Promotions</a>
    <a href="/admin/rewards" class="nav-item active"><svg viewBox="0 0 24 24"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>Rewards</a>
    <a href="/admin/reports" class="nav-item"><svg viewBox="0 0 24 24"><line x1="18" y1="20" x2="18" y2="10"/><line x1="12" y1="20" x2="12" y2="4"/><line x1="6" y1="20" x2="6" y2="14"/></svg>Reports</a>
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

<div class="toolbar">
  <button class="btn btn-primary" id="openRewardModal" type="button">
    <svg viewBox="0 0 24 24"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
    New Reward
  </button>
</div>

<div class="table-card">
  <table>
    <thead>
      <tr>
        <th>Reward</th>
        <th>Type</th>
        <th>Stars</th>
        <th>Discount</th>
        <th>Stock</th>
        <th>Status</th>
        <th style="text-align:right">Actions</th>
      </tr>
    </thead>
    <tbody>
      @forelse($rewards as $reward)
        <tr>
          <td>
            <div class="reward-name">{{ $reward->name }}</div>
            @if($reward->description)
              <div class="reward-desc">{{ $reward->description }}</div>
            @endif
          </td>
          <td><span class="badge {{ $reward->type }}">{{ ucfirst($reward->type) }}</span></td>
          <td>
            <span class="stars-cell">
              <svg viewBox="0 0 24 24"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
              {{ $reward->stars_required }}
            </span>
          </td>
          <td>{{ $reward->type === 'discount' ? 'S/ ' . number_format($reward->discount_value, 2) : '—' }}</td>
          <td>{{ $reward->available_stock }}</td>
          <td>
            @if($reward->status === 'active')
              <span class="badge active">Active</span>
            @else
              <span class="badge inactive">Inactive</span>
            @endif
          </td>
          <td>
            <div class="actions">
              <button type="button" class="icon-btn"
                      data-reward='@json($reward)'
                      onclick="openEditReward(JSON.parse(this.dataset.reward))">
                <svg viewBox="0 0 24 24"><path d="M17 3a2.85 2.83 0 114 4L7.5 20.5 2 22l1.5-5.5z"/></svg>
              </button>
              <form action="{{ route('admin.rewards.destroy', $reward) }}" method="POST" onsubmit="return confirm('Delete this reward?');">
                @csrf
                @method('DELETE')
                <button type="submit" class="icon-btn danger">
                  <svg viewBox="0 0 24 24"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14a2 2 0 01-2 2H8a2 2 0 01-2-2L5 6"/><path d="M10 11v6"/><path d="M14 11v6"/><path d="M9 6V4a1 1 0 011-1h4a1 1 0 011 1v2"/></svg>
                </button>
              </form>
            </div>
          </td>
        </tr>
      @empty
        <tr class="empty-row">
          <td colspan="7">No rewards created yet.</td>
        </tr>
      @endforelse
    </tbody>
  </table>
</div>

<div class="modal-overlay{{ $errors->any() ? ' open' : '' }}" id="rewardModal">
  <div class="modal-box">
    <div class="modal-header">
      <div class="modal-header-title">
        <div class="modal-icon">
          <svg viewBox="0 0 24 24"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
        </div>
        <h2 id="reward-modal-title">New reward</h2>
      </div>
      <button type="button" class="modal-close" id="closeRewardModal">
        <svg viewBox="0 0 24 24"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
      </button>
    </div>
    <p class="modal-subtitle" id="reward-modal-subtitle">Define a new reward customers can redeem with their stars.</p>

    <form id="rewardForm" action="{{ route('admin.rewards.store') }}" method="POST">
      @csrf
      <input type="hidden" name="_method" id="reward-method-field" value="PUT" disabled>

      <div class="form-group">
        <label for="name">Reward name</label>
        <input type="text" id="name" name="name" placeholder="E.g. S/10 discount" value="{{ old('name') }}" required>
        @error('name') <div class="field-error">{{ $message }}</div> @enderror
      </div>

      <div class="form-group">
        <label for="description">Description (optional)</label>
        <textarea id="description" name="description" rows="2">{{ old('description') }}</textarea>
      </div>

      <div class="form-row">
        <div class="form-group">
          <label for="type">Type</label>
          <select id="type" name="type" required>
            <option value="discount" {{ old('type') == 'discount' ? 'selected' : '' }}>Discount</option>
            <option value="gift" {{ old('type') == 'gift' ? 'selected' : '' }}>Gift / free product</option>
          </select>
          @error('type') <div class="field-error">{{ $message }}</div> @enderror
        </div>
        <div class="form-group">
          <label for="stars_required">Stars required</label>
          <input type="number" min="1" id="stars_required" name="stars_required" placeholder="100" value="{{ old('stars_required') }}" required>
          @error('stars_required') <div class="field-error">{{ $message }}</div> @enderror
        </div>
      </div>

      <div class="form-row">
        <div class="form-group" id="discount-value-group">
          <label for="discount_value">Discount value (S/)</label>
          <input type="number" step="0.01" min="0" id="discount_value" name="discount_value" placeholder="10.00" value="{{ old('discount_value') }}">
          @error('discount_value') <div class="field-error">{{ $message }}</div> @enderror
        </div>
        <div class="form-group">
          <label for="available_stock">Available stock</label>
          <input type="number" min="0" id="available_stock" name="available_stock" placeholder="0" value="{{ old('available_stock', 0) }}" required>
          @error('available_stock') <div class="field-error">{{ $message }}</div> @enderror
        </div>
      </div>

      <div class="form-row">
        <div class="form-group">
          <label for="start_date">Valid from (optional)</label>
          <input type="date" id="start_date" name="start_date" value="{{ old('start_date') }}">
        </div>
        <div class="form-group">
          <label for="end_date">Valid until (optional)</label>
          <input type="date" id="end_date" name="end_date" value="{{ old('end_date') }}">
          @error('end_date') <div class="field-error">{{ $message }}</div> @enderror
        </div>
      </div>

      <div class="form-group">
        <label for="status">Status</label>
        <select id="status" name="status" required>
          <option value="active" {{ old('status', 'active') == 'active' ? 'selected' : '' }}>Active</option>
          <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
        </select>
      </div>

      <div class="modal-actions">
        <button type="button" class="btn" id="cancelRewardModal">
          <svg viewBox="0 0 24 24"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
          Cancel
        </button>
        <button type="submit" class="btn btn-primary">
          <svg viewBox="0 0 24 24"><path d="M19 21H5a2 2 0 01-2-2V5a2 2 0 012-2h11l5 5v11a2 2 0 01-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/></svg>
          <span id="reward-submit-text">Save reward</span>
        </button>
      </div>
    </form>
  </div>
</div>

<script>
  const rewardModal = document.getElementById('rewardModal');
  const rewardForm = document.getElementById('rewardForm');
  const rewardMethodField = document.getElementById('reward-method-field');
  const discountValueGroup = document.getElementById('discount-value-group');
  const typeSelect = document.getElementById('type');

  function toggleDiscountField() {
    discountValueGroup.style.display = typeSelect.value === 'discount' ? '' : 'none';
  }
  typeSelect.addEventListener('change', toggleDiscountField);
  toggleDiscountField();

  function resetRewardModalToCreate() {
    document.getElementById('reward-modal-title').textContent = 'New reward';
    document.getElementById('reward-modal-subtitle').textContent = 'Define a new reward customers can redeem with their stars.';
    document.getElementById('reward-submit-text').textContent = 'Save reward';
    rewardForm.action = "{{ route('admin.rewards.store') }}";
    rewardMethodField.disabled = true;
    rewardForm.reset();
    toggleDiscountField();
  }

  function openEditReward(reward) {
    document.getElementById('reward-modal-title').textContent = 'Edit reward';
    document.getElementById('reward-modal-subtitle').textContent = 'Update this reward\'s details.';
    document.getElementById('reward-submit-text').textContent = 'Save changes';
    rewardForm.action = "{{ url('/admin/rewards') }}/" + reward.id;
    rewardMethodField.disabled = false;

    document.getElementById('name').value = reward.name ?? '';
    document.getElementById('description').value = reward.description ?? '';
    document.getElementById('type').value = reward.type ?? 'discount';
    document.getElementById('stars_required').value = reward.stars_required ?? '';
    document.getElementById('discount_value').value = reward.discount_value ?? '';
    document.getElementById('available_stock').value = reward.available_stock ?? 0;
    document.getElementById('status').value = reward.status ?? 'active';
    document.getElementById('start_date').value = reward.start_date ? reward.start_date.substring(0, 10) : '';
    document.getElementById('end_date').value = reward.end_date ? reward.end_date.substring(0, 10) : '';

    toggleDiscountField();
    rewardModal.classList.add('open');
  }

  document.getElementById('openRewardModal').addEventListener('click', () => {
    resetRewardModalToCreate();
    rewardModal.classList.add('open');
  });
  document.getElementById('closeRewardModal').addEventListener('click', () => rewardModal.classList.remove('open'));
  document.getElementById('cancelRewardModal').addEventListener('click', () => rewardModal.classList.remove('open'));
  rewardModal.addEventListener('click', (e) => { if (e.target === rewardModal) rewardModal.classList.remove('open'); });
</script>

</x-admin-layout>