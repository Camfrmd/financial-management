<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\Fund;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\LpjExport;
use Illuminate\Support\Facades\URL;

class ReportController extends Controller
{
    public function index()
    {
        $funds = Fund::orderBy('name')->get();
        return view('reports.index', compact('funds'));
    }

    private function getReportData($startDate, $endDate, $fundId = null)
    {
        $start = Carbon::parse($startDate)->startOfDay();
        $end = Carbon::parse($endDate)->endOfDay();

        $baseQuery = Transaction::where('validation_status', 'validated');
        if ($fundId) {
            $baseQuery->where('fund_id', $fundId);
        }

        // Starting Balance (before start date)
        $previousIncomes = (clone $baseQuery)->where('type', 'income')->where('date', '<', $start)->sum('amount');
        $previousExpenses = (clone $baseQuery)->where('type', 'expense')->where('date', '<', $start)->sum('amount');
        $startingBalance = $previousIncomes - $previousExpenses;

        // Transactions in period
        $periodTransactions = (clone $baseQuery)->whereBetween('date', [$start, $end])->with('category.parent')->get();

        $incomes = $periodTransactions->where('type', 'income');
        $expenses = $periodTransactions->where('type', 'expense');

        $totalIncomes = $incomes->sum('amount');
        $totalExpenses = $expenses->sum('amount');
        $endingBalance = $startingBalance + $totalIncomes - $totalExpenses;

        $groupedIncomes = $this->groupTransactionsByCategory($incomes);
        $groupedExpenses = $this->groupTransactionsByCategory($expenses);

        $fundName = $fundId ? Fund::find($fundId)->name : __('Global (All Funds)');

        return compact(
            'startDate', 'endDate', 'fundName', 
            'startingBalance', 'endingBalance', 
            'totalIncomes', 'totalExpenses', 
            'groupedIncomes', 'groupedExpenses'
        );
    }

    private function groupTransactionsByCategory($transactions)
    {
        $grouped = [];
        foreach ($transactions as $tx) {
            if ($tx->category) {
                $parentName = $tx->category->parent ? $tx->category->parent->category_name : $tx->category->category_name;
                $childName = $tx->category->parent ? $tx->category->category_name : __('Direct Post');
            } else {
                $parentName = __('Uncategorized');
                $childName = __('General');
            }

            if (!isset($grouped[$parentName])) {
                $grouped[$parentName] = ['total' => 0, 'details' => []];
            }
            if (!isset($grouped[$parentName]['details'][$childName])) {
                $grouped[$parentName]['details'][$childName] = 0;
            }
            
            $grouped[$parentName]['details'][$childName] += $tx->amount;
            $grouped[$parentName]['total'] += $tx->amount;
        }

        // Sort alphabetically
        ksort($grouped);
        foreach ($grouped as &$group) {
            ksort($group['details']);
        }

        return $grouped;
    }

    public function generate(Request $request)
    {
        $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'fund_id' => 'nullable|exists:funds,fund_id'
        ]);

        $data = $this->getReportData($request->start_date, $request->end_date, $request->fund_id);

        $shareUrl = URL::signedRoute('reports.public', [
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'fund_id' => $request->fund_id
        ]);

        $data['shareUrl'] = $shareUrl;

        return view('reports.preview', $data);
    }

    public function publicView(Request $request)
    {
        // Request signature is already validated by the 'signed' middleware
        $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'fund_id' => 'nullable|exists:funds,fund_id'
        ]);

        $data = $this->getReportData($request->start_date, $request->end_date, $request->fund_id);

        return view('reports.public', $data);
    }

    public function exportPdf(Request $request)
    {
        $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'fund_id' => 'nullable|exists:funds,fund_id'
        ]);

        $data = $this->getReportData($request->start_date, $request->end_date, $request->fund_id);

        $pdf = Pdf::loadView('reports.pdf', $data);
        return $pdf->download('LPJ_' . $data['startDate'] . '_to_' . $data['endDate'] . '.pdf');
    }

    public function exportExcel(Request $request)
    {
        $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'fund_id' => 'nullable|exists:funds,fund_id'
        ]);

        $data = $this->getReportData($request->start_date, $request->end_date, $request->fund_id);

        return Excel::download(new LpjExport($data), 'LPJ_' . $data['startDate'] . '_to_' . $data['endDate'] . '.xlsx');
    }
}
