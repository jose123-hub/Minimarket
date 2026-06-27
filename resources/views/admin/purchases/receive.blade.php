@push('portal-styles')
<style>
  .content { max-width: 820px; }
  .order-meta { background: #fff; border: 1px solid #eee; border-radius: 12px; padding: 18px 20px; margin-bottom: 18px; display: flex; gap: 32px; }
  .order-meta div strong { display: block; font-size: 13px; color: #999; margin-bottom: 4px; }
  .order-meta div span { font-size: 14px; font-weight: 700; color: #111; }

  .prod-name { font-weight: 700; color: #111; }
  .pending-tag { font-size: 11px; color: #ea580c; font-weight: 600; }
  .done-tag { font-size: 11px; color: #16a34a; font-weight: 600; }
  input.qty-input { width: 80px; border: 1px solid #e5e5e5; border-radius: 7px; padding: 7px 9px; font-size: 13px; }
  input.qty-input:disabled { background: #f5f5f5; color: #aaa; }

  .actions-bar { display: flex; justify-content: flex-end; gap: 10px; margin-top: 20px; }
</style>
@endpush

<x-portal-layout
    title="Receive Purchase {{ $purchase->order_number }}"
    subtitle="Confirm how many units actually arrived for each product"
    active="purchases"
>
    <div class="order-meta">
      <div><strong>Supplier</strong><span>{{ $purchase->supplier->company_name }}</span></div>
      <div><strong>Order date</strong><span>{{ \Carbon\Carbon::parse($purchase->order_date)->format('d/m/Y') }}</span></div>
      <div><strong>Status</strong><span>{{ ucfirst($purchase->status) }}</span></div>
    </div>

    <form action="/admin/purchases/{{ $purchase->id }}/receive" method="POST">
      @csrf
      <div class="table-card">
        <table>
          <thead>
            <tr>
              <th>Product</th>
              <th>Ordered</th>
              <th>Already received</th>
              <th>Pending</th>
              <th>Receive now</th>
            </tr>
          </thead>
          <tbody>
            @foreach($purchase->details as $detail)
              @php $pending = $detail->quantity_ordered - $detail->quantity_received; @endphp
              <tr>
                <td class="prod-name">{{ $detail->product->name }}</td>
                <td>{{ $detail->quantity_ordered }}</td>
                <td>{{ $detail->quantity_received }}</td>
                <td>
                  @if($pending > 0)
                    <span class="pending-tag">{{ $pending }} pending</span>
                  @else
                    <span class="done-tag">Complete</span>
                  @endif
                </td>
                <td>
                  <input type="number" min="0" max="{{ $pending }}"
                         class="qty-input" name="received[{{ $detail->id }}]"
                         value="{{ $pending }}" {{ $pending <= 0 ? 'disabled' : '' }}>
                </td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>

      <div class="actions-bar">
        <a href="/admin/purchases" class="btn">Cancel</a>
        <button type="submit" class="btn btn-primary">Confirm reception</button>
      </div>
    </form>

</x-portal-layout>