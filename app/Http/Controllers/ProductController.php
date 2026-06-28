<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::with('category.parent')->get();
        $categories = Category::withCount('children')
            ->orderByRaw('COALESCE(parent_id, id), parent_id IS NOT NULL, name')
            ->get();
        $categoriesForJs = $categories->map(function ($c) {
            return [
                'id' => $c->id,
                'name' => $c->name,
                'parent_id' => $c->parent_id,
                'has_children' => $c->children_count > 0,
            ];
        });
        $suppliers = \App\Models\Supplier::where('status', 'active')->get();

        return view('products.index', compact('products', 'categories', 'categoriesForJs', 'suppliers'));
    }

    public function create()
    {
    $categories = Category::all();
    $suppliers  = \App\Models\Supplier::where('status', 'active')->get();
    return view('products.create', compact('categories', 'suppliers'));
    }

    public function store(Request $request)
    {
    $request->validate([
        'category_id'  => [
            'required',
            'exists:categories,id',
            function ($attribute, $value, $fail) {
                $category = Category::withCount('children')->find($value);
                if ($category && !$category->parent_id && $category->children_count > 0) {
                    $fail('Please choose a specific subcategory instead of a parent category.');
                }
            },
        ],
        'supplier_id'  => 'nullable|exists:suppliers,id',
        'name'         => 'required|string|max:255',
        'description'  => 'nullable|string',
        'price'        => 'required|numeric|min:0',
        'cost'         => 'nullable|numeric|min:0',
        'stock'        => 'required|integer|min:0',
        'min_stock'    => 'nullable|integer|min:0',
        'image'        => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
    ]);

    $imagePath = null;
    if ($request->hasFile('image')) {
        $imagePath = $request->file('image')->store('products', 'public');
    }

    Product::create([
        'category_id' => $request->category_id,
        'supplier_id' => $request->supplier_id,
        'name'        => $request->name,
        'description' => $request->description,
        'price'       => $request->price,
        'cost'        => $request->cost ?? 0,
        'stock'       => $request->stock,
        'min_stock'   => $request->min_stock ?? 5,
        'image'       => $imagePath,
    ]);

    return redirect('/admin/products')->with('success', 'Product created successfully.');
    }
 
    public function show(Product $product)
    {
        //
    }
 
    public function edit(Product $product)
    {
    $categories = Category::withCount('children')
        ->orderByRaw('COALESCE(parent_id, id), parent_id IS NOT NULL, name')
        ->get();
    $suppliers  = \App\Models\Supplier::where('status', 'active')->get();
    return view('products.edit', compact('product', 'categories', 'suppliers'));
    }
 
    public function update(Request $request, Product $product)
    {
    $request->validate([
        'category_id'  => [
            'required',
            'exists:categories,id',
            function ($attribute, $value, $fail) {
                $category = Category::withCount('children')->find($value);
                if ($category && !$category->parent_id && $category->children_count > 0) {
                    $fail('Please choose a specific subcategory instead of a parent category.');
                }
            },
        ],
        'supplier_id'  => 'nullable|exists:suppliers,id',
        'name'         => 'required|string|max:255',
        'description'  => 'nullable|string',
        'price'        => 'required|numeric|min:0',
        'cost'         => 'nullable|numeric|min:0',
        'stock'        => 'required|integer|min:0',
        'min_stock'    => 'nullable|integer|min:0',
        'image'        => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
    ]);

    $imagePath = $product->image;
    if ($request->hasFile('image')) {
        if ($product->image) {
            Storage::disk('public')->delete($product->image);
        }
        $imagePath = $request->file('image')->store('products', 'public');
    }

    $product->update([
        'category_id' => $request->category_id,
        'supplier_id' => $request->supplier_id,
        'name'        => $request->name,
        'description' => $request->description,
        'price'       => $request->price,
        'cost'        => $request->cost ?? 0,
        'stock'       => $request->stock,
        'min_stock'   => $request->min_stock ?? 5,
        'image'       => $imagePath,
    ]);

    return redirect('/admin/products')->with('success', 'Product updated successfully.');
    }
 
    public function destroy(Product $product)
    {
        if ($product->image) {
            Storage::disk('public')->delete($product->image);
        }

        $product->delete();
        return redirect('/admin/products')->with('success', 'product removed.');
    }
    public function cashierInventory()
    {
    $products = Product::with('category.parent')
        ->orderBy('name')
        ->get();

    $mainCategories = Category::with(['children' => function ($query) {
            $query->orderBy('name');
        }])
        ->whereNull('parent_id')
        ->orderBy('name')
        ->get();

    return view('cashier.inventory', compact('products', 'mainCategories'));
    }
}