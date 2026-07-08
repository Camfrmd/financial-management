@extends('layouts.app')

@section('title', __('Chart of Accounts'))

@section('content')
<div class="mb-8 flex justify-between items-end">
    <div>
        <h1 class="text-2xl font-bold text-white mb-2">{{ __('Chart of Accounts') }}</h1>
        <p class="text-gray-400">{{ __('Organize your income and expense categories (MYOB Accounting Logic).') }}</p>
    </div>
    <a href="{{ route('categories.create') }}" class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white font-medium rounded-lg shadow-lg shadow-red-900/30 transition-all flex items-center">
        <span class="mr-2 text-xl leading-none">+</span> {{ __('New Category') }}
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

<div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
    <!-- Income Accounts Column -->
    <div class="bg-[#1a1d2d] rounded-xl border border-gray-800 shadow-xl overflow-hidden flex flex-col">
        <div class="p-5 border-b border-gray-800 bg-gray-800/20 flex items-center">
            <div class="w-8 h-8 rounded-lg bg-green-900/40 text-green-500 flex items-center justify-center mr-3 border border-green-800/50">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
            </div>
            <h2 class="text-xl font-bold text-white">{{ __('Income Accounts') }}</h2>
        </div>
        <div class="p-6 flex-grow">
            @if($incomeCategories->isEmpty())
                <p class="text-gray-500 italic text-center py-4">{{ __('No income categories configured.') }}</p>
            @else
                <div class="space-y-4">
                    @foreach($incomeCategories as $category)
                        <div class="bg-[#222538] rounded-lg border border-gray-700/50 p-4 transition-all hover:border-gray-600">
                            <div class="flex justify-between items-center mb-2">
                                <h3 class="text-base font-bold text-gray-200">{{ $category->category_name }}</h3>
                                <div class="flex space-x-3">
                                    <a href="{{ route('categories.edit', $category->category_id) }}" class="text-gray-400 hover:text-white transition-colors" title="{{ __('Edit') }}">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                    </a>
                                    <form action="{{ route('categories.destroy', $category->category_id) }}" method="POST" onsubmit="return confirm('{{ __('Are you sure?') }}');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-gray-400 hover:text-red-500 transition-colors" title="{{ __('Delete') }}">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                        </button>
                                    </form>
                                </div>
                            </div>
                            
                            @if($category->children->count() > 0)
                                <div class="mt-3 pl-4 border-l-2 border-gray-700/50 space-y-2">
                                    @foreach($category->children as $child)
                                        <div class="flex justify-between items-center group">
                                            <span class="text-sm text-gray-400 flex items-center">
                                                <svg class="w-3.5 h-3.5 mr-2 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                                                {{ $child->category_name }}
                                            </span>
                                            <div class="flex space-x-2">
                                                <a href="{{ route('categories.edit', $child->category_id) }}" class="p-1 text-gray-400 hover:text-white transition-colors" title="{{ __('Edit') }}">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                                </a>
                                                <form action="{{ route('categories.destroy', $child->category_id) }}" method="POST" onsubmit="return confirm('{{ __('Are you sure?') }}');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="p-1 text-gray-400 hover:text-red-500 transition-colors" title="{{ __('Delete') }}">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>

    <!-- Expense Accounts Column -->
    <div class="bg-[#1a1d2d] rounded-xl border border-gray-800 shadow-xl overflow-hidden flex flex-col">
        <div class="p-5 border-b border-gray-800 bg-gray-800/20 flex items-center">
            <div class="w-8 h-8 rounded-lg bg-red-900/40 text-red-500 flex items-center justify-center mr-3 border border-red-800/50">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"></path></svg>
            </div>
            <h2 class="text-xl font-bold text-white">{{ __('Expense Accounts') }}</h2>
        </div>
        <div class="p-6 flex-grow">
            @if($expenseCategories->isEmpty())
                <p class="text-gray-500 italic text-center py-4">{{ __('No expense categories configured.') }}</p>
            @else
                <div class="space-y-4">
                    @foreach($expenseCategories as $category)
                        <div class="bg-[#222538] rounded-lg border border-gray-700/50 p-4 transition-all hover:border-gray-600">
                            <div class="flex justify-between items-center mb-2">
                                <h3 class="text-base font-bold text-gray-200">{{ $category->category_name }}</h3>
                                <div class="flex space-x-3">
                                    <a href="{{ route('categories.edit', $category->category_id) }}" class="text-gray-400 hover:text-white transition-colors" title="{{ __('Edit') }}">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                    </a>
                                    <form action="{{ route('categories.destroy', $category->category_id) }}" method="POST" onsubmit="return confirm('{{ __('Are you sure?') }}');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-gray-400 hover:text-red-500 transition-colors" title="{{ __('Delete') }}">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                        </button>
                                    </form>
                                </div>
                            </div>
                            
                            @if($category->children->count() > 0)
                                <div class="mt-3 pl-4 border-l-2 border-gray-700/50 space-y-2">
                                    @foreach($category->children as $child)
                                        <div class="flex justify-between items-center group">
                                            <span class="text-sm text-gray-400 flex items-center">
                                                <svg class="w-3.5 h-3.5 mr-2 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                                                {{ $child->category_name }}
                                            </span>
                                            <div class="flex space-x-2">
                                                <a href="{{ route('categories.edit', $child->category_id) }}" class="p-1 text-gray-400 hover:text-white transition-colors" title="{{ __('Edit') }}">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                                </a>
                                                <form action="{{ route('categories.destroy', $child->category_id) }}" method="POST" onsubmit="return confirm('{{ __('Are you sure?') }}');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="p-1 text-gray-400 hover:text-red-500 transition-colors" title="{{ __('Delete') }}">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
