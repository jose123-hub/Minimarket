@push('portal-styles')
<style>
  .btn-add { display: flex; align-items: center; gap: 8px; padding: 10px 20px; background: #e8192c; color: #fff; border: none; border-radius: 8px; font-size: 13px; font-weight: 600; text-decoration: none; }
  .btn-add:hover { background: #c41525; color: #fff; }
  .btn-add svg { width: 16px; height: 16px; stroke: #fff; fill: none; stroke-width: 2.5; }

  .order-number { font-family: monospace; font-weight: 600; color: #111; text-decoration: none; }
  .order-number:hover { color: #e8192c; text-decoration: underline; }
  .supplier-name { font-weight: 600; color: #111; }
  .total { font-weight: 700; color: #111; }
  .badge-pending { background: #fff7ed; color: #ea580c; }
  .badge-partial { background: #eff6ff; color: #2563eb; }
  .badge-received { background: #f0fdf4; color: #16a34a; }
  .badge-cancelled { background: #f5f5f5; color: #999; }
  .btn-receive { display: inline-flex; align-items: center; gap: 6px; padding: 6px 12px; background: #111; color: #fff; border-radius: 7px; font-size: 12px; font-weight: 600; text-decoration: none; }
  .btn-receive:hover { background: #e8192c; }
  .btn-receive svg { width: 13px; height: 13px; stroke: #fff; fill: none; stroke-width: 2; }
</style>
@endpush

<x-portal-layout
    title="Purchases"
    subtitle="Purchase orders to suppliers"
    active="purchases"
>
    <div class="toolbar">
      <div class="search-box">
        <svg viewBox="0 0 24 24"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
        <input type="text" id="search-input" placeholder="Search order...">
      </div>
      <a href="/admin/purchases/create" class="btn-add">
        <svg viewBox="0 0 24 24"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
        New Purchase
      </a>
    </div>

    <div class="table-card">
      <table>
        <thead>
          <tr>
            <th>Order #</th>
            <th>Supplier</th>
            <th>Date</th>
            <th>Items</th>
            <th>Total</th>
            <th>Status</th>
            <th style="text-align:right">Actions</th>
          </tr>
        </thead>
        <tbody id="purchases-table">
          @forelse($purchases as $purchase)
          <tr data-name="{{ strtolower($purchase->order_number) }}">
            <td><a href="/admin/purchases/{{ $purchase->id }}" class="order-number">{{ $purchase->order_number }}</a></td>
            <td><span class="supplier-name">{{ $purchase->supplier->company_name }}</span></td>
            <td>{{ \Carbon\Carbon::parse($purchase->order_date)->format('d/m/Y') }}</td>
            <td>{{ $purchase->details->count() }} products</td>
            <td><span class="total">S/ {{ number_format($purchase->total, 2) }}</span></td>
            <td>
              @if($purchase->status === 'pending')
                <span class="badge badge-pending">Pending</span>
              @elseif($purchase->status === 'partial')
                <span class="badge badge-partial">Partial</span>
              @elseif($purchase->status === 'received')
                <span class="badge badge-received">Received</span>
              @else
                <span class="badge badge-cancelled">Cancelled</span>
              @endif
            </td>
            <td style="text-align:right">
              @if($purchase->status === 'pending' || $purchase->status === 'partial')
                <a href="/admin/purchases/{{ $purchase->id }}/receive" class="btn-receive">
                  <svg viewBox="0 0 24 24"><path d="M21 15v4a2 2 0 01-2 2H5a2 2 0 01-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
                  Receive
                </a>
              @endif
            </td>
          </tr>
          @empty
          <tr class="empty-row"><td colspan="7">No purchase orders yet</td></tr>
          @endforelse
        </tbody>
      </table>
    </div>

    <script>
      document.getElementById('search-input').addEventListener('input', function() {
        const q = this.value.toLowerCase();
        document.querySelectorAll('#purchases-table tr[data-name]').forEach(row => {
          row.style.display = row.dataset.name.includes(q) ? '' : 'none';
        });
      });
    </script>

</x-portal-layout>