<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Models\Sale;
use App\Models\SaleDetail;
use App\Models\Client;
use App\Models\StarHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class ClientController extends Controller
{
    public function catalog()
    {
        $products = Product::with('category')->where('stock', '>', 0)->get();
        $categories = Category::all();
        $user = Auth::user();

        $client = Client::firstOrCreate(
          ['user_id' => $user->id],
           [
            'first_name' => $user->name,
            'last_name' => '',
            'email' => $user->email,
            'type' => 'regular',
            'accumulated_stars' => 0,
           ]
        );
        $products = Product::with('category.parent')
          ->where('stock', '>', 0)
          ->get();

        $mainCategories = Category::with('children')
          ->whereNull('parent_id')
          ->orderBy('name')
          ->get();
        return view('client.catalog', compact('products', 'mainCategories', 'client'));
    }

    public function store(Request $request)
    {
    $request->validate([
        'products' => 'required|array|min:1',
        'products.*.product_id' => 'required|exists:products,id',
        'products.*.quantity' => 'required|integer|min:1',

        'delivery_type' => 'required|in:delivery,pickup',

        'delivery_address' => 'required_if:delivery_type,delivery|nullable|string|max:255',
        'delivery_reference' => 'required_if:delivery_type,delivery|nullable|string|max:255',
        'delivery_phone' => 'required_if:delivery_type,delivery|nullable|string|max:20',

        'pickup_store' => 'required_if:delivery_type,pickup|nullable|string|max:255',
        'pickup_note' => 'nullable|string|max:255',

        'payment_method' => 'required|in:card',
        'payment_status' => 'required|in:paid',
        'card_last_four' => 'required|string|size:4',
    ]);

    $user = Auth::user();

    $client = Client::firstOrCreate(
        ['user_id' => $user->id],
        [
            'first_name' => $user->name,
            'last_name' => '',
            'email' => $user->email,
            'type' => 'regular',
            'accumulated_stars' => 0,
        ]
    );

    $sale = DB::transaction(function () use ($request, $user, $client) {
        $total = 0;
        $items = [];

        foreach ($request->products as $item) {
            $product = Product::where('id', $item['product_id'])
                ->lockForUpdate()
                ->firstOrFail();

            $quantity = (int) $item['quantity'];

            if ($product->stock < $quantity) {
                throw ValidationException::withMessages([
                    'stock' => 'Not enough stock for ' . $product->name,
                ]);
            }

            $price = method_exists($product, 'finalPrice')
                ? $product->finalPrice()
                : $product->price;

            $subtotal = $price * $quantity;
            $total += $subtotal;

            $items[] = [
                'product' => $product,
                'quantity' => $quantity,
                'price' => $price,
                'subtotal' => $subtotal,
            ];
        }

        $sale = Sale::create([
            'customer_id' => $user->id,
            'cashier_id' => null,
            'cash_opening_id' => null,

            'total' => $total,
            'discount' => 0,
            'tax' => 0,

            'status' => 'completed',
            'order_status' => 'pending',

            'payment_method' => 'card',
            'payment_status' => 'paid',
            'card_last_four' => $request->card_last_four,

            'voucher_type' => 'receipt',

            'delivery_type' => $request->delivery_type,
            'delivery_address' => $request->delivery_type === 'delivery' ? $request->delivery_address : null,
            'delivery_reference' => $request->delivery_type === 'delivery' ? $request->delivery_reference : null,
            'delivery_phone' => $request->delivery_type === 'delivery' ? $request->delivery_phone : null,

            'pickup_store' => $request->delivery_type === 'pickup' ? $request->pickup_store : null,
            'pickup_note' => $request->delivery_type === 'pickup' ? $request->pickup_note : null,
        ]);

        $sale->update([
            'receipt_number' => 'WEB-' . str_pad($sale->id, 6, '0', STR_PAD_LEFT),
        ]);

        foreach ($items as $item) {
            SaleDetail::create([
                'sale_id' => $sale->id,
                'product_id' => $item['product']->id,
                'quantity' => $item['quantity'],
                'price' => $item['price'],
                'subtotal' => $item['subtotal'],
            ]);

            $item['product']->decrement('stock', $item['quantity']);
        }

        $starsEarned = floor($total / 5);

        $client->increment('accumulated_stars', $starsEarned);

        return $sale;
    });

    return redirect('/client/orders/' . $sale->id . '/receipt');
    }
}