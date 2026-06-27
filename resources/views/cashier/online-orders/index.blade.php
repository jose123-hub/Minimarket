<x-portal-layout
    title="Online Orders"
    subtitle="Prepare and deliver paid web orders"
    active="cashier-online-orders"
>
    <div class="toolbar" style="justify-content:space-between;">
        <div>
            <strong style="font-size:14px; color:#111;">Pending web orders</strong>
            <p style="font-size:12px; color:#999; margin-top:2px;">
                Orders paid online and waiting for store attention
            </p>
        </div>
    </div>

    @if(session('success'))
        <div style="background:#ecfdf5; color:#15803d; border:1px solid #bbf7d0; padding:12px 14px; border-radius:10px; margin-bottom:16px; font-size:13px; font-weight:700;">
            {{ session('success') }}
        </div>
    @endif

    <div class="table-card">
        <table>
            <thead>
                <tr>
                    <th>Receipt</th>
                    <th>Customer</th>
                    <th>Delivery</th>
                    <th>Payment</th>
                    <th>Status</th>
                    <th>Total</th>
                    <th>Date</th>
                    <th style="text-align:right;">Action</th>
                </tr>
            </thead>

            <tbody>
                @forelse($orders as $order)
                    <tr>
                        <td>
                            <div class="prod-name">
                                {{ $order->receipt_number ?? 'WEB-' . str_pad($order->id, 6, '0', STR_PAD_LEFT) }}
                            </div>
                        </td>

                        <td>
                            <strong>{{ $order->customer?->name ?? 'Customer' }}</strong>
                            <div style="font-size:12px; color:#999; margin-top:2px;">
                                {{ $order->customer?->email ?? 'No email' }}
                            </div>
                        </td>

                        <td>
                            @if($order->delivery_type === 'pickup')
                                <span class="badge low">Store pickup</span>
                            @else
                                <span class="badge ok">Delivery</span>
                            @endif
                        </td>

                        <td>
                            <span class="badge ok">
                                {{ ucfirst($order->payment_status ?? 'paid') }}
                            </span>

                            <div style="font-size:12px; color:#999; margin-top:4px;">
                                Card **** {{ $order->card_last_four ?? '----' }}
                            </div>
                        </td>

                        <td>
                            @php
                                $statusClass = match($order->order_status) {
                                    'pending' => 'low',
                                    'preparing' => 'low',
                                    'ready' => 'ok',
                                    default => 'low',
                                };
                            @endphp

                            <span class="badge {{ $statusClass }}">
                                {{ ucfirst($order->order_status ?? 'pending') }}
                            </span>
                        </td>

                        <td>
                            <strong>S/ {{ number_format($order->total, 2) }}</strong>
                        </td>

                        <td>
                            {{ $order->created_at->format('d/m/Y H:i') }}
                        </td>

                        <td>
                            <div class="actions" style="justify-content:flex-end;">
                                <a href="{{ route('cashier.online-orders.show', $order) }}"
                                   class="icon-btn"
                                   title="View order">
                                    <svg viewBox="0 0 24 24">
                                        <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                                        <circle cx="12" cy="12" r="3"/>
                                    </svg>
                                </a>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr class="empty-row">
                        <td colspan="8">No pending online orders found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</x-portal-layout>