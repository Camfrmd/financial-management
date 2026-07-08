<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaction;
use App\Models\Fund;
use Carbon\Carbon;

class CashFlowController extends Controller
{
    public function index(Request $request)
    {
        $month = $request->input('month', now()->format('Y-m'));
        
        try {
            $date = Carbon::createFromFormat('Y-m', $month);
        } catch (\Exception $e) {
            $date = now();
            $month = $date->format('Y-m');
        }

        $startOfMonth = $date->copy()->startOfMonth();
        $endOfMonth = $date->copy()->endOfMonth();

        // Previous Balance Calculation
        $previousIncomes = Transaction::where('validation_status', 'validated')
            ->where('type', 'income')
            ->where('date', '<', $startOfMonth)
            ->sum('amount');
            
        $previousExpenses = Transaction::where('validation_status', 'validated')
            ->where('type', 'expense')
            ->where('date', '<', $startOfMonth)
            ->sum('amount');
            
        $lastMonthBalance = $previousIncomes - $previousExpenses;

        // Current Month Transactions
        $transactions = Transaction::with(['category', 'fund'])
            ->where('validation_status', 'validated')
            ->whereBetween('date', [$startOfMonth, $endOfMonth])
            ->orderBy('date', 'asc')
            ->get();

        $incomes = $transactions->where('type', 'income');
        $expenses = $transactions->where('type', 'expense');

        $totalIncome = $incomes->sum('amount');
        $totalExpense = $expenses->sum('amount');
        
        $currentBalance = $lastMonthBalance + $totalIncome - $totalExpense;

        return view('cashflow.index', compact(
            'month', 
            'date',
            'lastMonthBalance', 
            'incomes', 
            'expenses', 
            'totalIncome', 
            'totalExpense',
            'currentBalance'
        ));
    }
}
