<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('Pending Transactions') }} - Sistem Keuangan Banjar</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <!-- Fallback if Vite is not running -->
    <script src="https://unpkg.com/@tailwindcss/browser@4"></script>
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
    </style>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
</head>

<body class="bg-gray-900 text-gray-100 min-h-screen">

    <nav class="bg-gray-800 border-b-2 border-red-700 px-6 py-4 flex justify-between items-center">
        <div class="flex items-center space-x-4">
            <div class="bg-red-700 text-white font-bold p-2 rounded">SKB</div>
            <h1 class="text-xl font-semibold hidden sm:block">Sistem Keuangan Banjar</h1>
        </div>

        <div class="flex items-center space-x-6">
            <a href="{{ route('dashboard') }}" class="text-gray-300 hover:text-white transition-colors text-sm font-medium">
                {{ __('Back to Dashboard') }}
            </a>
            <div class="text-gray-300">
                Om Swastiastu, <span class="text-white font-semibold">{{ Auth::user()->username }}</span>
            </div>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit"
                    class="border border-gray-600 hover:bg-gray-700 text-gray-200 px-4 py-2 rounded transition-colors text-sm font-medium">
                    {{ __('Logout') }}
                </button>
            </form>
        </div>
    </nav>

    <main class="container mx-auto px-4 py-8 max-w-6xl">
        
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
    </main>
</body>
</html>
