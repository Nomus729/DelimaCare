<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Portal Pasien DelimaCare - Akses layanan kesehatan ibu dan anak, rekam medis, dan reservasi online.">

    {{-- 🔥 INI DIA OBATNYA BANG! (CSRF TOKEN) 🔥 --}}
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Portal Pasien - DelimaCare</title>
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {}
            }
        }
    </script>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <style>
        [x-cloak] { display: none !important; }
        .chat-scroll::-webkit-scrollbar { width: 6px; }
        .chat-scroll::-webkit-scrollbar-track { background: transparent; }
        .chat-scroll::-webkit-scrollbar-thumb { background-color: var(--primary-light); border-radius: 10px; }
        html.dark .chat-scroll::-webkit-scrollbar-thumb { background-color: #334155; }

        /* App Layout Styles */
        body { background-color: #F1F5F9; }
        html.dark body { background-color: #0F172A; }

        /* Safe Area Utilities for Notched Devices */
        .pb-safe {
            padding-bottom: env(safe-area-inset-bottom, 0px);
        }

        /* Responsive tweaks for narrow screens (320px - 360px) */
        @media (max-width: 360px) {
            .nav-text-responsive {
                font-size: 9px !important;
            }
        }
    </style>
    <script>if(localStorage.getItem('delimacare-dark')==='true'||(localStorage.getItem('delimacare-dark')===null&&window.matchMedia('(prefers-color-scheme:dark)').matches))document.documentElement.classList.add('dark');</script>
</head>

<body class="text-gray-900 antialiased overflow-hidden flex h-[100dvh] md:h-screen" x-data="portalApp()">
    <a href="#main-content" class="sr-only focus:not-sr-only focus:absolute focus:z-50 focus:p-4 focus:bg-white focus:text-teal-600 focus:font-bold rounded-br-xl shadow-lg">Lanjut ke konten utama</a>

    {{-- Desktop Sidebar --}}
    <aside class="hidden md:flex flex-col w-72 bg-white/80 backdrop-blur-xl border-r border-gray-100 dark:bg-[#1E293B]/90 dark:border-gray-800 h-full flex-shrink-0 z-20 shadow-[4px_0_24px_rgba(0,0,0,0.02)]">
        {{-- Branding --}}
        <div class="h-20 flex items-center px-8 border-b border-gray-100/50 dark:border-gray-800/50">
            <a href="{{ route('home') }}" class="flex items-center gap-3 group">
                <div class="w-10 h-10 rounded-xl flex items-center justify-center transition-transform shadow-md shadow-teal-500/20 group-hover:scale-105 group-hover:rotate-3 duration-300" style="background: var(--gradient-main);">
                    <svg class="w-6 h-6 text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
                </div>
                <span class="text-xl font-extrabold tracking-tight text-gray-900 dark:text-white">DelimaCare</span>
            </a>
        </div>

        {{-- Nav Links --}}
        <nav aria-label="Navigasi Utama" role="tablist" aria-orientation="vertical" class="flex-1 px-4 py-6 space-y-2 overflow-y-auto">
            <div class="text-[11px] font-extrabold text-gray-400 uppercase tracking-widest mb-4 px-4 dark:text-gray-500">Menu Pasien</div>

            <button @click="switchTab('reservasi')"
                    role="tab" :aria-selected="activeTab === 'reservasi' ? 'true' : 'false'"
                    class="w-full flex items-center gap-3 px-4 py-3.5 rounded-xl text-sm font-semibold transition-all group relative overflow-hidden"
                    :class="activeTab === 'reservasi' ? 'text-white shadow-lg shadow-teal-500/25' : 'text-gray-600 hover:bg-teal-50/50 hover:text-teal-700 dark:text-gray-400 dark:hover:bg-gray-800/50 dark:hover:text-white'">
                <div x-show="activeTab === 'reservasi'" class="absolute inset-0 z-0" style="background: var(--gradient-main);"></div>
                <svg class="w-5 h-5 relative z-10 transition-colors" :class="activeTab === 'reservasi' ? 'text-white' : 'text-gray-400 group-hover:text-teal-600 dark:text-gray-500 dark:group-hover:text-teal-400'" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                <span class="relative z-10">Buat Reservasi</span>
            </button>

            <button @click="switchTab('jadwal')"
                    role="tab" :aria-selected="activeTab === 'jadwal' ? 'true' : 'false'"
                    class="w-full flex items-center gap-3 px-4 py-3.5 rounded-xl text-sm font-semibold transition-all group relative overflow-hidden"
                    :class="activeTab === 'jadwal' ? 'text-white shadow-lg shadow-teal-500/25' : 'text-gray-600 hover:bg-teal-50/50 hover:text-teal-700 dark:text-gray-400 dark:hover:bg-gray-800/50 dark:hover:text-white'">
                <div x-show="activeTab === 'jadwal'" class="absolute inset-0 z-0" style="background: var(--gradient-main);"></div>
                <svg class="w-5 h-5 relative z-10 transition-colors" :class="activeTab === 'jadwal' ? 'text-white' : 'text-gray-400 group-hover:text-teal-600 dark:text-gray-500 dark:group-hover:text-teal-400'" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <span class="relative z-10">Jadwal Saya</span>
            </button>

            <button @click="switchTab('rekam_medis')"
                    role="tab" :aria-selected="activeTab === 'rekam_medis' ? 'true' : 'false'"
                    class="w-full flex items-center gap-3 px-4 py-3.5 rounded-xl text-sm font-semibold transition-all group relative overflow-hidden"
                    :class="activeTab === 'rekam_medis' ? 'text-white shadow-lg shadow-teal-500/25' : 'text-gray-600 hover:bg-teal-50/50 hover:text-teal-700 dark:text-gray-400 dark:hover:bg-gray-800/50 dark:hover:text-white'">
                <div x-show="activeTab === 'rekam_medis'" class="absolute inset-0 z-0" style="background: var(--gradient-main);"></div>
                <svg class="w-5 h-5 relative z-10 transition-colors" :class="activeTab === 'rekam_medis' ? 'text-white' : 'text-gray-400 group-hover:text-teal-600 dark:text-gray-500 dark:group-hover:text-teal-400'" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                <span class="relative z-10">Rekam Medis</span>
            </button>

            <button @click="switchTab('konsultasi')"
                    role="tab" :aria-selected="activeTab === 'konsultasi' ? 'true' : 'false'"
                    class="w-full flex items-center gap-3 px-4 py-3.5 rounded-xl text-sm font-semibold transition-all group relative overflow-hidden"
                    :class="activeTab === 'konsultasi' ? 'text-white shadow-lg shadow-teal-500/25' : 'text-gray-600 hover:bg-teal-50/50 hover:text-teal-700 dark:text-gray-400 dark:hover:bg-gray-800/50 dark:hover:text-white'">
                <div x-show="activeTab === 'konsultasi'" class="absolute inset-0 z-0" style="background: var(--gradient-main);"></div>
                <svg class="w-5 h-5 relative z-10 transition-colors" :class="activeTab === 'konsultasi' ? 'text-white' : 'text-gray-400 group-hover:text-teal-600 dark:text-gray-500 dark:group-hover:text-teal-400'" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                <span class="relative z-10">Konsultasi Online</span>
            </button>
        </nav>

        {{-- Sidebar Footer / Logout --}}
        <div class="p-4 border-t border-gray-100/50 dark:border-gray-800/50">
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="w-full flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-semibold text-red-600 hover:bg-red-50 hover:text-red-700 transition-all dark:text-red-400 dark:hover:bg-red-500/10">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                    Keluar Akun
                </button>
            </form>
        </div>
    </aside>

    {{-- Main Content Wrapper --}}
    <div class="flex-1 flex flex-col min-w-0 h-[100dvh] md:h-screen overflow-hidden bg-[#FAFFFE] dark:bg-[#0B1120] relative z-10">

        {{-- Decorative Background Blobs for Light Mode --}}
        <div class="absolute top-0 right-0 w-[500px] h-[500px] bg-teal-100/40 rounded-full blur-3xl -translate-y-1/2 translate-x-1/3 pointer-events-none dark:hidden"></div>
        <div class="absolute bottom-0 left-0 w-[600px] h-[600px] bg-cyan-50/50 rounded-full blur-3xl translate-y-1/3 -translate-x-1/3 pointer-events-none dark:hidden"></div>

        {{-- Top Header --}}
        <header class="h-20 flex items-center justify-between px-6 md:px-10 bg-white/60 backdrop-blur-md border-b border-gray-100 dark:bg-[#1E293B]/80 dark:border-gray-800 z-10 flex-shrink-0">

            {{-- Mobile Logo --}}
            <div class="flex items-center gap-3 md:hidden">
                <a href="{{ route('home') }}" class="w-10 h-10 rounded-xl flex items-center justify-center shadow-md shadow-teal-500/20" style="background: var(--gradient-main);">
                    <svg class="w-6 h-6 text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
                </a>
            </div>

            {{-- Title (Desktop) --}}
            <div class="hidden md:block">
                <h2 class="text-2xl font-extrabold text-gray-900 dark:text-white capitalize tracking-tight" x-text="activeTab.replace('_', ' ')"></h2>
            </div>

            {{-- Right Actions --}}
            <div class="flex items-center gap-4 ml-auto">
                {{-- Dark Mode Toggle --}}
                <button @click="toggleDark()" class="w-10 h-10 rounded-full flex items-center justify-center text-gray-500 bg-white border border-gray-100 hover:bg-gray-50 hover:text-teal-600 shadow-sm dark:bg-[#0F172A] dark:border-gray-700 dark:text-gray-400 dark:hover:text-teal-400 transition-all" aria-label="Toggle dark mode">
                    <svg x-show="!darkMode" class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/></svg>
                    <svg x-show="darkMode" x-cloak class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                </button>

                {{-- User Profile (Langsung Buka Modal Edit) --}}
                @auth
                <div class="relative flex items-center gap-3 pl-4 border-l border-gray-200 dark:border-gray-700">

                    {{-- Tombol Profil - Klik langsung buka modal --}}
                    <button type="button" onclick="document.getElementById('modalEditProfil').classList.remove('hidden')" class="flex items-center gap-3 text-left focus:outline-none group transition-transform hover:scale-105 duration-300" title="Edit Profil Anda">
                        <div class="hidden md:block text-right">
                            <div class="text-sm font-bold text-gray-900 dark:text-white group-hover:text-teal-600 transition-colors">{{ Auth::user()->username }}</div>
                            <div class="text-[11px] font-medium text-teal-600 dark:text-teal-400">Pasien Terdaftar</div>
                        </div>

                        {{-- Icon Foto Profil --}}
                        <div class="w-10 h-10 rounded-full flex items-center justify-center text-white text-sm font-bold shadow-md shadow-teal-500/20 bg-cover bg-center border-2 border-transparent group-hover:border-teal-400 transition-all"
                             style="background-image: url('{{ Auth::user()->foto ? asset('storage/' . Auth::user()->foto) : '' }}'); background-color: var(--gradient-main);">
                            @if(!Auth::user()->foto)
                                {{ strtoupper(substr(Auth::user()->username, 0, 1)) }}
                            @endif
                        </div>
                    </button>

                </div>
                @endauth
            </div>
        </header>

        {{-- Scrollable Content Area --}}
        <main id="main-content" class="flex-1 overflow-y-auto p-4 md:p-8 pb-24 md:pb-8 relative z-10">
            <div class="max-w-6xl mx-auto">
                <div x-show="activeTab === 'reservasi'" x-cloak x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0">
                    @include('portal.tabs.reservasi')
                </div>
                <div x-show="activeTab === 'jadwal'" x-cloak x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0">
                    @include('portal.tabs.jadwal')
                </div>
                <div x-show="activeTab === 'rekam_medis'" x-cloak x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0">
                    @include('portal.tabs.rekam-medis')
                </div>
                <div x-show="activeTab === 'konsultasi'" x-cloak x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0">
                    @include('portal.tabs.konsultasi')
                </div>
            </div>
        </main>
    </div>

    {{-- Mobile Bottom Navigation --}}
    <nav aria-label="Navigasi Bawah" role="tablist" class="md:hidden fixed bottom-0 left-0 right-0 bg-white/90 backdrop-blur-xl border-t border-gray-100 dark:bg-[#1E293B]/90 dark:border-gray-800 pb-safe z-50 shadow-[0_-10px_30px_rgba(0,0,0,0.05)] dark:shadow-none">
        <div class="flex items-center justify-around px-1 h-16 relative">
            <button @click="switchTab('reservasi')" role="tab" :aria-selected="activeTab === 'reservasi' ? 'true' : 'false'" class="relative flex flex-col items-center justify-center w-full h-full min-w-0 px-1 space-y-1 font-bold transition-all" :class="activeTab === 'reservasi' ? 'text-teal-600 dark:text-teal-400 -translate-y-0.5' : 'text-gray-400 dark:text-gray-500'">
                <div x-show="activeTab === 'reservasi'" class="absolute -top-3 w-8 h-[2.5px] rounded-full bg-teal-500" x-transition></div>
                <svg class="w-5 h-5 transition-colors" :class="activeTab === 'reservasi' ? 'fill-teal-50 stroke-teal-600 dark:fill-teal-900/40 dark:stroke-teal-400' : 'fill-transparent'" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                <span class="nav-text-responsive text-[10px] min-[375px]:text-[11px] leading-tight text-center whitespace-normal">Reservasi</span>
            </button>
            <button @click="switchTab('jadwal')" role="tab" :aria-selected="activeTab === 'jadwal' ? 'true' : 'false'" class="relative flex flex-col items-center justify-center w-full h-full min-w-0 px-1 space-y-1 font-bold transition-all" :class="activeTab === 'jadwal' ? 'text-teal-600 dark:text-teal-400 -translate-y-0.5' : 'text-gray-400 dark:text-gray-500'">
                <div x-show="activeTab === 'jadwal'" class="absolute -top-3 w-8 h-[2.5px] rounded-full bg-teal-500" x-transition></div>
                <svg class="w-5 h-5 transition-colors" :class="activeTab === 'jadwal' ? 'fill-teal-50 stroke-teal-600 dark:fill-teal-900/40 dark:stroke-teal-400' : 'fill-transparent'" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <span class="nav-text-responsive text-[10px] min-[375px]:text-[11px] leading-tight text-center whitespace-normal">Jadwal</span>
            </button>
            <button @click="switchTab('rekam_medis')" role="tab" :aria-selected="activeTab === 'rekam_medis' ? 'true' : 'false'" class="relative flex flex-col items-center justify-center w-full h-full min-w-0 px-1 space-y-1 font-bold transition-all" :class="activeTab === 'rekam_medis' ? 'text-teal-600 dark:text-teal-400 -translate-y-0.5' : 'text-gray-400 dark:text-gray-500'">
                <div x-show="activeTab === 'rekam_medis'" class="absolute -top-3 w-8 h-[2.5px] rounded-full bg-teal-500" x-transition></div>
                <svg class="w-5 h-5 transition-colors" :class="activeTab === 'rekam_medis' ? 'fill-teal-50 stroke-teal-600 dark:fill-teal-900/40 dark:stroke-teal-400' : 'fill-transparent'" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                <span class="nav-text-responsive text-[10px] min-[375px]:text-[11px] leading-tight text-center whitespace-normal">Rekam Medis</span>
            </button>
            <button @click="switchTab('konsultasi')" role="tab" :aria-selected="activeTab === 'konsultasi' ? 'true' : 'false'" class="relative flex flex-col items-center justify-center w-full h-full min-w-0 px-1 space-y-1 font-bold transition-all" :class="activeTab === 'konsultasi' ? 'text-teal-600 dark:text-teal-400 -translate-y-0.5' : 'text-gray-400 dark:text-gray-500'">
                <div x-show="activeTab === 'konsultasi'" class="absolute -top-3 w-8 h-[2.5px] rounded-full bg-teal-500" x-transition></div>
                <svg class="w-5 h-5 transition-colors" :class="activeTab === 'konsultasi' ? 'fill-teal-50 stroke-teal-600 dark:fill-teal-900/40 dark:stroke-teal-400' : 'fill-transparent'" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                <span class="nav-text-responsive text-[10px] min-[375px]:text-[11px] leading-tight text-center whitespace-normal">Konsultasi</span>
            </button>
        </div>
    </nav>

    {{-- Success Popup Modal --}}
    @if(session('success'))
        <div id="success-popup"
             x-data="{ showSuccessModal: true }"
             x-show="showSuccessModal"
             x-cloak
             class="fixed inset-0 z-[999] flex items-center justify-center p-4">
            
            {{-- Backdrop --}}
            <div class="absolute inset-0 bg-gray-900/60 backdrop-blur-sm"
                 @click="showSuccessModal = false"
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 x-transition:leave="transition ease-in duration-200"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0"></div>

            {{-- Modal Content --}}
            <div class="relative bg-white dark:bg-[#1E293B] w-full max-w-md rounded-3xl shadow-2xl overflow-hidden p-8 text-center border border-gray-100 dark:border-gray-800"
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 translate-y-4 scale-95"
                 x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                 x-transition:leave="transition ease-in duration-200"
                 x-transition:leave-start="opacity-100 translate-y-0 scale-100"
                 x-transition:leave-end="opacity-0 translate-y-4 scale-95"
                 @click.stop>
                
                {{-- Success Icon (inspired by admin notification/success design) --}}
                <div class="w-20 h-20 bg-teal-50 dark:bg-teal-900/30 rounded-full flex items-center justify-center mx-auto mb-6 shadow-inner relative">
                    <div class="absolute inset-0 rounded-full bg-teal-500/10 dark:bg-teal-400/10 animate-ping opacity-25"></div>
                    <div class="w-16 h-16 rounded-full bg-teal-500 flex items-center justify-center text-white shadow-lg shadow-teal-500/40 relative z-10">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"></path>
                        </svg>
                    </div>
                </div>

                {{-- Heading --}}
                <h3 class="text-2xl font-black text-gray-900 dark:text-white tracking-tight mb-2">
                    @if(str_contains(session('success'), 'Reservasi'))
                        Reservasi Berhasil!
                    @else
                        Berhasil!
                    @endif
                </h3>
                
                {{-- Description --}}
                <p id="success-popup-message" class="text-sm text-gray-500 dark:text-gray-400 mb-6 font-semibold leading-relaxed">
                    {{ session('success') }}
                </p>

                {{-- Action Button --}}
                <div class="flex flex-col gap-2">
                    <button id="close-success-popup"
                            @click="showSuccessModal = false"
                            class="w-full py-3.5 bg-gradient-to-r from-teal-600 to-cyan-500 hover:from-teal-700 hover:to-cyan-600 text-white font-bold rounded-2xl shadow-lg shadow-teal-500/20 transition-all text-sm hover:-translate-y-0.5">
                        Tutup
                    </button>
                </div>
            </div>
        </div>
    @endif

    {{-- File Modal Profil Kita Panggil Di Sini Biar Aman --}}
    @include('portal.tabs.profil')

    <!-- Pusher & Echo -->
    <script src="https://js.pusher.com/8.2.0/pusher.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/laravel-echo@1.15.3/dist/echo.iife.js"></script>
    <script>
        window.Pusher = Pusher;
        window.Echo = new Echo({
            broadcaster: 'pusher',
            key: '{{ env("PUSHER_APP_KEY") }}',
            cluster: '{{ env("PUSHER_APP_CLUSTER") }}',
            forceTLS: true
        });
        window.currentUsername = '{{ Auth::user()->username }}';
    </script>

    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="{{ asset('js/portal.js') }}"></script>
</body>
</html>
