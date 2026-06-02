<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Member;
use Illuminate\Http\Request;

class MemberController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $members = Member::with('group')->get();
        return response()->json(['status' => 'success', 'data' => $members], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'group_id' => 'required|exists:community_groups,group_id',
            'member_name' => 'required|string|max:255',
            'status' => 'required|string'
        ]);

        $member = Member::create($validated);
        return response()->json(['status' => 'success', 'data' => $member], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $member = Member::with(['group', 'contributions'])->findOrFail($id);
        return response()->json(['status' => 'success', 'data' => $member], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $member = Member::findOrFail($id);

        $validated = $request->validate([
            'group_id' => 'sometimes|required|exists:community_groups,group_id',
            'member_name' => 'sometimes|required|string|max:255',
            'status' => 'sometimes|required|string'
        ]);

        $member->update($validated);
        return response()->json(['status' => 'success', 'data' => $member], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $member = Member::findOrFail($id);
        $member->delete();
        return response()->json(['status' => 'success', 'message' => 'Membre supprimé'], 200);
    }
}
