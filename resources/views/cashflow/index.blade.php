@extends('layouts.app')

@section('title', __('Cash Flow'))

@section('content')
<div class="bg-[#161925] border border-gray-800 rounded-xl p-8 shadow-xl max-w-5xl mx-auto">
    
    <!-- Header & Month Navigator -->
    <div class="flex flex-col md:flex-row justify-between items-center mb-8 border-b border-gray-800 pb-5">
        <div>
            <h2 class="text-3xl font-bold text-white tracking-tight">{{ __('Cash Flow Statement') }}</h2>
            <p class="text-gray-500 mt-1 text-sm">{{ __('Monthly summary of incomes and expenses') }}</p>
        </div>
        
        <div class="mt-4 md:mt-0 flex items-center bg-gray-900 rounded-lg p-1 border border-gray-700">
            <a href="{{ route('cashflow.index', ['month' => $date->copy()->subMonth()->format('Y-m')]) }}" class="p-2 text-gray-400 hover:text-white transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
            </a>
            <div class="px-4 font-semibold text-white min-w-[140px] text-center">
                {{ $date->format('F Y') }}
            </div>
            <a href="{{ route('cashflow.index', ['month' => $date->copy()->addMonth()->format('Y-m')]) }}" class="p-2 text-gray-400 hover:text-white transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
            </a>
        </div>
    </div>

    <!-- ERP Table -->
    @php
        // Calculate total rows for rowspan: 
        // 1 (Income Header) + Income count + 1 (Expense Header) + Expense count + 1 (Closing Balance) + 1 (Last Month Balance, technically part of income in mockup, but we'll put it first)
        $incomeCount = $incomes->count();
        $expenseCount = $expenses->count();
        $rowSpan = 1 + ($incomeCount > 0 ? $incomeCount : 1) + 1 + ($expenseCount > 0 ? $expenseCount : 1) + 1 + 1; // +1 for Last Month Balance
    @endphp

    <div class="overflow-x-auto rounded-lg border border-[#a855f7] shadow-[0_0_15px_rgba(168,85,247,0.15)] bg-[#ede9fe] dark:bg-[#1f1a2e]">
        <table class="w-full text-left text-sm border-collapse">
            <thead class="text-xs uppercase bg-[#d8b4fe] dark:bg-[#581c87] text-[#4c1d95] dark:text-[#f3e8ff] border-b border-[#a855f7]">
                <tr>
                    <th class="px-5 py-3 font-bold border-r border-[#a855f7] w-1/5 text-center">{{ __('Date') }}</th>
                    <th class="px-5 py-3 font-bold border-r border-[#a855f7]">{{ __('Description') }}</th>
                    <th class="px-5 py-3 font-bold w-1/4 text-center">{{ __('Amount') }}</th>
                </tr>
            </thead>
            <tbody class="text-[#3b0764] dark:text-[#e9d5ff]">
                
                <!-- ROW 1: Date Cell + Income Header -->
                <tr class="border-b border-[#c084fc] dark:border-[#7e22ce]">
                    <td rowspan="{{ $rowSpan }}" class="px-5 py-4 border-r border-[#c084fc] dark:border-[#7e22ce] text-center font-bold bg-[#f3e8ff] dark:bg-[#2e1065] align-top text-base">
                        {{ $date->format('m/Y') }}
                    </td>
                    <td class="px-5 py-2 font-bold bg-[#e9d5ff] dark:bg-[#4c1d95] border-r border-[#c084fc] dark:border-[#7e22ce]">
                        {{ __('Income') }}
                    </td>
                    <td class="px-5 py-2 font-bold bg-[#e9d5ff] dark:bg-[#4c1d95] text-right">
                        Rp {{ number_format($lastMonthBalance + $totalIncome, 0, ',', '.') }}
                    </td>
                </tr>

                <!-- Last Month Balance (Treated as Income for display) -->
                <tr class="border-b border-[#d8b4fe] dark:border-[#6b21a8] bg-[#faf5ff] dark:bg-[#3b0764]">
                    <td class="px-5 py-2 border-r border-[#d8b4fe] dark:border-[#6b21a8] flex items-center">
                        <span class="w-1.5 h-1.5 rounded-full bg-[#9333ea] mr-2"></span>
                        {{ __('Last Month\'s Balance') }}
                    </td>
                    <td class="px-5 py-2 text-right">
                        Rp {{ number_format($lastMonthBalance, 0, ',', '.') }}
                    </td>
                </tr>

                <!-- Income Details -->
                @forelse($incomes as $income)
                <tr class="border-b border-[#d8b4fe] dark:border-[#6b21a8] bg-[#faf5ff] dark:bg-[#3b0764]">
                    <td class="px-5 py-2 border-r border-[#d8b4fe] dark:border-[#6b21a8] flex items-center">
                        <span class="w-1.5 h-1.5 rounded-full bg-[#9333ea] mr-2"></span>
                        {{ $income->description }}
                    </td>
                    <td class="px-5 py-2 text-right">
                        Rp {{ number_format($income->amount, 0, ',', '.') }}
                    </td>
                </tr>
                @empty
                <tr class="border-b border-[#d8b4fe] dark:border-[#6b21a8] bg-[#faf5ff] dark:bg-[#3b0764]">
                    <td class="px-5 py-2 border-r border-[#d8b4fe] dark:border-[#6b21a8] text-center italic opacity-70">
                        {{ __('No income recorded') }}
                    </td>
                    <td class="px-5 py-2 text-right">
                        Rp 0
                    </td>
                </tr>
                @endforelse

                <!-- Expense Header -->
                <tr class="border-b border-[#c084fc] dark:border-[#7e22ce]">
                    <td class="px-5 py-2 font-bold bg-[#e9d5ff] dark:bg-[#4c1d95] border-r border-[#c084fc] dark:border-[#7e22ce]">
                        {{ __('Expense') }}
                    </td>
                    <td class="px-5 py-2 font-bold bg-[#e9d5ff] dark:bg-[#4c1d95] text-right">
                        Rp {{ number_format($totalExpense, 0, ',', '.') }}
                    </td>
                </tr>

                <!-- Expense Details -->
                @forelse($expenses as $expense)
                <tr class="border-b border-[#d8b4fe] dark:border-[#6b21a8] bg-[#faf5ff] dark:bg-[#3b0764]">
                    <td class="px-5 py-2 border-r border-[#d8b4fe] dark:border-[#6b21a8] flex items-center">
                        <span class="w-1.5 h-1.5 rounded-full bg-[#9333ea] mr-2"></span>
                        {{ $expense->description }}
                    </td>
                    <td class="px-5 py-2 text-right">
                        Rp {{ number_format($expense->amount, 0, ',', '.') }}
                    </td>
                </tr>
                @empty
                <tr class="border-b border-[#d8b4fe] dark:border-[#6b21a8] bg-[#faf5ff] dark:bg-[#3b0764]">
                    <td class="px-5 py-2 border-r border-[#d8b4fe] dark:border-[#6b21a8] text-center italic opacity-70">
                        {{ __('No expenses recorded') }}
                    </td>
                    <td class="px-5 py-2 text-right">
                        Rp 0
                    </td>
                </tr>
                @endforelse

                <!-- Month's Balance -->
                <tr class="bg-[#d8b4fe] dark:bg-[#581c87] text-[#4c1d95] dark:text-[#f3e8ff]">
                    <td class="px-5 py-3 font-bold border-r border-[#a855f7]">
                        {{ $date->format('F Y') }} {{ __('Balance') }}
                    </td>
                    <td class="px-5 py-3 font-bold text-right">
                        Rp {{ number_format($currentBalance, 0, ',', '.') }}
                    </td>
                </tr>

            </tbody>
        </table>
    </div>
</div>
@endsection
