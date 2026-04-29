<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="DelimaCare — Platform digital terintegrasi untuk manajemen klinik kesehatan ibu hamil dan keluarga berencana. Akses mudah, data aman, layanan profesional.">
    <title>DelimaCare - Layanan Kesehatan Ibu & Anak Terpadu</title>
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">

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
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <style>[x-cloak]{display:none!important;}</style>
    <script>if(localStorage.getItem('delimacare-dark')==='true'||(localStorage.getItem('delimacare-dark')===null&&window.matchMedia('(prefers-color-scheme:dark)').matches))document.documentElement.classList.add('dark');</script>
</head>

<body class="antialiased" x-data="layout">

    {{-- ====== NAVBAR ====== --}}
    <header :class="{ 'glass shadow-md py-3': scrolled, 'bg-transparent py-5': !scrolled }"
            class="fixed w-full top-0 z-50 transition-all duration-500 px-6 md:px-16 flex justify-between items-center animate-fade-in-down">
        <a href="{{ route('home') }}" class="flex items-center gap-2.5 group">
            <div class="w-9 h-9 rounded-lg flex items-center justify-center transition-all duration-300 group-hover:scale-110"
                 :class="scrolled ? '' : ''"
                 style="background: var(--gradient-main);">
                <svg class="w-5 h-5 text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                </svg>
            </div>
            <span class="text-xl font-extrabold tracking-tight transition-colors duration-300"
                  :class="scrolled ? 'text-gray-900' : 'text-white'">DelimaCare</span>
        </a>

        {{-- Nav Links (hidden on mobile) --}}
        <nav class="hidden md:flex items-center gap-8">
            <a href="#beranda" class="nav-link text-sm font-medium transition-colors duration-300"
               :class="scrolled ? 'text-gray-600 hover:text-teal-600' : 'text-white/80 hover:text-white'">Beranda</a>
            <a href="#fitur" class="nav-link text-sm font-medium transition-colors duration-300"
               :class="scrolled ? 'text-gray-600 hover:text-teal-600' : 'text-white/80 hover:text-white'">Fitur</a>
            <a href="#tentang" class="nav-link text-sm font-medium transition-colors duration-300"
               :class="scrolled ? 'text-gray-600 hover:text-teal-600' : 'text-white/80 hover:text-white'">Tentang</a>
            <a href="#konten" class="nav-link text-sm font-medium transition-colors duration-300"
               :class="scrolled ? 'text-gray-600 hover:text-teal-600' : 'text-white/80 hover:text-white'">Konten</a>
            <a href="#testimoni" class="nav-link text-sm font-medium transition-colors duration-300"
               :class="scrolled ? 'text-gray-600 hover:text-teal-600' : 'text-white/80 hover:text-white'">Testimoni</a>
        </nav>

        <div class="flex items-center gap-3">
            {{-- Dark Mode Toggle --}}
            <button @click="toggleDark()" class="dark-toggle" :class="scrolled ? 'text-gray-600' : 'text-white'" aria-label="Toggle dark mode">
                <svg x-show="!darkMode" class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/></svg>
                <svg x-show="darkMode" x-cloak class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
            </button>
            <a href="{{ route('login') }}"
               class="btn-dark text-sm !py-2.5 !px-6">
                Login
            </a>
        </div>
    </header>

    {{-- ====== HERO ====== --}}
    @include('landing._hero')

    {{-- ====== STATS ====== --}}
    @include('landing._stats')

    {{-- ====== FEATURES ====== --}}
    @include('landing._features')

    {{-- ====== ABOUT ====== --}}
    @include('landing._about')

    {{-- ====== STEPS ====== --}}
    @include('landing._steps')

    {{-- ====== ARTICLES ====== --}}
    @include('landing._articles')

    {{-- ====== TESTIMONIALS ====== --}}
    @include('landing._testimonials')

    {{-- ====== CTA + FOOTER ====== --}}
    @include('landing._footer')

    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="{{ asset('js/script.js') }}"></script>

</body>
</html>
