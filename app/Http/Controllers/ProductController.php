<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::with('category')->get();
        return view('products.index', compact('products'));
    }

    public function create()
    {
        $categories = Category::all();
        return view('products.create', compact('categories'));
    }

    public function store(Request $request)
    {
        Product::create([
            'category_id' => $request->category_id,
            'name'      => $request->name,
            'description' => $request->description,
            'price'      => $request->price,
            'stock'       => $request->stock,
        ]);

        return redirect('/admin/products')->with('success', 'created product.');
    }

    public function show(Product $product)
    {
        //
    }

    public function edit(Product $product)
    {
        $categories = Category::all();
        return view('products.edit', compact('product', 'categories'));
    }

    public function update(Request $request, Product $product)
    {
        $product->update([
            'category_id' => $request->category_id,
            'name'      => $request->name,
            'description' => $request->description,
            'price'      => $request->price,
            'stock'       => $request->stock,
        ]);

        return redirect('/admin/products')->with('success', 'product updated.');
    }

    public function destroy(Product $product)
    {
        $product->delete();
        return redirect('/admin/products')->with('success', 'product removed.');
    }
    public function cashierInventory()
    {
    $products = Product::with('category')->get();
    $categories = \App\Models\Category::all();
    return view('cashier.inventory', compact('products', 'categories'));
    }
}