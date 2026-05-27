<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Masuk ke akun DelimaCare Anda untuk mengakses layanan kesehatan.">
    <title>Masuk - DelimaCare</title>
    <link rel="icon" type="image/png" href="{{ asset('favicon.svg') }}" type="image/svg+xml">
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
<body class="antialiased min-h-screen flex" x-data="loginPage()">

    {{-- Left Side — Branding Panel --}}
    <div class="hidden lg:flex lg:w-1/2 relative overflow-hidden" style="background: var(--gradient-main);">
        {{-- Decorative elements --}}
        <div class="absolute top-20 left-10 w-64 h-64 rounded-full opacity-10" style="background: radial-gradient(circle, #fff, transparent 70%);"></div>
        <div class="absolute bottom-20 right-10 w-80 h-80 rounded-full opacity-10" style="background: radial-gradient(circle, #fff, transparent 70%);"></div>
        <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-96 h-96 border border-white/10 rounded-full hero-ring"></div>
        <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-72 h-72 border border-white/5 rounded-full hero-ring" style="animation-direction: reverse;"></div>

        {{-- Floating particles --}}
        <div class="particle" style="top:15%;left:20%;animation-delay:0s;"></div>
        <div class="particle" style="top:70%;left:75%;animation-delay:2s;"></div>
        <div class="particle" style="top:40%;left:60%;animation-delay:4s;"></div>
        <canvas id="boids-canvas"></canvas>

        {{-- Content --}}
        <div class="relative z-10 flex flex-col justify-center px-16 text-white max-w-lg">
            <a href="{{ route('home') }}" class="flex items-center gap-3 mb-12">
                <div class="w-12 h-12 rounded-xl bg-white/15 backdrop-blur-sm flex items-center justify-center border border-white/20">
                    <svg class="w-7 h-7 text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
                </div>
                <span class="text-2xl font-extrabold tracking-tight">DelimaCare</span>
            </a>

            <h2 class="text-4xl font-extrabold leading-tight mb-4">Layanan Kesehatan<br>Ibu & Anak Terpadu</h2>
            <p class="text-white/70 text-lg leading-relaxed mb-10">Platform digital terintegrasi untuk manajemen kesehatan keluarga Anda. Mudah, aman, dan terpercaya.</p>

            {{-- Feature pills --}}
            <div class="space-y-3">
                <div class="flex items-center gap-3 bg-white/10 backdrop-blur-sm rounded-xl px-4 py-3 border border-white/10">
                    <div class="w-8 h-8 rounded-lg bg-white/15 flex items-center justify-center flex-shrink-0">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                    </div>
                    <span class="text-sm font-medium text-white/90">Data Anda terenkripsi dan terlindungi</span>
                </div>
                <div class="flex items-center gap-3 bg-white/10 backdrop-blur-sm rounded-xl px-4 py-3 border border-white/10">
                    <div class="w-8 h-8 rounded-lg bg-white/15 flex items-center justify-center flex-shrink-0">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    </div>
                    <span class="text-sm font-medium text-white/90">Reservasi online 24 jam</span>
                </div>
                <div class="flex items-center gap-3 bg-white/10 backdrop-blur-sm rounded-xl px-4 py-3 border border-white/10">
                    <div class="w-8 h-8 rounded-lg bg-white/15 flex items-center justify-center flex-shrink-0">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    </div>
                    <span class="text-sm font-medium text-white/90">Rekam medis digital terintegrasi</span>
                </div>
            </div>
        </div>
    </div>

    {{-- Right Side — Login Form --}}
    <div class="w-full lg:w-1/2 flex items-center justify-center px-6 py-12 bg-[#FAFFFE] dark:bg-[#0B1120] relative transition-colors duration-300">

        {{-- Dark mode toggle --}}
        <button @click="toggleDark()" class="dark-toggle absolute top-6 right-6 text-gray-500" aria-label="Toggle dark mode">
            <svg x-show="!darkMode" class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/></svg>
            <svg x-show="darkMode" x-cloak class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
        </button>

        <div class="w-full max-w-md">
            {{-- Mobile logo --}}
            <a href="{{ route('home') }}" class="lg:hidden flex items-center gap-2.5 justify-center mb-10">
                <div class="w-10 h-10 rounded-xl flex items-center justify-center" style="background: var(--gradient-main);">
                    <svg class="w-6 h-6 text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
                </div>
                <span class="text-2xl font-extrabold text-gray-900 dark:text-white tracking-tight">DelimaCare</span>
            </a>

            {{-- Header --}}
            <div class="mb-8">
                <h1 class="text-3xl font-extrabold text-gray-900 dark:text-white mb-2">Masuk ke Akun</h1>
                <p class="text-gray-500 dark:text-gray-400">Selamat datang kembali! Silakan masuk untuk melanjutkan.</p>
            </div>

            {{-- Error Alert --}}
            @if(session('error'))
            <div class="mb-6 p-4 rounded-xl bg-red-50 dark:bg-red-500/10 border border-red-200 dark:border-red-500/20 flex items-start gap-3">
                <svg class="w-5 h-5 text-red-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                <p class="text-sm text-red-700 dark:text-red-400 font-medium">{{ session('error') }}</p>
            </div>
            @endif

            {{-- Too many attempts --}}
            @if(session('throttle'))
            <div class="mb-6 p-4 rounded-xl bg-amber-50 dark:bg-amber-500/10 border border-amber-200 dark:border-amber-500/20 flex items-start gap-3">
                <svg class="w-5 h-5 text-amber-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <p class="text-sm text-amber-700 dark:text-amber-400 font-medium">{{ session('throttle') }}</p>
            </div>
            @endif

            {{-- Sukses Reset Password Alert --}}
            @if (session('success'))
            <div class="mb-6 p-4 rounded-xl bg-teal-50 dark:bg-teal-500/10 border border-teal-200 dark:border-teal-500/20 flex items-start gap-3">
                <svg class="w-5 h-5 text-teal-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <p class="text-sm text-teal-700 dark:text-teal-400 font-medium">{{ session('success') }}</p>
            </div>
            @endif

            {{-- Login Form --}}
            <form action="{{ route('login') }}" method="POST" class="space-y-5" autocomplete="on">
                @csrf

                {{-- Email --}}
                <div>
                    <label for="email" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Alamat Email</label>
                    <div class="relative">
                        <svg class="w-5 h-5 text-gray-400 absolute left-3.5 top-1/2 -translate-y-1/2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                        <input type="email" id="email" name="email" value="{{ old('email') }}"
                               placeholder="contoh@email.com" required autocomplete="email"
                               class="w-full pl-11 pr-4 py-3.5 rounded-xl border transition-all text-sm outline-none dark:bg-[#1E293B] dark:text-white
                                      @error('email') border-red-400 focus:ring-2 focus:ring-red-500/20 focus:border-red-500
                                      @else border-gray-200 dark:border-gray-700 focus:ring-2 focus:ring-teal-500/20 focus:border-teal-500 @enderror">
                    </div>
                    @error('email')
                        <p class="text-red-500 text-xs mt-1.5 font-medium flex items-center gap-1">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01"/></svg>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                {{-- Password --}}
                <div x-data="{ show: false }">
                    <label for="password" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Kata Sandi</label>
                    <div class="relative">
                        <svg class="w-5 h-5 text-gray-400 absolute left-3.5 top-1/2 -translate-y-1/2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                        <input :type="show ? 'text' : 'password'" id="password" name="password"
                               placeholder="Masukkan kata sandi" required autocomplete="current-password"
                               class="w-full pl-11 pr-12 py-3.5 rounded-xl border transition-all text-sm outline-none dark:bg-[#1E293B] dark:text-white
                                      @error('password') border-red-400 focus:ring-2 focus:ring-red-500/20 focus:border-red-500
                                      @else border-gray-200 dark:border-gray-700 focus:ring-2 focus:ring-teal-500/20 focus:border-teal-500 @enderror">
                        <button type="button" @click="show = !show" class="absolute right-3.5 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600 focus:outline-none transition-colors p-1" tabindex="-1">
                            <svg x-show="!show" class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/></svg>
                            <svg x-show="show" x-cloak class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                        </button>
                    </div>
                    @error('password')
                        <p class="text-red-500 text-xs mt-1.5 font-medium flex items-center gap-1">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01"/></svg>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                {{-- Remember + Forgot --}}
                <div class="flex items-center justify-between mb-2">
                    <label class="flex items-center gap-2 cursor-pointer group">
                        <input type="checkbox" name="remember" class="w-4 h-4 rounded border-gray-300 dark:border-gray-600 dark:bg-gray-700 text-teal-600 focus:ring-teal-500 cursor-pointer">
                        <span class="text-sm text-gray-600 dark:text-gray-400 group-hover:text-gray-900 dark:group-hover:text-white transition-colors">Ingat saya</span>
                    </label>

                    {{-- 🔥 Ini Link Lupa Password-nya 🔥 --}}
                    <a href="{{ route('password.request') }}" class="text-sm font-semibold transition-colors hover:opacity-80" style="color: var(--primary);">
                        Lupa Sandi?
                    </a>
                </div>

                {{-- Submit --}}
                <button type="submit"
                        class="w-full py-3.5 rounded-xl text-white font-semibold text-sm transition-all duration-300 hover:-translate-y-0.5 active:scale-[0.98]"
                        style="background: var(--gradient-main); box-shadow: 0 4px 16px rgba(13,148,136,0.25);">
                    <span class="flex items-center justify-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/></svg>
                        Masuk
                    </span>
                </button>
            </form>

            {{-- Divider --}}
            <div class="mt-8 text-center">
                <p class="text-sm text-gray-500 dark:text-gray-400">
                    Belum punya akun?
                    <a href="{{ route('register') }}" class="font-semibold transition-colors" style="color: var(--primary);">Daftar Sekarang</a>
                </p>
            </div>

            {{-- Back link --}}
            <div class="mt-6 text-center">
                <a href="{{ route('home') }}" class="text-xs text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 transition-colors inline-flex items-center gap-1">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                    Kembali ke Beranda
                </a>
            </div>
        </div>
    </div>

    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="{{ asset('js/boids.js') }}"></script>
    <script>
    function loginPage() {
        return {
            darkMode: document.documentElement.classList.contains('dark'),
            toggleDark() {
                this.darkMode = !this.darkMode;
                localStorage.setItem('delimacare-dark', this.darkMode);
                document.documentElement.classList.add('dark-transition');
                setTimeout(() => document.documentElement.classList.remove('dark-transition'), 350);
                this.darkMode ? document.documentElement.classList.add('dark') : document.documentElement.classList.remove('dark');
            }
        };
    }
    </script>
</body>
</html>
