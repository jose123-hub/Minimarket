@push('portal-styles')
<style>
  .content { display: grid; grid-template-columns: 420px 1fr; gap: 24px; align-items: start; }
  .form-card { background: #fff; border-radius: 12px; border: 1px solid #eee; padding: 24px; position: sticky; top: 80px; }
  .form-card-title { display: flex; align-items: center; gap: 8px; font-size: 15px; font-weight: 700; color: #e8192c; margin-bottom: 20px; }
  .form-card-title svg { width: 16px; height: 16px; stroke: #e8192c; fill: none; stroke-width: 2.5; }

  .form-card .form-group select, .form-card .form-group input { width: 100%; padding: 10px 14px; border: 1px solid #e8e8e8; border-radius: 8px; font-size: 14px; color: #333; outline: none; transition: border-color 0.2s; background: #fff; }
  .form-card .form-group select:focus, .form-card .form-group input:focus { border-color: #e8192c; }

  .input-prefix { position: relative; }
  .input-prefix span { position: absolute; left: 12px; top: 50%; transform: translateY(-50%); font-size: 13px; color: #999; }
  .input-prefix input { padding-left: 28px; }

  .date-row { display: grid; grid-template-columns: 1fr 1fr; gap: 12px; }
  .date-row input[type="date"] { width: 100%; max-width: 100%; min-width: 0; box-sizing: border-box; }

  .checkbox-label { display: flex; align-items: center; gap: 8px; font-size: 13px; color: #555; cursor: pointer; margin-bottom: 20px; }
  .checkbox-label input[type="checkbox"] { accent-color: #e8192c; width: 16px; height: 16px; }

  .btn-save { width: 100%; padding: 13px; background: #e8192c; color: #fff; border: none; border-radius: 8px; font-size: 14px; font-weight: 700; cursor: pointer; transition: background 0.2s; }
  .btn-save:hover { background: #c41525; }

  .list-title { font-size: 15px; font-weight: 700; color: #111; margin-bottom: 16px; }

  .promo-card { background: #fff; border-radius: 12px; border: 1px solid #eee; padding: 18px 20px; margin-bottom: 12px; display: flex; align-items: center; gap: 16px; transition: border-color 0.15s; }
  .promo-card:hover { border-color: #e8192c; }
  .promo-badge { min-width: 60px; height: 60px; background: #fff0f2; border-radius: 10px; display: flex; align-items: center; justify-content: center; font-size: 16px; font-weight: 800; color: #e8192c; flex-shrink: 0; }
  .promo-info { flex: 1; }
  .promo-name { font-size: 15px; font-weight: 700; color: #111; margin-bottom: 4px; }
  .promo-meta { display: flex; align-items: center; gap: 12px; font-size: 12px; color: #999; }
  .promo-meta svg { width: 12px; height: 12px; stroke: currentColor; fill: none; stroke-width: 1.8; }
  .promo-code { background: #f5f5f5; color: #888; font-size: 11px; font-weight: 600; padding: 2px 8px; border-radius: 4px; font-family: monospace; }
  .promo-actions { display: flex; align-items: center; gap: 10px; flex-shrink: 0; }
  .status-badge { font-size: 11px; font-weight: 600; padding: 4px 10px; border-radius: 100px; }
  .status-active { background: #f0fdf4; color: #16a34a; }
  .status-inactive { background: #f5f5f5; color: #999; }
  .status-expired { background: #fff7ed; color: #ea580c; }
  .promo-actions .btn-edit { font-size: 13px; font-weight: 600; color: #e8192c; background: none; border: none; cursor: pointer; text-decoration: none; padding: 0; }
  .promo-actions .btn-edit:hover { text-decoration: underline; background: none; color: #e8192c; }

  .modal-overlay.show { display: flex; }
  .modal { background: #fff; border-radius: 14px; padding: 28px; width: 400px; }
  .modal h3 { font-size: 16px; font-weight: 700; margin-bottom: 20px; }
  .btn-update { flex: 1; padding: 11px; background: #e8192c; color: #fff; border: none; border-radius: 8px; font-size: 14px; font-weight: 600; cursor: pointer; }
  .btn-cancel-modal { flex: 1; padding: 11px; background: #f5f5f5; color: #555; border: none; border-radius: 8px; font-size: 14px; cursor: pointer; }
  .product-select { width: 100%; max-width: 100%; height: 42px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
  .product-select option { max-width: 100%; white-space: normal; }
  .form-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 14px;
    margin-bottom: 16px;
  }

  .form-grid label {
    display: block;
    font-size: 12px;
    font-weight: 700;
    color: #777;
    margin-bottom: 6px;
  }

  .form-grid input,
  .form-grid select {
    width: 100%;
    border: 1px solid #e5e5e5;
    border-radius: 8px;
    padding: 9px 10px;
    font-size: 13px;
  }

  .btn-primary {
    background: #e8192c;
    border: none;
    color: #fff;
    padding: 10px 16px;
    border-radius: 9px;
    font-weight: 800;
    cursor: pointer;
  }

  .btn-delete {
    background: #fee2e2;
    color: #dc2626;
    border: 1px solid #fecaca;
    padding: 7px 10px;
    border-radius: 8px;
    font-weight: 700;
    cursor: pointer;
  }

  .promo-code-table {
    margin-top: 18px;
    overflow-x: auto;
  }

  .promo-code-table table {
    width: 100%;
    border-collapse: collapse;
  }

  .promo-code-table th,
  .promo-code-table td {
    padding: 11px 12px;
    border-bottom: 1px solid #eee;
    font-size: 13px;
    text-align: left;
  }
</style>
@endpush

<x-portal-layout
    title="Promotions"
    subtitle="Create and manage discounts"
    active="promotions"
>

    <div>
      <div class="form-card">
        <div class="form-card-title">
          <svg viewBox="0 0 24 24"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
          New Promotion
        </div>

        <form method="POST" action="{{ route('admin.promotions.store') }}">
          @csrf
          <div class="form-group">
            <label>Product</label>
            <select name="product_id" class="form-control product-select" required>
              <option value="">Select product</option>
              @foreach($products as $product)
                <option value="{{ $product->id }}">
                  {{ $product->name }} — {{ $product->category?->name ?? 'No category' }}
                </option>
              @endforeach
            </select>
          </div>

          <div class="form-group">
            <label>Discount (%)</label>
            <div class="input-prefix">
              <span>%</span>
              <input type="number"
                     name="value"
                     class="form-control discount-input"
                     min="1"
                     max="100"
                     step="1"
                     required
                     oninput="this.value = this.value.replace(/[^0-9]/g, ''); if (this.value > 100) this.value = 100; if (this.value < 1 && this.value !== '') this.value = 1;">
            </div>
          </div>

          <div class="form-group">
            <label>Date Range</label>
            <div class="date-row">
              <div>
                <label style="font-size:11px; color:#bbb; display:block; margin-bottom:4px;">Start</label>
                <input type="date" name="start_date" value="{{ date('Y-m-d') }}" style="width:100%">
              </div>
              <div>
                <label style="font-size:11px; color:#bbb; display:block; margin-bottom:4px;">End</label>
                <input type="date" name="end_date" value="{{ date('Y-m-d', strtotime('+15 days')) }}" style="width:100%">
              </div>
            </div>
          </div>
          <label class="checkbox-label">
            <input type="checkbox" name="activate_now" checked>
            Activate immediately
          </label>

          <button type="submit" class="btn-save">Save promotion</button>
        </form>
      </div>
    </div>

    <div class="list-panel">
      <div class="list-title">Registered promotions</div>

      @forelse($discounts as $index => $pd)
      @php
        $d = $pd->discount;
        $now = now();
        if ($d->status === 'inactive') {
          $statusLabel = 'Inactive';
          $statusClass = 'status-inactive';
        } elseif ($now->lt(\Carbon\Carbon::parse($d->start_date))) {
          $statusLabel = 'Scheduled';
          $statusClass = 'status-inactive';
        } elseif ($now->gt(\Carbon\Carbon::parse($d->end_date))) {
          $statusLabel = 'Expired';
          $statusClass = 'status-expired';
        } else {
          $statusLabel = 'Active';
          $statusClass = 'status-active';
        }
      @endphp
      <div class="promo-card">
        <div class="promo-badge">-{{ $d->value }}%</div>
        <div class="promo-info">
          <div class="promo-name">{{ $pd->product->name }}</div>
          <div class="promo-meta">
            <svg viewBox="0 0 24 24"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
            {{ \Carbon\Carbon::parse($d->start_date)->format('Y-m-d') }} → {{ \Carbon\Carbon::parse($d->end_date)->format('Y-m-d') }}
            <span class="promo-code">PR{{ str_pad($index + 1, 2, '0', STR_PAD_LEFT) }}</span>
          </div>
        </div>
        <div class="promo-actions">
          <span class="status-badge {{ $statusClass }}">{{ $statusLabel }}</span>
          <button
          class="btn-edit"
          data-id="{{ $d->id }}"
          data-value="{{ $d->value }}"
          data-start="{{ $d->start_date }}"
          data-end="{{ $d->end_date }}"
          data-status="{{ $d->status }}"
          onclick="openEdit(this)">
          Edit
          </button>
        </div>
      </div>
      @empty
      <div class="empty-row">No promotions registered yet</div>
      @endforelse
    </div>

    <div class="modal-overlay" id="edit-modal">
      <div class="modal">
        <h3>Edit Promotion</h3>
        <form id="edit-form" method="POST">
          @csrf
          @method('PUT')
          <div class="form-group">
            <label>Discount (%)</label>
            <div class="input-prefix">
              <span>%</span>
              <input type="number" name="value" id="edit-value" min="1" max="100">
            </div>
          </div>
          <div class="form-group">
            <label>Start Date</label>
            <input type="date" name="start_date" id="edit-start">
          </div>
          <div class="form-group">
            <label>End Date</label>
            <input type="date" name="end_date" id="edit-end">
          </div>
          <div class="form-group">
            <label>Status</label>
            <select name="status" id="edit-status">
              <option value="active">Active</option>
              <option value="inactive">Inactive</option>
            </select>
          </div>
          <div class="modal-actions">
            <button type="submit" class="btn-update">Update</button>
            <button type="button" class="btn-cancel-modal" onclick="closeEdit()">Cancel</button>
          </div>
        </form>
      </div>
    </div>
    <div class="admin-card" style="margin-top: 24px;">
    <div class="card-header">
        <h3>Promotion Codes</h3>
        <p>Create codes for Yape, Plin, card or general discounts.</p>
    </div>

    <form method="POST" action="{{ route('admin.promotion-codes.store') }}" class="promo-code-form">
        @csrf

        <div class="form-grid">
            <div>
                <label>Code</label>
                <input type="text" name="code" placeholder="Example: YAPE10" required>
            </div>

            <div>
                <label>Payment method</label>
                <select name="payment_method" required>
                    <option value="all">All</option>
                    <option value="cash">Cash</option>
                    <option value="card">Card</option>
                    <option value="yape">Yape</option>
                    <option value="plin">Plin</option>
                </select>
            </div>

            <div>
                <label>Discount type</label>
                <select name="discount_type" required>
                    <option value="percentage">Percentage</option>
                    <option value="fixed">Fixed amount</option>
                </select>
            </div>

            <div>
                <label>Value</label>
                <input type="number" name="value" step="0.01" min="0.01" placeholder="10" required>
            </div>

            <div>
                <label>Minimum amount</label>
                <input type="number" name="minimum_amount" step="0.01" min="0" value="0" required>
            </div>

            <div>
                <label>Usage limit</label>
                <input type="number" name="usage_limit" min="1" placeholder="Optional">
            </div>

            <div>
                <label>Start date</label>
                <input type="date" name="start_date">
            </div>

            <div>
                <label>End date</label>
                <input type="date" name="end_date">
            </div>

            <div>
                <label>Status</label>
                <select name="status" required>
                    <option value="active">Active</option>
                    <option value="inactive">Inactive</option>
                </select>
            </div>
        </div>

        <button type="submit" class="btn-primary">
            Create code
        </button>
    </form>

    <div class="promo-code-table">
        <table>
            <thead>
                <tr>
                    <th>Code</th>
                    <th>Method</th>
                    <th>Type</th>
                    <th>Value</th>
                    <th>Min. amount</th>
                    <th>Uses</th>
                    <th>Status</th>
                    <th>Delete</th>
                </tr>
            </thead>

            <tbody>
                @forelse($promoCodes ?? [] as $code)
                    <tr>
                        <td><strong>{{ $code->code }}</strong></td>
                        <td>{{ ucfirst($code->payment_method) }}</td>
                        <td>{{ ucfirst($code->discount_type) }}</td>
                        <td>
                            @if($code->discount_type === 'percentage')
                                {{ number_format($code->value, 0) }}%
                            @else
                                S/ {{ number_format($code->value, 2) }}
                            @endif
                        </td>
                        <td>S/ {{ number_format($code->minimum_amount, 2) }}</td>
                        <td>
                            {{ $code->used_count }}
                            @if($code->usage_limit)
                                / {{ $code->usage_limit }}
                            @endif
                        </td>
                        <td>{{ ucfirst($code->status) }}</td>
                        <td>
                            <form method="POST" action="{{ route('admin.promotion-codes.destroy', $code) }}">
                                @csrf
                                @method('DELETE')

                                <button type="submit" class="btn-delete" onclick="return confirm('Delete this code?')">
                                    Delete
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8">No promotion codes registered.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

    <script>
      function openEdit(button) {
        const id = button.getAttribute('data-id');
        const value = button.getAttribute('data-value');
        const start = button.getAttribute('data-start');
        const end = button.getAttribute('data-end');
        const status = button.getAttribute('data-status');

        document.getElementById('edit-form').action = '/admin/promotions/' + id;
        document.getElementById('edit-value').value = value;
        document.getElementById('edit-start').value = start;
        document.getElementById('edit-end').value = end;
        document.getElementById('edit-status').value = status;
        document.getElementById('edit-modal').classList.add('show');
      }

      function closeEdit() {
        document.getElementById('edit-modal').classList.remove('show');
      }

      document.getElementById('edit-modal').addEventListener('click', function(e) {
        if (e.target === this) closeEdit();
      });
    </script>

</x-portal-layout>