<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Customers Report</title>
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

<h1>Customers Report</h1>
<div class="subtitle">
    Customer loyalty report
</div>

<table>
    <thead>
        <tr>
            <th>Customer</th>
            <th>Email</th>
            <th>Stars</th>
            <th>Registered</th>
        </tr>
    </thead>

    <tbody>
        @forelse($customers as $client)
            @php
                $clientName = trim(($client->first_name ?? '') . ' ' . ($client->last_name ?? ''));
            @endphp

            <tr>
                <td>{{ $clientName ?: ($client->name ?? '-') }}</td>
                <td>{{ $client->email ?? '-' }}</td>
                <td>{{ $client->accumulated_stars ?? 0 }}</td>
                <td>{{ $client->created_at?->format('d/m/Y') ?? '-' }}</td>
            </tr>
        @empty
            <tr>
                <td colspan="4">No customers found.</td>
            </tr>
        @endforelse
    </tbody>
</table>

</body>
</html>