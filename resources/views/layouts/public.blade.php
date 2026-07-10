<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Laporan Keuangan') - {{ config('app.name', 'Digital Village') }}</title>

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        brand: {
                            50: '#f0fdf4',
                            100: '#dcfce7',
                            500: '#22c55e',
                            600: '#16a34a',
                            700: '#15803d',
                            900: '#14532d',
                        }
                    }
                }
            }
        }
    </script>
    
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body class="bg-[#121420] text-gray-200 antialiased font-sans min-h-screen flex flex-col">
    <!-- Header -->
    <header class="bg-gradient-to-r from-red-900 to-[#1a1d2d] shadow-lg sticky top-0 z-50 border-b border-red-800/50">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-4 flex justify-between items-center">
            <div class="flex items-center">
                <div class="w-10 h-10 rounded-full bg-red-600 flex items-center justify-center mr-3 shadow-lg shadow-red-900/50">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                </div>
                <div>
                    <h1 class="text-white font-bold text-lg leading-tight">{{ config('app.name', 'Digital Village') }}</h1>
                    <p class="text-red-200 text-xs">{{ __('Public Transparency') }}</p>
                </div>
            </div>
            
            <div class="flex items-center space-x-2">
                @foreach(config('app.supported_locales') as $code => $label)
                    <a href="{{ route('lang.switch', $code) }}" class="text-sm font-medium transition-colors px-3 py-1.5 rounded-full border {{ app()->getLocale() === $code ? 'text-white bg-red-900/60 border-red-500/50' : 'text-red-300 hover:text-white bg-red-950/30 border-red-800/30' }}">
                        {{ $label }}
                    </a>
                @endforeach
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="flex-grow w-full max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-[#1a1d2d] border-t border-gray-800 py-6 mt-8">
        <div class="max-w-3xl mx-auto px-4 text-center">
            <p class="text-sm text-gray-500">
                &copy; {{ date('Y') }} {{ config('app.name') }}. {{ __('Generated automatically for public transparency.') }}
            </p>
        </div>
    </footer>
</body>
</html>
