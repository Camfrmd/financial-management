<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index()
    {
        $incomeCategories = Category::whereNull('parent_id')
            ->where('type', 'income')
            ->with('children')
            ->orderBy('category_name')
            ->get();
            
        $expenseCategories = Category::whereNull('parent_id')
            ->where('type', 'expense')
            ->with('children')
            ->orderBy('category_name')
            ->get();

        return view('categories.index', compact('incomeCategories', 'expenseCategories'));
    }

    public function create()
    {
        $parentCategories = Category::whereNull('parent_id')->orderBy('category_name')->get();
        return view('categories.create', compact('parentCategories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'category_name' => 'required|string|max:50',
            'parent_id' => 'nullable|exists:categories,category_id',
            'type' => 'nullable|in:income,expense'
        ]);

        if ($request->filled('parent_id')) {
            $parent = Category::findOrFail($request->parent_id);
            if ($parent->parent_id !== null) {
                return back()->withErrors(['parent_id' => __('Cannot assign a sub-category as a parent.')])->withInput();
            }
            $type = $parent->type;
        } else {
            if (!$request->filled('type')) {
                return back()->withErrors(['type' => __('The type field is required for main categories.')])->withInput();
            }
            $type = $request->type;
        }

        Category::create([
            'category_name' => $request->category_name,
            'parent_id' => $request->parent_id ?: null,
            'type' => $type,
        ]);

        return redirect()->route('categories.index')->with('success', __('Category created successfully.'));
    }

    public function edit(Category $category)
    {
        // Only fetch valid parents (cannot be itself, must be a root category)
        $parentCategories = Category::whereNull('parent_id')
            ->where('category_id', '!=', $category->category_id)
            ->orderBy('category_name')
            ->get();
            
        return view('categories.edit', compact('category', 'parentCategories'));
    }

    public function update(Request $request, Category $category)
    {
        $request->validate([
            'category_name' => 'required|string|max:50',
            'parent_id' => 'nullable|exists:categories,category_id',
            'type' => 'nullable|in:income,expense'
        ]);

        if ($request->filled('parent_id')) {
            $parent = Category::findOrFail($request->parent_id);
            if ($parent->parent_id !== null) {
                return back()->withErrors(['parent_id' => __('Cannot assign a sub-category as a parent.')])->withInput();
            }
            if ($parent->category_id === $category->category_id) {
                return back()->withErrors(['parent_id' => __('Category cannot be its own parent.')])->withInput();
            }
            // Cannot assign a parent if this category itself has children
            if ($category->children()->count() > 0) {
                return back()->withErrors(['parent_id' => __('Cannot assign a parent to a category that already has sub-categories.')])->withInput();
            }
            $type = $parent->type;
        } else {
            if (!$request->filled('type')) {
                return back()->withErrors(['type' => __('The type field is required for main categories.')])->withInput();
            }
            $type = $request->type;
        }

        $category->update([
            'category_name' => $request->category_name,
            'parent_id' => $request->parent_id ?: null,
            'type' => $type,
        ]);

        // If a root category changes its type, update its children's type for consistency
        if ($category->parent_id === null) {
            $category->children()->update(['type' => $type]);
        }

        return redirect()->route('categories.index')->with('success', __('Category updated successfully.'));
    }

    public function destroy(Category $category)
    {
        if ($category->children()->count() > 0) {
            return back()->withErrors(__('Cannot delete a category that has sub-categories. Please delete them first.'));
        }

        if ($category->transactions()->count() > 0) {
            return back()->withErrors(__('Cannot delete a category that has associated transactions.'));
        }

        $category->delete();

        return redirect()->route('categories.index')->with('success', __('Category deleted successfully.'));
    }
}
