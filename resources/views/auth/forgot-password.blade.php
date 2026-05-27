<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lupa Kata Sandi - DelimaCare</title>
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
        <canvas id="boids-canvas"></canvas>

        <div class="relative z-10 flex flex-col justify-center px-16 text-white max-w-lg">
            <a href="{{ route('home') }}" class="flex items-center gap-3 mb-12">
                <div class="w-12 h-12 rounded-xl bg-white/15 backdrop-blur-sm flex items-center justify-center border border-white/20">
                    <svg class="w-7 h-7 text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
                </div>
                <span class="text-2xl font-extrabold tracking-tight">DelimaCare</span>
            </a>
            <h2 class="text-4xl font-extrabold leading-tight mb-4">Pemulihan Akun</h2>
            <p class="text-white/70 text-lg leading-relaxed">Jangan khawatir, kami akan membantu Anda mendapatkan kembali akses ke akun Anda dengan aman.</p>
        </div>
    </div>

    {{-- Right Side — Form --}}
    <div class="w-full lg:w-1/2 flex items-center justify-center px-6 py-12 bg-[#FAFFFE] dark:bg-[#0B1120] relative transition-colors duration-300">

        <button @click="toggleDark()" class="dark-toggle absolute top-6 right-6 text-gray-500">
            <svg x-show="!darkMode" class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/></svg>
            <svg x-show="darkMode" x-cloak class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
        </button>

        <div class="w-full max-w-md">
            <div class="mb-8">
                <h1 class="text-3xl font-extrabold text-gray-900 dark:text-white mb-2">Lupa Kata Sandi?</h1>
                <p class="text-gray-500 dark:text-gray-400">Masukkan alamat email yang terdaftar. Kami akan mengirimkan tautan untuk mengatur ulang kata sandi Anda.</p>
            </div>

            {{-- Alert Sukses Pengiriman Email --}}
            @if(session('success'))
            <div class="mb-6 p-4 rounded-xl bg-teal-50 dark:bg-teal-500/10 border border-teal-200 dark:border-teal-500/20 flex items-start gap-3">
                <svg class="w-5 h-5 text-teal-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <p class="text-sm text-teal-700 dark:text-teal-400 font-medium">{{ session('success') }}</p>
            </div>
            @endif

            <form action="{{ route('password.email') }}" method="POST" class="space-y-5">
                @csrf
                <div>
                    <label for="email" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Alamat Email</label>
                    <div class="relative">
                        <svg class="w-5 h-5 text-gray-400 absolute left-3.5 top-1/2 -translate-y-1/2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                        <input type="email" id="email" name="email" value="{{ old('email') }}"
                               placeholder="contoh@email.com" required
                               class="w-full pl-11 pr-4 py-3.5 rounded-xl border transition-all text-sm outline-none dark:bg-[#1E293B] dark:text-white
                                      @error('email') border-red-400 focus:ring-2 focus:ring-red-500/20 @else border-gray-200 dark:border-gray-700 focus:ring-2 focus:ring-teal-500/20 @enderror">
                    </div>
                    @error('email')
                        <p class="text-red-500 text-xs mt-1.5 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                <button type="submit" class="w-full py-3.5 rounded-xl text-white font-semibold text-sm transition-all duration-300 hover:-translate-y-0.5" style="background: var(--gradient-main);">
                    Kirim Tautan Reset
                </button>
            </form>

            <div class="mt-8 text-center">
                <a href="{{ route('login') }}" class="text-sm text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200 transition-colors inline-flex items-center gap-1">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                    Kembali ke halaman masuk
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
                this.darkMode ? document.documentElement.classList.add('dark') : document.documentElement.classList.remove('dark');
            }
        };
    }
    </script>
</body>
</html>
