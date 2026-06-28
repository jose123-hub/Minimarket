<x-portal-layout
    title="Sales History"
    subtitle="Review your registered sales"
    active="sales"
>
    @push('portal-styles')
        <style>
            .history-card {
                background: #fff;
                border: 1px solid #eee;
                border-radius: 14px;
                overflow: hidden;
            }

            .history-toolbar {
                padding: 18px 22px;
                border-bottom: 1px solid #eee;
                display: flex;
                justify-content: space-between;
                align-items: center;
                gap: 12px;
                flex-wrap: wrap;
            }

            .history-toolbar h3 {
                font-size: 17px;
                font-weight: 900;
                color: #111;
                margin: 0;
            }

            .filters {
                display: flex;
                gap: 10px;
                flex-wrap: wrap;
            }

            .filters input,
            .filters select {
                border: 1px solid #e5e5e5;
                border-radius: 9px;
                padding: 9px 12px;
                font-size: 13px;
                outline: none;
            }

            .filters button,
            .filters a {
                border: none;
                border-radius: 9px;
                padding: 9px 14px;
                font-size: 13px;
                font-weight: 800;
                cursor: pointer;
                text-decoration: none;
            }

            .btn-filter {
                background: #e8192c;
                color: #fff;
            }

            .btn-clear-filter {
                background: #f5f5f5;
                color: #555;
            }

            .history-table {
                width: 100%;
                border-collapse: collapse;
            }

            .history-table th {
                padding: 16px 22px;
                font-size: 12px;
                color: #999;
                text-align: left;
                border-bottom: 1px solid #eee;
                text-transform: uppercase;
            }

            .history-table td {
                padding: 16px 22px;
                font-size: 14px;
                color: #111;
                border-bottom: 1px solid #f5f5f5;
            }

            .badge-method {
                display: inline-flex;
                padding: 5px 9px;
                border-radius: 999px;
                font-size: 11px;
                font-weight: 900;
                background: #f5f5f5;
                color: #444;
            }

            .history-total {
                font-weight: 900;
                text-align: right;
            }

            .empty-history {
                padding: 36px 22px;
                text-align: center;
                color: #aaa;
                font-size: 14px;
            }

            .pagination-box {
                padding: 16px 22px;
            }
        </style>
    @endpush

    <div class="history-card">
        <div class="history-toolbar">
            <h3>My sales</h3>

            <form method="GET" action="{{ route('cashier.sales.history') }}" class="filters">
                <input type="date" name="date" value="{{ request('date') }}">

                <select name="payment_method">
                    <option value="">All methods</option>
                    <option value="cash" {{ request('payment_method') === 'cash' ? 'selected' : '' }}>Cash</option>
                    <option value="card" {{ request('payment_method') === 'card' ? 'selected' : '' }}>Card</option>
                    <option value="yape" {{ request('payment_method') === 'yape' ? 'selected' : '' }}>Yape</option>
                    <option value="plin" {{ request('payment_method') === 'plin' ? 'selected' : '' }}>Plin</option>
                </select>

                <button type="submit" class="btn-filter">
                    Filter
                </button>

                <a href="{{ route('cashier.sales.history') }}" class="btn-clear-filter">
                    Clear
                </a>
            </form>
        </div>

        @if($sales->count() > 0)
            <table class="history-table">
                <thead>
                    <tr>
                        <th>Sale</th>
                        <th>Customer</th>
                        <th>Method</th>
                        <th>Date</th>
                        <th style="text-align:right;">Total</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach($sales as $sale)
                        <tr>
                            <td>
                                <strong>{{ $sale->invoice_number ?? '#' . $sale->id }}</strong>
                            </td>

                            <td>
                                {{ $sale->customer?->name ?? 'Customer' }}
                            </td>

                            <td>
                                <span class="badge-method">
                                    {{ ucfirst($sale->payment_method ?? 'cash') }}
                                </span>
                            </td>

                            <td>
                                {{ $sale->created_at->format('d/m/Y h:i A') }}
                            </td>

                            <td class="history-total">
                                S/ {{ number_format($sale->total, 2) }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <div class="pagination-box">
                {{ $sales->links() }}
            </div>
        @else
            <div class="empty-history">
                No sales found.
            </div>
        @endif
    </div>
</x-portal-layout>