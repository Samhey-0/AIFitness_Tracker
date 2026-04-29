<!DOCTYPE html>
<html lang="en" x-data="{ darkMode: localStorage.getItem('theme') === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches) }" x-init="$watch('darkMode', val => localStorage.setItem('theme', val ? 'dark' : 'light'))" :class="{ 'dark': darkMode }">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Fitness Tracker</title>
    <link rel="manifest" href="/manifest.json">
    <meta name="theme-color" content="#3b82f6">
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        primary: '#3b82f6', // Electric Blue
                        dark: '#0f172a',
                        darker: '#020617',
                        card: '#1e293b'
                    }
                }
            }
        }
    </script>
    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <!-- Lucide Icons -->
    <script src="https://unpkg.com/lucide@latest"></script>
    <style>
        [x-cloak] { display: none !important; }
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap');
        body { font-family: 'Inter', sans-serif; }
        /* Custom scrollbar */
        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 4px; }
        .dark ::-webkit-scrollbar-thumb { background: #334155; }
    </style>
</head>
<body class="bg-gray-50 dark:bg-darker text-gray-900 dark:text-gray-100 min-h-screen transition-colors duration-300 font-sans antialiased flex selection:bg-primary/30">

    <!-- Desktop Sidebar (Hidden on mobile) -->
    @auth
    <aside class="hidden md:flex flex-col w-72 bg-white dark:bg-card border-r border-gray-100 dark:border-gray-800 h-screen sticky top-0 shadow-2xl z-40 transition-colors duration-300">
        <div class="p-8 flex items-center gap-3">
            <div class="bg-gradient-to-br from-primary to-blue-400 p-2.5 rounded-xl shadow-lg shadow-primary/30">
                <i data-lucide="activity" class="w-7 h-7 text-white"></i>
            </div>
            <h1 class="text-2xl font-extrabold tracking-tight bg-clip-text text-transparent bg-gradient-to-r from-primary to-blue-400">FitTrack</h1>
        </div>
        
        <nav class="flex-1 px-4 space-y-2 mt-4">
            <p class="px-4 text-xs font-bold text-gray-400 uppercase tracking-widest mb-4">Main Menu</p>
            
            <a href="{{ route('dashboard') }}" class="group flex items-center gap-3 px-4 py-3.5 rounded-2xl {{ request()->routeIs('dashboard') ? 'bg-primary text-white shadow-lg shadow-primary/30' : 'text-gray-500 hover:bg-gray-50 dark:hover:bg-gray-800/50 hover:text-gray-900 dark:hover:text-gray-100' }} transition-all">
                <i data-lucide="layout-dashboard" class="w-5 h-5 {{ request()->routeIs('dashboard') ? 'text-white' : 'text-gray-400 group-hover:text-primary' }} transition-colors"></i> 
                <span class="font-semibold">Dashboard</span>
            </a>
            <a href="{{ route('track') }}" class="group flex items-center gap-3 px-4 py-3.5 rounded-2xl {{ request()->routeIs('track') ? 'bg-primary text-white shadow-lg shadow-primary/30' : 'text-gray-500 hover:bg-gray-50 dark:hover:bg-gray-800/50 hover:text-gray-900 dark:hover:text-gray-100' }} transition-all">
                <i data-lucide="play-circle" class="w-5 h-5 {{ request()->routeIs('track') ? 'text-white' : 'text-gray-400 group-hover:text-primary' }} transition-colors"></i> 
                <span class="font-semibold">Track Workout</span>
            </a>
            <a href="{{ route('stats') }}" class="group flex items-center gap-3 px-4 py-3.5 rounded-2xl {{ request()->routeIs('stats') ? 'bg-primary text-white shadow-lg shadow-primary/30' : 'text-gray-500 hover:bg-gray-50 dark:hover:bg-gray-800/50 hover:text-gray-900 dark:hover:text-gray-100' }} transition-all">
                <i data-lucide="bar-chart-2" class="w-5 h-5 {{ request()->routeIs('stats') ? 'text-white' : 'text-gray-400 group-hover:text-primary' }} transition-colors"></i> 
                <span class="font-semibold">Statistics</span>
            </a>
            <a href="{{ route('ai.generator') }}" class="group flex items-center gap-3 px-4 py-3.5 rounded-2xl {{ request()->routeIs('ai.generator') ? 'bg-primary text-white shadow-lg shadow-primary/30' : 'text-gray-500 hover:bg-gray-50 dark:hover:bg-gray-800/50 hover:text-gray-900 dark:hover:text-gray-100' }} transition-all">
                <i data-lucide="sparkles" class="w-5 h-5 {{ request()->routeIs('ai.generator') ? 'text-white' : 'text-gray-400 group-hover:text-primary' }} transition-colors"></i> 
                <span class="font-semibold">AI Coach & Nutritionist</span>
            </a>
        </nav>
        
        <div class="p-6 border-t border-gray-100 dark:border-gray-800">
             <div class="flex items-center gap-3 mb-4 p-2">
                 <div class="w-12 h-12 rounded-full bg-gradient-to-br from-indigo-100 to-blue-100 dark:from-indigo-900/50 dark:to-blue-900/50 flex items-center justify-center font-bold text-primary border-2 border-white dark:border-gray-800 shadow-md">
                     {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
                 </div>
                 <div class="flex-1 overflow-hidden">
                     <p class="text-sm font-bold truncate dark:text-white">{{ auth()->user()->name }}</p>
                     <p class="text-xs text-gray-500 truncate">Member</p>
                 </div>
             </div>
             <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="flex items-center justify-center gap-2 px-4 py-3 w-full rounded-xl text-red-600 bg-red-50 hover:bg-red-100 dark:bg-red-500/10 dark:hover:bg-red-500/20 dark:text-red-400 transition-colors font-bold">
                    <i data-lucide="log-out" class="w-4 h-4"></i> Logout
                </button>
             </form>
        </div>
    </aside>
    @endauth

    <!-- Main Content Area -->
    <div class="flex-1 flex flex-col min-h-screen relative w-full pb-20 md:pb-0 overflow-x-hidden">
        
        <!-- Top Header -->
        <header class="bg-white/80 dark:bg-darker/80 backdrop-blur-xl sticky top-0 z-30 border-b border-gray-100 dark:border-gray-800 px-6 py-4 flex justify-between items-center transition-colors duration-300">
            <div class="md:hidden flex items-center gap-3">
                 <div class="bg-gradient-to-br from-primary to-blue-400 p-1.5 rounded-lg shadow-md shadow-primary/20">
                    <i data-lucide="activity" class="w-5 h-5 text-white"></i>
                </div>
                <h1 class="text-xl font-extrabold text-gray-900 dark:text-white tracking-tight">FitTrack</h1>
            </div>
            <div class="hidden md:block">
                <h2 class="text-2xl font-bold text-gray-800 dark:text-gray-100">
                    @yield('header_title', 'Overview')
                </h2>
            </div>
            
            <div class="flex items-center gap-2">
                <button @click="darkMode = !darkMode" class="p-2.5 rounded-full bg-gray-100 dark:bg-card text-gray-600 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-700 transition-all hover:scale-105 active:scale-95 shadow-sm">
                    <i data-lucide="moon" x-show="!darkMode" class="w-5 h-5"></i>
                    <i data-lucide="sun" x-show="darkMode" class="w-5 h-5" x-cloak></i>
                </button>
            </div>
        </header>

        <!-- Dynamic Content -->
        <main class="flex-1 p-4 md:p-8 max-w-7xl mx-auto w-full">
            @yield('content')
        </main>
        
        <!-- Mobile Bottom Nav -->
        @auth
        <nav class="md:hidden fixed bottom-0 left-0 w-full bg-white/90 dark:bg-card/90 backdrop-blur-xl border-t border-gray-200 dark:border-gray-800 px-6 py-3 flex justify-between items-center z-50 shadow-[0_-10px_40px_rgba(0,0,0,0.05)] pb-safe">
            <a href="{{ route('dashboard') }}" class="flex flex-col items-center gap-1 {{ request()->routeIs('dashboard') ? 'text-primary' : 'text-gray-400 hover:text-gray-600 dark:hover:text-gray-300' }} transition-colors px-2">
                <i data-lucide="layout-dashboard" class="w-6 h-6 {{ request()->routeIs('dashboard') ? 'fill-primary/20' : '' }}"></i>
                <span class="text-[10px] font-bold">Home</span>
            </a>
            <a href="{{ route('track') }}" class="flex flex-col items-center gap-1 {{ request()->routeIs('track') ? 'text-primary' : 'text-gray-400 hover:text-gray-600 dark:hover:text-gray-300' }} transition-colors relative">
                <div class="bg-gradient-to-br from-primary to-blue-500 text-white p-4 rounded-full -mt-12 shadow-xl shadow-primary/40 transform transition hover:scale-110 border-4 border-gray-50 dark:border-darker">
                    <i data-lucide="activity" class="w-6 h-6"></i>
                </div>
                <span class="text-[10px] font-bold mt-1">Track</span>
            </a>
            <a href="{{ route('stats') }}" class="flex flex-col items-center gap-1 {{ request()->routeIs('stats') ? 'text-primary' : 'text-gray-400 hover:text-gray-600 dark:hover:text-gray-300' }} transition-colors px-2">
                <i data-lucide="bar-chart-2" class="w-6 h-6 {{ request()->routeIs('stats') ? 'fill-primary/20' : '' }}"></i>
                <span class="text-[10px] font-bold">Stats</span>
            </a>
        </nav>
        @endauth
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            lucide.createIcons();
        });
        
        // Register Service Worker for PWA
        if ('serviceWorker' in navigator) {
            window.addEventListener('load', () => {
                navigator.serviceWorker.register('/sw.js').catch(err => console.log('SW error: ', err));
            });
        }
    </script>
    @stack('scripts')
</body>
</html>
