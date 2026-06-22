<x-admin-layout title="Sales History">

    <h1>Sales History</h1>

    <table border="1" cellpadding="10">

        <tr>
            <th>ID</th>
            <th>Customer</th>
            <th>Cashier</th>
            <th>Total</th>
            <th>Date</th>
        </tr>

        @foreach($sales as $sale)

        <tr>
            <td>{{ $sale->id }}</td>

            <td>{{ $sale->customer->name }}</td>

            <td>{{ $sale->cashier->name }}</td>

            <td>S/{{ $sale->total }}</td>

            <td>{{ $sale->created_at }}</td>
        </tr>

        @endforeach

    </table>

</x-admin-layout>