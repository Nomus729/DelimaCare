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

            <form action="{{ route('password.update') }}" method="POST" class="space-y-5">
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
                    <input type="password" name="password" required placeholder="Minimal 8 karakter"
                           class="w-full px-4 py-3.5 rounded-xl border transition-all text-sm outline-none dark:bg-[#1E293B] dark:text-white border-gray-200 dark:border-gray-700 focus:ring-2 focus:ring-teal-500/20">
                    @error('password')
                        <p class="text-red-500 text-xs mt-1.5 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Konfirmasi Password --}}
                <div>
                    <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Konfirmasi Kata Sandi</label>
                    <input type="password" name="password_confirmation" required placeholder="Ulangi kata sandi baru"
                           class="w-full px-4 py-3.5 rounded-xl border transition-all text-sm outline-none dark:bg-[#1E293B] dark:text-white border-gray-200 dark:border-gray-700 focus:ring-2 focus:ring-teal-500/20">
                </div>

                <button type="submit" class="w-full py-3.5 rounded-xl text-white font-semibold text-sm transition-all duration-300 hover:-translate-y-0.5" style="background: var(--gradient-main);">
                    Ubah Kata Sandi
                </button>
            </form>

        </div>
    </div>
</body>
</html>
