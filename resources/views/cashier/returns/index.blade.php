<x-portal-layout
    title="Returns"
    subtitle="Your return requests and their approval status"
    active="returns"
>
    <a href="{{ route('cashier.returns.create') }}" class="btn btn-primary" style="margin-bottom:18px; display:inline-flex;">
      <svg viewBox="0 0 24 24"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
      New return request
    </a>

    <div class="table-card">
      <table>
        <thead>
          <tr>
            <th>Sale</th>
            <th>Products</th>
            <th>Amount</th>
            <th>Reason</th>
            <th>Status</th>
            <th>Date</th>
          </tr>
        </thead>
        <tbody>
          @forelse($returns as $return)
            <tr>
              <td class="prod-code">{{ $return->sale->invoice_number ?? ('B-' . str_pad($return->sale_id, 5, '0', STR_PAD_LEFT)) }}</td>
              <td style="max-width:240px; font-size:12px;">
                @foreach($return->details as $detail)
                  {{ $detail->quantity }}x {{ $detail->product->name ?? 'Unknown' }}@if(!$loop->last), @endif
                @endforeach
              </td>
              <td>S/ {{ number_format($return->amount_returned, 2) }}</td>
              <td style="max-width:200px; font-size:12px; color:#666;">{{ $return->reason }}</td>
              <td>
                <span class="badge {{ $return->status === 'approved' ? 'ok' : ($return->status === 'rejected' ? 'out' : 'low') }}">
                  {{ ucfirst($return->status) }}
                </span>
                @if($return->status === 'rejected' && $return->rejection_reason)
                  <div style="font-size:11px; color:#999; margin-top:4px;">{{ $return->rejection_reason }}</div>
                @endif
              </td>
              <td>{{ $return->return_date ? \Carbon\Carbon::parse($return->return_date)->format('d/m/Y h:i A') : '-' }}</td>
            </tr>
          @empty
            <tr class="empty-row">
              <td colspan="6">You haven't submitted any return requests yet.</td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>

</x-portal-layout>