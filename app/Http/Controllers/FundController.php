<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Fund;
use App\Models\CommunityGroup;
use App\Models\Activity;

class FundController extends Controller
{
    public function index()
    {
        $funds = Fund::with(['group', 'activity'])->get();
        return view('funds.index', compact('funds'));
    }

    public function create()
    {
        $groups = CommunityGroup::all();
        $activities = Activity::all();
        return view('funds.create', compact('groups', 'activities'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'group_id' => 'nullable|exists:community_groups,group_id',
            'activity_id' => 'nullable|exists:activities,activity_id',
        ]);

        // Force initial balance to 0 as per accounting rules
        $validated['current_balance'] = 0;

        Fund::create($validated);

        return redirect()->route('funds.index')->with('success', __('Fund created successfully.'));
    }

    public function edit(Fund $fund)
    {
        $groups = CommunityGroup::all();
        $activities = Activity::all();
        return view('funds.edit', compact('fund', 'groups', 'activities'));
    }

    public function update(Request $request, Fund $fund)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'group_id' => 'nullable|exists:community_groups,group_id',
            'activity_id' => 'nullable|exists:activities,activity_id',
        ]);

        // Note: We strictly prevent updating current_balance directly here.
        $fund->update($validated);

        return redirect()->route('funds.index')->with('success', __('Fund updated successfully.'));
    }

    public function destroy(Fund $fund)
    {
        if ($fund->transactions()->exists() || $fund->contributions()->exists()) {
            return back()->withErrors(['error' => __('Cannot delete a fund that contains transactions.')]);
        }

        $fund->delete();
        return redirect()->route('funds.index')->with('success', __('Fund deleted successfully.'));
    }
}
