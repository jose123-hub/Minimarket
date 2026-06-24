<x-admin-layout
    title="Returns"
    subtitle="Review and approve return requests from cashiers"
    active="returns"
>
    <div class="table-card">
      <table>
        <thead>
          <tr>
            <th>Sale</th>
            <th>Cashier</th>
            <th>Products</th>
            <th>Amount</th>
            <th>Reason</th>
            <th>Status</th>
            <th>Date</th>
            <th style="text-align:right">Actions</th>
          </tr>
        </thead>
        <tbody>
          @forelse($returns as $return)
            <tr>
              <td class="prod-code">{{ $return->sale->invoice_number ?? ('B-' . str_pad($return->sale_id, 5, '0', STR_PAD_LEFT)) }}</td>
              <td>{{ $return->sale->cashier->name ?? $return->user->name ?? '—' }}</td>
              <td style="max-width:240px;">
                @foreach($return->details as $detail)
                  <div style="font-size:12px;">{{ $detail->quantity }}x {{ $detail->product->name ?? 'Unknown' }}</div>
                @endforeach
              </td>
              <td>S/ {{ number_format($return->amount_returned, 2) }}</td>
              <td style="max-width:200px; font-size:12px; color:#666;">{{ $return->reason }}</td>
              <td>
                <span class="badge {{ $return->status === 'approved' ? 'ok' : ($return->status === 'rejected' ? 'out' : 'low') }}">
                  {{ ucfirst($return->status) }}
                </span>
              </td>
              <td>{{ $return->return_date ? \Carbon\Carbon::parse($return->return_date)->format('d/m/Y h:i A') : '-' }}</td>
              <td>
                @if($return->status === 'pending')
                  <div class="actions" style="justify-content:flex-end">
                    <form action="{{ route('admin.returns.approve', $return) }}" method="POST" onsubmit="return confirm('Approve this return? Stock will be restored automatically.');">
                      @csrf
                      <button type="submit" class="btn" style="padding:7px 12px; background:#ecfdf5; color:#059669; border-color:#a7f3d0;">
                        Approve
                      </button>
                    </form>
                    <form action="{{ route('admin.returns.reject', $return) }}" method="POST" onsubmit="return confirm('Reject this return request?');">
                      @csrf
                      <button type="submit" class="btn" style="padding:7px 12px; background:#fef2f2; color:#dc2626; border-color:#fecaca;">
                        Reject
                      </button>
                    </form>
                  </div>
                @else
                  <span style="font-size:12px; color:#bbb;">No actions</span>
                @endif
              </td>
            </tr>
          @empty
            <tr class="empty-row">
              <td colspan="8">No return requests yet.</td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>

</x-admin-layout>