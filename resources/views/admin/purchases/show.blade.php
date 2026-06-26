@push('portal-styles')
<style>
  .content { max-width: 880px; }

  .top-grid { display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 16px; margin-bottom: 20px; }
  .info-card { background: #fff; border: 1px solid #eee; border-radius: 12px; padding: 16px 18px; }
  .info-card strong { display: block; font-size: 11px; color: #999; text-transform: uppercase; letter-spacing: .03em; margin-bottom: 6px; }
  .info-card span { font-size: 15px; font-weight: 700; color: #111; }

  .badge-pending { background: #fff7ed; color: #ea580c; }
  .badge-partial { background: #eff6ff; color: #2563eb; }
  .badge-received { background: #f0fdf4; color: #16a34a; }
  .badge-cancelled { background: #f5f5f5; color: #999; }

  .product-name { font-weight: 700; color: #111; }
  .total-row td { font-weight: 700; font-size: 14px; }
  .grand-total { color: #e8192c; font-size: 16px; }

  .actions-bar { display: flex; justify-content: flex-end; gap: 10px; }
  .btn-danger { background: #fff0f0; border-color: #fcc; color: #c00; }
  .btn-danger:hover { background: #e8192c; border-color: #e8192c; color: #fff; }
</style>
@endpush

<x-portal-layout
    title="{{ $purchase->order_number }}"
    subtitle="Purchase order detail"
    active="purchases"
>
    <div class="top-grid">
      <div class="info-card">
        <strong>Supplier</strong>
        <span>{{ $purchase->supplier->company_name }}</span>
      </div>
      <div class="info-card">
        <strong>Order date</strong>
        <span>{{ \Carbon\Carbon::parse($purchase->order_date)->format('d/m/Y') }}</span>
      </div>
      <div class="info-card">
        <strong>Status</strong>
        @if($purchase->status === 'pending')
          <span class="badge badge-pending">Pending</span>
        @elseif($purchase->status === 'partial')
          <span class="badge badge-partial">Partial</span>
        @elseif($purchase->status === 'received')
          <span class="badge badge-received">Received</span>
        @else
          <span class="badge badge-cancelled">Cancelled</span>
        @endif
      </div>
      <div class="info-card">
        <strong>Created by</strong>
        <span>{{ $purchase->user->name ?? '—' }}</span>
      </div>
      <div class="info-card">
        <strong>Estimated delivery</strong>
        <span>{{ $purchase->estimated_delivery ? \Carbon\Carbon::parse($purchase->estimated_delivery)->format('d/m/Y') : '—' }}</span>
      </div>
      <div class="info-card">
        <strong>Actual delivery</strong>
        <span>{{ $purchase->actual_delivery ? \Carbon\Carbon::parse($purchase->actual_delivery)->format('d/m/Y') : '—' }}</span>
      </div>
    </div>

    @if($purchase->notes)
      <div class="info-card" style="margin-bottom:20px;">
        <strong>Notes</strong>
        <span style="font-size:13px; font-weight:400;">{{ $purchase->notes }}</span>
      </div>
    @endif

    <div class="table-card">
      <table>
        <thead>
          <tr>
            <th>Product</th>
            <th>Ordered</th>
            <th>Received</th>
            <th>Unit cost</th>
            <th>Subtotal</th>
          </tr>
        </thead>
        <tbody>
          @foreach($purchase->details as $detail)
            <tr>
              <td class="product-name">{{ $detail->product->name }}</td>
              <td>{{ $detail->quantity_ordered }}</td>
              <td>{{ $detail->quantity_received }}</td>
              <td>S/ {{ number_format($detail->unit_cost, 2) }}</td>
              <td>S/ {{ number_format($detail->subtotal, 2) }}</td>
            </tr>
          @endforeach
        </tbody>
        <tfoot>
          <tr class="total-row">
            <td colspan="4" style="text-align:right; color:#999; font-size:13px;">Total purchase</td>
            <td><span class="grand-total">S/ {{ number_format($purchase->total, 2) }}</span></td>
          </tr>
        </tfoot>
      </table>
    </div>

    <div class="actions-bar">
      <a href="/admin/purchases" class="btn">Back to list</a>

      @if(in_array($purchase->status, ['pending', 'partial']))
        <a href="/admin/purchases/{{ $purchase->id }}/receive" class="btn btn-primary">
          <svg viewBox="0 0 24 24" width="15" height="15" stroke="#fff" fill="none" stroke-width="2"><path d="M21 15v4a2 2 0 01-2 2H5a2 2 0 01-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
          Receive
        </a>
      @endif

      @if($purchase->status === 'pending')
        <a href="/admin/purchases/{{ $purchase->id }}/edit" class="btn">Edit</a>
        <form action="/admin/purchases/{{ $purchase->id }}/cancel" method="POST" onsubmit="return confirm('Cancel this purchase order?')">
          @csrf
          <button type="submit" class="btn btn-danger">Cancel order</button>
        </form>
      @endif

      @if(in_array($purchase->status, ['pending', 'cancelled']))
        <form action="/admin/purchases/{{ $purchase->id }}" method="POST" onsubmit="return confirm('Permanently delete this purchase order?')">
          @csrf
          @method('DELETE')
          <button type="submit" class="btn btn-danger">Delete</button>
        </form>
      @endif
    </div>

</x-portal-layout>