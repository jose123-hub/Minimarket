<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Inventory Report</title>
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

<h1>Inventory Report</h1>
<div class="subtitle">
    Generated from reports module
</div>

<table>
    <thead>
        <tr>
            <th>Product</th>
            <th>Category</th>
            <th>Stock</th>
            <th>Minimum</th>
            <th>Status</th>
        </tr>
    </thead>

    <tbody>
        @forelse($products as $product)
            <tr>
                <td>{{ $product->name }}</td>
                <td>{{ $product->category?->name ?? '-' }}</td>
                <td>{{ $product->stock }}</td>
                <td>{{ $product->min_stock }}</td>
                <td>
                    @if($product->stock <= 0)
                        Out of stock
                    @elseif($product->stock <= $product->min_stock)
                        Low stock
                    @else
                        Normal stock
                    @endif
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="5">No products found.</td>
            </tr>
        @endforelse
    </tbody>
</table>

</body>
</html>