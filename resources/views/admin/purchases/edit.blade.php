@push('portal-styles')
<style>
  .content { max-width: 820px; }

  .form-card { background: #fff; border: 1px solid #eee; border-radius: 12px; padding: 20px; margin-bottom: 20px; }
  .form-card h3 { font-size: 14px; font-weight: 700; color: #111; margin-bottom: 14px; }
  .form-card .form-row { display: grid; grid-template-columns: 1fr 1fr; gap: 14px; margin-bottom: 14px; }
  .form-card .form-group select, .form-card .form-group input, .form-card .form-group textarea {
    width: 100%; border: 1px solid #e0e0e0; border-radius: 8px; padding: 10px 12px; font-size: 13px; color: #333; outline: none;
  }
  .form-card .form-group select:focus, .form-card .form-group input:focus, .form-card .form-group textarea:focus { border-color: #e8192c; }

  .product-name { font-weight: 700; color: #111; }
  .qty-input, .cost-input { width: 90px; padding: 7px 10px; border: 1px solid #e0e0e0; border-radius: 6px; font-size: 13px; outline: none; }
  .qty-input:focus, .cost-input:focus { border-color: #e8192c; }
  .subtotal { font-weight: 700; color: #111; }

  .form-actions { display: flex; justify-content: flex-end; gap: 12px; }
  .btn-save { padding: 12px 28px; background: #e8192c; color: #fff; border: none; border-radius: 8px; font-size: 14px; font-weight: 600; cursor: pointer; }
  .btn-save:hover { background: #c41525; }
  .btn-cancel { padding: 12px 28px; background: #f5f5f5; color: #555; border: none; border-radius: 8px; font-size: 14px; font-weight: 500; text-decoration: none; }
  .btn-cancel:hover { background: #eee; }
</style>
@endpush

<x-portal-layout
    title="Edit {{ $purchase->order_number }}"
    subtitle="Only allowed while the order is still pending (nothing received yet)"
    active="purchases"
>
    <form action="/admin/purchases/{{ $purchase->id }}" method="POST">
      @csrf
      @method('PUT')

      <div class="form-card">
        <h3>Order info</h3>
        <div class="form-row">
          <div class="form-group">
            <label>Supplier</label>
            <select name="supplier_id">
              @foreach($suppliers as $supplier)
                <option value="{{ $supplier->id }}" {{ $purchase->supplier_id == $supplier->id ? 'selected' : '' }}>
                  {{ $supplier->company_name }}
                </option>
              @endforeach
            </select>
          </div>
          <div class="form-group">
            <label>Estimated delivery</label>
            <input type="date" name="estimated_delivery" value="{{ $purchase->estimated_delivery }}">
          </div>
        </div>
        <div class="form-group">
          <label>Notes</label>
          <textarea name="notes" rows="2">{{ $purchase->notes }}</textarea>
        </div>
      </div>

      <div class="table-card">
        <table>
          <thead>
            <tr>
              <th>Product</th>
              <th>Quantity</th>
              <th>Unit cost</th>
              <th>Subtotal</th>
            </tr>
          </thead>
          <tbody>
            @foreach($purchase->details as $i => $detail)
              <tr>
                <td class="product-name">
                  {{ $detail->product->name }}
                  <input type="hidden" name="details[{{ $i }}][id]" value="{{ $detail->id }}">
                </td>
                <td>
                  <input type="number" min="1" class="qty-input"
                         name="details[{{ $i }}][quantity]" value="{{ $detail->quantity_ordered }}">
                </td>
                <td>
                  <input type="number" min="0" step="0.01" class="cost-input"
                         name="details[{{ $i }}][unit_cost]" value="{{ $detail->unit_cost }}">
                </td>
                <td><span class="subtotal">S/ {{ number_format($detail->subtotal, 2) }}</span></td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>

      <div class="form-actions">
        <a href="/admin/purchases/{{ $purchase->id }}" class="btn-cancel">Cancel</a>
        <button type="submit" class="btn-save">Save changes</button>
      </div>
    </form>

</x-portal-layout>