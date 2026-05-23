<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Admin Panel DelimaCare - Manajemen klinik kesehatan ibu dan anak.">
    <meta name="csrf-token" content="{{ csrf_token() }}">
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

    {{--
        CSS Admin Panel (diekstrak dari inline style, di-bundle via Vite)
        Berisi: custom vars, animasi, sidebar layout, HTMX loading bar, page loader.
        Di-serve dengan content hash di production → browser cache optimal.
    --}}
    @vite('resources/css/admin.css')

    {{--
        Dark Mode Initialization — HARUS tetap inline di <head>.
        Alasan: harus jalan SYNCHRONOUS sebelum browser me-render body,
        agar tidak ada Flash of Unstyled Content (FOUC) di dark mode.
    --}}
    <script>
        if (localStorage.getItem('delimacare-dark') === 'true' ||
            (localStorage.getItem('delimacare-dark') === null &&
             window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        }
        window.addEventListener('load', () => {
            setTimeout(() => { document.documentElement.classList.add('page-ready'); }, 300);
        });
    </script>
</head>

{{--
    Data attributes pada <body>:
    - Pusher config via config() helper (aman saat config:cache aktif di production)
    - Admin username via data-attribute (TIDAK di-expose ke window global)
    --}}
<body class="text-gray-900 antialiased flex h-screen overflow-hidden dark:text-gray-100"
      x-data="adminPanel({ username: '{{ Auth::user()->username ?? null }}' })"
      data-pusher-key="{{ config('broadcasting.connections.pusher.key') }}"
      data-pusher-cluster="{{ config('broadcasting.connections.pusher.options.cluster', 'ap1') }}">
    <div id="page-loader"></div>

    <div class="page-content-wrapper flex w-full h-screen overflow-hidden">
        {{-- ========== SIDEBAR ========== --}}
        <aside id="main-sidebar"
               class="sidebar-wide hidden md:flex flex-col flex-shrink-0 h-full z-30 relative">

            <div id="sb-inner" class="bg-white dark:bg-[#0D1826] border-r border-gray-100 dark:border-gray-800/50 shadow-[4px_0_24px_rgba(0,0,0,0.04)] relative h-full">

            {{-- Top shimmer --}}
            <div class="absolute inset-x-0 top-0 h-[3px] shimmer-bar pointer-events-none"></div>

            {{-- Logo row --}}
            <div class="h-[72px] flex items-center justify-between px-4 flex-shrink-0 border-b border-gray-100/70 dark:border-gray-800/50">
                <a href="{{ route('home') }}" class="flex items-center gap-3 group min-w-0 flex-1">
                    <div class="w-10 h-10 logo-icon rounded-xl flex-shrink-0 flex items-center justify-center shadow-lg shadow-teal-500/25 group-hover:scale-110 group-hover:rotate-6 transition-all duration-300">
                        <svg class="w-5 h-5 text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                        </svg>
                    </div>
                    <div class="sb-label min-w-0">
                        <p class="text-[15px] font-black tracking-tight text-gray-900 dark:text-white leading-none">DelimaCare</p>
                        <p class="text-[9px] font-extrabold uppercase tracking-[0.18em] bg-gradient-to-r from-teal-600 to-cyan-500 bg-clip-text text-transparent">Admin Panel</p>
                    </div>
                </a>
                {{-- Live pill --}}
                <div class="sb-live flex items-center gap-1 bg-teal-50 dark:bg-teal-900/25 px-2 py-1 rounded-lg flex-shrink-0 ml-2">
                    <span class="w-1.5 h-1.5 rounded-full bg-teal-500 dot-pulse"></span>
                    <span class="text-[8px] font-black uppercase tracking-wider text-teal-700 dark:text-teal-400">Live</span>
                </div>
            </div>

            {{-- Nav --}}
            <nav class="flex-1 px-3 py-4 overflow-y-auto space-y-0.5">
                <p class="sb-section-title text-[9px] font-black text-gray-400 dark:text-gray-600 uppercase tracking-[0.22em] mb-2 px-3">Menu Utama</p>

                @php
                    $menus = [
                        ['id'=>'dashboard',   'label'=>'Dashboard',          'icon'=>'M4 5a1 1 0 011-1h4a1 1 0 011 1v4a1 1 0 01-1 1H5a1 1 0 01-1-1V5zM14 5a1 1 0 011-1h4a1 1 0 011 1v4a1 1 0 01-1 1h-4a1 1 0 01-1-1V5zM4 15a1 1 0 011-1h4a1 1 0 011 1v4a1 1 0 01-1 1H5a1 1 0 01-1-1v-4zM14 15a1 1 0 011-1h4a1 1 0 011 1v4a1 1 0 01-1 1h-4a1 1 0 01-1-1v-4z'],
                        ['id'=>'konten',      'label'=>'Kelola Konten',      'icon'=>'M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z'],
                        ['id'=>'inventori',   'label'=>'Inventori Obat',     'icon'=>'M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4'],
                        ['id'=>'keuangan',    'label'=>'Keuangan',           'icon'=>'M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z'],
                        ['id'=>'laporan',     'label'=>'Laporan Pengunjung', 'icon'=>'M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z'],
                        ['id'=>'doctors',     'label'=>'Jadwal Dokter',      'icon'=>'M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z'],
                        ['id'=>'reservasi',   'label'=>'Antrean Pasien',     'icon'=>'M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z'],
                        ['id'=>'rekam_medis', 'label'=>'Rekam Medis',        'icon'=>'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z'],
                        ['id'=>'konsultasi',  'label'=>'Konsultasi Live',    'icon'=>'M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z'],
                    ];
                @endphp

                @foreach($menus as $menu)
                <button @click="switchMenu('{{ $menu['id'] }}')"
                        class="sidebar-link w-full flex items-center gap-3 px-3 py-2.5 text-sm group"
                        :class="activeMenu === '{{ $menu['id'] }}' ? 'nav-active' : 'text-gray-500 dark:text-gray-400 hover:text-gray-800 dark:hover:text-gray-200'">

                    {{-- Mini active left bar --}}
                    <span class="mini-active-bar absolute left-0 inset-y-2.5 w-[3px] rounded-r-full bg-teal-500"
                          :class="activeMenu === '{{ $menu['id'] }}' ? 'opacity-100' : 'opacity-0'"
                          style="display: block;"></span>

                    {{-- Icon --}}
                    <svg class="w-[18px] h-[18px] flex-shrink-0"
                         :class="activeMenu === '{{ $menu['id'] }}' ? 'text-teal-600 dark:text-teal-400' : 'text-gray-400 group-hover:text-gray-600 dark:group-hover:text-gray-300'"
                         fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="{{ $menu['icon'] }}"/>
                    </svg>

                    {{-- Label --}}
                    <span class="sb-label flex-1 text-left font-semibold truncate">{{ $menu['label'] }}</span>

                    {{-- Badge (always visible, adapts in mini mode) --}}
                    @if($menu['id'] === 'inventori')
                    <span class="sb-badge font-black bg-amber-100 text-amber-700 dark:bg-amber-900/40 dark:text-amber-400"
                          x-show="lowStockCount > 0" x-text="lowStockCount"></span>
                    @elseif($menu['id'] === 'reservasi')
                    <span class="sb-badge font-black bg-teal-100 text-teal-700 dark:bg-teal-900/40 dark:text-teal-400"
                          x-show="pendingCount > 0" x-text="pendingCount"></span>
                    @endif

                    {{-- Active dot (wide mode only) --}}
                    <span class="sb-dot w-1.5 h-1.5 rounded-full bg-teal-500 dark:bg-teal-400 flex-shrink-0"
                          x-show="activeMenu === '{{ $menu['id'] }}'"></span>
                </button>
                @endforeach
            </nav>

            {{-- User + Logout --}}
            <div class="p-3 border-t border-gray-100/70 dark:border-gray-800/50 space-y-1.5 flex-shrink-0">
                @auth
                <div class="flex items-center gap-2.5 px-2.5 py-2 rounded-xl bg-teal-50/60 dark:bg-teal-900/15 border border-teal-100/50 dark:border-teal-800/30 overflow-hidden">
                    <div class="w-8 h-8 logo-icon rounded-full flex items-center justify-center text-white text-xs font-black shadow-md flex-shrink-0 hover:rotate-12 transition-transform duration-300" x-text="username ? username.charAt(0).toUpperCase() : 'G'">
                    </div>
                    <div class="sb-user-info min-w-0 flex-1">
                        <p class="text-xs font-black text-gray-900 dark:text-white truncate" x-text="username"></p>
                        <p class="text-[9px] font-bold bg-gradient-to-r from-teal-600 to-cyan-500 bg-clip-text text-transparent">Administrator</p>
                    </div>
                    <span class="sb-live w-1.5 h-1.5 rounded-full bg-emerald-400 flex-shrink-0 dot-pulse"></span>
                </div>
                @endauth
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit"
                            class="sidebar-link w-full flex items-center gap-2.5 px-3 py-2 text-[13px] font-bold text-rose-500 dark:text-rose-400
                                   hover:bg-rose-50 dark:hover:bg-rose-500/10 hover:text-rose-600 group">
                        <svg class="w-[18px] h-[18px] flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                        </svg>
                        <span class="sb-logout-text">Keluar Akun</span>
                    </button>
                </form>
            </div>
            </div>{{-- /sb-inner --}}
        </aside>

        {{-- Toggle button (outside aside so it's never clipped) --}}
        <button id="sidebar-toggle"
                class="sidebar-wide hidden md:flex fixed z-40 items-center justify-center
                       w-6 h-6 rounded-full bg-white dark:bg-gray-800
                       border border-gray-200 dark:border-gray-700 shadow-md
                       hover:bg-teal-50 dark:hover:bg-teal-900/40 hover:border-teal-300
                       transition-all duration-300 group"
                id="sidebar-toggle-btn"
                onclick="toggleSidebar()">
            <svg id="toggle-icon" class="w-3 h-3 text-gray-400 group-hover:text-teal-600 transition-all duration-300"
                 fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
            </svg>
        </button>

        <script>
            function toggleSidebar() {
                const sb      = document.getElementById('main-sidebar');
                const btn     = document.getElementById('sidebar-toggle');
                const ico     = document.getElementById('toggle-icon');
                const content = document.getElementById('main-content');

                // Toggle classes to trigger CSS transitions (GPU accelerated)
                const isWide = sb.classList.toggle('sidebar-wide');
                btn.classList.toggle('sidebar-wide');

                // Save preference to localStorage
                localStorage.setItem('sidebar-state', isWide ? 'wide' : 'mini');

                ico.style.transform = isWide ? 'rotate(0deg)' : 'rotate(180deg)';

                // Prevent chart lag by disabling resize during animation
                if (content) {
                    content.style.pointerEvents = 'none';
                    if (window.ApexCharts) {
                        window.dispatchEvent(new Event('sidebar-transitioning'));
                    }

                    setTimeout(() => {
                        content.style.pointerEvents = '';
                        window.dispatchEvent(new Event('resize'));
                    }, 450);
                }
            }

            // Apply sidebar state immediately to prevent flicker
            (function() {
                const state = localStorage.getItem('sidebar-state');
                const sb = document.getElementById('main-sidebar');
                const btn = document.getElementById('sidebar-toggle');
                const ico = document.getElementById('toggle-icon');

                if (state === 'mini' && sb) {
                    sb.classList.remove('sidebar-wide');
                    if (btn) btn.classList.remove('sidebar-wide');
                    if (ico) ico.style.transform = 'rotate(180deg)';
                }
            })();

            document.addEventListener('DOMContentLoaded', function() {
                const btn = document.getElementById('sidebar-toggle');
                const ico = document.getElementById('toggle-icon');
                if (btn) btn.style.top = '84px';
                if (ico) ico.style.transition = 'transform 0.4s ease';
            });
        </script>

        {{-- ========== MAIN CONTENT ========== --}}
        <div id="main-content" class="flex-1 flex flex-col h-screen overflow-hidden bg-[#F0FDFB] dark:bg-[#080F1E] relative">

            {{-- Ambient blobs --}}
            <div class="blob-1 absolute top-[-100px] right-[-100px] w-[500px] h-[500px] rounded-full blur-3xl pointer-events-none blob-float opacity-30"></div>
            <div class="blob-2 absolute bottom-[-80px] left-[-60px] w-[400px] h-[400px] rounded-full blur-3xl pointer-events-none blob-float-2 opacity-20"></div>

            {{-- ===== HEADER ===== --}}
            <header class="h-[72px] flex-shrink-0 flex items-center justify-between px-8
                           bg-white/80 dark:bg-[#0E1A2E]/90 backdrop-blur-2xl
                           border-b border-gray-100/80 dark:border-gray-800/60
                           shadow-sm z-20 relative">
                {{-- Left: page title --}}
                <div class="anim-up">
                    <h2 class="text-xl font-black text-gray-900 dark:text-white tracking-tight leading-none" x-text="menuLabel"></h2>
                    <p class="header-date text-[11px] font-bold uppercase tracking-widest mt-1">
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
                    <div class="relative" x-data="{ open: false }" @click.away="open = false">
                        <button @click="open = !open"
                                class="relative w-9 h-9 rounded-xl flex items-center justify-center transition-all duration-300 group
                                       bg-gray-50 border border-gray-200 text-gray-500
                                       hover:bg-white hover:text-teal-600 hover:shadow-md hover:-translate-y-0.5
                                       dark:bg-[#1E293B] dark:border-gray-700 dark:text-gray-400
                                       dark:hover:bg-gray-700 dark:hover:text-teal-400">
                            <svg class="w-4 h-4 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                            </svg>
                            <span x-show="pendingCount > 0" class="absolute top-1.5 right-1.5 w-2.5 h-2.5 bg-red-500 rounded-full border-2 border-white dark:border-[#0E1A2E] bell-pulse"></span>
                        </button>

                        {{-- Notification Dropdown --}}
                        <div x-show="open" x-cloak
                             x-transition:enter="transition ease-out duration-200"
                             x-transition:enter-start="opacity-0 translate-y-2 scale-95"
                             x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                             x-transition:leave="transition ease-in duration-150"
                             x-transition:leave-start="opacity-100 translate-y-0 scale-100"
                             x-transition:leave-end="opacity-0 translate-y-2 scale-95"
                             class="absolute right-0 mt-3 w-80 bg-white dark:bg-gray-900 rounded-2xl shadow-2xl border border-gray-100 dark:border-gray-800 overflow-hidden z-50">
                            <div class="px-5 py-4 border-b border-gray-50 dark:border-gray-800 flex items-center justify-between">
                                <span class="text-xs font-black uppercase tracking-widest text-gray-900 dark:text-white">Notifikasi</span>
                                <span class="text-[10px] font-bold text-teal-600 bg-teal-50 dark:bg-teal-900/30 px-2 py-0.5 rounded-full" x-text="pendingCount + ' Baru'"></span>
                            </div>
                            <div class="max-h-[350px] overflow-y-auto p-2">
                                <template x-if="recentNotifications.length === 0">
                                    <div class="py-10 text-center">
                                        <p class="text-xs text-gray-400 font-medium">Tidak ada notifikasi baru</p>
                                    </div>
                                </template>
                                <template x-for="(notif, index) in recentNotifications" :key="index">
                                    <div class="flex items-start gap-3 p-3 rounded-xl hover:bg-gray-50 dark:hover:bg-gray-800/50 transition-all cursor-pointer">
                                        <div class="w-8 h-8 rounded-lg bg-teal-50 dark:bg-teal-900/30 flex items-center justify-center flex-shrink-0">
                                            <svg class="w-4 h-4 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                        </div>
                                        <div>
                                            <p class="text-xs font-bold text-gray-900 dark:text-white" x-text="notif.title"></p>
                                            <p class="text-[10px] text-gray-400 mt-0.5" x-text="notif.time"></p>
                                        </div>
                                    </div>
                                </template>
                            </div>
                            <button @click="switchMenu('reservasi'); open = false" class="w-full py-3 text-[10px] font-black uppercase tracking-widest text-gray-400 hover:text-teal-600 border-t border-gray-50 dark:border-gray-800 transition-all">
                                Lihat Semua Antrean
                            </button>
                        </div>

                        {{-- Pop-out Notification for New Reservation --}}
                        <div x-show="showPopout" x-cloak
                             class="absolute right-0 mt-2 w-64 bg-gradient-to-br from-teal-600 to-cyan-500 text-white rounded-2xl shadow-xl p-4 notif-popout z-[60]">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-xl bg-white/20 backdrop-blur-md flex items-center justify-center shadow-inner">
                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-[10px] font-black uppercase tracking-widest opacity-80">Reservasi Baru!</p>
                                    <p class="text-xs font-bold truncate">Pasien baru telah mendaftar.</p>
                                </div>
                            </div>
                            <button @click="switchMenu('reservasi'); showPopout = false" class="w-full mt-3 py-2 bg-white/20 hover:bg-white/30 rounded-lg text-[10px] font-black uppercase tracking-widest transition-all">
                                Cek Sekarang
                            </button>
                        </div>
                    </div>

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
            {{--
                LAZY LOADING ARCHITECTURE:
                - tab-dashboard : Pre-rendered server-side (always the first tab seen).
                - tab-*         : Empty on initial load. Populated via AJAX on first click.
                                  Content is cached in the DOM — no re-fetch on revisit.
            --}}
            <main class="flex-1 relative z-10" :class="activeMenu === 'konsultasi' ? 'overflow-hidden p-0' : 'overflow-y-auto p-6 md:p-8'">

                {{-- ── Dashboard (PRE-RENDERED — tidak lazy) ───────────────── --}}
                <div id="tab-dashboard"
                     x-show="activeMenu === 'dashboard'"
                     x-transition:enter="transition ease-out duration-300"
                     x-transition:enter-start="opacity-0 translate-y-4"
                     x-transition:enter-end="opacity-100 translate-y-0">
                    @include('admin.partials.dashboard')
                </div>

                {{-- ── Tab Lain (LAZY — container kosong, diisi AJAX) ─────── --}}
                @foreach([
                    'konten'      => 'overflow-y-auto',
                    'inventori'   => 'overflow-y-auto',
                    'keuangan'    => 'overflow-y-auto',
                    'laporan'     => 'overflow-y-auto',
                    'doctors'     => 'overflow-y-auto',
                    'reservasi'   => 'overflow-y-auto',
                    'rekam_medis' => 'overflow-y-auto',
                    'konsultasi'  => 'overflow-hidden h-full p-0',
                ] as $tabId => $tabClass)
                <div id="tab-{{ $tabId }}"
                     x-show="activeMenu === '{{ $tabId }}'"
                     x-transition:enter="transition ease-out duration-300"
                     x-transition:enter-start="opacity-0 translate-y-4"
                     x-transition:enter-end="opacity-100 translate-y-0"
                     class="min-h-0">
                    {{-- Diisi via JavaScript oleh adminPanel._fetchTab() --}}
                </div>
                @endforeach

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

    {{--
        Pusher & Echo — inisialisasi via resources/js/admin-init.js
        Config dibaca dari data-attributes <body> (aman untuk production / config:cache)
        Lihat: resources/js/admin-init.js
    --}}
    <script src="https://js.pusher.com/8.2.0/pusher.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/laravel-echo@1.15.3/dist/echo.iife.js"></script>
    @vite('resources/js/admin-init.js')

    {{-- HTMX — dimuat sebelum Alpine agar event htmx:afterSwap bisa memanggil Alpine.initTree() --}}
    <script src="https://unpkg.com/htmx.org@2.0.3" crossorigin="anonymous"></script>
    <script>
        // Konfigurasi HTMX agar kompatibel dengan Alpine.js
        htmx.config.allowScriptTags  = true;  // izinkan re-exec <script> (ApexCharts)
        htmx.config.scrollBehavior   = 'smooth';
        htmx.config.defaultSwapDelay = 0;

        // Tambahkan CSRF token ke semua request HTMX secara otomatis
        document.addEventListener('htmx:configRequest', function(e) {
            e.detail.headers['X-CSRF-TOKEN'] = document.querySelector('meta[name="csrf-token"]')?.content || '';
            e.detail.headers['X-Requested-With'] = 'XMLHttpRequest';
        });
    </script>

    {{-- PDF Export Libraries --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.28/jspdf.plugin.autotable.min.js"></script>

    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="{{ asset('js/admin.js') }}"></script>



    {{--
        Pagination & form filter kini ditangani oleh HTMX.
        Lihat fungsi enhanceTabContent() di public/js/admin.js yang dipanggil
        setelah setiap tab selesai dimuat (htmx:afterSwap + _fetchTab).
    --}}
    <div id="htmx-loading-bar"></div>
</body>
</html>
