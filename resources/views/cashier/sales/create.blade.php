<x-app-layout>

    <h1>Register Sale</h1>

    <form action="{{ route('sales.store') }}" method="POST">
        @csrf

        <label>Customer</label>

        <select name="customer_id" required>
            @foreach($customers as $customer)
                <option value="{{ $customer->id }}">
                    {{ $customer->name }}
                </option>
            @endforeach
        </select>

        <br><br>

        <label>Product</label>

        <select name="products[0][product_id]">
            @foreach($products as $product)
                <option value="{{ $product->id }}">
                    {{ $product->nombre }}
                    - S/{{ $product->precio }}
                </option>
            @endforeach
        </select>

        <br><br>

        <label>Quantity</label>

        <input
            type="number"
            name="products[0][quantity]"
            min="1"
            required
        >

        <br><br>

        <button type="submit">
            Register Sale
        </button>

    </form>

</x-app-layout>