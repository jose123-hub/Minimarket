@extends('layouts.app')

@section('contenido')
<div class="container py-4">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold mb-1">Online Orders</h2>
            <p class="text-muted mb-0">Manage web orders from customers.</p>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <div class="card border-0 shadow-sm">
        <div class="card-body">

            <table class="table align-middle">
                <thead>
                    <tr>
                        <th>Receipt</th>
                        <th>Customer</th>
                        <th>Delivery</th>
                        <th>Payment</th>
                        <th>Status</th>
                        <th>Total</th>
                        <th>Date</th>
                        <th class="text-end">Action</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($orders as $order)
                        <tr>
                            <td>
                                <strong>{{ $order->receipt_number }}</strong>
                            </td>

                            <td>
                                {{ $order->customer?->name ?? 'Customer' }}
                            </td>

                            <td>
                                @if($order->delivery_type === 'pickup')
                                    <span class="badge bg-primary">Store pickup</span>
                                @else
                                    <span class="badge bg-info text-dark">Delivery</span>
                                @endif
                            </td>

                            <td>
                                <span class="badge bg-success">
                                    {{ ucfirst($order->payment_status ?? 'paid') }}
                                </span>
                                <div class="small text-muted">
                                    Card **** {{ $order->card_last_four }}
                                </div>
                            </td>

                            <td>
                                @php
                                    $statusClass = match($order->order_status) {
                                        'pending' => 'bg-warning text-dark',
                                        'preparing' => 'bg-primary',
                                        'ready' => 'bg-info text-dark',
                                        'delivered' => 'bg-success',
                                        'cancelled' => 'bg-danger',
                                        default => 'bg-secondary'
                                    };
                                @endphp

                                <span class="badge {{ $statusClass }}">
                                    {{ ucfirst($order->order_status) }}
                                </span>
                            </td>

                            <td>
                                <strong>S/ {{ number_format($order->total, 2) }}</strong>
                            </td>

                            <td>
                                {{ $order->created_at->format('d/m/Y H:i') }}
                            </td>

                            <td class="text-end">
                                <a href="{{ route('admin.online-orders.show', $order) }}"
                                   class="btn btn-sm btn-outline-danger">
                                    View
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center text-muted py-4">
                                No online orders found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

        </div>
    </div>
</div>
@endsection