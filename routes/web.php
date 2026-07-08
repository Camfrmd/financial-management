<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\DashboardController;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::middleware('guest')->group(function () {
    Route::get('login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('login', [LoginController::class, 'login']);
});

Route::get('/lang/{locale}', [\App\Http\Controllers\LanguageController::class, 'switch'])->name('lang.switch');

use App\Http\Controllers\FundController;
use App\Http\Controllers\TransactionController;

Route::middleware('auth')->group(function () {
    Route::post('logout', [LoginController::class, 'logout'])->name('logout');
    Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    Route::resource('funds', FundController::class)->middleware('can:manage-funds');
    Route::resource('categories', \App\Http\Controllers\CategoryController::class)->except(['show'])->middleware('can:manage-funds');

    Route::get('transactions', [TransactionController::class, 'index'])->name('transactions.index');
    Route::get('transactions/create', [TransactionController::class, 'create'])->name('transactions.create');
    Route::post('transactions', [TransactionController::class, 'store'])->name('transactions.store');

    Route::middleware('can:validate-transactions')->group(function () {
        Route::get('transactions/pending', [TransactionController::class, 'pending'])->name('transactions.pending');
        Route::patch('transactions/{transaction}/approve', [TransactionController::class, 'approve'])->name('transactions.approve');
        Route::patch('transactions/{transaction}/reject', [TransactionController::class, 'reject'])->name('transactions.reject');
    });

    Route::middleware('can:manage-users')->group(function () {
        Route::resource('users', \App\Http\Controllers\UserController::class)->except(['show', 'destroy']);
    });
    
    // Reports (LPJ)
    Route::middleware('can:view-reports')->group(function () {
        Route::get('reports', [\App\Http\Controllers\ReportController::class, 'index'])->name('reports.index');
        Route::get('reports/generate', [\App\Http\Controllers\ReportController::class, 'generate'])->name('reports.generate');
        Route::get('reports/export/pdf', [\App\Http\Controllers\ReportController::class, 'exportPdf'])->name('reports.export.pdf');
        Route::get('reports/export/excel', [\App\Http\Controllers\ReportController::class, 'exportExcel'])->name('reports.export.excel');
    });

    // Cash Flow Summary
    Route::middleware('can:view-reports')->group(function () {
        Route::get('cashflow', [\App\Http\Controllers\CashFlowController::class, 'index'])->name('cashflow.index');
    });

    // Member Management
    Route::resource('members', \App\Http\Controllers\MemberController::class)->except(['show']);
    
    // Contributions Tracking
    Route::get('contributions', [\App\Http\Controllers\ContributionController::class, 'index'])->name('contributions.index');
    Route::post('contributions/update-status', [\App\Http\Controllers\ContributionController::class, 'updateStatus'])->name('contributions.updateStatus');
});
