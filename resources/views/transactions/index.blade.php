@extends('layouts.app')

@section('title', __('Financial Journal'))

@section('content')
    <div class="bg-[#161925] border border-gray-800 rounded-xl p-8 shadow-xl">
        
        <!-- Header -->
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 border-b border-gray-800 pb-5">
            <div>
                <h2 class="text-3xl font-bold text-white tracking-tight">{{ __('Financial Journal') }}</h2>
                <p class="text-gray-500 mt-1 text-sm">{{ __('Complete history of village financial activities') }}</p>
            </div>
            
            <a href="{{ route('transactions.create') }}" class="mt-4 md:mt-0 bg-red-600 hover:bg-red-700 text-white font-semibold py-2.5 px-5 rounded-lg shadow-lg shadow-red-900/30 transition-all duration-200 flex items-center group">
                <svg class="w-5 h-5 mr-2 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                {{ __('New Entry') }}
            </a>
        </div>

        <!-- Toolbar (Search & Filter) -->
        <div class="flex flex-col md:flex-row justify-between items-center mb-6 space-y-4 md:space-y-0">
            <div class="relative w-full md:w-96">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="h-5 w-5 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>
                <input type="text" class="bg-gray-900 border border-gray-700 text-gray-300 text-sm rounded-lg focus:ring-red-500 focus:border-red-500 block w-full pl-10 p-2.5 placeholder-gray-500 shadow-inner" placeholder="{{ __('Search transactions...') }}">
            </div>
            <div class="flex space-x-3 w-full md:w-auto">
                <button class="flex-1 md:flex-none flex items-center justify-center px-4 py-2.5 bg-gray-800 border border-gray-700 text-gray-300 rounded-lg text-sm font-medium hover:bg-gray-700 hover:text-white transition-colors">
                    <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path>
                    </svg>
                    {{ __('Filter') }}
                </button>
            </div>
        </div>

        <!-- Table -->
        <div class="overflow-x-auto bg-gray-900/50 rounded-lg border border-gray-800">
            <table class="w-full text-left text-sm text-gray-300">
                <thead class="text-xs text-gray-400 uppercase bg-gray-800/80 border-b border-gray-700">
                    <tr>
                        <th class="px-5 py-4 font-semibold">{{ __('Date') }}</th>
                        <th class="px-5 py-4 font-semibold">{{ __('Description') }}</th>
                        <th class="px-5 py-4 font-semibold text-right">{{ __('Amount') }}</th>
                        <th class="px-5 py-4 font-semibold text-right">{{ __('Running Balance') }}</th>
                        <th class="px-5 py-4 font-semibold text-center">{{ __('Status') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-800/50">
                    @forelse($transactions as $tx)
                        <tr class="hover:bg-gray-800/30 transition-colors group">
                            <td class="px-5 py-4 whitespace-nowrap">
                                <div class="text-gray-200 font-medium">{{ \Carbon\Carbon::parse($tx->date)->format('d M Y') }}</div>
                                <div class="text-xs text-gray-500">{{ $tx->creator->username ?? __('System') }}</div>
                            </td>
                            <td class="px-5 py-4">
                                <div class="font-medium text-white mb-0.5 group-hover:text-red-400 transition-colors">{{ $tx->description }}</div>
                                <div class="text-xs text-gray-400 flex items-center">
                                    <span class="truncate max-w-[150px]" title="{{ $tx->category->category_name ?? __('Uncategorized') }}">{{ $tx->category->category_name ?? __('Uncategorized') }}</span>
                                    <span class="mx-1.5 text-gray-600">&bull;</span>
                                    <span class="text-gray-500 font-medium">{{ $tx->fund->name ?? __('Unknown Fund') }}</span>
                                </div>
                            </td>
                            <td class="px-5 py-4 text-right whitespace-nowrap">
                                <div class="font-bold text-base {{ $tx->type === 'income' ? 'text-green-500' : 'text-red-500' }}">
                                    {{ $tx->type === 'income' ? '+' : '-' }}Rp {{ number_format($tx->amount, 0, ',', '.') }}
                                </div>
                            </td>
                            <td class="px-5 py-4 text-right whitespace-nowrap">
                                @if($tx->validation_status === 'validated')
                                    <div class="font-bold text-base text-white">
                                        Rp {{ number_format($tx->running_balance, 0, ',', '.') }}
                                    </div>
                                @else
                                    <div class="text-sm text-gray-500 italic">-</div>
                                @endif
                            </td>
                            <td class="px-5 py-4 text-center whitespace-nowrap">
                                @if($tx->validation_status === 'validated')
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-green-900/30 text-green-400 border border-green-800/50">
                                        <span class="w-1.5 h-1.5 mr-1.5 rounded-full bg-green-500"></span>
                                        {{ __('Validated') }}
                                    </span>
                                @elseif($tx->validation_status === 'pending')
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-yellow-900/30 text-yellow-400 border border-yellow-800/50">
                                        <span class="w-1.5 h-1.5 mr-1.5 rounded-full bg-yellow-500 animate-pulse"></span>
                                        {{ __('Pending') }}
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-red-900/30 text-red-400 border border-red-800/50">
                                        <span class="w-1.5 h-1.5 mr-1.5 rounded-full bg-red-500"></span>
                                        {{ __('Rejected') }}
                                    </span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-5 py-12 text-center">
                                <div class="flex flex-col items-center justify-center text-gray-500">
                                    <svg class="w-12 h-12 mb-3 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                    <p class="text-lg font-medium">{{ __('No transactions found.') }}</p>
                                    <p class="text-sm mt-1">{{ __('Start by creating a new entry.') }}</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($transactions->hasPages())
            <div class="mt-6">
                {{ $transactions->links() }}
            </div>
        @endif
        
    </div>
@endsection
