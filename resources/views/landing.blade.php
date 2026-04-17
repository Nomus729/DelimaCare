<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DelimaCare - Layanan Kesehatan Ibu & Anak Terpadu</title>

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>body { font-family: 'Inter', sans-serif; }</style>

    <script src="https://cdn.tailwindcss.com"></script>

    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>

<body class="bg-gray-50 text-gray-900 antialiased" x-data="layout">

    <header :class="{ 'bg-white/90 backdrop-blur-sm shadow-sm py-3': scrolled, 'bg-white py-5': !scrolled }"
            class="fixed w-full top-0 z-50 transition-all duration-300 px-8 md:px-16 border-b border-gray-200 flex justify-between items-center animate-fade-in">
        <div class="flex items-center gap-2 cursor-pointer">
            <svg class="w-8 h-8 text-black" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path></svg>
            <span class="text-2xl font-extrabold tracking-tight">DelimaCare</span>
        </div>
        <div class="flex gap-4">
            <a href="{{ route('portal') }}" class="px-6 py-2 border-2 border-gray-300 rounded-full hover:border-black font-semibold text-sm transition-all duration-300">Portal Pasien</a>
            <a href="{{ route('login') }}" class="px-8 py-2.5 bg-black text-white rounded-full hover:bg-gray-800 hover:shadow-lg transform active:scale-95 font-semibold text-sm transition-all duration-300">Login</a>
        </div>
    </header>

    <div class="h-24 bg-white"></div>

    <section class="bg-white px-8 md:px-16 py-16 md:py-24 max-w-4xl mx-auto opacity-0 animate-fade-in delay-100">
        <h1 class="text-5xl md:text-7xl font-extrabold leading-tight mb-8 tracking-tighter">
            Layanan<br>
            Kesehatan<br>
            Ibu & Anak<br>
            Terpadu
        </h1>
        <p class="text-xl text-gray-600 mb-12 max-w-xl leading-relaxed">
            Platform digital terintegrasi untuk manajemen klinik kesehatan ibu hamil dan keluarga berencana. Akses mudah, data aman, layanan profesional.
        </p>
        <div class="flex flex-wrap gap-5">
            <button class="px-9 py-4 bg-black text-white rounded-xl hover:bg-gray-800 hover:shadow-xl hover:-translate-y-1 transform font-semibold transition-all duration-300">
                Buat Reservasi
            </button>
            <a href="#fitur" class="px-9 py-4 border-2 border-gray-300 rounded-xl hover:border-black font-semibold transition-colors duration-300">
                Pelajari Lebih Lanjut
            </a>
        </div>
    </section>

    <section id="fitur" class="bg-white py-24 border-t border-gray-200">
        <div class="text-center mb-16 opacity-0 animate-fade-in delay-200">
            <h2 class="text-4xl font-extrabold text-gray-900">Fitur Unggulan</h2>
        </div>

        <div class="px-8 md:px-16 max-w-7xl mx-auto grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">

            <div class="bg-white border border-gray-200 p-8 rounded-2xl hover:shadow-xl hover:-translate-y-2 transition-all duration-300">
                <div class="w-14 h-14 bg-gray-50 rounded-xl border border-gray-100 flex items-center justify-center text-black mb-6">
                    <svg fill="none" stroke="currentColor" stroke-width="2" class="w-8 h-8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4"></path></svg>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-3">Rekam Medis Digital</h3>
                <p class="text-gray-600 leading-relaxed text-sm">Basis data terintegrasi untuk riwayat kesehatan pasien ibu hamil dan KB yang aman dan mudah diakses.</p>
            </div>

            <div class="bg-white border border-gray-200 p-8 rounded-2xl hover:shadow-xl hover:-translate-y-2 transition-all duration-300">
                <div class="w-14 h-14 bg-gray-50 rounded-xl border border-gray-100 flex items-center justify-center text-black mb-6">
                    <svg fill="none" stroke="currentColor" stroke-width="2" class="w-8 h-8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path><circle cx="12" cy="14" r="2"></circle></svg>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-3">Reservasi Online</h3>
                <p class="text-gray-600 leading-relaxed text-sm">Pastikan jadwal dokter real-time dan buat janji temu dengan mudah melalui sistem online.</p>
            </div>

            <div class="bg-white border border-gray-200 p-8 rounded-2xl hover:shadow-xl hover:-translate-y-2 transition-all duration-300">
                <div class="w-14 h-14 bg-gray-50 rounded-xl border border-gray-100 flex items-center justify-center text-black mb-6">
                    <svg fill="none" stroke="currentColor" stroke-width="2" class="w-8 h-8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z"></path></svg>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-3">Laporan Otomatis</h3>
                <p class="text-gray-600 leading-relaxed text-sm">Pelaporan administrasi instan untuk kebutuhan Pusat dan Bidan Desa tanpa input manual.</p>
            </div>

            <div class="bg-white border border-gray-200 p-8 rounded-2xl hover:shadow-xl hover:-translate-y-2 transition-all duration-300">
                <div class="w-14 h-14 bg-gray-50 rounded-xl border border-gray-100 flex items-center justify-center text-black mb-6">
                    <svg fill="none" stroke="currentColor" stroke-width="2" class="w-8 h-8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-3">Manajemen Stok</h3>
                <p class="text-gray-600 leading-relaxed text-sm">Pantau ketersediaan obat dan logistik medis secara real-time untuk operasional optimal.</p>
            </div>

            <div class="bg-white border border-gray-200 p-8 rounded-2xl hover:shadow-xl hover:-translate-y-2 transition-all duration-300">
                <div class="w-14 h-14 bg-gray-50 rounded-xl border border-gray-100 flex items-center justify-center text-black mb-6">
                    <svg fill="none" stroke="currentColor" stroke-width="2" class="w-8 h-8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-3">Dashboard Terintegrasi</h3>
                <p class="text-gray-600 leading-relaxed text-sm">Antarmuka intuitif untuk semua staf klinik dengan akses mudah ke semua modul sistem.</p>
            </div>

            <div class="bg-white border border-gray-200 p-8 rounded-2xl hover:shadow-xl hover:-translate-y-2 transition-all duration-300">
                <div class="w-14 h-14 bg-gray-50 rounded-xl border border-gray-100 flex items-center justify-center text-black mb-6">
                    <svg fill="none" stroke="currentColor" stroke-width="2" class="w-8 h-8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-3">Keamanan Data</h3>
                <p class="text-gray-600 leading-relaxed text-sm">Perlindungan data pasien dengan enkripsi dan sistem keamanan tingkat tinggi.</p>
            </div>

        </div>
    </section>

    <section class="bg-[#0b005e] py-24 px-8 md:px-16">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8 max-w-7xl mx-auto h-[450px]">
            <div class="group rounded-2xl w-full h-full overflow-hidden relative cursor-pointer">
                <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/20 to-transparent z-10"></div>
                <img src="https://images.unsplash.com/photo-1579684385127-1ef15d508118?w=800&q=80" class="absolute inset-0 w-full h-full object-cover group-hover:scale-110 transition-transform duration-700">
            </div>

            <div class="group rounded-2xl w-full h-full overflow-hidden relative cursor-pointer">
                <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/20 to-transparent z-10"></div>
                <img src="https://images.unsplash.com/photo-1551076805-e1869043e560?w=800&q=80" class="absolute inset-0 w-full h-full object-cover group-hover:scale-110 transition-transform duration-700">
            </div>

            <div class="bg-white rounded-2xl w-full h-full p-8 relative flex flex-col justify-center hover:-translate-y-2 hover:shadow-2xl transition-all duration-300">
                <div class="absolute top-8 left-8 px-5 py-2 bg-blue-700 text-white text-sm font-bold rounded-full">
                    Pengumuman
                </div>
                <div class="mt-8">
                    <h4 class="text-2xl font-bold text-gray-900 mb-4">Jadwal Vaksinasi Anak</h4>
                    <p class="text-gray-600 mb-6 leading-relaxed">Pendaftaran vaksinasi untuk bulan ini telah dibuka. Segera daftarkan buah hati Anda melalui portal pasien.</p>
                </div>
            </div>
        </div>
    </section>

    <footer class="bg-[#050505] text-white py-16 px-8 md:px-16 border-t border-gray-900">
        <div class="max-w-7xl mx-auto grid grid-cols-1 md:grid-cols-3 gap-12 mb-12">
            <div>
                <h3 class="text-xl font-bold mb-4">DelimaCare</h3>
                <p class="text-gray-400 text-sm leading-relaxed max-w-xs">
                    Platform manajemen klinik kesehatan ibu dan anak terintegrasi.
                </p>
            </div>
            <div>
                <h3 class="text-lg font-semibold mb-5">Layanan</h3>
                <ul class="space-y-4 text-sm text-gray-400">
                    <li><a href="#" class="hover:text-white transition">Pemeriksaan Kehamilan</a></li>
                    <li><a href="#" class="hover:text-white transition">Keluarga Berencana</a></li>
                    <li><a href="#" class="hover:text-white transition">Konsultasi Online</a></li>
                    <li><a href="#" class="hover:text-white transition">Rekam Medis Digital</a></li>
                </ul>
            </div>
            <div>
                <h3 class="text-lg font-semibold mb-5">Kontak</h3>
                <ul class="space-y-4 text-sm text-gray-400">
                    <li>Email: info@delimacare.id</li>
                    <li>Telepon: (021) 1234-5678</li>
                    <li>Alamat: Subang, Indonesia</li>
                </ul>
            </div>
        </div>
        <div class="max-w-7xl mx-auto border-t border-gray-800 pt-8 text-center text-sm text-gray-500">
            © {{ date('Y') }} DelimaCare. Semua hak dilindungi.
        </div>
    </footer>

    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="{{ asset('js/script.js') }}"></script>

</body>
</html>
