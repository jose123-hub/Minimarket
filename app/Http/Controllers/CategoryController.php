<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index(Request $request)
    {
        $categories = Category::withCount('products')
            ->whereNull('parent_id')
            ->with(['children' => function ($query) {
                $query->withCount('products')->orderBy('name');
            }])
            ->orderBy('name')
            ->get();

        if ($request->wantsJson()) {
            return response()->json($categories);
        }

        return view('categories.index', compact('categories'));
    }

    public function create()
    {
        return view('categories.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'        => 'required|string|max:255|unique:categories,name',
            'description' => 'nullable|string',
            'parent_id'   => 'nullable|integer|exists:categories,id',
        ]);

        if ($request->parent_id) {
            $parent = Category::find($request->parent_id);
            if ($parent && $parent->parent_id) {
                return $this->parentLevelError($request);
            }
        }

        $category = Category::create([
            'name'        => $request->name,
            'description' => $request->description,
            'parent_id'   => $request->parent_id,
        ]);

        if ($request->wantsJson()) {
            return response()->json($category->loadCount('products'), 201);
        }

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
        $request->validate([
            'name'        => 'required|string|max:255|unique:categories,name,' . $category->id,
            'description' => 'nullable|string',
            'parent_id'   => 'nullable|integer|exists:categories,id',
        ]);

        if ($request->parent_id) {
            if ((int) $request->parent_id === $category->id) {
                return $this->parentLevelError($request, 'A category cannot be its own parent.');
            }
            if ($category->children()->exists()) {
                return $this->parentLevelError($request, 'This category already has subcategories, so it cannot become a subcategory itself.');
            }

            $parent = Category::find($request->parent_id);
            if ($parent && $parent->parent_id) {
                return $this->parentLevelError($request);
            }
            if ($category->children()->where('id', $request->parent_id)->exists()) {
                return $this->parentLevelError($request, 'A category cannot become a child of its own subcategory.');
            }
        }

        $category->update([
            'name'        => $request->name,
            'description' => $request->description,
            'parent_id'   => $request->parent_id,
        ]);

        if ($request->wantsJson()) {
            return response()->json($category->loadCount('products'));
        }

        return redirect('/admin/categories')->with('success', 'updated category.');
    }

    public function destroy(Request $request, Category $category)
    {
        if ($category->products()->exists()) {
            return $this->blockedDeleteError($request, 'Cannot delete a category that still has products assigned to it.');
        }

        if ($category->children()->exists()) {
            return $this->blockedDeleteError($request, 'Cannot delete a category that still has subcategories. Remove or reassign them first.');
        }

        $category->delete();

        if ($request->wantsJson()) {
            return response()->json(['message' => 'Category deleted.']);
        }

        return redirect('/admin/categories')->with('success', 'category deleted.');
    }

    private function blockedDeleteError(Request $request, string $message)
    {
        if ($request->wantsJson()) {
            return response()->json(['message' => $message], 422);
        }

        return redirect('/admin/categories')->with('error', $message);
    }

    private function parentLevelError(Request $request, string $message = 'Categories can only be nested one level deep.')
    {
        if ($request->wantsJson()) {
            return response()->json([
                'message' => $message,
                'errors'  => ['parent_id' => [$message]],
            ], 422);
        }

        return redirect('/admin/categories')->with('error', $message);
    }
}