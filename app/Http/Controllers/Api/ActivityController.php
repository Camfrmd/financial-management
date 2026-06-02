<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Activity;
use Illuminate\Http\Request;

class ActivityController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $activities = Activity::with('funds')->get();
        return response()->json(['status' => 'success', 'data' => $activities], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'activity_name' => 'required|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'status' => 'required|string'
        ]);

        $activity = Activity::create($validated);
        return response()->json(['status' => 'success', 'data' => $activity], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $activity = Activity::with('funds')->findOrFail($id);
        return response()->json(['status' => 'success', 'data' => $activity], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $activity = Activity::findOrFail($id);

        $validated = $request->validate([
            'activity_name' => 'sometimes|required|string|max:255',
            'start_date' => 'sometimes|required|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'status' => 'sometimes|required|string'
        ]);

        $activity->update($validated);
        return response()->json(['status' => 'success', 'data' => $activity], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $activity = Activity::findOrFail($id);
        $activity->delete();
        return response()->json(['status' => 'success', 'message' => 'Activité supprimée'], 200);
    }
}
