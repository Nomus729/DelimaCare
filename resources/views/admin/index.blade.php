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

        /* ── Per-tab accent CSS variables ── */
        :root {
            --acc1: #0D9488; --acc2: #06B6D4;
            --blob1: rgba(13,148,136,0.18); --blob2: rgba(6,182,212,0.12);
            --grad: linear-gradient(135deg, #0D9488 0%, #06B6D4 100%);
        }

        /* Tab themes applied via JS to :root */
        .shimmer-bar {
            background: linear-gradient(90deg, var(--acc1) 0%, var(--acc2) 40%, var(--acc1) 100%);
            background-size: 200% auto; animation: shimmer 2s linear infinite;
            transition: background 0.5s ease;
        }
        .blob-1 { background: var(--blob1); transition: background 0.6s ease; }
        .blob-2 { background: var(--blob2); transition: background 0.6s ease; }
        .header-date { color: var(--acc1); transition: color 0.5s ease; }

        .nav-active {
            background: linear-gradient(135deg, color-mix(in srgb, var(--acc1) 12%, transparent), color-mix(in srgb, var(--acc2) 8%, transparent));
            color: var(--acc1); box-shadow: inset 3px 0 0 var(--acc1); font-weight: 800;
        }
        html.dark .nav-active { color: var(--acc2); box-shadow: inset 3px 0 0 var(--acc2); }
        .nav-icon-active { color: var(--acc1); }
        html.dark .nav-icon-active { color: var(--acc2); }
        .dot-active { background: var(--acc1); }
        html.dark .dot-active { background: var(--acc2); }

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
        @keyframes popOut {
            0% { opacity: 0; transform: scale(0.3) translateY(-20px); }
            100% { opacity: 1; transform: scale(1) translateY(0); }
        }
        .notif-popout {
            animation: popOut 0.5s cubic-bezier(0.34, 1.56, 0.64, 1) both;
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

        /* ── Sidebar ──────────────────────────────────── */
        #main-sidebar {
            transition: width 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            will-change: width;
            overflow: visible;
        }
        #main-sidebar.sidebar-wide { width: 18rem !important; }
        #main-sidebar:not(.sidebar-wide) { width: 4.5rem !important; }

        /* Inner scroll container clips content */
        #sb-inner {
            overflow: hidden;
            width: 100%;
            height: 100%;
            display: flex;
            flex-direction: column;
            backface-visibility: hidden;
        }
        .sidebar-link {
            position: relative;
            transition: background 0.22s ease, transform 0.22s cubic-bezier(0.34,1.56,0.64,1), color 0.18s ease;
            border-radius: 14px;
            overflow: hidden;
        }
        .sidebar-link::before {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(135deg, rgba(13,148,136,0.08), rgba(6,182,212,0.06));
            opacity: 0;
            transition: opacity 0.2s ease;
            border-radius: 14px;
        }
        .sidebar-link:hover::before { opacity: 1; }
        .sidebar-link:hover { transform: translateX(4px); }
        .sidebar-link:hover svg { transform: scale(1.12) rotate(4deg); }
        .sidebar-link:active { transform: scale(0.97) translateX(2px); }
        .sidebar-link svg { transition: transform 0.25s cubic-bezier(0.34,1.56,0.64,1); }
        #main-sidebar:not(.sidebar-wide) .sidebar-link:hover {
            transform: scale(1.1) translateX(0);
        }
        .nav-active {
            background: linear-gradient(135deg, rgba(13,148,136,0.12) 0%, rgba(6,182,212,0.08) 100%) !important;
            border: 1px solid rgba(13,148,136,0.18);
            color: #0d9488 !important;
            box-shadow: 0 2px 12px rgba(13,148,136,0.1);
        }
        .dark .nav-active {
            background: linear-gradient(135deg, rgba(13,148,136,0.18) 0%, rgba(6,182,212,0.12) 100%) !important;
            color: #2dd4bf !important;
        }
        .nav-active svg { color: #0d9488 !important; }
        .dark .nav-active svg { color: #2dd4bf !important; }
        /* Sidebar text & extras fade */
        .sb-label, .sb-dot, .sb-user-info, .sb-logout-text, .sb-live, .sb-section-title {
            transition: opacity 0.25s ease, max-width 0.4s cubic-bezier(0.65,0,0.35,1);
            white-space: nowrap;
            overflow: hidden;
            flex-shrink: 0;
        }
        #main-sidebar:not(.sidebar-wide) .sb-label,
        #main-sidebar:not(.sidebar-wide) .sb-dot,
        #main-sidebar:not(.sidebar-wide) .sb-user-info,
        #main-sidebar:not(.sidebar-wide) .sb-logout-text,
        #main-sidebar:not(.sidebar-wide) .sb-live,
        #main-sidebar:not(.sidebar-wide) .sb-section-title {
            opacity: 0;
            max-width: 0;
            pointer-events: none;
        }
        #main-sidebar.sidebar-wide .sb-label,
        #main-sidebar.sidebar-wide .sb-dot,
        #main-sidebar.sidebar-wide .sb-user-info,
        #main-sidebar.sidebar-wide .sb-logout-text,
        #main-sidebar.sidebar-wide .sb-live,
        #main-sidebar.sidebar-wide .sb-section-title {
            opacity: 1;
            max-width: 220px;
        }
        /* Badge — always visible, style changes per mode */
        .sb-badge {
            transition: all 0.3s ease;
            white-space: nowrap;
            flex-shrink: 0;
        }
        /* Wide badge: full pill */
        #main-sidebar.sidebar-wide .sb-badge {
            font-size: 10px; padding: 1px 6px; border-radius: 9999px;
        }
        /* Mini badge: compact square next to icon */
        #main-sidebar:not(.sidebar-wide) .sb-badge {
            font-size: 9px; padding: 1px 4px; border-radius: 5px;
            min-width: 16px; text-align: center;
        }
        /* Mini active bar */
        .mini-active-bar {
            transition: opacity 0.25s ease;
        }
        /* Scrollbar for sidebar */
        #sb-inner nav::-webkit-scrollbar { width: 3px; }
        #sb-inner nav::-webkit-scrollbar-track { background: transparent; }
        #sb-inner nav::-webkit-scrollbar-thumb { background: rgba(13,148,136,0.2); border-radius: 10px; }
        /* Prevent main content from janking during sidebar transition */
        #main-content {
            contain: layout;
            flex: 1;
            min-width: 0;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        }
        /* Toggle button always transitions with sidebar width */
        #sidebar-toggle {
            transition: left 0.4s cubic-bezier(0.4, 0, 0.2, 1),
                        background-color 0.2s ease,
                        border-color 0.2s ease !important;
            will-change: left;
        }
        #sidebar-toggle.sidebar-wide { left: calc(18rem - 12px) !important; }
        #sidebar-toggle:not(.sidebar-wide) { left: calc(4.5rem - 12px) !important; }
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
                    <div class="w-8 h-8 logo-icon rounded-full flex items-center justify-center text-white text-xs font-black shadow-md flex-shrink-0 hover:rotate-12 transition-transform duration-300">
                        {{ strtoupper(substr(Auth::user()->username, 0, 1)) }}
                    </div>
                    <div class="sb-user-info min-w-0 flex-1">
                        <p class="text-xs font-black text-gray-900 dark:text-white truncate">{{ Auth::user()->username }}</p>
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

                <div x-show="activeMenu === 'doctors'" x-cloak
                     x-transition:enter="transition ease-out duration-350"
                     x-transition:enter-start="opacity-0 translate-y-5"
                     x-transition:enter-end="opacity-100 translate-y-0">
                    @include('admin.partials.doctors')
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
