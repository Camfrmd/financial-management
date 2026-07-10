<?php

namespace App\Http\Controllers;

use App\Models\Fund;
use App\Models\Transaction;
use App\Models\Activity;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Show the application dashboard.
     */
    public function index()
    {
        // 1. Calcul du total des soldes des cagnottes
        $totalBalance = Fund::sum('current_balance');

        // 2. Current Month Data for Doughnut Chart
        $currentMonthStart = now()->startOfMonth();
        $currentMonthEnd = now()->endOfMonth();
        
        $currentMonthIncomes = Transaction::where('validation_status', 'validated')
            ->where('type', 'income')
            ->whereBetween('date', [$currentMonthStart, $currentMonthEnd])
            ->sum('amount');
            
        $currentMonthExpenses = Transaction::where('validation_status', 'validated')
            ->where('type', 'expense')
            ->whereBetween('date', [$currentMonthStart, $currentMonthEnd])
            ->sum('amount');

        // 3. 6-Month Trend Data for Bar Chart
        // Tech Lead Advice: Handle "Empty Month Syndrome" using Laravel Collections
        $sixMonthsAgo = now()->subMonths(5)->startOfMonth(); // 5 months ago + current month = 6 months
        
        $trendTransactions = Transaction::where('validation_status', 'validated')
            ->where('date', '>=', $sixMonthsAgo)
            ->get()
            ->groupBy(function($tx) {
                return \Carbon\Carbon::parse($tx->date)->format('Y-m');
            });

        $chartLabels = [];
        $chartIncomes = [];
        $chartExpenses = [];

        // Build the 6-month array sequentially to guarantee no missing months
        for ($i = 5; $i >= 0; $i--) {
            $targetMonth = now()->subMonths($i);
            $monthKey = $targetMonth->format('Y-m');
            
            $chartLabels[] = $targetMonth->translatedFormat('M Y');
            
            if ($trendTransactions->has($monthKey)) {
                $monthGroup = $trendTransactions->get($monthKey);
                $chartIncomes[] = $monthGroup->where('type', 'income')->sum('amount');
                $chartExpenses[] = $monthGroup->where('type', 'expense')->sum('amount');
            } else {
                $chartIncomes[] = 0;
                $chartExpenses[] = 0;
            }
        }

        // 4. Projections (6 Months Forward)
        // Calculate Baseline Recurring Income (Average of last 6 months' recurring income)
        $recurringIncomeTotal = Transaction::where('validation_status', 'validated')
            ->where('type', 'income')
            ->where('date', '>=', $sixMonthsAgo)
            ->whereHas('category', function($q) {
                $q->where('is_recurring', true);
            })
            ->sum('amount');
            
        $baselineMonthlyIncome = $recurringIncomeTotal / 6;

        $projectedLabels = [];
        $projectedBalances = [];
        
        $currentProjectionBalance = $totalBalance;
        
        // Next 6 months (starting next month)
        for ($i = 1; $i <= 6; $i++) {
            $futureMonth = now()->addMonths($i);
            $projectedLabels[] = $futureMonth->translatedFormat('M Y');
            
            // Find planned expenses (activities) in this specific future month
            $plannedExpenses = Activity::whereYear('start_date', $futureMonth->year)
                ->whereMonth('start_date', $futureMonth->month)
                ->sum('estimated_budget');
                
            $currentProjectionBalance = $currentProjectionBalance + $baselineMonthlyIncome - $plannedExpenses;
            $projectedBalances[] = $currentProjectionBalance;
        }

        // 4. Récupération des 5 dernières transactions validées
        $recentTransactions = Transaction::with('category')
            ->where('validation_status', 'validated')
            ->orderBy('date', 'desc')
            ->take(5)
            ->get();

        // 5. Récupération des dépenses planifiées
        $upcomingActivities = Activity::where('start_date', '>', now())
            ->orderBy('start_date', 'asc')
            ->take(5)
            ->get();

        // 6. Compte des transactions en attente
        $pendingCount = Transaction::where('validation_status', 'pending')->count();

        return view('dashboard', compact(
            'totalBalance', 
            'recentTransactions', 
            'upcomingActivities', 
            'pendingCount',
            'currentMonthIncomes',
            'currentMonthExpenses',
            'chartLabels',
            'chartIncomes',
            'chartExpenses',
            'projectedLabels',
            'projectedBalances',
            'baselineMonthlyIncome'
        ));
    }
}
