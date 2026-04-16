<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Portal Pasien - DelimaCare</title>

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; background-color: #f8fafc; }
        [x-cloak] { display: none !important; }
        .chat-scroll::-webkit-scrollbar { width: 6px; }
        .chat-scroll::-webkit-scrollbar-track { background: transparent; }
        .chat-scroll::-webkit-scrollbar-thumb { background-color: #cbd5e1; border-radius: 10px; }
    </style>

    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="text-gray-900 antialiased min-h-screen flex flex-col" x-data="portalApp()">

    <header class="bg-white border-b border-gray-200 py-4 px-8 md:px-16 flex justify-between items-center sticky top-0 z-50">
        <div class="flex items-center gap-2">
            <svg class="w-7 h-7 text-black" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path></svg>
            <span class="text-xl font-extrabold tracking-tight">DelimaCare</span>
        </div>
        <a href="{{ route('home') }}" class="flex items-center gap-2 px-5 py-2 border border-gray-300 rounded-lg hover:bg-gray-50 font-medium text-sm transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            Kembali ke Beranda
        </a>
    </header>

    <main class="flex-grow w-full max-w-7xl mx-auto px-4 md:px-8 py-8">

        <div class="mb-8">
            <h1 class="text-3xl font-extrabold text-gray-900 mb-2">Portal Pasien</h1>
            <p class="text-gray-500">Buat reservasi dan akses rekam medis Anda</p>
        </div>

        <div class="bg-gray-100/80 p-1.5 rounded-xl flex flex-col md:flex-row gap-1 mb-10 border border-gray-200">
            <button @click="switchTab('reservasi')" :class="activeTab === 'reservasi' ? 'bg-white shadow-sm text-blue-700 font-bold' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-200/50 font-medium'" class="flex-1 flex items-center justify-center gap-2 py-3 px-4 rounded-lg text-sm transition-all duration-200">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                Buat Reservasi
            </button>
            <button @click="switchTab('jadwal')" :class="activeTab === 'jadwal' ? 'bg-white shadow-sm text-blue-700 font-bold' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-200/50 font-medium'" class="flex-1 flex items-center justify-center gap-2 py-3 px-4 rounded-lg text-sm transition-all duration-200">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                Jadwal Saya
            </button>
            <button @click="switchTab('rekam_medis')" :class="activeTab === 'rekam_medis' ? 'bg-white shadow-sm text-blue-700 font-bold' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-200/50 font-medium'" class="flex-1 flex items-center justify-center gap-2 py-3 px-4 rounded-lg text-sm transition-all duration-200">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                Rekam Medis
            </button>
            <button @click="switchTab('konsultasi')" :class="activeTab === 'konsultasi' ? 'bg-white shadow-sm text-blue-700 font-bold' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-200/50 font-medium'" class="flex-1 flex items-center justify-center gap-2 py-3 px-4 rounded-lg text-sm transition-all duration-200">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path></svg>
                Konsultasi Online
            </button>
        </div>

        <div x-show="activeTab === 'reservasi'" x-cloak x-transition.opacity.duration.400ms>
            @include('portal.tabs.reservasi')
        </div>

        <div x-show="activeTab === 'jadwal'" x-cloak x-transition.opacity.duration.400ms>
            @include('portal.tabs.jadwal')
        </div>

        <div x-show="activeTab === 'rekam_medis'" x-cloak x-transition.opacity.duration.400ms>
            @include('portal.tabs.rekam-medis')
        </div>

        <div x-show="activeTab === 'konsultasi'" x-cloak x-transition.opacity.duration.400ms>
            @include('portal.tabs.konsultasi')
        </div>

    </main>

    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="{{ asset('js/portal.js') }}"></script>

</body>
</html>
