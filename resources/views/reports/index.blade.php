@extends('layouts.app')

@section('title', __('LPJ Reports'))

@section('content')
<div class="mb-8">
    <h1 class="text-2xl font-bold text-white mb-2">{{ __('Financial Reports (LPJ)') }}</h1>
    <p class="text-gray-400">{{ __('Generate official Laporan Pertanggungjawaban for the village assembly.') }}</p>
</div>

<div class="bg-[#1a1d2d] rounded-xl border border-gray-800 shadow-xl overflow-hidden max-w-2xl">
    <div class="p-6">
        <form action="{{ route('reports.generate') }}" method="GET">
            <!-- Date Range -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                    <label for="start_date" class="block text-sm font-medium text-gray-300 mb-2">{{ __('Start Date') }}</label>
                    <input type="date" name="start_date" id="start_date" required value="{{ request('start_date', now()->startOfMonth()->format('Y-m-d')) }}"
                           class="w-full bg-gray-900/50 border border-gray-700 rounded-lg px-4 py-2.5 text-white focus:outline-none focus:border-red-500 focus:ring-1 focus:ring-red-500 transition-colors">
                </div>
                <div>
                    <label for="end_date" class="block text-sm font-medium text-gray-300 mb-2">{{ __('End Date') }}</label>
                    <input type="date" name="end_date" id="end_date" required value="{{ request('end_date', now()->endOfMonth()->format('Y-m-d')) }}"
                           class="w-full bg-gray-900/50 border border-gray-700 rounded-lg px-4 py-2.5 text-white focus:outline-none focus:border-red-500 focus:ring-1 focus:ring-red-500 transition-colors">
                </div>
            </div>

            <!-- Fund Selection -->
            <div class="mb-8">
                <label for="fund_id" class="block text-sm font-medium text-gray-300 mb-2">{{ __('Select Fund') }}</label>
                <select name="fund_id" id="fund_id"
                        class="w-full bg-gray-900/50 border border-gray-700 rounded-lg px-4 py-2.5 text-white focus:outline-none focus:border-red-500 focus:ring-1 focus:ring-red-500 transition-colors">
                    <option value="">-- {{ __('Global (All Funds)') }} --</option>
                    @foreach($funds as $fund)
                        <option value="{{ $fund->fund_id }}" {{ request('fund_id') == $fund->fund_id ? 'selected' : '' }}>
                            {{ $fund->name }}
                        </option>
                    @endforeach
                </select>
                <p class="text-gray-500 text-xs mt-1">{{ __('Select a specific fund for targeted auditing, or leave blank for the consolidated village report.') }}</p>
            </div>

            <div class="flex justify-end pt-4 border-t border-gray-800">
                <button type="submit" class="px-6 py-2.5 bg-red-600 hover:bg-red-700 text-white font-medium rounded-lg shadow-lg shadow-red-900/30 transition-all flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    {{ __('Generate Preview') }}
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
