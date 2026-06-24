<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Purchases Report</title>
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

<h1>Purchases Report</h1>
<div class="subtitle">
    Period: {{ $startDate }} to {{ $endDate }}
</div>

<table>
    <thead>
        <tr>
            <th>Order</th>
            <th>Supplier</th>
            <th>Status</th>
            <th>Date</th>
            <th class="right">Total</th>
        </tr>
    </thead>

    <tbody>
        @forelse($purchases as $purchase)
            <tr>
                <td>PO-{{ str_pad($purchase->id, 5, '0', STR_PAD_LEFT) }}</td>
                <td>{{ $purchase->supplier?->company_name ?? '-' }}</td>
                <td>{{ ucfirst($purchase->status) }}</td>
                <td>{{ $purchase->created_at->format('d/m/Y') }}</td>
                <td class="right">S/ {{ number_format($purchase->total, 2) }}</td>
            </tr>
        @empty
            <tr>
                <td colspan="5">No purchase orders found for this period.</td>
            </tr>
        @endforelse
    </tbody>
</table>

</body>
</html>