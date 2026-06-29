<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem Keuangan Banjar @hasSection('title') - @yield('title') @endif</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <!-- Fallback if Vite is not running -->
    <script src="https://unpkg.com/@tailwindcss/browser@4"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="bg-gray-900 text-gray-100 min-h-screen flex flex-col">

    <!-- Navbar Global (Top-Nav) -->
    <nav class="bg-gray-800 border-b-2 border-red-700 shadow-md">
        <div class="container mx-auto px-4 max-w-6xl">
            <div class="flex justify-between items-center h-16">
                
                <!-- Logo & Menu Principal (Desktop) -->
                <div class="flex items-center space-x-8">
                    <a href="{{ route('dashboard') }}" class="flex items-center space-x-3">
                        <div class="bg-red-700 text-white font-bold px-2 py-1 rounded shadow">SKB</div>
                        <span class="text-xl font-semibold hidden md:block text-white">Sistem Keuangan Banjar</span>
                    </a>

                    <!-- Navigation Links (Desktop) -->
                    <div class="hidden md:flex space-x-1">
                        <a href="{{ route('dashboard') }}" 
                           class="px-3 py-2 rounded-md text-sm font-medium transition-colors border-b-2 
                           {{ request()->routeIs('dashboard') ? 'border-red-500 text-white' : 'border-transparent text-gray-300 hover:text-white hover:border-gray-500' }}">
                            Dashboard
                        </a>
                        
                        <a href="{{ route('transactions.create') }}" 
                           class="px-3 py-2 rounded-md text-sm font-medium transition-colors border-b-2 
                           {{ request()->routeIs('transactions.create') ? 'border-red-500 text-white' : 'border-transparent text-gray-300 hover:text-white hover:border-gray-500' }}">
                            + Transaksi Baru
                        </a>

                        @can('validate-transactions')
                        <a href="{{ route('transactions.pending') }}" 
                           class="px-3 py-2 rounded-md text-sm font-medium transition-colors border-b-2 
                           {{ request()->routeIs('transactions.pending') ? 'border-yellow-500 text-yellow-400' : 'border-transparent text-gray-300 hover:text-yellow-400 hover:border-yellow-600' }}">
                            Validasi Transaksi
                        </a>
                        @endcan

                        @can('manage-users')
                        <a href="{{ route('users.index') }}" 
                           class="px-3 py-2 rounded-md text-sm font-medium transition-colors border-b-2 
                           {{ request()->routeIs('users.*') ? 'border-red-500 text-white' : 'border-transparent text-gray-300 hover:text-white hover:border-gray-500' }}">
                            Manajemen Pengguna
                        </a>
                        @endcan
                    </div>
                </div>

                <!-- Langues & Utilisateur & Déconnexion -->
                <div class="hidden md:flex items-center space-x-6">
                    <!-- Language Switcher -->
                    <div class="flex space-x-2 text-xs font-semibold">
                        <a href="{{ route('lang.switch', 'id') }}" class="{{ app()->getLocale() == 'id' ? 'text-red-500 border-b border-red-500' : 'text-gray-400 hover:text-white' }}">ID</a>
                        <span class="text-gray-600">|</span>
                        <a href="{{ route('lang.switch', 'en') }}" class="{{ app()->getLocale() == 'en' ? 'text-red-500 border-b border-red-500' : 'text-gray-400 hover:text-white' }}">EN</a>
                        <span class="text-gray-600">|</span>
                        <a href="{{ route('lang.switch', 'fr') }}" class="{{ app()->getLocale() == 'fr' ? 'text-red-500 border-b border-red-500' : 'text-gray-400 hover:text-white' }}">FR</a>
                    </div>

                    <div class="flex items-center space-x-4 border-l border-gray-700 pl-6">
                        <div class="text-sm text-gray-300">
                            Om Swastiastu, <span class="text-white font-semibold">{{ Auth::user()->username }}</span>
                            <span class="text-xs text-gray-500 ml-1">({{ ucfirst(Auth::user()->role) }})</span>
                        </div>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="border border-gray-600 hover:bg-gray-700 text-gray-300 hover:text-white px-3 py-1.5 rounded transition-colors text-sm font-medium">
                                Logout
                            </button>
                        </form>
                    </div>
                </div>
                        <span class="text-xs text-gray-500 ml-1">({{ ucfirst(Auth::user()->role) }})</span>
                    </div>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="border border-gray-600 hover:bg-gray-700 text-gray-300 hover:text-white px-3 py-1.5 rounded transition-colors text-sm font-medium">
                            Logout
                        </button>
                    </form>
                </div>

                <!-- Hamburger Menu Button (Mobile) -->
                <div class="md:hidden flex items-center">
                    <button id="mobile-menu-btn" class="text-gray-300 hover:text-white focus:outline-none">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path id="menu-icon-bars" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                            <path id="menu-icon-close" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        <!-- Mobile Menu -->
        <div id="mobile-menu" class="md:hidden hidden bg-gray-800 border-t border-gray-700">
            <div class="px-2 pt-2 pb-3 space-y-1 sm:px-3">
                <a href="{{ route('dashboard') }}" class="block px-3 py-2 rounded-md text-base font-medium {{ request()->routeIs('dashboard') ? 'bg-gray-900 text-white border-l-4 border-red-500' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }}">{{ __('Dashboard') }}</a>
                
                <a href="{{ route('transactions.create') }}" class="block px-3 py-2 rounded-md text-base font-medium {{ request()->routeIs('transactions.create') ? 'bg-gray-900 text-white border-l-4 border-red-500' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }}">+ {{ __('New Transaction') }}</a>

                @can('validate-transactions')
                <a href="{{ route('transactions.pending') }}" class="block px-3 py-2 rounded-md text-base font-medium {{ request()->routeIs('transactions.pending') ? 'bg-gray-900 text-yellow-400 border-l-4 border-yellow-500' : 'text-gray-300 hover:bg-gray-700 hover:text-yellow-400' }}">{{ __('Review Queue') }}</a>
                @endcan

                @can('manage-users')
                <a href="{{ route('users.index') }}" class="block px-3 py-2 rounded-md text-base font-medium {{ request()->routeIs('users.*') ? 'bg-gray-900 text-white border-l-4 border-red-500' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }}">{{ __('User Management') }}</a>
                @endcan
            </div>
            <div class="pt-4 pb-3 border-t border-gray-700">
                <div class="flex items-center px-5">
                    <div class="text-base font-medium leading-none text-white">{{ Auth::user()->username }}</div>
                    <div class="text-sm font-medium leading-none text-gray-400 mt-1">{{ ucfirst(Auth::user()->role) }}</div>
                </div>
                <div class="mt-3 px-2 space-y-1">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="w-full text-left block px-3 py-2 rounded-md text-base font-medium text-gray-400 hover:text-white hover:bg-gray-700">{{ __('Logout') }}</button>
                    </form>
                </div>
            </div>
        </div>
    </nav>

    <!-- Contenu Principal -->
    <main class="container mx-auto px-4 py-8 max-w-6xl flex-grow">
        @hasSection('back_button')
            <div class="mb-4">
                <a href="@yield('back_url', url()->previous())" class="inline-flex items-center text-sm text-gray-400 hover:text-white transition-colors group">
                    <svg class="w-4 h-4 mr-2 group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                    @yield('back_text', 'Kembali')
                </a>
            </div>
        @endif

        @yield('content')
    </main>

    <footer class="bg-gray-900 border-t border-gray-800 py-6 mt-auto">
        <div class="container mx-auto px-4 text-center text-gray-500 text-sm">
            &copy; {{ date('Y') }} Sistem Keuangan Banjar. All rights reserved.
        </div>
    </footer>

    <script>
        // Simple script to toggle mobile menu
        document.getElementById('mobile-menu-btn').addEventListener('click', function() {
            var menu = document.getElementById('mobile-menu');
            var iconBars = document.getElementById('menu-icon-bars');
            var iconClose = document.getElementById('menu-icon-close');
            
            menu.classList.toggle('hidden');
            iconBars.classList.toggle('hidden');
            iconClose.classList.toggle('hidden');
        });
    </script>
</body>
</html>
