<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ClientController extends Controller
{
    public function catalog()
    {
        $products = Product::with('category')->where('stock', '>', 0)->get();
        $categories = Category::all();
        return view('client.catalog', compact('products', 'categories'));
    }

    public function store(Request $request)
    {
        return redirect()->back()->with('success', 'Order placed successfully.');
    }
}