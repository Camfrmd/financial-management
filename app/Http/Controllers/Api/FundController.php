<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Fund;
use Illuminate\Http\Request;

class FundController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $funds = Fund::with(['group', 'activity'])->get();
        return response()->json(['status' => 'success', 'data' => $funds], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'group_id' => 'required|exists:community_groups,group_id',
            'activity_id' => 'nullable|exists:activities,activity_id',
            'name' => 'required|string|max:255',
            'current_balance' => 'required|numeric|min:0',
            'description' => 'nullable|string'
        ]);

        $fund = Fund::create($validated);
        return response()->json(['status' => 'success', 'data' => $fund], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $fund = Fund::with(['group', 'activity', 'transactions'])->findOrFail($id);
        return response()->json(['status' => 'success', 'data' => $fund], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $fund = Fund::findOrFail($id);

        $validated = $request->validate([
            'group_id' => 'sometimes|required|exists:community_groups,group_id',
            'activity_id' => 'nullable|exists:activities,activity_id',
            'name' => 'sometimes|required|string|max:255',
            'current_balance' => 'sometimes|required|numeric|min:0',
            'description' => 'nullable|string'
        ]);

        $fund->update($validated);
        return response()->json(['status' => 'success', 'data' => $fund], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $fund = Fund::findOrFail($id);
        $fund->delete();
        return response()->json(['status' => 'success', 'message' => 'Fond supprimé'], 200);
    }
}
