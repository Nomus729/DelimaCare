<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DelimaCare - Layanan Kesehatan Ibu & Anak</title>

    <script src="https://cdn.tailwindcss.com"></script>

    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <style>
        body { font-family: 'Inter', sans-serif; }
        /* Kustom Animasi Masuk */
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .animate-fade-in {
            animation: fadeInUp 0.8s ease-out forwards;
        }
        .delay-100 { animation-delay: 0.1s; }
        .delay-200 { animation-delay: 0.2s; }
    </style>
</head>
<body class="bg-gray-50 text-gray-900 antialiased" x-data="{ scrolled: false }" @scroll.window="scrolled = (window.pageYOffset > 20)">

    <header :class="{ 'bg-white/80 backdrop-blur-md shadow-sm py-3': scrolled, 'bg-transparent py-5': !scrolled }"
            class="fixed w-full top-0 z-50 transition-all duration-300 px-8 md:px-16 border-b border-gray-200 flex justify-between items-center">
        <div class="flex items-center gap-2 cursor-pointer hover:scale-105 transition-transform duration-300">
            <div class="bg-blue-600 text-white p-1.5 rounded-lg">
                <svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path></svg>
            </div>
            <span class="text-xl font-bold bg-clip-text text-transparent bg-gradient-to-r from-gray-900 to-gray-600">DelimaCare</span>
        </div>
        <div class="flex gap-4">
            <a href="#" class="px-5 py-2 border-2 border-gray-200 rounded-full hover:border-blue-600 hover:text-blue-600 font-medium transition-colors duration-300">Portal Pasien</a>
            <a href="#" class="px-6 py-2 bg-black text-white rounded-full hover:bg-blue-700 hover:shadow-lg hover:shadow-blue-500/30 transform active:scale-95 font-medium transition-all duration-300">Login</a>
        </div>
    </header>

    <div class="h-24 bg-white"></div>

    <section class="bg-white px-8 md:px-16 py-12 md:py-20 max-w-4xl opacity-0 animate-fade-in relative">
        <div class="absolute top-0 right-[-20%] w-96 h-96 bg-blue-50 rounded-full blur-3xl -z-10"></div>

        <h1 class="text-5xl md:text-7xl font-extrabold leading-tight mb-6 tracking-tight">
            Layanan<br>
            <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-600 to-purple-600">Kesehatan</span><br>
            Ibu & Anak<br>
            Terpadu
        </h1>
        <p class="text-lg text-gray-600 mb-10 max-w-xl leading-relaxed">
            Platform digital terintegrasi untuk manajemen klinik kesehatan ibu hamil dan keluarga berencana. Akses mudah, data aman, layanan profesional.
        </p>
        <div class="flex flex-wrap gap-4">
            <button class="px-8 py-3.5 bg-black text-white rounded-xl hover:bg-blue-600 hover:shadow-xl hover:shadow-blue-500/20 hover:-translate-y-1 transform active:scale-95 font-medium transition-all duration-300 flex items-center gap-2">
                Buat Reservasi
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
            </button>
            <a href="#fitur" class="px-8 py-3.5 border-2 border-gray-200 rounded-xl hover:border-gray-900 hover:bg-gray-50 font-medium transition-colors duration-300">Pelajari Lebih Lanjut</a>
        </div>
    </section>

    <section id="fitur" class="bg-white py-20 border-t border-gray-100">
        <div class="text-center mb-16 opacity-0 animate-fade-in delay-100">
            <h2 class="text-sm font-bold text-blue-600 uppercase tracking-widest mb-2">Keunggulan Sistem</h2>
            <h3 class="text-3xl md:text-4xl font-bold">Fitur Unggulan Kami</h3>
        </div>

        <div class="px-8 md:px-16 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">

            <div class="group p-8 border border-gray-100 bg-white rounded-2xl hover:-translate-y-2 hover:shadow-2xl hover:shadow-blue-500/10 hover:border-blue-100 transition-all duration-300 cursor-pointer">
                <div class="w-14 h-14 mb-6 bg-blue-50 text-blue-600 rounded-xl flex items-center justify-center group-hover:scale-110 group-hover:bg-blue-600 group-hover:text-white transition-all duration-300">
                    <svg fill="none" stroke="currentColor" stroke-width="1.5" class="w-7 h-7" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m3.75 9v6m3-3H9m1.5-12H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z"></path></svg>
                </div>
                <h3 class="text-xl font-bold mb-3 group-hover:text-blue-600 transition-colors">Rekam Medis Digital</h3>
                <p class="text-gray-500 text-sm leading-relaxed">Basis data terintegrasi untuk riwayat kesehatan pasien ibu hamil dan KB yang aman dan mudah diakses.</p>
            </div>

            <div class="group p-8 border border-gray-100 bg-white rounded-2xl hover:-translate-y-2 hover:shadow-2xl hover:shadow-blue-500/10 hover:border-blue-100 transition-all duration-300 cursor-pointer">
                <div class="w-14 h-14 mb-6 bg-blue-50 text-blue-600 rounded-xl flex items-center justify-center group-hover:scale-110 group-hover:bg-blue-600 group-hover:text-white transition-all duration-300">
                     <svg fill="none" stroke="currentColor" stroke-width="1.5" class="w-7 h-7" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5"></path></svg>
                </div>
                <h3 class="text-xl font-bold mb-3 group-hover:text-blue-600 transition-colors">Reservasi Online</h3>
                <p class="text-gray-500 text-sm leading-relaxed">Pastikan jadwal dokter real-time dan buat janji temu dengan mudah melalui sistem online.</p>
            </div>

            <div class="group p-8 border border-gray-100 bg-white rounded-2xl hover:-translate-y-2 hover:shadow-2xl hover:shadow-blue-500/10 hover:border-blue-100 transition-all duration-300 cursor-pointer">
                <div class="w-14 h-14 mb-6 bg-blue-50 text-blue-600 rounded-xl flex items-center justify-center group-hover:scale-110 group-hover:bg-blue-600 group-hover:text-white transition-all duration-300">
                     <svg fill="none" stroke="currentColor" stroke-width="1.5" class="w-7 h-7" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 013 19.875v-6.75zM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V8.625zM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V4.125z"></path></svg>
                </div>
                <h3 class="text-xl font-bold mb-3 group-hover:text-blue-600 transition-colors">Laporan Otomatis</h3>
                <p class="text-gray-500 text-sm leading-relaxed">Pelaporan administrasi instan untuk kebutuhan Pusat dan Bidan Desa tanpa input manual.</p>
            </div>

            <div class="group p-8 border border-gray-100 bg-white rounded-2xl hover:-translate-y-2 hover:shadow-2xl hover:shadow-blue-500/10 hover:border-blue-100 transition-all duration-300 cursor-pointer">
                <div class="w-14 h-14 mb-6 bg-blue-50 text-blue-600 rounded-xl flex items-center justify-center group-hover:scale-110 group-hover:bg-blue-600 group-hover:text-white transition-all duration-300">
                    <svg fill="none" stroke="currentColor" stroke-width="1.5" class="w-7 h-7" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21 7.5l-9-5.25L3 7.5m18 0l-9 5.25m9-5.25v9l-9 5.25M3 7.5l9 5.25M3 7.5v9l9 5.25m0-9v9"></path></svg>
                </div>
                <h3 class="text-xl font-bold mb-3 group-hover:text-blue-600 transition-colors">Manajemen Stok</h3>
                <p class="text-gray-500 text-sm leading-relaxed">Pantau ketersediaan obat dan logistik medis secara real-time untuk operasional optimal.</p>
            </div>

            <div class="group p-8 border border-gray-100 bg-white rounded-2xl hover:-translate-y-2 hover:shadow-2xl hover:shadow-blue-500/10 hover:border-blue-100 transition-all duration-300 cursor-pointer">
                <div class="w-14 h-14 mb-6 bg-blue-50 text-blue-600 rounded-xl flex items-center justify-center group-hover:scale-110 group-hover:bg-blue-600 group-hover:text-white transition-all duration-300">
                     <svg fill="none" stroke="currentColor" stroke-width="1.5" class="w-7 h-7" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z"></path></svg>
                </div>
                <h3 class="text-xl font-bold mb-3 group-hover:text-blue-600 transition-colors">Dashboard Terintegrasi</h3>
                <p class="text-gray-500 text-sm leading-relaxed">Antarmuka intuitif untuk semua staf klinik dengan akses mudah ke semua modul sistem.</p>
            </div>

            <div class="group p-8 border border-gray-100 bg-white rounded-2xl hover:-translate-y-2 hover:shadow-2xl hover:shadow-blue-500/10 hover:border-blue-100 transition-all duration-300 cursor-pointer">
                <div class="w-14 h-14 mb-6 bg-blue-50 text-blue-600 rounded-xl flex items-center justify-center group-hover:scale-110 group-hover:bg-blue-600 group-hover:text-white transition-all duration-300">
                    <svg fill="none" stroke="currentColor" stroke-width="1.5" class="w-7 h-7" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
                <h3 class="text-xl font-bold mb-3 group-hover:text-blue-600 transition-colors">Keamanan Data</h3>
                <p class="text-gray-500 text-sm leading-relaxed">Perlindungan data pasien dengan enkripsi dan sistem keamanan tingkat tinggi.</p>
            </div>
        </div>
    </section>

    <section class="bg-[#0b005e] relative overflow-hidden py-24 px-8 md:px-16">
        <div class="absolute inset-0 opacity-10 bg-[radial-gradient(circle_at_center,_var(--tw-gradient-stops))] from-white via-transparent to-transparent"></div>

        <div class="relative z-10 grid grid-cols-1 md:grid-cols-3 gap-8 h-[450px]">
            <div class="group bg-blue-900/50 rounded-2xl w-full h-full overflow-hidden relative cursor-pointer border border-white/10 hover:border-blue-400/50 transition-colors">
                <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/20 to-transparent z-10"></div>
                <img src="https://images.unsplash.com/photo-1579684385127-1ef15d508118?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80" alt="Clinic" class="absolute inset-0 w-full h-full object-cover group-hover:scale-110 transition-transform duration-700">
                <div class="absolute bottom-0 left-0 p-6 z-20 translate-y-4 group-hover:translate-y-0 transition-transform duration-300">
                    <h4 class="text-white font-bold text-xl mb-2">Fasilitas Modern</h4>
                    <p class="text-gray-300 text-sm opacity-0 group-hover:opacity-100 transition-opacity duration-300 delay-100">Dilengkapi dengan peralatan medis terbaru untuk kenyamanan Anda.</p>
                </div>
            </div>

            <div class="group bg-blue-900/50 rounded-2xl w-full h-full overflow-hidden relative cursor-pointer border border-white/10 hover:border-blue-400/50 transition-colors">
                <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/20 to-transparent z-10"></div>
                <img src="https://images.unsplash.com/photo-1551076805-e1869043e560?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80" alt="Doctor" class="absolute inset-0 w-full h-full object-cover group-hover:scale-110 transition-transform duration-700">
                <div class="absolute bottom-0 left-0 p-6 z-20 translate-y-4 group-hover:translate-y-0 transition-transform duration-300">
                    <h4 class="text-white font-bold text-xl mb-2">Tenaga Medis Ahli</h4>
                    <p class="text-gray-300 text-sm opacity-0 group-hover:opacity-100 transition-opacity duration-300 delay-100">Ditangani langsung oleh dokter spesialis berpengalaman.</p>
                </div>
            </div>

            <div class="group bg-white rounded-2xl w-full h-full p-8 relative flex flex-col justify-center hover:-translate-y-2 hover:shadow-2xl transition-all duration-300 cursor-pointer">
                <div class="absolute top-6 left-6 px-4 py-1.5 bg-blue-600 text-white text-xs uppercase tracking-wider font-bold rounded-full shadow-md">
                    Pengumuman Baru
                </div>
                <div class="mt-8">
                    <h4 class="text-2xl font-bold text-gray-900 mb-4 group-hover:text-blue-600 transition-colors">Jadwal Vaksinasi Anak Bulan Ini</h4>
                    <p class="text-gray-600 mb-6">Pendaftaran vaksinasi campak dan rubella untuk bulan ini telah dibuka. Kuota terbatas, segera daftarkan buah hati Anda.</p>
                    <span class="text-blue-600 font-semibold flex items-center gap-2 group-hover:gap-3 transition-all">
                        Baca Selengkapnya
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
                    </span>
                </div>
            </div>
        </div>
    </section>

    <footer class="bg-[#050505] text-white py-16 px-8 md:px-16">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-12 mb-12">
            <div class="md:col-span-2">
                <div class="flex items-center gap-2 mb-6">
                    <div class="bg-blue-600 text-white p-1 rounded-md">
                        <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path></svg>
                    </div>
                    <h3 class="text-xl font-bold">DelimaCare</h3>
                </div>
                <p class="text-gray-400 text-sm max-w-sm leading-relaxed">
                    Platform manajemen klinik kesehatan ibu dan anak terintegrasi yang didedikasikan untuk memberikan pelayanan medis terbaik dan terpercaya.
                </p>
            </div>
            <div>
                <h3 class="text-lg font-semibold mb-6">Layanan</h3>
                <ul class="space-y-4 text-sm text-gray-400">
                    <li><a href="#" class="hover:text-blue-400 hover:underline underline-offset-4 transition-all">Pemeriksaan Kehamilan</a></li>
                    <li><a href="#" class="hover:text-blue-400 hover:underline underline-offset-4 transition-all">Keluarga Berencana</a></li>
                    <li><a href="#" class="hover:text-blue-400 hover:underline underline-offset-4 transition-all">Konsultasi Online</a></li>
                    <li><a href="#" class="hover:text-blue-400 hover:underline underline-offset-4 transition-all">Rekam Medis Digital</a></li>
                </ul>
            </div>
            <div>
                <h3 class="text-lg font-semibold mb-6">Hubungi Kami</h3>
                <ul class="space-y-4 text-sm text-gray-400">
                    <li class="flex items-center gap-3">
                        <svg class="w-4 h-4 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                        info@delimacare.id
                    </li>
                    <li class="flex items-center gap-3">
                        <svg class="w-4 h-4 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>
                        (021) 1234-5678
                    </li>
                    <li class="flex items-center gap-3">
                         <svg class="w-4 h-4 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                        Subang, Indonesia
                    </li>
                </ul>
            </div>
        </div>
        <div class="border-t border-gray-800 pt-8 flex flex-col md:flex-row justify-between items-center gap-4 text-sm text-gray-500">
            <p>© {{ date('Y') }} DelimaCare. Semua hak dilindungi.</p>
            <div class="flex gap-4">
                <a href="#" class="hover:text-white transition">Privasi</a>
                <a href="#" class="hover:text-white transition">Syarat & Ketentuan</a>
            </div>
        </div>
    </footer>

</body>
</html>
