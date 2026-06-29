<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $transactions = Transaction::with(['category', 'fund', 'creator', 'validator', 'contributions'])->get();
        return response()->json(['status' => 'success', 'data' => $transactions], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'category_id' => 'required|exists:categories,category_id',
            'fund_id' => 'required|exists:funds,fund_id',
            'user_id' => 'required|exists:users,user_id',
            'type' => 'required|in:income,expense',
            'amount' => 'required|numeric|min:0',
            'date' => 'required|date',
            'description' => 'required|string',
            'receipt_path' => 'nullable|string',
            'validation_status' => 'in:pending,validated,rejected',
            'validated_by' => 'nullable|exists:users,user_id'
        ]);

        $transaction = Transaction::create($validated);
        return response()->json(['status' => 'success', 'data' => $transaction], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $transaction = Transaction::with(['category', 'fund', 'creator', 'validator', 'contributions'])->findOrFail($id);
        return response()->json(['status' => 'success', 'data' => $transaction], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $transaction = Transaction::findOrFail($id);

        \Illuminate\Support\Facades\Gate::authorize('update', $transaction);

        $validated = $request->validate([
            'category_id' => 'sometimes|required|exists:categories,category_id',
            'fund_id' => 'sometimes|required|exists:funds,fund_id',
            'user_id' => 'sometimes|required|exists:users,user_id',
            'type' => 'sometimes|required|in:income,expense',
            'amount' => 'sometimes|required|numeric|min:0',
            'date' => 'sometimes|required|date',
            'description' => 'sometimes|required|string',
            'receipt_path' => 'nullable|string',
            'validation_status' => 'sometimes|in:pending,validated,rejected',
            'validated_by' => 'nullable|exists:users,user_id'
        ]);

        $transaction->update($validated);
        return response()->json(['status' => 'success', 'data' => $transaction], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $transaction = Transaction::findOrFail($id);
        
        \Illuminate\Support\Facades\Gate::authorize('delete', $transaction);
        
        $transaction->delete();
        return response()->json(['status' => 'success', 'message' => 'Transaction supprimée'], 200);
    }
}
