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

        // 2. Récupération des 5 dernières transactions validées
        $recentTransactions = Transaction::with('category')
            ->where('validation_status', 'validated')
            ->orderBy('date', 'desc')
            ->take(5)
            ->get();

        // 3. Récupération des dépenses planifiées (Activités futures)
        // Utilisation de la table Activities comme demandé par l'utilisateur
        $upcomingActivities = Activity::where('start_date', '>', now())
            ->orderBy('start_date', 'asc')
            ->take(5)
            ->get();

        // 4. Compte des transactions en attente
        $pendingCount = Transaction::where('validation_status', 'pending')->count();

        return view('dashboard', compact('totalBalance', 'recentTransactions', 'upcomingActivities', 'pendingCount'));
    }
}
