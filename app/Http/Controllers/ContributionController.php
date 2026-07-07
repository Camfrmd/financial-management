<?php

namespace App\Http\Controllers;

use App\Models\Member;
use App\Models\Fund;
use App\Models\MemberContribution;
use Illuminate\Http\Request;

class ContributionController extends Controller
{
    public function index(Request $request)
    {
        $funds = Fund::all();
        
        $selectedFundId = $request->input('fund_id', $funds->first()->fund_id ?? null);
        $selectedPeriod = $request->input('period', date('Y-m'));

        $members = Member::orderBy('member_name')->get();
        
        // Fetch all contributions for this fund and period
        $contributions = MemberContribution::where('fund_id', $selectedFundId)
            ->where('period', $selectedPeriod)
            ->get()
            ->keyBy('member_id');

        return view('contributions.index', compact(
            'funds', 'selectedFundId', 'selectedPeriod', 'members', 'contributions'
        ));
    }

    public function updateStatus(Request $request)
    {
        $request->validate([
            'member_id' => 'required|exists:members,member_id',
            'fund_id' => 'required|exists:funds,fund_id',
            'period' => 'required|string|size:7', // YYYY-MM
            'status' => 'required|in:paid,pending,exempted',
        ]);

        $contribution = MemberContribution::updateOrCreate(
            [
                'member_id' => $request->member_id,
                'fund_id' => $request->fund_id,
                'period' => $request->period,
            ],
            [
                'payment_status' => $request->status,
                // transaction_id remains null for now as per user instruction
            ]
        );

        return response()->json([
            'success' => true,
            'message' => 'Status updated',
            'data' => $contribution
        ]);
    }
}
