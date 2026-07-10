@extends('layouts.public')

@section('title', __('Laporan') . ' ' . \Carbon\Carbon::parse($startDate)->translatedFormat('M Y'))

@section('content')
<div class="mb-6 text-center">
    <h2 class="text-2xl font-bold text-white mb-1">{{ __('Laporan Keuangan') }}</h2>
    <h3 class="text-lg text-red-400 font-medium">{{ $fundName }}</h3>
    <p class="text-gray-400 mt-2 flex items-center justify-center">
        <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
        {{ \Carbon\Carbon::parse($startDate)->translatedFormat('d M Y') }} - {{ \Carbon\Carbon::parse($endDate)->translatedFormat('d M Y') }}
    </p>
</div>

<!-- Summary Cards -->
<div class="grid grid-cols-2 gap-4 mb-8">
    <!-- Starting Balance -->
    <div class="bg-[#1a1d2d] rounded-xl border border-gray-800 p-4 shadow-lg text-center col-span-2 sm:col-span-1">
        <div class="text-gray-400 text-xs mb-1 uppercase tracking-wider font-semibold">{{ __('Saldo Awal') }}</div>
        <div class="text-xl font-bold text-white">Rp {{ number_format($startingBalance, 0, ',', '.') }}</div>
    </div>
    
    <!-- Ending Balance -->
    <div class="bg-gradient-to-br from-[#1a1d2d] to-[#121420] rounded-xl border border-blue-800/50 p-4 shadow-lg text-center col-span-2 sm:col-span-1 ring-1 ring-blue-900/30">
        <div class="text-blue-400 text-xs mb-1 uppercase tracking-wider font-semibold">{{ __('Saldo Akhir') }}</div>
        <div class="text-2xl font-bold text-blue-100">Rp {{ number_format($endingBalance, 0, ',', '.') }}</div>
    </div>

    <!-- Total Incomes -->
    <div class="bg-[#1a1d2d] rounded-xl border border-green-900/30 p-4 shadow-lg flex flex-col items-center justify-center">
        <div class="w-8 h-8 rounded-full bg-green-900/40 text-green-500 flex items-center justify-center mb-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"></path></svg>
        </div>
        <div class="text-gray-400 text-xs mb-1 uppercase tracking-wider font-semibold">{{ __('Total Pemasukan') }}</div>
        <div class="text-lg font-bold text-green-400">+Rp {{ number_format($totalIncomes, 0, ',', '.') }}</div>
    </div>

    <!-- Total Expenses -->
    <div class="bg-[#1a1d2d] rounded-xl border border-red-900/30 p-4 shadow-lg flex flex-col items-center justify-center">
        <div class="w-8 h-8 rounded-full bg-red-900/40 text-red-500 flex items-center justify-center mb-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18"></path></svg>
        </div>
        <div class="text-gray-400 text-xs mb-1 uppercase tracking-wider font-semibold">{{ __('Total Pengeluaran') }}</div>
        <div class="text-lg font-bold text-red-400">-Rp {{ number_format($totalExpenses, 0, ',', '.') }}</div>
    </div>
</div>

<!-- Charts Row -->
<div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
    <!-- Income Chart -->
    <div class="bg-[#1a1d2d] rounded-xl border border-gray-800 p-5 shadow-lg">
        <h3 class="text-center text-sm font-semibold text-gray-300 mb-4">{{ __('Distribusi Pemasukan') }}</h3>
        @if($totalIncomes > 0)
            <div class="relative w-full h-48">
                <canvas id="incomeChart"></canvas>
            </div>
        @else
            <div class="h-48 flex items-center justify-center text-gray-500 text-sm italic">{{ __('Tidak ada pemasukan') }}</div>
        @endif
    </div>

    <!-- Expense Chart -->
    <div class="bg-[#1a1d2d] rounded-xl border border-gray-800 p-5 shadow-lg">
        <h3 class="text-center text-sm font-semibold text-gray-300 mb-4">{{ __('Distribusi Pengeluaran') }}</h3>
        @if($totalExpenses > 0)
            <div class="relative w-full h-48">
                <canvas id="expenseChart"></canvas>
            </div>
        @else
            <div class="h-48 flex items-center justify-center text-gray-500 text-sm italic">{{ __('Tidak ada pengeluaran') }}</div>
        @endif
    </div>
</div>

<!-- Simple List (No PII) -->
<div class="space-y-6">
    <!-- Incomes List -->
    <div class="bg-[#1a1d2d] rounded-xl border border-gray-800 shadow-lg overflow-hidden">
        <div class="p-4 bg-green-900/20 border-b border-gray-800">
            <h3 class="font-bold text-green-400 flex items-center">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                {{ __('Rincian Pemasukan') }}
            </h3>
        </div>
        <div class="divide-y divide-gray-800">
            @forelse($groupedIncomes as $parentName => $group)
                <div class="p-4 flex justify-between items-center hover:bg-gray-800/30 transition-colors">
                    <span class="text-gray-300 font-medium">{{ $parentName }}</span>
                    <span class="text-green-400 font-bold">Rp {{ number_format($group['total'], 0, ',', '.') }}</span>
                </div>
            @empty
                <div class="p-4 text-center text-gray-500 text-sm italic">{{ __('Data kosong') }}</div>
            @endforelse
        </div>
    </div>

    <!-- Expenses List -->
    <div class="bg-[#1a1d2d] rounded-xl border border-gray-800 shadow-lg overflow-hidden">
        <div class="p-4 bg-red-900/20 border-b border-gray-800">
            <h3 class="font-bold text-red-400 flex items-center">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"></path></svg>
                {{ __('Rincian Pengeluaran') }}
            </h3>
        </div>
        <div class="divide-y divide-gray-800">
            @forelse($groupedExpenses as $parentName => $group)
                <div class="p-4 flex justify-between items-center hover:bg-gray-800/30 transition-colors">
                    <span class="text-gray-300 font-medium">{{ $parentName }}</span>
                    <span class="text-red-400 font-bold">Rp {{ number_format($group['total'], 0, ',', '.') }}</span>
                </div>
            @empty
                <div class="p-4 text-center text-gray-500 text-sm italic">{{ __('Data kosong') }}</div>
            @endforelse
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    Chart.defaults.color = '#9ca3af';
    Chart.defaults.font.family = "'Inter', 'sans-serif'";

    const commonOptions = {
        responsive: true,
        maintainAspectRatio: false,
        cutout: '70%',
        plugins: {
            legend: {
                display: false
            },
            tooltip: {
                backgroundColor: '#1f2937',
                titleColor: '#f3f4f6',
                bodyColor: '#e5e7eb',
                borderColor: '#374151',
                borderWidth: 1,
                padding: 10,
                callbacks: {
                    label: function(context) {
                        return ' Rp ' + new Intl.NumberFormat('id-ID').format(context.parsed);
                    }
                }
            }
        }
    };

    // Prepare Income Data
    @if($totalIncomes > 0)
    const incomeLabels = {!! json_encode(array_keys($groupedIncomes)) !!};
    const incomeData = {!! json_encode(array_values(array_map(function($g) { return $g['total']; }, $groupedIncomes))) !!};
    
    new Chart(document.getElementById('incomeChart').getContext('2d'), {
        type: 'doughnut',
        data: {
            labels: incomeLabels,
            datasets: [{
                data: incomeData,
                backgroundColor: ['#22c55e', '#3b82f6', '#14b8a6', '#f59e0b', '#8b5cf6', '#ec4899'],
                borderWidth: 0
            }]
        },
        options: commonOptions
    });
    @endif

    // Prepare Expense Data
    @if($totalExpenses > 0)
    const expenseLabels = {!! json_encode(array_keys($groupedExpenses)) !!};
    const expenseData = {!! json_encode(array_values(array_map(function($g) { return $g['total']; }, $groupedExpenses))) !!};
    
    new Chart(document.getElementById('expenseChart').getContext('2d'), {
        type: 'doughnut',
        data: {
            labels: expenseLabels,
            datasets: [{
                data: expenseData,
                backgroundColor: ['#ef4444', '#f97316', '#eab308', '#a855f7', '#06b6d4', '#64748b'],
                borderWidth: 0
            }]
        },
        options: commonOptions
    });
    @endif
});
</script>
@endsection
