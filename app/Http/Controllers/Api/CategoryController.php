<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categories = Category::with('transactions')->get();
        return response()->json(['status' => 'success', 'data' => $categories], 200);
    }
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'category_name' => 'required|string|max:255',
            'type' => 'required|in:income,expense'
        ]);

        $category = Category::create($validated);
        return response()->json(['status' => 'success', 'data' => $category], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $category = Category::with('transactions')->findOrFail($id);
        return response()->json(['status' => 'success', 'data' => $category], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $category = Category::findOrFail($id);

        $validated = $request->validate([
            'category_name' => 'sometimes|required|string|max:255',
            'type' => 'sometimes|required|in:income,expense'
        ]);

        $category->update($validated);
        return response()->json(['status' => 'success', 'data' => $category], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $category = Category::findOrFail($id);
        $category->delete();
        return response()->json(['status' => 'success', 'message' => 'Catégorie supprimée'], 200);
    }
}
