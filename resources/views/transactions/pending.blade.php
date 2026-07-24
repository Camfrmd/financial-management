@extends('layouts.app')

@section('title', 'Pending Transactions')
@section('back_button', true)
@section('back_url', route('dashboard'))
@section('back_text', __('Back to Dashboard'))

@section('content')
    <div class="bg-gray-800 border border-gray-700 rounded-lg p-8 shadow-sm">
        <div class="flex justify-between items-center mb-6 border-b border-gray-700 pb-4">
            <h2 class="text-2xl font-bold text-white">{{ __('Pending Transactions') }}</h2>
            <span class="text-xs text-orange-400 border border-orange-500/30 bg-orange-500/10 px-3 py-1.5 rounded-full font-semibold">{{ __('Requires your approval') }}</span>
        </div>

        @if(session('success'))
            <div class="bg-green-900/50 border border-green-500/50 text-green-400 px-4 py-3 rounded mb-6">
                {{ session('success') }}
            </div>
        @endif

        @if($transactions->isEmpty())
            <div class="text-center py-10">
                <p class="text-gray-500 text-lg">{{ __('No pending transactions at the moment.') }}</p>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm text-gray-300">
                    <thead class="text-xs text-gray-400 uppercase bg-gray-700/50">
                        <tr>
                            <th class="px-4 py-3 rounded-tl-md">{{ __('Date') }}</th>
                            <th class="px-4 py-3">{{ __('Creator') }}</th>
                            <th class="px-4 py-3">{{ __('Category & Detail') }}</th>
                            <th class="px-4 py-3 text-right">{{ __('Amount') }}</th>
                            <th class="px-4 py-3 text-center rounded-tr-md">{{ __('Actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($transactions as $tx)
                            <tr class="border-b border-gray-700 hover:bg-gray-700/20 transition-colors">
                                <td class="px-4 py-3 whitespace-nowrap">
                                    {{ \Carbon\Carbon::parse($tx->date)->format('d/m/Y') }}
                                </td>
                                <td class="px-4 py-3 whitespace-nowrap text-gray-400">
                                    {{ $tx->creator->username ?? __('Unknown') }}
                                </td>
                                <td class="px-4 py-3">
                                    <div class="font-medium text-gray-200">{{ $tx->description }}</div>
                                    <div class="text-xs text-gray-500">
                                        {{ $tx->category->category_name ?? __('Uncategorized') }} &bull; {{ $tx->fund->name ?? __('Unknown Fund') }}
                                    </div>
                                </td>
                                <td class="px-4 py-3 text-right font-bold {{ $tx->type === 'income' ? 'text-green-500' : 'text-red-500' }}">
                                    {{ $tx->type === 'income' ? '+' : '-' }}Rp {{ number_format($tx->amount, 0, ',', '.') }}
                                </td>
                                <td class="px-4 py-3">
                                    <div class="flex justify-center space-x-2">
                                        <form action="{{ route('transactions.approve', $tx->transaction_id) }}" method="POST">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="bg-green-600 hover:bg-green-500 text-white px-3 py-1.5 rounded text-xs font-semibold shadow transition-colors">
                                                {{ __('Approve') }}
                                            </button>
                                        </form>
                                        <form action="{{ route('transactions.reject', $tx->transaction_id) }}" method="POST">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="bg-red-700 hover:bg-red-600 text-white px-3 py-1.5 rounded text-xs font-semibold shadow transition-colors">
                                                {{ __('Reject') }}
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
@endsection
