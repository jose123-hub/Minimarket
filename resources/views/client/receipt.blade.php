<x-client-layout
    title="Order Receipt"
    active="orders"
    :client="$client"
>
    <x-slot name="styles">
        <style>
            .receipt-wrap {
                max-width: 820px;
                margin: 0 auto;
            }

            .receipt-card {
                background: #fff;
                border: 1px solid #eee;
                border-radius: 18px;
                padding: 28px;
                box-shadow: 0 8px 30px rgba(0,0,0,0.06);
            }

            .receipt-header {
                display: flex;
                justify-content: space-between;
                align-items: flex-start;
                gap: 20px;
                border-bottom: 1px solid #eee;
                padding-bottom: 18px;
                margin-bottom: 22px;
            }

            .receipt-title h2 {
                font-size: 26px;
                font-weight: 900;
                color: #111;
                margin-bottom: 6px;
            }

            .receipt-title p {
                font-size: 13px;
                color: #777;
            }

            .receipt-badge {
                background: #fff0f2;
                color: #e8192c;
                padding: 8px 12px;
                border-radius: 999px;
                font-size: 12px;
                font-weight: 900;
                white-space: nowrap;
            }

            .receipt-grid {
                display: grid;
                grid-template-columns: repeat(2, 1fr);
                gap: 14px;
                margin-bottom: 24px;
            }

            .info-box {
                background: #fafafa;
                border: 1px solid #f0f0f0;
                border-radius: 14px;
                padding: 14px;
            }

            .info-label {
                font-size: 11px;
                color: #999;
                font-weight: 800;
                text-transform: uppercase;
                margin-bottom: 5px;
            }

            .info-value {
                font-size: 14px;
                color: #111;
                font-weight: 700;
            }

            .items-table {
                width: 100%;
                border-collapse: collapse;
                margin-bottom: 20px;
            }

            .items-table th {
                text-align: left;
                font-size: 12px;
                color: #777;
                padding: 10px 0;
                border-bottom: 1px solid #eee;
            }

            .items-table td {
                font-size: 14px;
                color: #222;
                padding: 12px 0;
                border-bottom: 1px solid #f3f3f3;
            }

            .items-table .right {
                text-align: right;
            }

            .receipt-summary {
                display: flex;
                justify-content: flex-end;
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
                font-size: 18px;
                font-weight: 900;
                color: #111;
                border-top: 1px solid #eee;
                padding-top: 12px;
                margin-top: 10px;
            }

            .receipt-actions {
                display: flex;
                gap: 10px;
                margin-top: 22px;
            }

            .btn-back,
            .btn-print {
                padding: 12px 16px;
                border-radius: 10px;
                font-size: 13px;
                font-weight: 800;
                text-decoration: none;
                cursor: pointer;
            }

            .btn-back {
                background: #fff;
                border: 1px solid #e5e5e5;
                color: #555;
            }

            .btn-print {
                background: #e8192c;
                border: none;
                color: #fff;
            }

            @media print {
                .client-sidebar,
                .client-topbar,
                .receipt-actions {
                    display: none !important;
                }

                .receipt-card {
                    box-shadow: none;
                    border: none;
                }
            }

            @media (max-width: 700px) {
                .receipt-header,
                .receipt-grid {
                    grid-template-columns: 1fr;
                    display: grid;
                }

                .receipt-summary {
                    justify-content: stretch;
                }

                .summary-box {
                    width: 100%;
                }
            }
        </style>
    </x-slot>

    <div class="receipt-wrap">
        <div class="receipt-card">
            <div class="receipt-header">
                <div class="receipt-title">
                    <h2>Order confirmed</h2>
                    <p>Your payment was processed successfully.</p>
                </div>

                <div class="receipt-badge">
                    {{ $sale->receipt_number ?? 'WEB-' . str_pad($sale->id, 6, '0', STR_PAD_LEFT) }}
                </div>
            </div>

            <div class="receipt-grid">
                <div class="info-box">
                    <div class="info-label">Client</div>
                    <div class="info-value">{{ Auth::user()->name }}</div>
                </div>

                <div class="info-box">
                    <div class="info-label">Date</div>
                    <div class="info-value">{{ $sale->created_at->format('d/m/Y H:i') }}</div>
                </div>

                <div class="info-box">
                    <div class="info-label">Payment</div>
                    <div class="info-value">
                        Card ending in {{ $sale->card_last_four ?? '----' }}
                    </div>
                </div>

                <div class="info-box">
                    <div class="info-label">Order status</div>
                    <div class="info-value">{{ ucfirst($sale->order_status ?? 'pending') }}</div>
                </div>

                <div class="info-box">
                    <div class="info-label">Delivery method</div>
                    <div class="info-value">
                        {{ $sale->delivery_type === 'pickup' ? 'Store pickup' : 'Delivery' }}
                    </div>
                </div>

                <div class="info-box">
                    <div class="info-label">
                        {{ $sale->delivery_type === 'pickup' ? 'Pickup store' : 'Address' }}
                    </div>
                    <div class="info-value">
                        @if($sale->delivery_type === 'pickup')
                            {{ $sale->pickup_store ?? 'Minimarket Express - Main Store' }}
                        @else
                            {{ $sale->delivery_address }}
                        @endif
                    </div>
                </div>
            </div>

            <table class="items-table">
                <thead>
                    <tr>
                        <th>Product</th>
                        <th class="right">Qty</th>
                        <th class="right">Price</th>
                        <th class="right">Subtotal</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach($sale->details as $detail)
                        <tr>
                            <td>{{ $detail->product?->name ?? 'Product deleted' }}</td>
                            <td class="right">{{ $detail->quantity }}</td>
                            <td class="right">S/ {{ number_format($detail->price, 2) }}</td>
                            <td class="right">S/ {{ number_format($detail->subtotal, 2) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <div class="receipt-summary">
                <div class="summary-box">
                    <div class="summary-line">
                        <span>Subtotal</span>
                        <span>S/ {{ number_format($sale->total, 2) }}</span>
                    </div>

                    <div class="summary-line">
                        <span>Stars earned</span>
                        <span>+{{ floor($sale->total / 5) }} ⭐</span>
                    </div>

                    <div class="summary-total">
                        <span>Total</span>
                        <span>S/ {{ number_format($sale->total, 2) }}</span>
                    </div>
                </div>
            </div>

            <div class="receipt-actions">
                <a href="{{ url('/client/orders') }}" class="btn-back">
                    Back to orders
                </a>

                <button type="button" class="btn-print" onclick="window.print()">
                    Print receipt
                </button>
            </div>
        </div>
    </div>
</x-client-layout>