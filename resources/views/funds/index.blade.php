@extends('layouts.app')

@section('title', __('Funds Management'))

@section('content')
<div class="mb-8 flex justify-between items-end">
    <div>
        <h1 class="text-2xl font-bold text-white mb-2">{{ __('Funds Management') }}</h1>
        <p class="text-gray-400">{{ __('Manage community funds and view their current balances.') }}</p>
    </div>
    <a href="{{ route('funds.create') }}" class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white font-medium rounded-lg shadow-lg shadow-red-900/30 transition-all flex items-center">
        <span class="mr-2 text-xl leading-none">+</span> {{ __('New Fund') }}
    </a>
</div>

@if(session('success'))
    <div class="mb-6 p-4 bg-green-900/30 border border-green-800/50 rounded-lg text-green-400 flex items-center shadow-lg">
        <svg class="w-5 h-5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
        {{ session('success') }}
    </div>
@endif

@if($errors->any())
    <div class="mb-6 p-4 bg-red-900/30 border border-red-800/50 rounded-lg text-red-400 flex items-center shadow-lg">
        <svg class="w-5 h-5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
        <ul class="list-disc pl-5">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
    @forelse($funds as $fund)
        <div class="bg-[#1a1d2d] rounded-xl border border-gray-800 shadow-xl overflow-hidden hover:border-gray-700 transition-all group flex flex-col">
            <div class="p-6 flex-grow">
                <div class="flex justify-between items-start mb-4">
                    <h3 class="text-lg font-bold text-white group-hover:text-red-400 transition-colors">{{ $fund->name }}</h3>
                </div>
                
                <div class="mb-6">
                    <p class="text-sm text-gray-500 mb-1">{{ __('Current Balance') }}</p>
                    <p class="text-3xl font-bold {{ $fund->current_balance >= 0 ? 'text-green-500' : 'text-red-500' }}">
                        Rp {{ number_format($fund->current_balance, 0, ',', '.') }}
                    </p>
                </div>

                @if($fund->description)
                    <p class="text-sm text-gray-400 mb-4 line-clamp-2" title="{{ $fund->description }}">{{ $fund->description }}</p>
                @endif

                <div class="space-y-2 mt-auto pt-4">
                    @if($fund->group)
                    <div class="flex items-center text-xs text-gray-400 bg-gray-800/50 px-2.5 py-1.5 rounded-md border border-gray-700/50">
                        <svg class="w-3.5 h-3.5 mr-1.5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                        <span class="truncate">{{ $fund->group->group_name }}</span>
                    </div>
                    @endif
                    
                    @if($fund->activity)
                    <div class="flex items-center text-xs text-gray-400 bg-gray-800/50 px-2.5 py-1.5 rounded-md border border-gray-700/50">
                        <svg class="w-3.5 h-3.5 mr-1.5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                        <span class="truncate">{{ $fund->activity->activity_name }}</span>
                    </div>
                    @endif
                </div>
            </div>
            
            <div class="bg-gray-800/30 px-6 py-3 border-t border-gray-800 flex justify-end space-x-4">
                <a href="{{ route('funds.edit', $fund->fund_id) }}" class="text-sm font-medium text-gray-400 hover:text-white transition-colors flex items-center">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                    {{ __('Edit') }}
                </a>
                <form action="{{ route('funds.destroy', $fund->fund_id) }}" method="POST" onsubmit="return confirm('{{ __('Are you sure you want to delete this fund? This action cannot be undone.') }}');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="text-sm font-medium text-red-500 hover:text-red-400 transition-colors flex items-center">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                        {{ __('Delete') }}
                    </button>
                </form>
            </div>
        </div>
    @empty
        <div class="col-span-full py-16 bg-[#1a1d2d] rounded-xl border border-gray-800 flex flex-col items-center justify-center text-center shadow-xl">
            <div class="w-16 h-16 bg-gray-800 rounded-full flex items-center justify-center mb-4">
                <svg class="w-8 h-8 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
            <h3 class="text-lg font-medium text-white mb-1">{{ __('No Funds Found') }}</h3>
            <p class="text-gray-400 mb-6 max-w-md">{{ __('You have not created any funds yet. Create your first fund to start tracking financial transactions.') }}</p>
            <a href="{{ route('funds.create') }}" class="px-5 py-2.5 bg-red-600 hover:bg-red-700 text-white font-medium rounded-lg shadow-lg shadow-red-900/30 transition-all flex items-center">
                <span class="mr-2 text-lg leading-none">+</span> {{ __('Create Your First Fund') }}
            </a>
        </div>
    @endforelse
</div>
@endsection
