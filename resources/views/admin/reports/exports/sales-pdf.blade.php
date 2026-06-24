<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Sales Report</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
            color: #111;
        }

        h1 {
            font-size: 22px;
            margin-bottom: 4px;
        }

        .subtitle {
            color: #666;
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th {
            background: #111;
            color: #fff;
            padding: 8px;
            font-size: 10px;
            text-align: left;
        }

        td {
            border-bottom: 1px solid #ddd;
            padding: 7px;
            font-size: 10px;
        }

        .right {
            text-align: right;
        }
    </style>
</head>
<body>

<h1>Sales Report</h1>
<div class="subtitle">
    Period: {{ $startDate }} to {{ $endDate }}
</div>

<table>
    <thead>
        <tr>
            <th>Invoice</th>
            <th>Date</th>
            <th>Items</th>
            <th>Cashier</th>
            <th>Payment</th>
            <th class="right">Total</th>
        </tr>
    </thead>

    <tbody>
        @forelse($sales as $sale)
            <tr>
                <td>{{ $sale->invoice_number ?? 'B-' . str_pad($sale->id, 5, '0', STR_PAD_LEFT) }}</td>
                <td>{{ $sale->created_at->format('d/m/Y H:i') }}</td>
                <td>{{ $sale->details->count() }}</td>
                <td>{{ $sale->cashier?->name ?? '-' }}</td>
                <td>{{ ucfirst($sale->payment_method ?? 'Cash') }}</td>
                <td class="right">S/ {{ number_format($sale->total, 2) }}</td>
            </tr>
        @empty
            <tr>
                <td colspan="6">No sales found for this period.</td>
            </tr>
        @endforelse
    </tbody>
</table>

</body>
</html>