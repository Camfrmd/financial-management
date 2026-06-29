<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem Keuangan Banjar - Dashboard</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <!-- Fallback if Vite is not running -->
    <script src="https://unpkg.com/@tailwindcss/browser@4"></script>
    <style>
        /* Minimal custom overrides for Tridatu strict flat design */
        body {
            font-family: 'Inter', sans-serif;
        }
    </style>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
</head>

<body class="bg-gray-900 text-gray-100 min-h-screen">

    <!-- En-tête -->
    <nav class="bg-gray-800 border-b-2 border-red-700 px-6 py-4 flex justify-between items-center">
        <div class="flex items-center space-x-4">
            <div class="bg-red-700 text-white font-bold p-2 rounded">SKB</div>
            <h1 class="text-xl font-semibold hidden sm:block">Sistem Keuangan Banjar</h1>
        </div>

        <div class="flex items-center space-x-6">
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

        @can('validate-transactions')
            <div class="mb-8 p-4 bg-gray-800 rounded-lg border border-yellow-600 shadow-sm">
                <div class="flex flex-col sm:flex-row justify-between items-center gap-4">
                    <div>
                        <h3 class="text-lg font-bold text-white">{{ __('Transactions awaiting validation') }}</h3>
                        <p class="text-gray-400 text-sm">{{ __('You have') }} <span class="text-yellow-500 font-bold">{{ $pendingCount }}</span> {{ __('transactions to review.') }}</p>
                    </div>
                    <a href="{{ url('/transactions/pending') }}" class="bg-yellow-600 hover:bg-yellow-500 text-white font-bold py-2 px-6 rounded shadow-lg transition duration-200 whitespace-nowrap">
                        {{ __('Review Queue') }}
                    </a>
                </div>
            </div>
        @endcan

        <!-- Carte Grand Chiffre -->
        <section class="mb-10">
            <div class="bg-white/5 border border-gray-700 rounded-lg p-8 shadow-sm">
                <h2 class="text-gray-400 text-sm font-semibold uppercase tracking-wider mb-2">{{ __('Total Fund Balance') }}</h2>
                <div class="text-5xl font-bold text-white">
                    Rp {{ number_format($totalBalance, 0, ',', '.') }}
                </div>
            </div>
        </section>

        <div class="flex justify-end mb-6">
            <a href="{{ url('/transactions/create') }}" class="bg-red-700 hover:bg-red-600 text-white font-bold py-2 px-6 rounded shadow-lg transition duration-200">
                + {{ __('New Transaction') }}
            </a>
        </div>

        <!-- Grille Principale -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">

            <!-- Dernières Transactions -->
            <section class="bg-gray-800 border border-gray-700 rounded-lg p-6 shadow-sm">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-lg font-semibold text-white">{{ __('Latest 5 Transactions') }}</h2>
                    <span class="text-xs text-gray-400 bg-gray-700 px-2 py-1 rounded">{{ __('Validated') }}</span>
                </div>

                @if($recentTransactions->isEmpty())
                    <p class="text-gray-500 text-sm italic">{{ __('No recent transactions found.') }}</p>
                @else
                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-sm text-gray-300">
                            <thead class="text-xs text-gray-400 uppercase bg-gray-700/50">
                                <tr>
                                    <th class="px-4 py-3 rounded-tl-md">{{ __('Date') }}</th>
                                    <th class="px-4 py-3">{{ __('Description') }}</th>
                                    <th class="px-4 py-3 text-right">{{ __('Amount') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($recentTransactions as $tx)
                                    <tr class="border-b border-gray-700 hover:bg-gray-700/20 transition-colors">
                                        <td class="px-4 py-3 whitespace-nowrap">
                                            {{ \Carbon\Carbon::parse($tx->date)->format('d/m/Y') }}</td>
                                        <td class="px-4 py-3">
                                            <div class="font-medium text-gray-200">{{ $tx->description }}</div>
                                            <div class="text-xs text-gray-500">
                                                {{ $tx->category->category_name ?? __('Uncategorized') }}</div>
                                        </td>
                                        <td
                                            class="px-4 py-3 text-right font-bold {{ $tx->type === 'income' ? 'text-green-500' : 'text-red-500' }}">
                                            {{ $tx->type === 'income' ? '+' : '-' }}Rp
                                            {{ number_format($tx->amount, 0, ',', '.') }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </section>

            <!-- Dépenses Planifiées / Activités Futures -->
            <section class="bg-gray-800 border border-gray-700 rounded-lg p-6 shadow-sm">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-lg font-semibold text-white">{{ __('Upcoming Events & Activities') }}</h2>
                    <span
                        class="text-xs text-red-400 border border-red-500/30 bg-red-500/10 px-2 py-1 rounded">{{ __('Planning') }}</span>
                </div>

                @if($upcomingActivities->isEmpty())
                    <p class="text-gray-500 text-sm italic">{{ __('No upcoming events planned.') }}</p>
                @else
                    <ul class="space-y-4">
                        @foreach($upcomingActivities as $activity)
                            <li class="flex items-start p-4 bg-gray-900/50 border border-gray-700 rounded-md">
                                <div
                                    class="flex-shrink-0 bg-red-900/40 text-red-500 p-3 rounded-md mr-4 text-center min-w-[60px]">
                                    <div class="text-xs font-semibold uppercase">
                                        {{ \Carbon\Carbon::parse($activity->start_date)->translatedFormat('M') }}</div>
                                    <div class="text-xl font-bold">
                                        {{ \Carbon\Carbon::parse($activity->start_date)->format('d') }}</div>
                                </div>
                                <div>
                                    <h3 class="text-gray-100 font-medium">{{ $activity->activity_name }}</h3>
                                    <p class="text-gray-400 text-sm mt-1">
                                        {{ __('Status:') }} <span class="text-gray-300">{{ ucfirst($activity->status) }}</span>
                                    </p>
                                    @if($activity->end_date)
                                        <p class="text-gray-500 text-xs mt-1">
                                            {{ __('Until') }} {{ \Carbon\Carbon::parse($activity->end_date)->format('d/m/Y') }}
                                        </p>
                                    @endif
                                </div>
                            </li>
                        @endforeach
                    </ul>
                @endif
            </section>

        </div>
    </main>

</body>

</html>