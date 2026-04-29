<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Admin Panel DelimaCare - Manajemen klinik kesehatan ibu dan anak.">
    <title>Admin Panel - DelimaCare</title>
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">

    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = { darkMode: 'class', theme: { extend: {} } }
    </script>
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

    <style>
        [x-cloak] { display: none !important; }
        * { font-family: 'Inter', sans-serif; }
        body { background-color: #F0FDFB; }
        html.dark body { background-color: #080F1E; }

        :root {
            --teal-main: #0D9488;
            --cyan-main: #06B6D4;
            --grad: linear-gradient(135deg, #0D9488 0%, #06B6D4 100%);
        }

        /* Scrollbar */
        ::-webkit-scrollbar { width: 5px; height: 5px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: #CBD5E1; border-radius: 10px; }
        html.dark ::-webkit-scrollbar-thumb { background: #2D3F55; }

        /* Sidebar nav active */
        .nav-active {
            background: linear-gradient(135deg, rgba(13,148,136,0.12) 0%, rgba(6,182,212,0.08) 100%);
            color: #0D9488;
            box-shadow: inset 3px 0 0 #0D9488;
            font-weight: 800;
        }
        html.dark .nav-active {
            background: linear-gradient(135deg, rgba(20,184,166,0.18) 0%, rgba(34,211,238,0.10) 100%);
            color: #2DD4BF;
            box-shadow: inset 3px 0 0 #2DD4BF;
        }

        /* Animations */
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(18px); }
            to   { opacity: 1; transform: translateY(0); }
        }
        @keyframes fadeInLeft {
            from { opacity: 0; transform: translateX(-18px); }
            to   { opacity: 1; transform: translateX(0); }
        }
        @keyframes floatBlob {
            0%, 100% { transform: translateY(0) scale(1); }
            50%       { transform: translateY(-20px) scale(1.05); }
        }
        @keyframes pulseDot {
            0%   { box-shadow: 0 0 0 0 rgba(20,184,166,0.5); }
            70%  { box-shadow: 0 0 0 8px rgba(20,184,166,0); }
            100% { box-shadow: 0 0 0 0 rgba(20,184,166,0); }
        }
        @keyframes pulseRed {
            0%   { box-shadow: 0 0 0 0 rgba(239,68,68,0.4); }
            70%  { box-shadow: 0 0 0 6px rgba(239,68,68,0); }
            100% { box-shadow: 0 0 0 0 rgba(239,68,68,0); }
        }
        @keyframes shimmer {
            0%   { background-position: -200% center; }
            100% { background-position: 200% center; }
        }

        .anim-up  { animation: fadeInUp  0.55s cubic-bezier(0.16,1,0.3,1) both; }
        .anim-left{ animation: fadeInLeft 0.5s cubic-bezier(0.16,1,0.3,1) both; }
        .d-100 { animation-delay: 100ms; }
        .d-150 { animation-delay: 150ms; }
        .d-200 { animation-delay: 200ms; }
        .d-300 { animation-delay: 300ms; }

        .blob-float { animation: floatBlob 8s ease-in-out infinite; }
        .blob-float-2 { animation: floatBlob 10s ease-in-out infinite 2s; }
        .dot-pulse { animation: pulseDot 2s infinite; }
        .bell-pulse { animation: pulseRed 2s infinite; }

        /* Sidebar gradient logo */
        .logo-icon { background: var(--grad); }

        /* Shimmer loading bar */
        .shimmer-bar {
            background: linear-gradient(90deg, #0D9488 0%, #06B6D4 40%, #0D9488 100%);
            background-size: 200% auto;
            animation: shimmer 2s linear infinite;
        }

        /* Menu tooltip on hover for collapsed sidebar (future) */
        .sidebar-link {
            transition: all 0.25s cubic-bezier(0.16,1,0.3,1);
        }
        .sidebar-link:hover {
            transform: translateX(3px);
        }
        /* Page Loader */
        #page-loader {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 3px;
            z-index: 9999;
            background: linear-gradient(90deg, transparent, var(--teal-main), var(--cyan-main), transparent);
            background-size: 200% 100%;
            animation: loaderProgress 2s linear infinite;
            transition: opacity 0.5s ease-in-out;
        }
        @keyframes loaderProgress {
            0% { background-position: 200% 0; }
            100% { background-position: -200% 0; }
        }
        .page-ready #page-loader {
            opacity: 0;
            pointer-events: none;
        }

        /* Smooth Entrance */
        .page-content-wrapper {
            opacity: 0;
            transform: translateY(10px);
            transition: opacity 0.6s cubic-bezier(0.16, 1, 0.3, 1), 
                        transform 0.6s cubic-bezier(0.16, 1, 0.3, 1);
        }
        .page-ready .page-content-wrapper {
            opacity: 1;
            transform: translateY(0);
        }
    </style>

    <script>
        // Dark Mode Initialization
        if (localStorage.getItem('delimacare-dark') === 'true' ||
            (localStorage.getItem('delimacare-dark') === null && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        }

        // Page Loader Control
        window.addEventListener('load', () => {
            setTimeout(() => {
                document.documentElement.classList.add('page-ready');
            }, 300);
        });
    </script>
</head>

<body class="text-gray-900 antialiased flex h-screen overflow-hidden dark:text-gray-100" x-data="adminPanel()">
    <div id="page-loader"></div>

    <div class="page-content-wrapper flex w-full h-screen overflow-hidden">
        {{-- ========== SIDEBAR ========== --}}
        <aside class="hidden md:flex flex-col w-72 flex-shrink-0 h-full z-30 relative
                      bg-white/90 dark:bg-[#0E1A2E]/95 backdrop-blur-2xl
                      border-r border-gray-100 dark:border-gray-800/60
                      shadow-[6px_0_32px_rgba(0,0,0,0.04)] anim-left">

            {{-- Decorative gradient top --}}
            <div class="absolute inset-x-0 top-0 h-1 shimmer-bar rounded-t-none"></div>

            {{-- Logo --}}
            <div class="h-20 flex items-center px-6 border-b border-gray-100/70 dark:border-gray-800/50 flex-shrink-0">
                <a href="{{ route('home') }}" class="flex items-center gap-3.5 group w-full">
                    <div class="w-11 h-11 logo-icon rounded-2xl flex items-center justify-center shadow-lg shadow-teal-500/30
                                group-hover:scale-105 group-hover:rotate-6 transition-all duration-400">
                        <svg class="w-6 h-6 text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                        </svg>
                    </div>
                    <div class="min-w-0">
                        <span class="text-[17px] font-black tracking-tight text-gray-900 dark:text-white block leading-tight">DelimaCare</span>
                        <span class="text-[10px] font-extrabold uppercase tracking-[0.18em] bg-gradient-to-r from-teal-600 to-cyan-500 bg-clip-text text-transparent">Admin Panel</span>
                    </div>
                    {{-- Live badge --}}
                    <div class="ml-auto flex-shrink-0 flex items-center gap-1.5 bg-teal-50 dark:bg-teal-900/30 px-2.5 py-1.5 rounded-lg">
                        <span class="w-1.5 h-1.5 rounded-full bg-teal-500 dot-pulse"></span>
                        <span class="text-[9px] font-black uppercase tracking-wider text-teal-700 dark:text-teal-400">Live</span>
                    </div>
                </a>
            </div>

            {{-- Nav --}}
            <nav class="flex-1 px-4 py-5 overflow-y-auto space-y-0.5">
                <p class="text-[9px] font-black text-gray-400 dark:text-gray-600 uppercase tracking-[0.2em] mb-3 px-4">Menu Utama</p>

                @php
                    $menus = [
                        ['id'=>'dashboard',   'label'=>'Dashboard',         'icon'=>'M4 5a1 1 0 011-1h4a1 1 0 011 1v4a1 1 0 01-1 1H5a1 1 0 01-1-1V5zM14 5a1 1 0 011-1h4a1 1 0 011 1v4a1 1 0 01-1 1h-4a1 1 0 01-1-1V5zM4 15a1 1 0 011-1h4a1 1 0 011 1v4a1 1 0 01-1 1H5a1 1 0 01-1-1v-4zM14 15a1 1 0 011-1h4a1 1 0 011 1v4a1 1 0 01-1 1h-4a1 1 0 01-1-1v-4z', 'badge'=>null],
                        ['id'=>'konten',      'label'=>'Kelola Konten',     'icon'=>'M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z', 'badge'=>null],
                        ['id'=>'inventori',   'label'=>'Inventori Obat',    'icon'=>'M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4', 'badge' => $lowStockCount > 0 ? $lowStockCount : null],
                        ['id'=>'keuangan',    'label'=>'Keuangan',          'icon'=>'M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z', 'badge'=>null],
                        ['id'=>'laporan',     'label'=>'Laporan Pengunjung','icon'=>'M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z', 'badge'=>null],
                        ['id'=>'reservasi',   'label'=>'Reservasi',         'icon'=>'M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z', 'badge'=>'28'],
                        ['id'=>'rekam_medis', 'label'=>'Rekam Medis',       'icon'=>'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z', 'badge'=>null],
                    ];
                @endphp

                @foreach($menus as $i => $menu)
                <button @click="switchMenu('{{ $menu['id'] }}')"
                        class="sidebar-link w-full flex items-center gap-3 px-4 py-3 rounded-xl text-sm relative group"
                        :class="activeMenu === '{{ $menu['id'] }}' ? 'nav-active' : 'text-gray-500 hover:bg-gray-50 hover:text-gray-800 dark:text-gray-400 dark:hover:bg-white/5 dark:hover:text-gray-200'">
                    <svg class="w-[18px] h-[18px] flex-shrink-0 transition-all duration-300"
                         :class="activeMenu === '{{ $menu['id'] }}' ? 'text-teal-600 dark:text-teal-400' : 'text-gray-400 group-hover:text-gray-600 dark:group-hover:text-gray-300'"
                         fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        {!! $menu['icon'] !!}
                    </svg>
                    <span class="flex-1 text-left truncate font-semibold">{{ $menu['label'] }}</span>
                    @if($menu['badge'])
                    <span class="text-[10px] font-black px-2 py-0.5 rounded-full
                        {{ $menu['id'] === 'inventori' ? 'bg-amber-100 text-amber-700 dark:bg-amber-900/40 dark:text-amber-400' : 'bg-teal-100 text-teal-700 dark:bg-teal-900/40 dark:text-teal-400' }}">
                        {{ $menu['badge'] }}
                    </span>
                    @endif
                    {{-- Active indicator dot --}}
                    <span x-show="activeMenu === '{{ $menu['id'] }}'" class="w-1.5 h-1.5 rounded-full bg-teal-500 dark:bg-teal-400 flex-shrink-0"></span>
                </button>
                @endforeach
            </nav>

            {{-- User + Logout --}}
            <div class="p-3 border-t border-gray-100/70 dark:border-gray-800/50 space-y-2 flex-shrink-0">
                @auth
                <div class="flex items-center gap-2.5 px-2.5 py-2 rounded-xl bg-gradient-to-r from-teal-50 to-cyan-50 dark:from-teal-900/20 dark:to-cyan-900/20 border border-teal-100/60 dark:border-teal-800/30">
                    <div class="w-8 h-8 logo-icon rounded-full flex items-center justify-center text-white text-xs font-black shadow-md flex-shrink-0">
                        {{ strtoupper(substr(Auth::user()->username, 0, 1)) }}
                    </div>
                    <div class="min-w-0 flex-1">
                        <p class="text-xs font-black text-gray-900 dark:text-white truncate">{{ Auth::user()->username }}</p>
                        <p class="text-[9px] font-bold bg-gradient-to-r from-teal-600 to-cyan-500 bg-clip-text text-transparent">Administrator</p>
                    </div>
                    <div class="w-1.5 h-1.5 rounded-full bg-emerald-400 flex-shrink-0 dot-pulse"></div>
                </div>
                @endauth
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="w-full flex items-center gap-2.5 px-3 py-1.5 rounded-lg text-[13px] font-bold text-red-500 dark:text-red-400
                                                 hover:bg-red-50 dark:hover:bg-red-500/10 hover:text-red-600 dark:hover:text-red-300
                                                 transition-all duration-200 group">
                        <svg class="w-4 h-4 group-hover:translate-x-0.5 transition-transform" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                        </svg>
                        Keluar Akun
                    </button>
                </form>
            </div>
        </aside>

        {{-- ========== MAIN CONTENT ========== --}}
        <div class="flex-1 flex flex-col h-screen overflow-hidden bg-[#F0FDFB] dark:bg-[#080F1E] relative">

            {{-- Ambient blobs --}}
            <div class="absolute top-[-100px] right-[-100px] w-[500px] h-[500px] bg-teal-200/20 dark:bg-teal-900/10 rounded-full blur-3xl pointer-events-none blob-float"></div>
            <div class="absolute bottom-[-80px] left-[-60px] w-[400px] h-[400px] bg-cyan-200/15 dark:bg-cyan-900/10 rounded-full blur-3xl pointer-events-none blob-float-2"></div>

            {{-- ===== HEADER ===== --}}
            <header class="h-[72px] flex-shrink-0 flex items-center justify-between px-8
                           bg-white/80 dark:bg-[#0E1A2E]/90 backdrop-blur-2xl
                           border-b border-gray-100/80 dark:border-gray-800/60
                           shadow-sm z-20 relative">
                {{-- Left: page title --}}
                <div class="anim-up">
                    <h2 class="text-xl font-black text-gray-900 dark:text-white tracking-tight leading-none" x-text="menuLabel"></h2>
                    <p class="text-[11px] font-bold text-teal-600 dark:text-teal-400 uppercase tracking-widest mt-1">
                        {{ now()->isoFormat('dddd, D MMMM YYYY') }}
                    </p>
                </div>

                {{-- Right: actions --}}
                <div class="flex items-center gap-3 anim-up d-100">
                    {{-- Dark Mode Toggle --}}
                    <button @click="toggleDark()" aria-label="Toggle dark mode"
                            class="w-9 h-9 rounded-xl flex items-center justify-center transition-all duration-300 group
                                   bg-gray-50 border border-gray-200 text-gray-500
                                   hover:bg-white hover:text-teal-600 hover:shadow-md hover:-translate-y-0.5
                                   dark:bg-[#1E293B] dark:border-gray-700 dark:text-gray-400
                                   dark:hover:bg-gray-700 dark:hover:text-teal-400">
                        <svg x-show="!darkMode" class="w-4 h-4 group-hover:rotate-12 transition-transform duration-300" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/>
                        </svg>
                        <svg x-show="darkMode" x-cloak class="w-4 h-4 group-hover:rotate-12 transition-transform duration-300" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/>
                        </svg>
                    </button>

                    {{-- Notification --}}
                    <button class="relative w-9 h-9 rounded-xl flex items-center justify-center transition-all duration-300 group
                                   bg-gray-50 border border-gray-200 text-gray-500
                                   hover:bg-white hover:text-teal-600 hover:shadow-md hover:-translate-y-0.5
                                   dark:bg-[#1E293B] dark:border-gray-700 dark:text-gray-400
                                   dark:hover:bg-gray-700 dark:hover:text-teal-400">
                        <svg class="w-4 h-4 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                        </svg>
                        <span class="absolute top-1.5 right-1.5 w-2 h-2 bg-red-500 rounded-full bell-pulse"></span>
                    </button>

                    {{-- Divider --}}
                    <div class="w-px h-6 bg-gray-200 dark:bg-gray-700"></div>

                    {{-- Home Link --}}
                    <a href="{{ route('home') }}"
                       class="flex items-center gap-2 px-4 py-2 rounded-xl text-sm font-bold transition-all duration-300
                              bg-gray-900 text-white hover:bg-teal-700 shadow-md hover:shadow-teal-500/25 hover:-translate-y-0.5
                              dark:bg-white dark:text-gray-900 dark:hover:bg-teal-400 dark:hover:text-white">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                        </svg>
                        <span class="hidden sm:inline">Landing Page</span>
                    </a>
                </div>
            </header>

            {{-- ===== PAGE CONTENT ===== --}}
            <main class="flex-1 overflow-y-auto p-6 md:p-8 relative z-10">

                <div x-show="activeMenu === 'dashboard'" x-cloak
                     x-transition:enter="transition ease-out duration-350"
                     x-transition:enter-start="opacity-0 translate-y-5"
                     x-transition:enter-end="opacity-100 translate-y-0">
                    @include('admin.partials.dashboard')
                </div>

                <div x-show="activeMenu === 'konten'" x-cloak
                     x-transition:enter="transition ease-out duration-350"
                     x-transition:enter-start="opacity-0 translate-y-5"
                     x-transition:enter-end="opacity-100 translate-y-0">
                    @include('admin.partials.konten')
                </div>

                <div x-show="activeMenu === 'inventori'" x-cloak
                     x-transition:enter="transition ease-out duration-350"
                     x-transition:enter-start="opacity-0 translate-y-5"
                     x-transition:enter-end="opacity-100 translate-y-0">
                    @include('admin.partials.inventori')
                </div>

                <div x-show="activeMenu === 'keuangan'" x-cloak
                     x-transition:enter="transition ease-out duration-350"
                     x-transition:enter-start="opacity-0 translate-y-5"
                     x-transition:enter-end="opacity-100 translate-y-0">
                    @include('admin.partials.keuangan')
                </div>

                <div x-show="activeMenu === 'laporan'" x-cloak
                     x-transition:enter="transition ease-out duration-350"
                     x-transition:enter-start="opacity-0 translate-y-5"
                     x-transition:enter-end="opacity-100 translate-y-0">
                    @include('admin.partials.laporan')
                </div>

                <div x-show="activeMenu === 'reservasi'" x-cloak
                     x-transition:enter="transition ease-out duration-350"
                     x-transition:enter-start="opacity-0 translate-y-5"
                     x-transition:enter-end="opacity-100 translate-y-0">
                    @include('admin.partials.reservasi')
                </div>

                <div x-show="activeMenu === 'rekam_medis'" x-cloak
                     x-transition:enter="transition ease-out duration-350"
                     x-transition:enter-start="opacity-0 translate-y-5"
                     x-transition:enter-end="opacity-100 translate-y-0">
                    @include('admin.partials.rekam-medis')
                </div>

            </main>
        </div>
    </div>

    {{-- Global Notification --}}
    <div x-show="notification.show" 
         x-transition:enter="transition ease-out duration-500"
         x-transition:enter-start="opacity-0 -translate-y-10 scale-95"
         x-transition:enter-end="opacity-100 translate-y-0 scale-100"
         x-transition:leave="transition ease-in duration-300"
         x-transition:leave-start="opacity-100 translate-y-0 scale-100"
         x-transition:leave-end="opacity-0 -translate-y-10 scale-95"
         class="fixed top-6 left-1/2 -translate-x-1/2 z-[200] w-full max-w-sm px-4"
         x-cloak>
        <div class="bg-white/90 dark:bg-gray-900/95 backdrop-blur-xl border border-emerald-500/20 shadow-[0_20px_50px_rgba(0,0,0,0.15)] rounded-3xl p-4 flex items-center gap-4 relative overflow-hidden group">
            {{-- Decorative glow --}}
            <div class="absolute -right-4 -bottom-4 w-24 h-24 bg-emerald-500/10 rounded-full blur-2xl group-hover:bg-emerald-500/20 transition-all duration-700"></div>
            
            <div class="w-12 h-12 rounded-2xl bg-emerald-500 flex items-center justify-center text-white shadow-lg shadow-emerald-500/40 flex-shrink-0">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
            </div>
            <div class="flex-1 min-w-0">
                <p class="text-[10px] font-black text-emerald-600 dark:text-emerald-400 uppercase tracking-[0.2em] mb-0.5">Berhasil</p>
                <p class="text-sm font-bold text-gray-900 dark:text-white truncate" x-text="notification.message"></p>
            </div>
            <button @click="notification.show = false" class="w-8 h-8 rounded-xl flex items-center justify-center text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-800 transition-all">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>
    </div>

    {{-- Session Message to Alpine --}}
    @if(session('success'))
        <input type="hidden" id="initial-success-message" value="{{ session('success') }}">
    @endif

    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="{{ asset('js/admin.js') }}"></script>
</body>
</html>
