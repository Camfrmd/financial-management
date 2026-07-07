<?php

namespace App\Http\Controllers;

use App\Models\Member;
use App\Models\CommunityGroup;
use Illuminate\Http\Request;

class MemberController extends Controller
{
    public function index()
    {
        $members = Member::with('group')->orderBy('member_name')->get();
        return view('members.index', compact('members'));
    }

    public function create()
    {
        $groups = CommunityGroup::all();
        return view('members.create', compact('groups'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'member_name' => 'required|string|max:50',
            'group_id' => 'required|exists:community_groups,group_id',
            'status' => 'required|in:active,exempted',
        ]);

        Member::create($validated);

        return redirect()->route('members.index')->with('success', __('Member created successfully.'));
    }

    public function edit(Member $member)
    {
        $groups = CommunityGroup::all();
        return view('members.edit', compact('member', 'groups'));
    }

    public function update(Request $request, Member $member)
    {
        $validated = $request->validate([
            'member_name' => 'required|string|max:50',
            'group_id' => 'required|exists:community_groups,group_id',
            'status' => 'required|in:active,exempted',
        ]);

        $member->update($validated);

        return redirect()->route('members.index')->with('success', __('Member updated successfully.'));
    }

    public function destroy(Member $member)
    {
        if ($member->contributions()->exists()) {
            return back()->withErrors(['error' => __('Cannot delete member because they have registered contributions. Set them to exempted instead.')]);
        }
        $member->delete();
        return redirect()->route('members.index')->with('success', __('Member deleted successfully.'));
    }
}
