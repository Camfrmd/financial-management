<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="theme-color" content="#161925">
    <link rel="manifest" href="/manifest.json">
    <link rel="apple-touch-icon" href="https://upload.wikimedia.org/wikipedia/commons/thumb/3/3b/Eo_circle_red_white_letter-s.svg/192px-Eo_circle_red_white_letter-s.svg.png">
    <title>Sistem Keuangan Banjar @hasSection('title') - @yield('title') @endif</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <!-- Fallback if Vite is not running -->
    <script src="https://unpkg.com/@tailwindcss/browser@4"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/idb@8/build/umd.js"></script>
    <script src="/js/offline-sync.js" defer></script>
    <style>
        body { font-family: 'Inter', sans-serif; }
        /* Premium custom scrollbar */
        ::-webkit-scrollbar { width: 8px; height: 8px; }
        ::-webkit-scrollbar-track { background: #111827; }
        ::-webkit-scrollbar-thumb { background: #374151; border-radius: 4px; }
        ::-webkit-scrollbar-thumb:hover { background: #4B5563; }
    </style>
</head>
<body class="bg-[#0f111a] text-gray-200 min-h-screen flex flex-col antialiased selection:bg-red-500/30">

    <!-- Premium Navbar -->
    <nav class="bg-[#161925] border-b border-gray-800/60 sticky top-0 z-50 backdrop-blur-sm bg-opacity-90">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                
                <!-- Left Section: Logo & Links -->
                <div class="flex items-center">
                    <!-- Logo -->
                    <a href="{{ route('dashboard') }}" class="flex items-center space-x-3 group mr-8">
                        <div class="bg-gradient-to-br from-red-600 to-red-800 text-white font-bold px-2.5 py-1.5 rounded-lg shadow-lg shadow-red-900/20 group-hover:shadow-red-900/40 transition-all duration-300">
                            SKB
                        </div>
                        <div class="hidden lg:flex flex-col">
                            <span class="text-lg font-bold text-white leading-tight tracking-wide">Sistem Keuangan</span>
                            <span class="text-xs text-red-500 font-medium tracking-widest uppercase">Banjar</span>
                        </div>
                    </a>

                    <!-- Desktop Nav Links -->
                    <div class="hidden md:flex space-x-1">
                        <a href="{{ route('dashboard') }}" 
                           class="px-4 py-2 rounded-lg text-sm font-medium transition-all duration-200 flex items-center
                           {{ request()->routeIs('dashboard') ? 'bg-gray-800/50 text-white shadow-inner border border-gray-700/50' : 'text-gray-400 hover:text-white hover:bg-gray-800/30' }}">
                            {{ __('Dashboard') }}
                        </a>

                        <a href="{{ route('transactions.index') }}" 
                           class="px-4 py-2 rounded-lg text-sm font-medium transition-all duration-200 flex items-center
                           {{ request()->routeIs('transactions.index') ? 'bg-gray-800/50 text-white shadow-inner border border-gray-700/50' : 'text-gray-400 hover:text-white hover:bg-gray-800/30' }}">
                            {{ __('Financial Journal') }}
                        </a>
                        
                        @can('manage-funds')
                        <a href="{{ route('funds.index') }}" 
                           class="px-4 py-2 rounded-lg text-sm font-medium transition-all duration-200 flex items-center
                           {{ request()->routeIs('funds.*') ? 'bg-gray-800/50 text-white shadow-inner border border-gray-700/50' : 'text-gray-400 hover:text-white hover:bg-gray-800/30' }}">
                            {{ __('Funds Management') }}
                        </a>
                        <a href="{{ route('categories.index') }}" 
                           class="px-4 py-2 rounded-lg text-sm font-medium transition-all duration-200 flex items-center
                           {{ request()->routeIs('categories.*') ? 'bg-gray-800/50 text-white shadow-inner border border-gray-700/50' : 'text-gray-400 hover:text-white hover:bg-gray-800/30' }}">
                            {{ __('Chart of Accounts') }}
                        </a>
                        @endcan
                        
                        <a href="{{ route('transactions.create') }}" 
                           class="px-4 py-2 rounded-lg text-sm font-medium transition-all duration-200 flex items-center
                           {{ request()->routeIs('transactions.create') ? 'bg-gray-800/50 text-white shadow-inner border border-gray-700/50' : 'text-gray-400 hover:text-white hover:bg-gray-800/30' }}">
                            <span class="text-red-500 mr-1.5 font-bold">+</span> {{ __('New Transaction') }}
                        </a>

                        @can('validate-transactions')
                        <a href="{{ route('transactions.pending') }}" 
                           class="px-4 py-2 rounded-lg text-sm font-medium transition-all duration-200 flex items-center
                           {{ request()->routeIs('transactions.pending') ? 'bg-yellow-900/20 text-yellow-400 border border-yellow-700/30' : 'text-gray-400 hover:text-yellow-400 hover:bg-yellow-900/10' }}">
                            {{ __('Review Queue') }}
                        </a>
                        @endcan

                        @can('manage-users')
                        <a href="{{ route('users.index') }}" 
                           class="px-4 py-2 rounded-lg text-sm font-medium transition-all duration-200 flex items-center
                           {{ request()->routeIs('users.*') ? 'bg-gray-800/50 text-white shadow-inner border border-gray-700/50' : 'text-gray-400 hover:text-white hover:bg-gray-800/30' }}">
                            {{ __('User Management') }}
                        </a>
                        @endcan

                        @can('view-reports')
                        <a href="{{ route('cashflow.index') }}" 
                           class="px-4 py-2 rounded-lg text-sm font-medium transition-all duration-200 flex items-center
                           {{ request()->routeIs('cashflow.*') ? 'bg-[#581c87] text-[#f3e8ff] shadow-inner border border-[#7e22ce]' : 'text-gray-400 hover:text-white hover:bg-gray-800/30' }}">
                            {{ __('Cash Flow') }}
                        </a>
                        <a href="{{ route('reports.index') }}" 
                           class="px-4 py-2 rounded-lg text-sm font-medium transition-all duration-200 flex items-center
                           {{ request()->routeIs('reports.*') ? 'bg-gray-800/50 text-white shadow-inner border border-gray-700/50' : 'text-gray-400 hover:text-white hover:bg-gray-800/30' }}">
                            {{ __('Reports (LPJ)') }}
                        </a>
                        <a href="{{ route('activity.index') }}" 
                           class="px-4 py-2 rounded-lg text-sm font-medium transition-all duration-200 flex items-center
                           {{ request()->routeIs('activity.*') ? 'bg-blue-900/30 text-blue-200 shadow-inner border border-blue-800/50' : 'text-gray-400 hover:text-white hover:bg-gray-800/30' }}">
                            {{ __('Audit Trail') }}
                        </a>
                        @endcan
                    </div>
                </div>

                <!-- Right Section: Language, User & Mobile Toggle -->
                <div class="flex items-center space-x-6">
                    
                    <!-- Language Switcher -->
                    <div class="flex bg-gray-900 p-1 rounded-lg">
                        @foreach(config('app.supported_locales') as $code => $label)
                            <a href="{{ route('lang.switch', $code) }}" class="px-2.5 py-1 text-xs font-bold rounded transition-colors {{ app()->getLocale() == $code ? 'bg-red-600 text-white shadow' : 'text-gray-500 hover:text-gray-300' }}">
                                {{ $label }}
                            </a>
                        @endforeach
                    </div>

                    <!-- User Profile & Logout -->
                    <div class="hidden md:flex items-center space-x-4 pl-6 border-l border-gray-800">
                        <div class="flex flex-col text-right">
                            <span class="text-sm font-semibold text-white leading-tight">{{ Auth::user()->username }}</span>
                            <span class="text-xs text-gray-500 font-medium">{{ ucfirst(Auth::user()->role) }}</span>
                        </div>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="p-2 text-gray-400 hover:text-red-400 bg-gray-800/50 hover:bg-red-900/20 border border-gray-700 hover:border-red-900/50 rounded-lg transition-all duration-200 group" title="{{ __('Logout') }}">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 group-hover:translate-x-0.5 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                                </svg>
                            </button>
                        </form>
                    </div>

                    <!-- Mobile Menu Button -->
                    <div class="md:hidden flex items-center">
                        <button id="mobile-menu-btn" class="p-2 rounded-lg text-gray-400 hover:text-white hover:bg-gray-800 focus:outline-none transition-colors">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path id="menu-icon-bars" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                                <path id="menu-icon-close" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </div>

                </div>
            </div>
        </div>

        <!-- Mobile Dropdown Menu -->
        <div id="mobile-menu" class="md:hidden hidden bg-[#1a1d2d] border-t border-gray-800 shadow-xl">
            <div class="px-4 py-3 space-y-1">
                <a href="{{ route('dashboard') }}" class="block px-3 py-2.5 rounded-lg text-base font-medium {{ request()->routeIs('dashboard') ? 'bg-gray-800 text-white' : 'text-gray-400 hover:bg-gray-800/50 hover:text-white' }}">{{ __('Dashboard') }}</a>
                <a href="{{ route('transactions.index') }}" class="block px-3 py-2.5 rounded-lg text-base font-medium {{ request()->routeIs('transactions.index') ? 'bg-gray-800 text-white' : 'text-gray-400 hover:bg-gray-800/50 hover:text-white' }}">{{ __('Financial Journal') }}</a>
                @can('manage-funds')
                <a href="{{ route('funds.index') }}" class="block px-3 py-2.5 rounded-lg text-base font-medium {{ request()->routeIs('funds.*') ? 'bg-gray-800 text-white' : 'text-gray-400 hover:bg-gray-800/50 hover:text-white' }}">{{ __('Funds Management') }}</a>
                <a href="{{ route('categories.index') }}" class="block px-3 py-2.5 rounded-lg text-base font-medium {{ request()->routeIs('categories.*') ? 'bg-gray-800 text-white' : 'text-gray-400 hover:bg-gray-800/50 hover:text-white' }}">{{ __('Chart of Accounts') }}</a>
                @endcan
                <a href="{{ route('transactions.create') }}" class="block px-3 py-2.5 rounded-lg text-base font-medium {{ request()->routeIs('transactions.create') ? 'bg-gray-800 text-white' : 'text-gray-400 hover:bg-gray-800/50 hover:text-white' }}">{{ __('New Transaction') }}</a>
                @can('validate-transactions')
                <a href="{{ route('transactions.pending') }}" class="block px-3 py-2.5 rounded-lg text-base font-medium {{ request()->routeIs('transactions.pending') ? 'bg-yellow-900/30 text-yellow-400' : 'text-gray-400 hover:bg-gray-800/50 hover:text-yellow-400' }}">{{ __('Review Queue') }}</a>
                @endcan
                @can('manage-users')
                <a href="{{ route('users.index') }}" class="block px-3 py-2.5 rounded-lg text-base font-medium {{ request()->routeIs('users.*') ? 'bg-gray-800 text-white' : 'text-gray-400 hover:bg-gray-800/50 hover:text-white' }}">{{ __('User Management') }}</a>
                @endcan
                @can('view-reports')
                <a href="{{ route('cashflow.index') }}" class="block px-3 py-2.5 rounded-lg text-base font-medium {{ request()->routeIs('cashflow.*') ? 'bg-[#581c87] text-[#f3e8ff]' : 'text-gray-400 hover:bg-gray-800/50 hover:text-white' }}">{{ __('Cash Flow') }}</a>
                <a href="{{ route('reports.index') }}" class="block px-3 py-2.5 rounded-lg text-base font-medium {{ request()->routeIs('reports.*') ? 'bg-gray-800 text-white' : 'text-gray-400 hover:bg-gray-800/50 hover:text-white' }}">{{ __('Reports (LPJ)') }}</a>
                <a href="{{ route('activity.index') }}" class="block px-3 py-2.5 rounded-lg text-base font-medium {{ request()->routeIs('activity.*') ? 'bg-blue-900/50 text-blue-200' : 'text-gray-400 hover:bg-gray-800/50 hover:text-white' }}">{{ __('Audit Trail') }}</a>
                @endcan
            </div>
            
            <div class="pt-4 pb-4 border-t border-gray-800 px-5">
                <!-- Mobile Language -->
                <div class="flex space-x-2">
                    @foreach(config('app.supported_locales') as $code => $label)
                        <a href="{{ route('lang.switch', $code) }}" class="px-3 py-1.5 text-xs font-bold rounded {{ app()->getLocale() == $code ? 'bg-red-600 text-white' : 'text-gray-500' }}">
                            {{ $label }}
                        </a>
                    @endforeach
                </div>
                
                <!-- Mobile User -->
                <div class="flex justify-between items-center">
                    <div>
                        <div class="text-base font-medium text-white">{{ Auth::user()->username }}</div>
                        <div class="text-sm text-gray-500">{{ ucfirst(Auth::user()->role) }}</div>
                    </div>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white text-sm font-medium rounded-lg shadow transition-colors">{{ __('Logout') }}</button>
                    </form>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content Area -->
    <main class="max-w-7xl mx-auto w-full px-4 sm:px-6 lg:px-8 py-8 flex-grow">
        @hasSection('back_button')
            <div class="mb-6">
                <a href="@yield('back_url', url()->previous())" class="inline-flex items-center px-3 py-1.5 text-sm font-medium text-gray-400 bg-gray-800/30 border border-gray-700/50 rounded-lg hover:text-white hover:bg-gray-800 transition-all group shadow-sm">
                    <svg class="w-4 h-4 mr-1.5 text-gray-500 group-hover:text-white group-hover:-translate-x-1 transition-all" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                    @yield('back_text', __('Back'))
                </a>
            </div>
        @endif

        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-[#161925] border-t border-gray-800/60 py-6 mt-auto">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col md:flex-row justify-between items-center text-sm text-gray-500">
            <div>
                &copy; {{ date('Y') }} Sistem Keuangan Banjar. All rights reserved.
            </div>
            <div class="mt-2 md:mt-0 flex items-center space-x-2">
                <span class="w-2 h-2 rounded-full bg-green-500"></span>
                <span>System Online</span>
            </div>
        </div>
    </footer>

    <script>
        // Mobile menu toggle script
        const btn = document.getElementById('mobile-menu-btn');
        const menu = document.getElementById('mobile-menu');
        const iconBars = document.getElementById('menu-icon-bars');
        const iconClose = document.getElementById('menu-icon-close');
        
        btn.addEventListener('click', () => {
            menu.classList.toggle('hidden');
            iconBars.classList.toggle('hidden');
            iconClose.classList.toggle('hidden');
        });

        // Register Service Worker for PWA
        if ('serviceWorker' in navigator) {
            window.addEventListener('load', () => {
                navigator.serviceWorker.register('/sw.js')
                    .then(registration => {
                        console.log('SW registered:', registration);
                    })
                    .catch(error => {
                        console.log('SW registration failed:', error);
                    });
            });
        }
    </script>
</body>
</html>
