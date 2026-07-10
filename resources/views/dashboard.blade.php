@extends('layouts.app')

@section('title', __('Dashboard Analytics'))

@section('content')
    <!-- Script CDN for Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <!-- Alert for Pending Validations -->
    @can('validate-transactions')
        @if($pendingCount > 0)
        <div class="mb-8 p-4 bg-gray-800 rounded-xl border border-yellow-600 shadow-xl flex flex-col sm:flex-row justify-between items-center gap-4">
            <div class="flex items-center gap-4">
                <div class="p-3 bg-yellow-900/30 rounded-full border border-yellow-700/50">
                    <svg class="w-6 h-6 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                </div>
                <div>
                    <h3 class="text-lg font-bold text-white">{{ __('Transactions awaiting validation') }}</h3>
                    <p class="text-gray-400 text-sm">{{ __('You have') }} <span class="text-yellow-500 font-bold">{{ $pendingCount }}</span> {{ __('transactions to review in the queue.') }}</p>
                </div>
            </div>
            <a href="{{ url('/transactions/pending') }}" class="bg-yellow-600 hover:bg-yellow-500 text-white font-bold py-2.5 px-6 rounded-lg shadow-lg shadow-yellow-900/30 transition-all duration-200 whitespace-nowrap">
                {{ __('Review Queue') }}
            </a>
        </div>
        @endif
    @endcan

    <!-- Metric Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <!-- Total Global Balance -->
        <div class="bg-gradient-to-br from-[#1a1d2d] to-[#121420] border border-gray-800 rounded-xl p-6 shadow-xl relative overflow-hidden group">
            <div class="absolute top-0 right-0 p-4 opacity-10 group-hover:opacity-20 transition-opacity">
                <svg class="w-16 h-16 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
            <h2 class="text-gray-400 text-sm font-semibold uppercase tracking-wider mb-2 relative z-10">{{ __('Total Fund Balance') }}</h2>
            <div class="text-3xl font-bold text-white relative z-10">
                Rp {{ number_format($totalBalance, 0, ',', '.') }}
            </div>
        </div>

        <!-- Current Month Incomes -->
        <div class="bg-gradient-to-br from-[#1a1d2d] to-[#062c16] border border-gray-800 rounded-xl p-6 shadow-xl relative overflow-hidden group">
            <div class="absolute top-0 right-0 p-4 opacity-10 group-hover:opacity-20 transition-opacity">
                <svg class="w-16 h-16 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path></svg>
            </div>
            <h2 class="text-gray-400 text-sm font-semibold uppercase tracking-wider mb-2 relative z-10">{{ __('Incomes This Month') }}</h2>
            <div class="text-3xl font-bold text-green-500 relative z-10">
                Rp {{ number_format($currentMonthIncomes, 0, ',', '.') }}
            </div>
        </div>

        <!-- Current Month Expenses -->
        <div class="bg-gradient-to-br from-[#1a1d2d] to-[#3f0f15] border border-gray-800 rounded-xl p-6 shadow-xl relative overflow-hidden group">
            <div class="absolute top-0 right-0 p-4 opacity-10 group-hover:opacity-20 transition-opacity">
                <svg class="w-16 h-16 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 17h8m0 0V9m0 8l-8-8-4 4-6-6"></path></svg>
            </div>
            <h2 class="text-gray-400 text-sm font-semibold uppercase tracking-wider mb-2 relative z-10">{{ __('Expenses This Month') }}</h2>
            <div class="text-3xl font-bold text-red-500 relative z-10">
                Rp {{ number_format($currentMonthExpenses, 0, ',', '.') }}
            </div>
        </div>
    </div>

    <!-- Charts Row -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-8">
        
        <!-- Doughnut Chart (Current Month Ratio) -->
        <section class="bg-[#1a1d2d] border border-gray-800 rounded-xl p-6 shadow-xl flex flex-col lg:col-span-1">
            <h2 class="text-lg font-semibold text-white mb-6 text-center">{{ __('This Month: Income vs Expense') }}</h2>
            
            @if($currentMonthIncomes == 0 && $currentMonthExpenses == 0)
                <div class="flex-grow flex flex-col items-center justify-center text-gray-500 h-64">
                    <svg class="w-12 h-12 mb-3 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 12H4"></path></svg>
                    <p class="text-sm italic">{{ __('No transactions this month.') }}</p>
                </div>
            @else
                <div class="relative flex-grow flex items-center justify-center" style="min-height: 250px;">
                    <canvas id="currentMonthChart"></canvas>
                </div>
            @endif
        </section>

        <!-- Bar Chart (6-Month Trend) -->
        <section class="bg-[#1a1d2d] border border-gray-800 rounded-xl p-6 shadow-xl flex flex-col lg:col-span-2">
            <h2 class="text-lg font-semibold text-white mb-6">{{ __('6-Month Cash Flow Trend') }}</h2>
            <div class="relative flex-grow w-full" style="min-height: 250px;">
                <canvas id="trendChart"></canvas>
            </div>
        </section>

    </div>

    <!-- Projection Row -->
    <div class="mb-8">
        <section class="bg-[#1a1d2d] border border-gray-800 rounded-xl p-6 shadow-xl">
            <div class="flex flex-col md:flex-row justify-between items-center mb-6 gap-4 border-b border-gray-800 pb-4">
                <h2 class="text-lg font-semibold text-white flex items-center">
                    <svg class="w-5 h-5 mr-2 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path></svg>
                    {{ __('6-Month Financial Projection') }}
                </h2>
                <div class="bg-blue-900/30 border border-blue-800/50 rounded-lg px-4 py-2 flex items-center shadow-inner">
                    <span class="text-sm text-gray-400 mr-2">{{ __('Calculated MRR (Monthly Recurring Revenue):') }}</span>
                    <span class="font-bold text-blue-400">Rp {{ number_format($baselineMonthlyIncome, 0, ',', '.') }}</span>
                </div>
            </div>
            <div class="relative w-full" style="min-height: 250px;">
                <canvas id="projectionChart"></canvas>
            </div>
            <p class="text-xs text-gray-500 mt-4 italic text-center">
                {{ __('Projections are calculated dynamically using the 6-month average of categories marked as "Recurring", minus any planned activity budgets.') }}
            </p>
        </section>
    </div>

    <!-- Data Tables Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">

        <!-- Dernières Transactions -->
        <section class="bg-[#1a1d2d] border border-gray-800 rounded-xl p-6 shadow-xl">
            <div class="flex justify-between items-center mb-6 border-b border-gray-800 pb-4">
                <h2 class="text-lg font-semibold text-white flex items-center">
                    <svg class="w-5 h-5 mr-2 text-[#a855f7]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                    {{ __('Latest Transactions') }}
                </h2>
                <a href="{{ route('transactions.index') }}" class="text-xs text-[#a855f7] hover:text-[#d8b4fe] transition-colors">{{ __('View All') }} &rarr;</a>
            </div>

            @if($recentTransactions->isEmpty())
                <p class="text-gray-500 text-sm italic">{{ __('No recent transactions found.') }}</p>
            @else
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm text-gray-300">
                        <tbody>
                            @foreach($recentTransactions as $tx)
                                <tr class="border-b border-gray-800/50 hover:bg-gray-800/30 transition-colors">
                                    <td class="py-3 pr-4">
                                        <div class="font-medium text-gray-200">{{ $tx->description }}</div>
                                        <div class="text-xs text-gray-500 mt-1 flex items-center gap-2">
                                            <span>{{ \Carbon\Carbon::parse($tx->date)->format('d M') }}</span>
                                            <span>&bull;</span>
                                            <span>{{ $tx->category->category_name ?? __('Uncategorized') }}</span>
                                        </div>
                                    </td>
                                    <td class="py-3 pl-4 text-right whitespace-nowrap">
                                        <div class="font-bold text-sm {{ $tx->type === 'income' ? 'text-green-500' : 'text-red-500' }}">
                                            {{ $tx->type === 'income' ? '+' : '-' }}Rp {{ number_format($tx->amount, 0, ',', '.') }}
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </section>

        <!-- Dépenses Planifiées / Activités Futures -->
        <section class="bg-[#1a1d2d] border border-gray-800 rounded-xl p-6 shadow-xl">
            <div class="flex justify-between items-center mb-6 border-b border-gray-800 pb-4">
                <h2 class="text-lg font-semibold text-white flex items-center">
                    <svg class="w-5 h-5 mr-2 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                    {{ __('Upcoming Activities') }}
                </h2>
            </div>

            @if($upcomingActivities->isEmpty())
                <p class="text-gray-500 text-sm italic">{{ __('No upcoming events planned.') }}</p>
            @else
                <ul class="space-y-4">
                    @foreach($upcomingActivities as $activity)
                        <li class="flex items-start p-4 bg-gray-900/50 border border-gray-800 rounded-xl hover:border-gray-700 transition-colors">
                            <div class="flex-shrink-0 bg-gradient-to-b from-red-900/40 to-red-900/10 text-red-500 p-3 rounded-lg mr-4 text-center min-w-[60px] border border-red-900/30">
                                <div class="text-xs font-semibold uppercase tracking-wider">{{ \Carbon\Carbon::parse($activity->start_date)->translatedFormat('M') }}</div>
                                <div class="text-xl font-bold">{{ \Carbon\Carbon::parse($activity->start_date)->format('d') }}</div>
                            </div>
                            <div>
                                <h3 class="text-gray-100 font-medium text-base">{{ $activity->activity_name }}</h3>
                                <div class="flex items-center gap-3 mt-1.5">
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-gray-800 text-gray-300 border border-gray-700">
                                        {{ ucfirst($activity->status) }}
                                    </span>
                                    @if($activity->end_date)
                                        <span class="text-gray-500 text-xs flex items-center">
                                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                                            {{ \Carbon\Carbon::parse($activity->end_date)->format('d M') }}
                                        </span>
                                    @endif
                                </div>
                            </div>
                            @if($activity->estimated_budget > 0)
                                <div class="ml-auto text-right flex-shrink-0">
                                    <div class="text-xs text-gray-500 mb-0.5">{{ __('Budget') }}</div>
                                    <div class="font-bold text-sm text-red-400">-Rp {{ number_format($activity->estimated_budget, 0, ',', '.') }}</div>
                                </div>
                            @endif
                        </li>
                    @endforeach
                </ul>
            @endif
        </section>

    </div>

    <!-- Chart.js Configuration -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            
            // Tridatu Theme Colors
            const colorIncome = '#22c55e'; // Green 500
            const colorExpense = '#ef4444'; // Red 500
            const colorExpenseSecondary = '#991b1b'; // Red 800
            const colorText = '#9ca3af'; // Gray 400
            const colorGrid = '#374151'; // Gray 700

            Chart.defaults.color = colorText;
            Chart.defaults.font.family = "'Inter', 'Roboto', sans-serif";

            // 1. Doughnut Chart (Current Month)
            const doughnutCanvas = document.getElementById('currentMonthChart');
            if (doughnutCanvas) {
                const ctxDoughnut = doughnutCanvas.getContext('2d');
                new Chart(ctxDoughnut, {
                    type: 'doughnut',
                    data: {
                        labels: ['{{ __("Income") }}', '{{ __("Expense") }}'],
                        datasets: [{
                            data: [{{ $currentMonthIncomes }}, {{ $currentMonthExpenses }}],
                            backgroundColor: [colorIncome, colorExpense],
                            borderWidth: 0,
                            hoverOffset: 4
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        cutout: '75%',
                        plugins: {
                            legend: {
                                position: 'bottom',
                                labels: {
                                    padding: 20,
                                    usePointStyle: true,
                                    pointStyle: 'circle'
                                }
                            }
                        }
                    }
                });
            }

            // 2. Bar Chart (6-Month Trend)
            const trendCanvas = document.getElementById('trendChart');
            if (trendCanvas) {
                const ctxTrend = trendCanvas.getContext('2d');
                new Chart(ctxTrend, {
                    type: 'bar',
                    data: {
                        labels: {!! json_encode($chartLabels) !!},
                        datasets: [
                            {
                                label: '{{ __("Income") }}',
                                data: {!! json_encode($chartIncomes) !!},
                                backgroundColor: colorIncome,
                                borderRadius: 4,
                                borderSkipped: false,
                            },
                            {
                                label: '{{ __("Expense") }}',
                                data: {!! json_encode($chartExpenses) !!},
                                backgroundColor: colorExpense,
                                borderRadius: 4,
                                borderSkipped: false,
                            }
                        ]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        interaction: {
                            mode: 'index',
                            intersect: false,
                        },
                        plugins: {
                            legend: {
                                position: 'top',
                                align: 'end',
                                labels: {
                                    usePointStyle: true,
                                    boxWidth: 8
                                }
                            },
                            tooltip: {
                                backgroundColor: '#1f2937',
                                titleColor: '#f3f4f6',
                                bodyColor: '#d1d5db',
                                borderColor: '#374151',
                                borderWidth: 1,
                                padding: 12,
                                callbacks: {
                                    label: function(context) {
                                        let label = context.dataset.label || '';
                                        if (label) {
                                            label += ': ';
                                        }
                                        if (context.parsed.y !== null) {
                                            label += 'Rp ' + new Intl.NumberFormat('id-ID').format(context.parsed.y);
                                        }
                                        return label;
                                    }
                                }
                            }
                        },
                        scales: {
                            x: {
                                grid: {
                                    display: false,
                                    drawBorder: false
                                }
                            },
                            y: {
                                grid: {
                                    color: colorGrid,
                                    drawBorder: false,
                                    borderDash: [5, 5]
                                },
                                ticks: {
                                    callback: function(value) {
                                        if (value >= 1000000) return (value / 1000000) + 'M';
                                        if (value >= 1000) return (value / 1000) + 'K';
                                        return value;
                                    }
                                }
                            }
                        }
                    }
                });
            }

            // 3. Line Chart (Projections)
            const projCanvas = document.getElementById('projectionChart');
            if (projCanvas) {
                const ctxProj = projCanvas.getContext('2d');
                new Chart(ctxProj, {
                    type: 'line',
                    data: {
                        labels: {!! json_encode($projectedLabels) !!},
                        datasets: [{
                            label: '{{ __("Projected Balance") }}',
                            data: {!! json_encode($projectedBalances) !!},
                            borderColor: '#3b82f6', // Blue 500
                            backgroundColor: 'rgba(59, 130, 246, 0.1)',
                            borderWidth: 3,
                            pointBackgroundColor: '#3b82f6',
                            pointBorderColor: '#1e3a8a',
                            pointBorderWidth: 2,
                            pointRadius: 4,
                            pointHoverRadius: 6,
                            fill: true,
                            tension: 0.3
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        interaction: {
                            mode: 'index',
                            intersect: false,
                        },
                        plugins: {
                            legend: {
                                display: false
                            },
                            tooltip: {
                                backgroundColor: '#1f2937',
                                titleColor: '#f3f4f6',
                                bodyColor: '#60a5fa',
                                borderColor: '#374151',
                                borderWidth: 1,
                                padding: 12,
                                callbacks: {
                                    label: function(context) {
                                        return 'Rp ' + new Intl.NumberFormat('id-ID').format(context.parsed.y);
                                    }
                                }
                            }
                        },
                        scales: {
                            x: {
                                grid: {
                                    display: false,
                                    drawBorder: false
                                }
                            },
                            y: {
                                grid: {
                                    color: colorGrid,
                                    drawBorder: false,
                                    borderDash: [5, 5]
                                },
                                ticks: {
                                    callback: function(value) {
                                        if (value >= 1000000) return (value / 1000000) + 'M';
                                        if (value >= 1000) return (value / 1000) + 'K';
                                        return value;
                                    }
                                }
                            }
                        }
                    }
                });
            }
        });
    </script>
@endsection