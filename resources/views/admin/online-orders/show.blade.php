@extends('layouts.app')

@section('contenido')
<div class="container py-4">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold mb-1">Order {{ $sale->receipt_number }}</h2>
            <p class="text-muted mb-0">Review and update this online order.</p>
        </div>

        <a href="{{ route('admin.online-orders.index') }}" class="btn btn-outline-secondary">
            Back
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <div class="row g-4">

        <div class="col-md-8">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body">
                    <h5 class="fw-bold mb-3">Products</h5>

                    <table class="table align-middle">
                        <thead>
                            <tr>
                                <th>Product</th>
                                <th class="text-end">Qty</th>
                                <th class="text-end">Price</th>
                                <th class="text-end">Subtotal</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach($sale->details as $detail)
                                <tr>
                                    <td>{{ $detail->product?->name ?? 'Product deleted' }}</td>
                                    <td class="text-end">{{ $detail->quantity }}</td>
                                    <td class="text-end">S/ {{ number_format($detail->price, 2) }}</td>
                                    <td class="text-end">S/ {{ number_format($detail->subtotal, 2) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    <div class="text-end mt-3">
                        <h4 class="fw-bold">
                            Total: S/ {{ number_format($sale->total, 2) }}
                        </h4>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">

            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body">
                    <h5 class="fw-bold mb-3">Customer</h5>

                    <p class="mb-1">
                        <strong>Name:</strong>
                        {{ $sale->customer?->name ?? 'Customer' }}
                    </p>

                    <p class="mb-1">
                        <strong>Email:</strong>
                        {{ $sale->customer?->email ?? '-' }}
                    </p>

                    <p class="mb-0">
                        <strong>Date:</strong>
                        {{ $sale->created_at->format('d/m/Y H:i') }}
                    </p>
                </div>
            </div>

            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body">
                    <h5 class="fw-bold mb-3">Delivery</h5>

                    <p class="mb-1">
                        <strong>Type:</strong>
                        {{ $sale->delivery_type === 'pickup' ? 'Store pickup' : 'Delivery' }}
                    </p>

                    @if($sale->delivery_type === 'delivery')
                        <p class="mb-1">
                            <strong>Address:</strong>
                            {{ $sale->delivery_address }}
                        </p>

                        <p class="mb-1">
                            <strong>Reference:</strong>
                            {{ $sale->delivery_reference }}
                        </p>

                        <p class="mb-0">
                            <strong>Phone:</strong>
                            {{ $sale->delivery_phone }}
                        </p>
                    @else
                        <p class="mb-1">
                            <strong>Store:</strong>
                            {{ $sale->pickup_store }}
                        </p>

                        <p class="mb-0">
                            <strong>Note:</strong>
                            {{ $sale->pickup_note ?? '-' }}
                        </p>
                    @endif
                </div>
            </div>

            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body">
                    <h5 class="fw-bold mb-3">Payment</h5>

                    <p class="mb-1">
                        <strong>Status:</strong>
                        {{ ucfirst($sale->payment_status) }}
                    </p>

                    <p class="mb-0">
                        <strong>Card:</strong>
                        **** {{ $sale->card_last_four }}
                    </p>
                </div>
            </div>

            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <h5 class="fw-bold mb-3">Order status</h5>

                    <form method="POST" action="{{ route('admin.online-orders.update-status', $sale) }}">
                        @csrf
                        @method('PATCH')

                        <select name="order_status" class="form-select mb-3">
                            <option value="pending" {{ $sale->order_status === 'pending' ? 'selected' : '' }}>
                                Pending
                            </option>

                            <option value="preparing" {{ $sale->order_status === 'preparing' ? 'selected' : '' }}>
                                Preparing
                            </option>

                            <option value="ready" {{ $sale->order_status === 'ready' ? 'selected' : '' }}>
                                Ready
                            </option>

                            <option value="delivered" {{ $sale->order_status === 'delivered' ? 'selected' : '' }}>
                                Delivered
                            </option>

                            <option value="cancelled" {{ $sale->order_status === 'cancelled' ? 'selected' : '' }}>
                                Cancelled
                            </option>
                        </select>

                        <button type="submit" class="btn btn-danger w-100">
                            Update status
                        </button>
                    </form>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection