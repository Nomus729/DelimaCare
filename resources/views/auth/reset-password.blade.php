<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buat Sandi Baru - DelimaCare</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = { darkMode: 'class', theme: { extend: {} } }
    </script>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <script>if(localStorage.getItem('delimacare-dark')==='true'||(localStorage.getItem('delimacare-dark')===null&&window.matchMedia('(prefers-color-scheme:dark)').matches))document.documentElement.classList.add('dark');</script>
</head>
<body class="antialiased min-h-screen flex bg-[#FAFFFE] dark:bg-[#0B1120]">

    <div class="w-full flex items-center justify-center px-6 py-12">
        <div class="w-full max-w-md">

            <div class="mb-8 text-center">
                <div class="w-16 h-16 mx-auto mb-4 rounded-2xl flex items-center justify-center" style="background: var(--gradient-main);">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                </div>
                <h1 class="text-3xl font-extrabold text-gray-900 dark:text-white mb-2">Verifikasi OTP</h1>
                <p class="text-gray-500 dark:text-gray-400">Masukkan kode 6 digit yang telah dikirim ke email <strong class="text-teal-600">{{ request('email') }}</strong></p>
            </div>

            @if(session('error'))
            <div class="mb-6 p-4 rounded-xl bg-red-50 dark:bg-red-500/10 border border-red-200 dark:border-red-500/20">
                <p class="text-sm text-red-700 dark:text-red-400 font-medium text-center">{{ session('error') }}</p>
            </div>
            @endif

            @if(session('success'))
            <div class="mb-6 p-4 rounded-xl bg-teal-50 dark:bg-teal-500/10 border border-teal-200 dark:border-teal-500/20">
                <p class="text-sm text-teal-700 dark:text-teal-400 font-medium text-center">{{ session('success') }}</p>
            </div>
            @endif

            <form action="{{ route('password.update') }}" method="POST" class="space-y-5" x-data="{ showPassword: false, showConfirmPassword: false }">
                @csrf
                {{-- Email dibawa secara otomatis/hidden --}}
                <input type="hidden" name="email" value="{{ request('email') }}">

                {{-- 🔥 INPUT KODE OTP 🔥 --}}
                <div>
                    <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Kode OTP</label>
                    <input type="text" name="code" required placeholder="Contoh: 123456" maxlength="6"
                           class="w-full px-4 py-3.5 rounded-xl border transition-all text-sm text-center tracking-[0.5em] font-bold outline-none dark:bg-[#1E293B] dark:text-white border-gray-200 dark:border-gray-700 focus:ring-2 focus:ring-teal-500/20">
                    @error('code')
                        <p class="text-red-500 text-xs mt-1.5 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Password Baru --}}
                <div>
                    <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Kata Sandi Baru</label>
                    <div class="relative">
                        <input :type="showPassword ? 'text' : 'password'" name="password" required placeholder="Minimal 8 karakter"
                               class="w-full pl-4 pr-12 py-3.5 rounded-xl border transition-all text-sm outline-none dark:bg-[#1E293B] dark:text-white border-gray-200 dark:border-gray-700 focus:ring-2 focus:ring-teal-500/20">
                        <button type="button" @click="showPassword = !showPassword" class="absolute right-3.5 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600 focus:outline-none transition-colors p-1" tabindex="-1">
                            <svg x-show="!showPassword" class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/></svg>
                            <svg x-show="showPassword" x-cloak class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                        </button>
                    </div>
                    @error('password')
                        <p class="text-red-500 text-xs mt-1.5 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Konfirmasi Password --}}
                <div>
                    <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Konfirmasi Kata Sandi</label>
                    <div class="relative">
                        <input :type="showConfirmPassword ? 'text' : 'password'" name="password_confirmation" required placeholder="Ulangi kata sandi baru"
                               class="w-full pl-4 pr-12 py-3.5 rounded-xl border transition-all text-sm outline-none dark:bg-[#1E293B] dark:text-white border-gray-200 dark:border-gray-700 focus:ring-2 focus:ring-teal-500/20">
                        <button type="button" @click="showConfirmPassword = !showConfirmPassword" class="absolute right-3.5 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600 focus:outline-none transition-colors p-1" tabindex="-1">
                            <svg x-show="!showConfirmPassword" class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/></svg>
                            <svg x-show="showConfirmPassword" x-cloak class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                        </button>
                    </div>
                </div>

                <button type="submit" class="w-full py-3.5 rounded-xl text-white font-semibold text-sm transition-all duration-300 hover:-translate-y-0.5" style="background: var(--gradient-main);">
                    Ubah Kata Sandi
                </button>
            </form>

        </div>
    </div>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</body>
</html>
