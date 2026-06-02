<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\MemberContribution;
use Illuminate\Http\Request;

class MemberContributionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $contributions = MemberContribution::with(['member', 'transaction', 'fund'])->get();
        return response()->json(['status' => 'success', 'data' => $contributions], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'member_id' => 'required|exists:members,member_id',
            'transaction_id' => 'required|exists:transactions,transaction_id',
            'fund_id' => 'required|exists:funds,fund_id',
            'payment_status' => 'required|string'
        ]);

        $contribution = MemberContribution::create($validated);
        return response()->json(['status' => 'success', 'data' => $contribution], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $contribution = MemberContribution::with(['member', 'transaction', 'fund'])->findOrFail($id);
        return response()->json(['status' => 'success', 'data' => $contribution], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $contribution = MemberContribution::findOrFail($id);

        $validated = $request->validate([
            'member_id' => 'sometimes|required|exists:members,member_id',
            'transaction_id' => 'sometimes|required|exists:transactions,transaction_id',
            'fund_id' => 'sometimes|required|exists:funds,fund_id',
            'payment_status' => 'sometimes|required|string'
        ]);

        $contribution->update($validated);
        return response()->json(['status' => 'success', 'data' => $contribution], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $contribution = MemberContribution::findOrFail($id);
        $contribution->delete();
        return response()->json(['status' => 'success', 'message' => 'Contribution supprimée'], 200);
    }
}
