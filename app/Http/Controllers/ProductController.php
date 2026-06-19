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
        $categories = Category::all();
        return view('products.index', compact('products', 'categories'));
    }

    public function create()
    {
        $categories = Category::all();
        return view('products.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name'        => 'required|string|max:255',
            'description' => 'nullable|string',
            'price'       => 'required|numeric|min:0',
            'cost'        => 'nullable|numeric|min:0',
            'stock'       => 'required|integer|min:0',
            'min_stock'   => 'nullable|integer|min:0',
        ]);

        Product::create([
            'category_id' => $request->category_id,
            'name'      => $request->name,
            'description' => $request->description,
            'price'      => $request->price,
            'cost'       => $request->cost ?? 0,
            'stock'       => $request->stock,
            'min_stock'   => $request->min_stock ?? 5,
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
        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name'        => 'required|string|max:255',
            'description' => 'nullable|string',
            'price'       => 'required|numeric|min:0',
            'cost'        => 'nullable|numeric|min:0',
            'stock'       => 'required|integer|min:0',
            'min_stock'   => 'nullable|integer|min:0',
        ]);

        $product->update([
            'category_id' => $request->category_id,
            'name'      => $request->name,
            'description' => $request->description,
            'price'      => $request->price,
            'cost'       => $request->cost ?? 0,
            'stock'       => $request->stock,
            'min_stock'   => $request->min_stock ?? 5,
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