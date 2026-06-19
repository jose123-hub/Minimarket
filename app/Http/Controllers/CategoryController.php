<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::all();
        return view('categories.index', compact('categories'));
    }

    public function create()
    {
        return view('categories.create');
    }

    public function store(Request $request)
    {
        Category::create([
            'name'      => $request->name,
            'description' => $request->description,
        ]);

        return redirect('/admin/categories')->with('success', 'category created.');
    }

    public function show(Category $category)
    {
        //
    }

    public function edit(Category $category)
    {
        return view('categories.edit', compact('category'));
    }

    public function update(Request $request, Category $category)
    {
        $category->update([
            'name'      => $request->name,
            'description' => $request->description,
        ]);

        return redirect('/admin/categories')->with('success', 'updated category.');
    }

    public function destroy(Category $category)
    {
        $category->delete();
        return redirect('/admin/categories')->with('success', 'category deleted.');
    }
}