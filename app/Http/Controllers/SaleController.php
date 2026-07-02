<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Sale;
use App\Models\SaleDetail;
use App\Models\User;
use App\Models\Client;
use App\Models\StarHistory;
use App\Models\PromotionCode;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\DB;

class SaleController extends Controller
{
    public function index()
    {
        $sales = Sale::with(['customer', 'cashier'])->latest()->get();
        return view('admin.sales.index', compact('sales'));
    }

    public function create(Request $request)
    {
    $products = Product::with('category.parent')->get();
    $customers = User::whereHas('roleInfo', fn($q) => $q->where('name', 'client'))->orderBy('name')->get();
    $clientsByUserId = Client::whereIn('user_id', $customers->pluck('id'))->get()->keyBy('user_id');
    $mainCategories = \App\Models\Category::with(['children' => function ($query) {$query->orderBy('name');
    }])
      ->whereNull('parent_id')
      ->orderBy('name')
      ->get();
    $orderItems = collect();

    if ($request->has('order_id')) {
        $order = Sale::with('details.product')->find($request->order_id);
        if ($order && $order->status === 'pending') {
            $orderItems = $order->details;
        }
    }

    $recentSales = Sale::with('details')
        ->where('cashier_id', Auth::id())
        ->whereDate('created_at', now())
        ->latest()
        ->take(20)
        ->get()
        ->map(fn ($sale) => [
            'id'             => $sale->id,
            'invoice_number' => $sale->invoice_number ?? ('B-' . str_pad($sale->id, 5, '0', STR_PAD_LEFT)),
            'total'          => $sale->total,
            'items'          => $sale->details->count(),
            'time'           => $sale->created_at->format('h:i A'),
        ]);
    
    $receiptSale = null;

    if (session('receipt_sale_id')) {
    $receiptSale = Sale::with(['details.product', 'customer'])
        ->where('cashier_id', Auth::id())
        ->find(session('receipt_sale_id'));
    }

    return view('cashier.sales.create', compact('products', 'customers', 'mainCategories', 'orderItems', 'receiptSale', 'clientsByUserId', 'recentSales'));
    }

    public function store(Request $request)
    {
    $request->validate([
        'customer_id' => 'required|exists:users,id',
        'payment_method' => 'required|in:cash,card,yape,plin',
        'payment_reference' => 'required_unless:payment_method,cash|nullable|string|min:4|max:50',
        'promo_code' => 'nullable|string|max:20',
        'cash_received' => 'required_if:payment_method,cash|nullable|numeric|min:0',
        'products' => 'required|array|min:1',
        'products.*.product_id' => 'required|integer|exists:products,id',
        'products.*.quantity' => 'required|integer|min:1',
    ]);

    $opening = \App\Models\CashOpening::where('user_id', Auth::id())
        ->where('status', 'open')
        ->first();

    if (!$opening) {
        return redirect()
            ->route('cashier.cash')
            ->with('error', 'You must open the cash register before registering a sale.');
    }

    DB::beginTransaction();

    try {
        $customer = User::findOrFail($request->customer_id);
        $isGenericCustomer = strtolower($customer->email) === 'cliente@example.com';

        $sale = Sale::create([
            'customer_id' => $customer->id,
            'cashier_id' => Auth::id(),
            'cash_opening_id' => $opening->id,

            'total' => 0,
            'stars_earned' => 0,
            'discount' => 0,
            'store_credit_used' => 0,
            'tax' => 0,

            'status' => 'completed',
            'order_status' => 'delivered',

            'payment_method' => $request->payment_method,
            'payment_reference' => null,
            'promo_code' => null,
            'cash_received' => null,
            'cash_change' => null,

            'payment_status' => 'paid',
            'card_last_four' => null,

            'voucher_type' => 'receipt',
        ]);

        $subtotal = 0;

        foreach ($request->products as $item) {
            $product = Product::where('id', $item['product_id'])
                ->lockForUpdate()
                ->firstOrFail();

            $quantity = (int) $item['quantity'];

            if ($quantity > $product->stock) {
                throw new \Exception("Not enough stock for {$product->name}.");
            }

            $unitPrice = method_exists($product, 'finalPrice')
                ? $product->finalPrice()
                : $product->price;

            $lineSubtotal = round($unitPrice * $quantity, 2);
            $subtotal += $lineSubtotal;
            $promoCodeText = strtoupper(trim($request->promo_code ?? ''));
            $discount = 0;
            $promotionCode = null;

            if ($promoCodeText !== '') {
            $promotionCode = PromotionCode::where('code', $promoCodeText)->first();

            if (!$promotionCode) {
            return back()->with('error', 'Invalid promotion code.');
            }

            if (!$promotionCode->isAvailableFor($request->payment_method, $subtotal)) {
            return back()->with('error', 'Promotion code is not available for this sale.');
            }

            $discount = $promotionCode->calculateDiscount($subtotal);
            }

            SaleDetail::create([
                'sale_id' => $sale->id,
                'product_id' => $product->id,
                'quantity' => $quantity,
                'price' => $unitPrice,
                'subtotal' => $lineSubtotal,
            ]);

            $product->decrement('stock', $quantity);
        }

        $subtotal = round($subtotal, 2);

        $promoCode = $request->filled('promo_code')
            ? strtoupper(trim($request->promo_code))
            : null;

        $promoDiscount = 0;

        $validPromoCodes = [
            'YAPE10' => [
                'method' => 'yape',
                'percent' => 10,
            ],
            'PLIN5' => [
                'method' => 'plin',
                'percent' => 5,
            ],
        ];

        if ($promoCode) {
            if (!isset($validPromoCodes[$promoCode])) {
                throw ValidationException::withMessages([
                    'promo_code' => 'Invalid promotional code.',
                ]);
            }

            $promo = $validPromoCodes[$promoCode];

            if ($promo['method'] !== $request->payment_method) {
                throw ValidationException::withMessages([
                    'promo_code' => 'This promotional code is not valid for the selected payment method.',
                ]);
            }

            $promoDiscount = round($subtotal * ($promo['percent'] / 100), 2);
        }

        $total = round($subtotal - $promoDiscount, 2);

        $roundingAdjustment = 0;

        if ($request->payment_method === 'cash') {
         $roundedTotal = round($total * 10) / 10;
         $roundingAdjustment = round($roundedTotal - $total, 2);
         $total = round($roundedTotal, 2);
        }

        $paymentReference = null;
        $cashReceived = null;
        $cashChange = null;

        if ($request->payment_method === 'cash') {
            $cashReceived = (float) $request->cash_received;

            if ($cashReceived <= 0) {
                throw ValidationException::withMessages([
                    'payment' => 'Cash received is required.',
                ]);
            }

            if ($cashReceived < $total) {
                throw ValidationException::withMessages([
                    'payment' => 'Cash received is less than the sale total.',
                ]);
            }

            $cashChange = round($cashReceived - $total, 2);
        } else {
            if (!$request->filled('payment_reference')) {
                throw ValidationException::withMessages([
                    'payment' => 'Payment operation code or voucher is required.',
                ]);
            }

            $paymentReference = trim($request->payment_reference);
        }

        $starsEarned = 0;
        $newProgressAmount = 0;

        if (!$isGenericCustomer) {
            $client = Client::where('user_id', $customer->id)
                ->lockForUpdate()
                ->first();

            if ($client) {
                $previousProgressCents = (int) round(((float) ($client->star_progress_amount ?? 0)) * 100);
                $totalCents = (int) round($total * 100);

                $starBaseCents = $previousProgressCents + $totalCents;

                $starsEarned = intdiv($starBaseCents, 500);
                $newProgressCents = $starBaseCents % 500;
                $newProgressAmount = $newProgressCents / 100;

                $client->update([
                    'accumulated_stars' => $client->accumulated_stars + $starsEarned,
                    'star_progress_amount' => $newProgressAmount,
                ]);

                if ($starsEarned > 0) {
                    StarHistory::create([
                        'movement_type' => 'earned',
                        'amount' => $starsEarned,
                        'reason' => 'Cashier sale - Sale #' . $sale->id,
                        'date' => now(),
                        'client_id' => $client->id_cliente,
                        'sale_id' => $sale->id,
                    ]);
                }
            }
        }

        $sale->update([
            'invoice_number' => 'B-' . str_pad($sale->id, 6, '0', STR_PAD_LEFT),
            'total' => $total,
            'discount' => $promoDiscount,
            'stars_earned' => $starsEarned,
            'payment_reference' => $paymentReference,
            'promo_code' => $promoCode,
            'cash_received' => $cashReceived,
            'cash_change' => $cashChange,
            'rounding_adjustment' => $roundingAdjustment,
        ]);

        DB::commit();

        $message = 'Sale registered successfully.';

        if ($isGenericCustomer) {
            $message .= ' Generic customer does not earn stars.';
        } else {
            $message .= ' +' . $starsEarned . ' stars earned.';
            $message .= ' Progress: S/ ' . number_format($newProgressAmount, 2) . ' / S/ 5.00.';
        }

        return redirect()
            ->route('sales.create')
            ->with('success', $message)
            ->with('receipt_sale_id', $sale->id);

    } catch (\Exception $e) {
        DB::rollBack();

        return redirect()
            ->back()
            ->withInput()
            ->with('error', 'Error registering sale: ' . $e->getMessage());
      }
    }
    public function history(Request $request)
    {
    $query = Sale::with('customer')
        ->where('cashier_id', Auth::id())
        ->latest();

    if ($request->filled('date')) {
        $query->whereDate('created_at', $request->date);
    }

    if ($request->filled('payment_method')) {
        $query->where('payment_method', $request->payment_method);
    }

    $sales = $query->paginate(12)->withQueryString();

    return view('cashier.sales.history', compact('sales'));
    }
}