<x-portal-layout
    title="Order {{ $sale->receipt_number ?? 'WEB-' . str_pad($sale->id, 6, '0', STR_PAD_LEFT) }}"
    subtitle="Prepare and update this customer order"
    active="cashier-online-orders"
>
    @push('portal-styles')
        <style>
            .order-layout {
                display: grid;
                grid-template-columns: minmax(0, 1fr) 360px;
                gap: 20px;
                align-items: start;
            }

            .info-stack {
                display: flex;
                flex-direction: column;
                gap: 16px;
            }

            .info-box {
                padding: 18px;
            }

            .info-box h3 {
                font-size: 15px;
                font-weight: 800;
                color: #111;
                margin-bottom: 14px;
            }

            .info-line {
                font-size: 13px;
                color: #333;
                margin-bottom: 10px;
                line-height: 1.5;
            }

            .summary-panel {
                display: flex;
                justify-content: flex-end;
                margin-top: 18px;
            }

            .summary-box {
                width: 260px;
            }

            .summary-line {
                display: flex;
                justify-content: space-between;
                font-size: 14px;
                color: #555;
                margin-bottom: 8px;
            }

            .summary-total {
                display: flex;
                justify-content: space-between;
                border-top: 1px solid #eee;
                padding-top: 12px;
                font-size: 20px;
                font-weight: 900;
                color: #111;
            }

            @media (max-width: 1000px) {
                .order-layout {
                    grid-template-columns: 1fr;
                }
            }
        </style>
    @endpush

    <div class="toolbar" style="justify-content:space-between;">
        <div>
            <strong style="font-size:14px; color:#111;">Order detail</strong>
            <p style="font-size:12px; color:#999; margin-top:2px;">
                Review products, delivery information and payment
            </p>
        </div>

        <a href="{{ route('cashier.online-orders.index') }}" class="btn">
            Back
        </a>
    </div>

    @if(session('success'))
        <div style="background:#ecfdf5; color:#15803d; border:1px solid #bbf7d0; padding:12px 14px; border-radius:10px; margin-bottom:16px; font-size:13px; font-weight:700;">
            {{ session('success') }}
        </div>
    @endif

    <div class="order-layout">
        <div>
            <div class="table-card">
                <div style="padding:18px 18px 0;">
                    <h3 style="font-size:15px; font-weight:800; color:#111;">
                        Products to prepare
                    </h3>
                    <p style="font-size:12px; color:#999; margin-top:2px;">
                        Check each item before delivery or pickup
                    </p>
                </div>

                <table>
                    <thead>
                        <tr>
                            <th>Product</th>
                            <th style="text-align:right;">Qty</th>
                            <th style="text-align:right;">Price</th>
                            <th style="text-align:right;">Subtotal</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach($sale->details as $detail)
                            <tr>
                                <td>
                                    <div class="prod-name">
                                        {{ $detail->product?->name ?? 'Product deleted' }}
                                    </div>
                                </td>

                                <td style="text-align:right;">
                                    {{ $detail->quantity }}
                                </td>

                                <td style="text-align:right;">
                                    S/ {{ number_format($detail->price, 2) }}
                                </td>

                                <td style="text-align:right;">
                                    <strong>S/ {{ number_format($detail->subtotal, 2) }}</strong>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <div style="padding:0 18px 18px;">
                    <div class="summary-panel">
                        <div class="summary-box">
                            <div class="summary-line">
                                <span>Subtotal</span>
                                <span>S/ {{ number_format($sale->total, 2) }}</span>
                            </div>

                            <div class="summary-total">
                                <span>Total</span>
                                <span>S/ {{ number_format($sale->total, 2) }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="info-stack">
            <div class="table-card info-box">
                <h3>Customer</h3>

                <div class="info-line">
                    <strong>Name:</strong>
                    {{ $sale->customer?->name ?? 'Customer' }}
                </div>

                <div class="info-line">
                    <strong>Email:</strong>
                    {{ $sale->customer?->email ?? '-' }}
                </div>

                <div class="info-line">
                    <strong>Date:</strong>
                    {{ $sale->created_at->format('d/m/Y H:i') }}
                </div>
            </div>

            <div class="table-card info-box">
                <h3>Delivery</h3>

                <div class="info-line">
                    <strong>Type:</strong>

                    @if($sale->delivery_type === 'pickup')
                        <span class="badge low">Store pickup</span>
                    @else
                        <span class="badge ok">Delivery</span>
                    @endif
                </div>

                @if($sale->delivery_type === 'delivery')
                    <div class="info-line">
                        <strong>Address:</strong>
                        {{ $sale->delivery_address }}
                    </div>

                    <div class="info-line">
                        <strong>Reference:</strong>
                        {{ $sale->delivery_reference }}
                    </div>

                    <div class="info-line">
                        <strong>Phone:</strong>
                        {{ $sale->delivery_phone }}
                    </div>
                @else
                    <div class="info-line">
                        <strong>Store:</strong>
                        {{ $sale->pickup_store ?? 'Minimarket Express - Main Store' }}
                    </div>

                    <div class="info-line">
                        <strong>Note:</strong>
                        {{ $sale->pickup_note ?? '-' }}
                    </div>
                @endif
            </div>

            <div class="table-card info-box">
                <h3>Payment</h3>

                <div class="info-line">
                    <strong>Status:</strong>
                    <span class="badge ok">{{ ucfirst($sale->payment_status ?? 'paid') }}</span>
                </div>

                <div class="info-line">
                    <strong>Method:</strong>
                    Card
                </div>

                <div class="info-line">
                    <strong>Card:</strong>
                    **** {{ $sale->card_last_four ?? '----' }}
                </div>
            </div>

            <div class="table-card info-box">
                <h3>Order status</h3>

                <form method="POST" action="{{ route('cashier.online-orders.update-status', $sale) }}">
                    @csrf
                    @method('PATCH')

                    <div class="form-group">
                        <label for="order_status">Status</label>

                        <select id="order_status" name="order_status" required>
                            <option value="preparing" {{ $sale->order_status === 'preparing' ? 'selected' : '' }}>
                                Preparing
                            </option>

                            <option value="ready" {{ $sale->order_status === 'ready' ? 'selected' : '' }}>
                                Ready
                            </option>

                            <option value="delivered" {{ $sale->order_status === 'delivered' ? 'selected' : '' }}>
                                Delivered
                            </option>
                        </select>
                    </div>

                    <button type="submit" class="btn btn-primary" style="width:100%; justify-content:center;">
                        Update status
                    </button>
                </form>
            </div>
        </div>
    </div>
</x-portal-layout>