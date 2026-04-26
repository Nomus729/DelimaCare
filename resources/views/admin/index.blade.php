<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Admin Panel DelimaCare - Manajemen klinik kesehatan ibu dan anak.">
    <title>Admin Panel - DelimaCare</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: { extend: {} }
        }
    </script>
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

    <style>
        [x-cloak] { display: none !important; }
        body { font-family: 'Inter', sans-serif; }

        /* Sidebar active indicator */
        .nav-item-active::before {
            content: '';
            position: absolute;
            left: 0; top: 50%;
            transform: translateY(-50%);
            width: 3px; height: 60%;
            background: var(--gradient-main);
            border-radius: 0 4px 4px 0;
        }

        :root {
            --gradient-main: linear-gradient(135deg, #0D9488 0%, #06B6D4 100%);
        }

        /* Custom scrollbar */
        ::-webkit-scrollbar { width: 5px; height: 5px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: #CBD5E1; border-radius: 10px; }
        html.dark ::-webkit-scrollbar-thumb { background: #334155; }

        /* Stat card gradient top borders */
        .stat-card-teal { background: linear-gradient(180deg, rgba(13,148,136,0.08) 0%, transparent 40%); }
        .stat-card-blue { background: linear-gradient(180deg, rgba(6,182,212,0.08) 0%, transparent 40%); }
        .stat-card-amber { background: linear-gradient(180deg, rgba(245,158,11,0.08) 0%, transparent 40%); }
        .stat-card-emerald { background: linear-gradient(180deg, rgba(16,185,129,0.08) 0%, transparent 40%); }

        /* Pulse animation for status dots */
        @keyframes pulse-ring {
            0% { transform: scale(0.8); opacity: 1; }
            80%, 100% { transform: scale(2); opacity: 0; }
        }
        .pulse-ring::before {
            content: '';
            position: absolute;
            inset: 0;
            border-radius: 50%;
            background: currentColor;
            animation: pulse-ring 1.5s cubic-bezier(0.215, 0.61, 0.355, 1) infinite;
        }

        /* Sidebar transition */
        .sidebar-link {
            position: relative;
            transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .sidebar-link:hover { transform: translateX(2px); }

        /* Count-up animation placeholder */
        @keyframes countUp {
            from { opacity: 0; transform: translateY(8px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .animate-count { animation: countUp 0.5s ease-out forwards; }

        /* Dark mode body background */
        body { background-color: #F8FFFE; }
        html.dark body { background-color: #0B1120; }
    </style>

    <script>
        if (localStorage.getItem('delimacare-dark') === 'true' ||
            (localStorage.getItem('delimacare-dark') === null && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        }
    </script>
</head>

<body class="text-gray-900 antialiased flex h-screen overflow-hidden dark:text-gray-100" x-data="adminPanel()">

    {{-- ============================================================ --}}
    {{-- SIDEBAR --}}
    {{-- ============================================================ --}}
    <aside class="hidden md:flex flex-col w-72 bg-white/80 backdrop-blur-xl border-r border-gray-100 dark:bg-[#1E293B]/90 dark:border-gray-800 h-full flex-shrink-0 z-20 shadow-[4px_0_24px_rgba(0,0,0,0.03)]">

        {{-- Logo --}}
        <div class="h-20 flex items-center px-7 border-b border-gray-100/60 dark:border-gray-800/60">
            <a href="{{ route('home') }}" class="flex items-center gap-3 group">
                <div class="w-10 h-10 rounded-xl flex items-center justify-center shadow-md shadow-teal-500/25 group-hover:scale-105 group-hover:rotate-3 transition-all duration-300" style="background: var(--gradient-main);">
                    <svg class="w-6 h-6 text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
                </div>
                <div>
                    <span class="text-lg font-extrabold tracking-tight text-gray-900 dark:text-white block">DelimaCare</span>
                    <span class="text-[10px] font-bold text-teal-600 dark:text-teal-400 uppercase tracking-widest">Admin Panel</span>
                </div>
            </a>
        </div>

        {{-- Nav --}}
        <nav class="flex-1 px-4 py-6 overflow-y-auto space-y-1">
            <p class="text-[10px] font-extrabold text-gray-400 uppercase tracking-[0.15em] mb-4 px-4 dark:text-gray-500">Menu Utama</p>

            @php
                $menus = [
                    ['id' => 'dashboard', 'label' => 'Dashboard', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M4 5a1 1 0 011-1h4a1 1 0 011 1v4a1 1 0 01-1 1H5a1 1 0 01-1-1V5zM14 5a1 1 0 011-1h4a1 1 0 011 1v4a1 1 0 01-1 1h-4a1 1 0 01-1-1V5zM4 15a1 1 0 011-1h4a1 1 0 011 1v4a1 1 0 01-1 1H5a1 1 0 01-1-1v-4zM14 15a1 1 0 011-1h4a1 1 0 011 1v4a1 1 0 01-1 1h-4a1 1 0 01-1-1v-4z"/>'],
                    ['id' => 'konten', 'label' => 'Kelola Konten', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>'],
                    ['id' => 'inventori', 'label' => 'Inventori Obat', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>'],
                    ['id' => 'keuangan', 'label' => 'Keuangan', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>'],
                    ['id' => 'laporan', 'label' => 'Laporan Pengunjung', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z"/>'],
                    ['id' => 'reservasi', 'label' => 'Reservasi', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>'],
                    ['id' => 'rekam_medis', 'label' => 'Rekam Medis', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>'],
                ];
            @endphp

            @foreach($menus as $menu)
            <button @click="switchMenu('{{ $menu['id'] }}')"
                    class="sidebar-link w-full flex items-center gap-3.5 px-4 py-3.5 rounded-xl text-sm font-semibold transition-all relative overflow-hidden"
                    :class="activeMenu === '{{ $menu['id'] }}' ? 'text-white shadow-lg shadow-teal-500/20 nav-item-active' : 'text-gray-600 hover:bg-teal-50/70 hover:text-teal-700 dark:text-gray-400 dark:hover:bg-gray-800/50 dark:hover:text-teal-300'">
                <div x-show="activeMenu === '{{ $menu['id'] }}'" class="absolute inset-0 rounded-xl" style="background: var(--gradient-main);" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"></div>
                <svg class="w-5 h-5 relative z-10 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24"
                     :class="activeMenu === '{{ $menu['id'] }}' ? 'text-white' : 'text-gray-400 group-hover:text-teal-500'">{!! $menu['icon'] !!}</svg>
                <span class="relative z-10">{{ $menu['label'] }}</span>
            </button>
            @endforeach
        </nav>

        {{-- User Info + Logout --}}
        <div class="p-4 border-t border-gray-100/60 dark:border-gray-800/60 space-y-3">
            @auth
            <div class="flex items-center gap-3 px-3 py-3 rounded-xl bg-teal-50/60 dark:bg-teal-900/20">
                <div class="w-9 h-9 rounded-full flex items-center justify-center text-white text-sm font-bold shadow-sm flex-shrink-0" style="background: var(--gradient-main);">
                    {{ strtoupper(substr(Auth::user()->username, 0, 1)) }}
                </div>
                <div class="min-w-0">
                    <p class="text-sm font-bold text-gray-900 dark:text-white truncate">{{ Auth::user()->username }}</p>
                    <p class="text-[11px] font-medium text-teal-600 dark:text-teal-400">Administrator</p>
                </div>
            </div>
            @endauth
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="w-full flex items-center gap-3 px-4 py-2.5 rounded-xl text-sm font-semibold text-red-500 hover:bg-red-50 hover:text-red-600 transition-all dark:text-red-400 dark:hover:bg-red-500/10">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                    Keluar Akun
                </button>
            </form>
        </div>
    </aside>

    {{-- ============================================================ --}}
    {{-- MAIN CONTENT --}}
    {{-- ============================================================ --}}
    <div class="flex-1 flex flex-col h-screen overflow-hidden bg-[#F5FFFE] dark:bg-[#0B1120] relative">

        {{-- Ambient light blobs --}}
        <div class="absolute top-0 right-0 w-[600px] h-[600px] bg-teal-100/30 rounded-full blur-3xl -translate-y-1/2 translate-x-1/3 pointer-events-none dark:hidden"></div>
        <div class="absolute bottom-0 left-0 w-[500px] h-[500px] bg-cyan-50/50 rounded-full blur-3xl translate-y-1/3 -translate-x-1/3 pointer-events-none dark:hidden"></div>

        {{-- Top Header --}}
        <header class="h-20 flex-shrink-0 flex items-center justify-between px-8 bg-white/60 backdrop-blur-md border-b border-gray-100 dark:bg-[#1E293B]/80 dark:border-gray-800 z-10 relative">
            <div>
                <h2 class="text-2xl font-extrabold text-gray-900 dark:text-white capitalize tracking-tight" x-text="menuLabel"></h2>
                <p class="text-sm text-gray-500 dark:text-gray-400">{{ now()->isoFormat('dddd, D MMMM YYYY') }}</p>
            </div>
            <div class="flex items-center gap-3">
                {{-- Dark Mode Toggle --}}
                <button @click="toggleDark()" class="w-10 h-10 rounded-full flex items-center justify-center text-gray-500 bg-white border border-gray-100 hover:text-teal-600 shadow-sm dark:bg-[#0F172A] dark:border-gray-700 dark:text-gray-400 dark:hover:text-teal-400 transition-all" aria-label="Toggle dark mode">
                    <svg x-show="!darkMode" class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/></svg>
                    <svg x-show="darkMode" x-cloak class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                </button>
                {{-- Notification Bell --}}
                <button class="relative w-10 h-10 rounded-full flex items-center justify-center text-gray-500 bg-white border border-gray-100 hover:text-teal-600 shadow-sm dark:bg-[#0F172A] dark:border-gray-700 dark:text-gray-400 transition-all">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
                    <span class="absolute top-2 right-2 w-2 h-2 bg-red-500 rounded-full border-2 border-white dark:border-[#1E293B]"></span>
                </button>
                {{-- Home Link --}}
                <a href="{{ route('home') }}" class="flex items-center gap-2 px-4 py-2.5 rounded-xl text-sm font-semibold bg-white border border-gray-100 text-gray-600 hover:border-teal-300 hover:text-teal-600 shadow-sm dark:bg-[#0F172A] dark:border-gray-700 dark:text-gray-400 transition-all">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                    <span class="hidden sm:block">Landing Page</span>
                </a>
            </div>
        </header>

        {{-- Page Content --}}
        <main class="flex-1 overflow-y-auto p-6 md:p-8 relative z-10">

            <div x-show="activeMenu === 'dashboard'" x-cloak
                 x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0">
                @include('admin.partials.dashboard')
            </div>

            <div x-show="activeMenu === 'konten'" x-cloak
                 x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0">
                @include('admin.partials.konten')
            </div>

            <div x-show="activeMenu === 'inventori'" x-cloak
                 x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0">
                @include('admin.partials.inventori')
            </div>

            <div x-show="activeMenu === 'keuangan'" x-cloak
                 x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0">
                @include('admin.partials.keuangan')
            </div>

            <div x-show="activeMenu === 'laporan'" x-cloak
                 x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0">
                @include('admin.partials.laporan')
            </div>

            <div x-show="activeMenu === 'reservasi'" x-cloak
                 x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0">
                @include('admin.partials.reservasi')
            </div>

            <div x-show="activeMenu === 'rekam_medis'" x-cloak
                 x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0">
                @include('admin.partials.rekam-medis')
            </div>

        </main>
    </div>

    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="{{ asset('js/admin.js') }}"></script>
</body>
</html>
