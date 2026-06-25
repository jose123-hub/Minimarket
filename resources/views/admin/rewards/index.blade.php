<x-admin-layout
    title="Rewards"
    subtitle="Loyalty rewards catalog management"
    active="rewards"
>
    <div class="toolbar" style="justify-content:flex-end;">
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
                <div class="prod-name">{{ $reward->name }}</div>
                @if($reward->description)
                  <div style="font-size:12px; color:#999; margin-top:2px;">{{ $reward->description }}</div>
                @endif
              </td>
              <td><span class="badge {{ $reward->type === 'discount' ? 'ok' : 'low' }}">{{ ucfirst($reward->type) }}</span></td>
              <td>
                <span style="display:flex; align-items:center; gap:5px; font-weight:700; color:#d97706;">
                  <svg width="14" height="14" viewBox="0 0 24 24" fill="#fbbf24" stroke="none"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
                  {{ $reward->stars_required }}
                </span>
              </td>
              <td>{{ $reward->type === 'discount' ? 'S/ ' . number_format($reward->discount_value, 2) : '—' }}</td>
              <td>{{ $reward->available_stock }}</td>
              <td>
                <span class="badge {{ $reward->status === 'active' ? 'ok' : 'out' }}">{{ ucfirst($reward->status) }}</span>
              </td>
              <td>
                <div class="actions" style="justify-content:flex-end">
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