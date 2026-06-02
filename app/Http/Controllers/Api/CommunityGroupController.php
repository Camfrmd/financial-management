<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\CommunityGroup;
use Illuminate\Http\Request;

class CommunityGroupController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $groups = CommunityGroup::with(['funds', 'members'])->get();
        return response()->json(['status' => 'success', 'data' => $groups], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'group_name' => 'required|string|max:255',
            'description' => 'nullable|string'
        ]);

        $group = CommunityGroup::create($validated);
        return response()->json(['status' => 'success', 'data' => $group], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $group = CommunityGroup::with(['funds', 'members'])->findOrFail($id);
        return response()->json(['status' => 'success', 'data' => $group], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $group = CommunityGroup::findOrFail($id);

        $validated = $request->validate([
            'group_name' => 'sometimes|required|string|max:255',
            'description' => 'nullable|string'
        ]);

        $group->update($validated);
        return response()->json(['status' => 'success', 'data' => $group], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $group = CommunityGroup::findOrFail($id);
        $group->delete();
        return response()->json(['status' => 'success', 'message' => 'Groupe supprimé'], 200);
    }
}
