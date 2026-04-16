<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - DelimaCare</title>

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        body { font-family: 'Inter', sans-serif; background-color: #f8fafc; }
        [x-cloak] { display: none !important; }
        /* Kustom scrollbar biar rapi */
        ::-webkit-scrollbar { width: 6px; height: 6px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background-color: #cbd5e1; border-radius: 10px; }
    </style>

    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
</head>

<body class="text-gray-900 antialiased flex h-screen overflow-hidden" x-data="adminPanel()">

    <aside class="w-64 bg-white border-r border-gray-200 flex flex-col h-full flex-shrink-0 z-20">
        <div class="h-16 flex items-center px-6 border-b border-gray-200">
            <svg class="w-6 h-6 text-black mr-2" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path></svg>
            <div>
                <h1 class="text-lg font-bold leading-tight">DelimaCare</h1>
                <p class="text-[10px] text-gray-500 font-medium uppercase tracking-wider">Admin Panel</p>
            </div>
        </div>

        <nav class="flex-1 overflow-y-auto py-4 px-3 space-y-1">
            <button @click="switchMenu('dashboard')" :class="activeMenu === 'dashboard' ? 'bg-black text-white font-semibold shadow-md' : 'text-gray-700 hover:bg-gray-100 font-medium'" class="w-full flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm transition-all duration-200">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path></svg>
                Dashboard
            </button>

            <button @click="switchMenu('rekam_medis')" :class="activeMenu === 'rekam_medis' ? 'bg-black text-white font-semibold shadow-md' : 'text-gray-700 hover:bg-gray-100 font-medium'" class="w-full flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm transition-all duration-200">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                Rekam Medis
            </button>

            <button @click="switchMenu('reservasi')" :class="activeMenu === 'reservasi' ? 'bg-black text-white font-semibold shadow-md' : 'text-gray-700 hover:bg-gray-100 font-medium'" class="w-full flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm transition-all duration-200">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                Reservasi
            </button>

            <button @click="switchMenu('laporan')" :class="activeMenu === 'laporan' ? 'bg-black text-white font-semibold shadow-md' : 'text-gray-700 hover:bg-gray-100 font-medium'" class="w-full flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm transition-all duration-200">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z"></path></svg>
                Laporan
            </button>

            <button @click="switchMenu('inventori')" :class="activeMenu === 'inventori' ? 'bg-black text-white font-semibold shadow-md' : 'text-gray-700 hover:bg-gray-100 font-medium'" class="w-full flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm transition-all duration-200">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                Inventori
            </button>

            <button @click="switchMenu('keuangan')" :class="activeMenu === 'keuangan' ? 'bg-black text-white font-semibold shadow-md' : 'text-gray-700 hover:bg-gray-100 font-medium'" class="w-full flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm transition-all duration-200">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                Keuangan
            </button>

            <button @click="switchMenu('konten')" :class="activeMenu === 'konten' ? 'bg-black text-white font-semibold shadow-md' : 'text-gray-700 hover:bg-gray-100 font-medium'" class="w-full flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm transition-all duration-200">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"></path></svg>
                Konten
            </button>
        </nav>
    </aside>

    <div class="flex-1 flex flex-col h-full overflow-hidden bg-gray-50/50">

        <header class="h-16 bg-white border-b border-gray-200 flex items-center justify-end px-8 flex-shrink-0 z-10">
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="flex items-center gap-2 px-4 py-2 border border-gray-300 rounded-lg text-sm font-semibold text-gray-700 hover:bg-gray-50 hover:text-red-600 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                    Logout
                </button>
            </form>
        </header>

        <main class="flex-1 overflow-y-auto p-6 md:p-8 relative">

            <div x-show="activeMenu === 'dashboard'" x-cloak x-transition.opacity.duration.300ms>
                @include('admin.partials.dashboard')
            </div>

            <div x-show="activeMenu === 'rekam_medis'" x-cloak x-transition.opacity.duration.300ms>
                @include('admin.partials.rekam-medis')
            </div>

            <div x-show="activeMenu === 'reservasi'" x-cloak x-transition.opacity.duration.300ms>
                @include('admin.partials.reservasi')
            </div>

            <div x-show="activeMenu === 'laporan'" x-cloak x-transition.opacity.duration.300ms>
                @include('admin.partials.laporan')
            </div>

            <div x-show="activeMenu === 'inventori'" x-cloak x-transition.opacity.duration.300ms>
                @include('admin.partials.inventori')
            </div>

            <div x-show="activeMenu === 'keuangan'" x-cloak x-transition.opacity.duration.300ms>
                @include('admin.partials.keuangan')
            </div>

            <div x-show="activeMenu === 'konten'" x-cloak x-transition.opacity.duration.300ms>
                @include('admin.partials.konten')
            </div>

        </main>
    </div>

    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <script src="{{ asset('js/admin.js') }}"></script>
</body>
</html>
