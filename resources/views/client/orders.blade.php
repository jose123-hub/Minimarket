<x-client-layout
    title="My Orders"
    active="orders"
    :client="$client"
>
    <x-slot name="styles">
        <style>
            .orders-summary {
                display: grid;
                grid-template-columns: repeat(3, minmax(0, 1fr));
                gap: 16px;
                margin-bottom: 24px;
            }

            .summary-card {
                background: #fff;
                border: 1px solid #eee;
                border-radius: 14px;
                padding: 22px;
            }

            .summary-label {
                font-size: 13px;
                color: #777;
                margin-bottom: 8px;
            }

            .summary-value {
                font-size: 30px;
                font-weight: 900;
                color: #111;
            }

            .orders-list {
                display: flex;
                flex-direction: column;
                gap: 14px;
            }

            .order-card {
                background: #fff;
                border: 1px solid #eee;
                border-radius: 14px;
                padding: 20px;
            }

            .order-header {
                display: flex;
                justify-content: space-between;
                gap: 14px;
                align-items: flex-start;
                margin-bottom: 14px;
                padding-bottom: 14px;
                border-bottom: 1px solid #f3f3f3;
            }

            .order-title {
                font-size: 16px;
                font-weight: 900;
                color: #111;
            }

            .order-date {
                font-size: 13px;
                color: #888;
                margin-top: 3px;
            }

            .order-total {
                text-align: right;
                font-size: 18px;
                font-weight: 900;
                color: #e8192c;
            }

            .order-items {
                display: flex;
                flex-direction: column;
                gap: 8px;
            }

            .order-item {
                display: flex;
                justify-content: space-between;
                align-items: center;
                font-size: 14px;
                color: #444;
            }

            .item-name {
                font-weight: 700;
                color: #222;
            }

            .item-meta {
                font-size: 12px;
                color: #999;
                margin-top: 2px;
            }

            .empty-box {
                background: #fff;
                border: 1px dashed #ddd;
                border-radius: 16px;
                padding: 60px 20px;
                text-align: center;
                color: #777;
            }

            .empty-box svg {
                width: 44px;
                height: 44px;
                stroke: #aaa;
                fill: none;
                stroke-width: 1.8;
                margin-bottom: 12px;
            }

            .empty-box h3 {
                color: #111;
                margin-bottom: 6px;
            }

            @media (max-width: 800px) {
                .orders-summary {
                    grid-template-columns: 1fr;
                }

                .order-header {
                    flex-direction: column;
                }

                .order-total {
                    text-align: left;
                }
            }
        </style>
    </x-slot>

    <div class="page-title">
        <div class="page-icon">
            <svg viewBox="0 0 24 24">
                <path d="M21 16V8a2 2 0 00-1-1.73l-7-4a2 2 0 00-2 0l-7 4A2 2 0 003 8v8a2 2 0 001 1.73l7 4a2 2 0 002 0l7-4A2 2 0 0021 16z"/>
            </svg>
        </div>

        <div>
            <h1>My orders</h1>
            <p>Check your registered purchases at Minimarket Express.</p>
        </div>
    </div>

    <div class="orders-summary">
        <div class="summary-card">
            <div class="summary-label">Total orders</div>
            <div class="summary-value">{{ $sales->count() }}</div>
        </div>

        <div class="summary-card">
            <div class="summary-label">Total purchased</div>
            <div class="summary-value">
                S/ {{ number_format($sales->sum('total'), 2) }}
            </div>
        </div>

        <div class="summary-card">
            <div class="summary-label">Current stars</div>
            <div class="summary-value">
                ★ {{ $client->accumulated_stars }}
            </div>
        </div>
    </div>

    @if($sales->count() > 0)
        <div class="orders-list">
            @foreach($sales as $sale)
                <div class="order-card">
                    <div class="order-header">
                        <div>
                            <div class="order-title">
                                Order #{{ $sale->invoice_number ?? 'B-' . str_pad($sale->id, 5, '0', STR_PAD_LEFT) }}
                            </div>

                            <div class="order-date">
                                {{ $sale->created_at->format('d/m/Y H:i') }}
                            </div>
                        </div>

                        <div class="order-total">
                            S/ {{ number_format($sale->total, 2) }}
                        </div>
                    </div>

                    <div class="order-items">
                        @foreach($sale->details as $detail)
                            <div class="order-item">
                                <div>
                                    <div class="item-name">
                                        {{ $detail->product?->name ?? 'Product removed' }}
                                    </div>

                                    <div class="item-meta">
                                        {{ $detail->quantity }} unidad(es) × S/ {{ number_format($detail->price, 2) }}
                                    </div>
                                </div>

                                <strong>
                                    S/ {{ number_format($detail->subtotal, 2) }}
                                </strong>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="empty-box">
            <svg viewBox="0 0 24 24">
                <path d="M6 2L3 6v14a2 2 0 002 2h14a2 2 0 002-2V6l-3-4z"/>
                <line x1="3" y1="6" x2="21" y2="6"/>
            </svg>

            <h3>You don't have any orders yet</h3>
            <p>When you make a purchase, it will show up here.</p>
        </div>
    @endif
</x-client-layout>