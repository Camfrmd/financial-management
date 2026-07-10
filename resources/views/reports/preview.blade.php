@extends('layouts.app')

@section('title', __('LPJ Preview'))
@section('back_button', true)
@section('back_url', route('reports.index'))

@section('content')
<div class="mb-6 flex flex-col md:flex-row justify-between items-start md:items-end gap-4">
    <div>
        <h1 class="text-2xl font-bold text-white mb-1">{{ __('Laporan Pertanggungjawaban (LPJ)') }}</h1>
        <p class="text-gray-400">{{ __('Period') }}: {{ \Carbon\Carbon::parse($startDate)->format('d M Y') }} - {{ \Carbon\Carbon::parse($endDate)->format('d M Y') }} | {{ __('Scope') }}: {{ $fundName }}</p>
    </div>
    <div class="flex space-x-3">
        <a href="{{ route('reports.export.excel', request()->all()) }}" class="px-4 py-2 bg-green-700 hover:bg-green-600 text-white font-medium rounded-lg shadow-lg shadow-green-900/30 transition-all flex items-center">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3M3 17V7a2 2 0 012-2h6l2 2h6a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2z"></path></svg>
            Excel
        </a>
        <a href="{{ route('reports.export.pdf', request()->all()) }}" class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white font-medium rounded-lg shadow-lg shadow-red-900/30 transition-all flex items-center" target="_blank">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
            PDF
        </a>
        <button type="button" onclick="copyShareLink()" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg shadow-lg shadow-blue-900/30 transition-all flex items-center">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z"></path></svg>
            <span id="shareBtnText">{{ __('Share (Public)') }}</span>
        </button>
    </div>
</div>

<script>
    function copyShareLink() {
        const url = "{{ $shareUrl }}";
        navigator.clipboard.writeText(url).then(() => {
            const btnText = document.getElementById('shareBtnText');
            const originalText = btnText.innerText;
            btnText.innerText = "{{ __('Copied!') }}";
            setTimeout(() => {
                btnText.innerText = originalText;
            }, 2000);
        });
    }
</script>

<div class="bg-[#1a1d2d] rounded-xl border border-gray-800 shadow-xl overflow-hidden mb-8">
    
    <!-- Starting Balance -->
    <div class="bg-gray-800/40 p-6 border-b border-gray-800 flex justify-between items-center">
        <h3 class="text-lg font-bold text-gray-300">{{ __('Starting Balance') }}</h3>
        <span class="text-xl font-bold {{ $startingBalance >= 0 ? 'text-green-500' : 'text-red-500' }}">Rp {{ number_format($startingBalance, 0, ',', '.') }}</span>
    </div>

    <!-- Incomes -->
    <div class="p-6 border-b border-gray-800">
        <h3 class="text-lg font-bold text-green-500 mb-4">{{ __('Incomes') }}</h3>
        @if(empty($groupedIncomes))
            <p class="text-gray-500 italic">{{ __('No income recorded in this period.') }}</p>
        @else
            <div class="space-y-4">
                @foreach($groupedIncomes as $parent => $data)
                    <div>
                        <div class="flex justify-between items-center bg-gray-900/50 p-3 rounded-lg border border-gray-700/50">
                            <span class="font-bold text-gray-200">{{ $parent }}</span>
                            <span class="font-bold text-green-400">Rp {{ number_format($data['total'], 0, ',', '.') }}</span>
                        </div>
                        <div class="mt-2 pl-4 pr-3 space-y-1">
                            @foreach($data['details'] as $child => $amount)
                                <div class="flex justify-between items-center text-sm border-b border-gray-800 border-dashed pb-1">
                                    <span class="text-gray-400">- {{ $child }}</span>
                                    <span class="text-gray-300">Rp {{ number_format($amount, 0, ',', '.') }}</span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
        <div class="mt-6 flex justify-between items-center pt-4 border-t border-gray-800">
            <span class="font-bold text-gray-400">{{ __('Total Incomes') }}</span>
            <span class="font-bold text-green-500 text-lg">Rp {{ number_format($totalIncomes, 0, ',', '.') }}</span>
        </div>
    </div>

    <!-- Expenses -->
    <div class="p-6 border-b border-gray-800">
        <h3 class="text-lg font-bold text-red-500 mb-4">{{ __('Expenses') }}</h3>
        @if(empty($groupedExpenses))
            <p class="text-gray-500 italic">{{ __('No expenses recorded in this period.') }}</p>
        @else
            <div class="space-y-4">
                @foreach($groupedExpenses as $parent => $data)
                    <div>
                        <div class="flex justify-between items-center bg-gray-900/50 p-3 rounded-lg border border-gray-700/50">
                            <span class="font-bold text-gray-200">{{ $parent }}</span>
                            <span class="font-bold text-red-400">Rp {{ number_format($data['total'], 0, ',', '.') }}</span>
                        </div>
                        <div class="mt-2 pl-4 pr-3 space-y-1">
                            @foreach($data['details'] as $child => $amount)
                                <div class="flex justify-between items-center text-sm border-b border-gray-800 border-dashed pb-1">
                                    <span class="text-gray-400">- {{ $child }}</span>
                                    <span class="text-gray-300">Rp {{ number_format($amount, 0, ',', '.') }}</span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
        <div class="mt-6 flex justify-between items-center pt-4 border-t border-gray-800">
            <span class="font-bold text-gray-400">{{ __('Total Expenses') }}</span>
            <span class="font-bold text-red-500 text-lg">Rp {{ number_format($totalExpenses, 0, ',', '.') }}</span>
        </div>
    </div>

    <!-- Ending Balance -->
    <div class="bg-gray-800/60 p-6 flex justify-between items-center">
        <h3 class="text-xl font-bold text-white">{{ __('Ending Balance') }}</h3>
        <span class="text-2xl font-bold {{ $endingBalance >= 0 ? 'text-green-500' : 'text-red-500' }}">Rp {{ number_format($endingBalance, 0, ',', '.') }}</span>
    </div>

</div>
@endsection
