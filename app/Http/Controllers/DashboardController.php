<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Show the application dashboard.
     */
    public function index()
    {
        // For now, we return a basic view. In the future, we will fetch
        // balances, latest transactions, etc.
        return view('dashboard');
    }
}
