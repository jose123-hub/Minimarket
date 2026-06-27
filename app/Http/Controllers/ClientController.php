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
            'star_progress_amount' => 0,
            'store_credit_balance' => 0,
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

        'payment_method' => 'required|in:card,store_credit',
        'payment_status' => 'required|in:paid',
        'card_last_four' => 'nullable|string|size:4',
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
            'star_progress_amount' => 0,
            'store_credit_balance' => 0,
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

    $subtotalItem = $price * $quantity;
    $total += $subtotalItem;

    $items[] = [
        'product' => $product,
        'quantity' => $quantity,
        'price' => $price,
        'subtotal' => $subtotalItem,
      ];
    }

        $subtotal = $total;

        $onlineDiscount = round($subtotal * 0.10, 2);

        $totalAfterOnlineDiscount = round($subtotal - $onlineDiscount, 2);

        $client = Client::where('user_id', $user->id)
            ->lockForUpdate()
            ->firstOrFail();

        $availableCredit = (float) ($client->store_credit_balance ?? 0);

        $storeCreditUsed = min($availableCredit, $totalAfterOnlineDiscount);

        $total = round($totalAfterOnlineDiscount - $storeCreditUsed, 2);

        if ($total > 0 && ($request->payment_method !== 'card' || empty($request->card_last_four))) {
        throw ValidationException::withMessages([
        'payment' => 'Card payment is required for this order.',
         ]);
        }

        $paymentMethod = $total <= 0 ? 'store_credit' : 'card';
        $cardLastFour = $total <= 0 ? null : $request->card_last_four;
        
        $previousProgressCents = (int) round(($client->star_progress_amount ?? 0) * 100);
        $totalCents = (int) round($total * 100);

        $starBaseCents = $previousProgressCents + $totalCents;

        $starsEarned = intdiv($starBaseCents, 500);
        $newProgressCents = $starBaseCents % 500;
        $newProgressAmount = $newProgressCents / 100;

        $sale = Sale::create([
            'customer_id' => $user->id,
            'cashier_id' => null,
            'cash_opening_id' => null,

            'total' => $total,
            'stars_earned' => $starsEarned,
            'discount' => $onlineDiscount,
            'store_credit_used' => $storeCreditUsed,
            'tax' => 0,

            'status' => 'completed',
            'order_status' => 'pending',

            'payment_method' => $paymentMethod,
            'payment_status' => 'paid',
            'card_last_four' => $cardLastFour,

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
        
        $client->update([
        'accumulated_stars' => $client->accumulated_stars + $starsEarned,
        'star_progress_amount' => $newProgressAmount,
        'store_credit_balance' => round($availableCredit - $storeCreditUsed, 2),
     ]);

    if ($starsEarned > 0) {
    StarHistory::create([
        'movement_type' => 'earned',
        'amount' => $starsEarned,
        'reason' => 'Online purchase - ' . ($sale->receipt_number ?? 'WEB-' . str_pad($sale->id, 6, '0', STR_PAD_LEFT)),
        'date' => now(),
        'client_id' => $client->id_cliente,
        'sale_id' => $sale->id,
     ]);
    }

        return $sale;
    });

    return redirect('/client/orders/' . $sale->id . '/receipt');
    }
}