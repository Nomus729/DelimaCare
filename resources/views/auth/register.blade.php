<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar - DelimaCare</title>

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style>
        body { font-family: 'Inter', sans-serif; }
        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="bg-gray-50 flex items-center justify-center min-h-screen p-4">

    <div class="bg-white w-full max-w-md p-8 md:p-10 rounded-2xl shadow-sm border border-gray-200">

        <div class="flex justify-center items-center gap-2 mb-10">
            <svg class="w-7 h-7 text-black" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path></svg>
            <span class="text-xl font-bold tracking-tight text-gray-900">DelimaCare</span>
        </div>

        <div class="mb-8">
            <h1 class="text-3xl font-semibold text-gray-900 mb-2">Daftar</h1>
            <p class="text-gray-600 text-sm">Selamat Datang!</p>
        </div>

        <form action="/register" method="POST" class="space-y-6">
            @csrf

            <div>
                <label class="block text-sm font-medium text-gray-800 mb-2">Alamat Email</label>
                <input type="email" name="email" value="{{ old('email') }}" placeholder="Masukan alamat email" required
                       class="w-full px-4 py-3 rounded-lg border @error('email') border-red-500 @else border-gray-300 @enderror focus:ring-2 focus:ring-gray-900 focus:border-gray-900 outline-none transition-all placeholder-gray-400 text-sm">

                @error('email')
                    <p class="text-red-500 text-xs mt-1.5 font-medium">{{ $message }}</p>
                @enderror
            </div>

            <div x-data="{ show: false }">
                <label class="block text-sm font-medium text-gray-800 mb-2">Nomer Pendaftaran</label>
                <div class="relative">
                    <input :type="show ? 'text' : 'password'" name="password" placeholder="Masukan kata sandi" required
                           class="w-full pl-4 pr-12 py-3 rounded-lg border @error('password') border-red-500 @else border-gray-300 @enderror focus:ring-2 focus:ring-gray-900 focus:border-gray-900 outline-none transition-all placeholder-gray-400 text-sm">

                    <button type="button" @click="show = !show" class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-700 focus:outline-none transition-colors">
                        <svg x-show="!show" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"></path></svg>
                        <svg x-show="show" x-cloak class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                    </button>
                </div>

                @error('password')
                    <p class="text-red-500 text-xs mt-1.5 font-medium">{{ $message }}</p>
                @enderror
            </div>

            <button type="submit" class="w-full py-3.5 bg-[#050505] text-white font-medium rounded-lg mt-6 hover:bg-gray-800 hover:shadow-lg transform active:scale-[0.98] transition-all duration-200">
                Daftar
            </button>
        </form>

        <div class="mt-10 text-center">
            <p class="text-sm text-gray-400">
                Sudah Punya Akun ? <a href="{{ route('login') }}" class="text-black font-semibold hover:underline">Masuk</a>
            </p>
        </div>

    </div>

</body>
</html>
