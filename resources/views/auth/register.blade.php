<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Daftar akun DelimaCare untuk mengakses layanan kesehatan ibu dan anak.">
    <title>Daftar Akun - DelimaCare</title>
    <link rel="icon" type="image/png" href="{{ asset('favicon.svg') }}" type="image/svg+xml">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <style>[x-cloak]{display:none!important;}</style>
    <script>if(localStorage.getItem('delimacare-dark')==='true'||(localStorage.getItem('delimacare-dark')===null&&window.matchMedia('(prefers-color-scheme:dark)').matches))document.documentElement.classList.add('dark');</script>
</head>
<body class="antialiased min-h-screen flex" x-data="registerPage()">

    {{-- Left Side — Branding Panel --}}
    <div class="hidden lg:flex lg:w-1/2 relative overflow-hidden" style="background: var(--gradient-main);">
        {{-- Decorative --}}
        <div class="absolute top-20 left-10 w-64 h-64 rounded-full opacity-10" style="background: radial-gradient(circle, #fff, transparent 70%);"></div>
        <div class="absolute bottom-20 right-10 w-80 h-80 rounded-full opacity-10" style="background: radial-gradient(circle, #fff, transparent 70%);"></div>
        <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-96 h-96 border border-white/10 rounded-full hero-ring"></div>
        <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-72 h-72 border border-white/5 rounded-full hero-ring" style="animation-direction: reverse;"></div>
        <div class="particle" style="top:15%;left:20%;animation-delay:0s;"></div>
        <div class="particle" style="top:70%;left:75%;animation-delay:2s;"></div>
        <div class="particle" style="top:40%;left:60%;animation-delay:4s;"></div>

        {{-- Content --}}
        <div class="relative z-10 flex flex-col justify-center px-16 text-white max-w-lg">
            <a href="{{ route('home') }}" class="flex items-center gap-3 mb-12">
                <div class="w-12 h-12 rounded-xl bg-white/15 backdrop-blur-sm flex items-center justify-center border border-white/20">
                    <svg class="w-7 h-7 text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
                </div>
                <span class="text-2xl font-extrabold tracking-tight">DelimaCare</span>
            </a>

            <h2 class="text-4xl font-extrabold leading-tight mb-4">Bergabung Bersama<br>Keluarga DelimaCare</h2>
            <p class="text-white/70 text-lg leading-relaxed mb-10">Buat akun gratis untuk reservasi online, akses rekam medis digital, dan berbagai layanan kesehatan lainnya.</p>

            {{-- Steps --}}
            <div class="space-y-4">
                <div class="flex items-start gap-4">
                    <div class="w-9 h-9 rounded-full bg-white/15 flex items-center justify-center flex-shrink-0 text-sm font-bold border border-white/20">1</div>
                    <div>
                        <h4 class="font-semibold text-white/95 text-sm">Daftar Akun</h4>
                        <p class="text-white/50 text-xs mt-0.5">Isi data diri Anda dengan mudah</p>
                    </div>
                </div>
                <div class="flex items-start gap-4">
                    <div class="w-9 h-9 rounded-full bg-white/15 flex items-center justify-center flex-shrink-0 text-sm font-bold border border-white/20">2</div>
                    <div>
                        <h4 class="font-semibold text-white/95 text-sm">Buat Reservasi</h4>
                        <p class="text-white/50 text-xs mt-0.5">Pilih dokter dan jadwal konsultasi</p>
                    </div>
                </div>
                <div class="flex items-start gap-4">
                    <div class="w-9 h-9 rounded-full bg-white/15 flex items-center justify-center flex-shrink-0 text-sm font-bold border border-white/20">3</div>
                    <div>
                        <h4 class="font-semibold text-white/95 text-sm">Mulai Konsultasi</h4>
                        <p class="text-white/50 text-xs mt-0.5">Dapatkan pelayanan terbaik</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Right Side — Register Form --}}
    <div class="w-full lg:w-1/2 flex items-center justify-center px-6 py-12 bg-[#FAFFFE] relative">

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
                <span class="text-2xl font-extrabold text-gray-900 tracking-tight">DelimaCare</span>
            </a>

            {{-- Header --}}
            <div class="mb-8">
                <h1 class="text-3xl font-extrabold text-gray-900 mb-2">Buat Akun Baru</h1>
                <p class="text-gray-500">Daftar untuk mulai menggunakan layanan DelimaCare.</p>
            </div>

            {{-- Error Alert --}}
            @if(session('error'))
            <div class="mb-6 p-4 rounded-xl bg-red-50 border border-red-200 flex items-start gap-3">
                <svg class="w-5 h-5 text-red-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                <p class="text-sm text-red-700 font-medium">{{ session('error') }}</p>
            </div>
            @endif

            {{-- Register Form --}}
            <form action="{{ route('register') }}" method="POST" class="space-y-5" autocomplete="on">
                @csrf

                {{-- Username --}}
                <div>
                    <label for="username" class="block text-sm font-semibold text-gray-700 mb-2">Username</label>
                    <div class="relative">
                        <svg class="w-5 h-5 text-gray-400 absolute left-3.5 top-1/2 -translate-y-1/2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                        <input type="text" id="username" name="username" value="{{ old('username') }}"
                               placeholder="Masukkan username" required autocomplete="username"
                               class="w-full pl-11 pr-4 py-3.5 rounded-xl border transition-all text-sm outline-none
                                      @error('username') border-red-400 focus:ring-2 focus:ring-red-500/20 focus:border-red-500
                                      @else border-gray-200 focus:ring-2 focus:ring-teal-500/20 focus:border-teal-500 @enderror">
                    </div>
                    @error('username')
                        <p class="text-red-500 text-xs mt-1.5 font-medium flex items-center gap-1">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01"/></svg>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                {{-- Email --}}
                <div>
                    <label for="email" class="block text-sm font-semibold text-gray-700 mb-2">Alamat Email</label>
                    <div class="relative">
                        <svg class="w-5 h-5 text-gray-400 absolute left-3.5 top-1/2 -translate-y-1/2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                        <input type="email" id="email" name="email" value="{{ old('email') }}"
                               placeholder="contoh@email.com" required autocomplete="email"
                               class="w-full pl-11 pr-4 py-3.5 rounded-xl border transition-all text-sm outline-none
                                      @error('email') border-red-400 focus:ring-2 focus:ring-red-500/20 focus:border-red-500
                                      @else border-gray-200 focus:ring-2 focus:ring-teal-500/20 focus:border-teal-500 @enderror">
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
                    <label for="password" class="block text-sm font-semibold text-gray-700 mb-2">Kata Sandi</label>
                    <div class="relative">
                        <svg class="w-5 h-5 text-gray-400 absolute left-3.5 top-1/2 -translate-y-1/2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                        <input :type="show ? 'text' : 'password'" id="password" name="password"
                               placeholder="Minimal 8 karakter" required autocomplete="new-password"
                               class="w-full pl-11 pr-12 py-3.5 rounded-xl border transition-all text-sm outline-none
                                      @error('password') border-red-400 focus:ring-2 focus:ring-red-500/20 focus:border-red-500
                                      @else border-gray-200 focus:ring-2 focus:ring-teal-500/20 focus:border-teal-500 @enderror">
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
                    <p class="text-xs text-gray-400 mt-2 flex items-center gap-1">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        Minimal 8 karakter dengan kombinasi huruf dan angka
                    </p>
                </div>

                {{-- Confirm Password --}}
                <div x-data="{ show: false }">
                    <label for="password_confirmation" class="block text-sm font-semibold text-gray-700 mb-2">Konfirmasi Kata Sandi</label>
                    <div class="relative">
                        <svg class="w-5 h-5 text-gray-400 absolute left-3.5 top-1/2 -translate-y-1/2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                        <input :type="show ? 'text' : 'password'" id="password_confirmation" name="password_confirmation"
                               placeholder="Ulangi kata sandi" required autocomplete="new-password"
                               class="w-full pl-11 pr-12 py-3.5 rounded-xl border border-gray-200 focus:ring-2 focus:ring-teal-500/20 focus:border-teal-500 transition-all text-sm outline-none">
                        <button type="button" @click="show = !show" class="absolute right-3.5 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600 focus:outline-none transition-colors p-1" tabindex="-1">
                            <svg x-show="!show" class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/></svg>
                            <svg x-show="show" x-cloak class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                        </button>
                    </div>
                </div>

                {{-- Submit --}}
                <button type="submit"
                        class="w-full py-3.5 rounded-xl text-white font-semibold text-sm transition-all duration-300 hover:-translate-y-0.5 active:scale-[0.98]"
                        style="background: var(--gradient-main); box-shadow: 0 4px 16px rgba(13,148,136,0.25);">
                    <span class="flex items-center justify-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/></svg>
                        Daftar Sekarang
                    </span>
                </button>
            </form>

            {{-- Divider --}}
            <div class="mt-8 text-center">
                <p class="text-sm text-gray-500">
                    Sudah punya akun?
                    <a href="{{ route('login') }}" class="font-semibold transition-colors" style="color: var(--primary);">Masuk</a>
                </p>
            </div>

            {{-- Back link --}}
            <div class="mt-6 text-center">
                <a href="{{ route('home') }}" class="text-xs text-gray-400 hover:text-gray-600 transition-colors inline-flex items-center gap-1">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                    Kembali ke Beranda
                </a>
            </div>
        </div>
    </div>

    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script>
    function registerPage() {
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
