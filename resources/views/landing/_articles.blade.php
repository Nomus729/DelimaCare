{{-- Berita & Konten Terbaru --}}
<section id="konten" class="relative py-24 px-6 md:px-16 overflow-hidden" style="background: linear-gradient(180deg, #FAFFFE 0%, #F0FDFA 30%, #CCFBF1 60%, #F0FDFA 85%, #F0FDF4 100%);">

    {{-- Subtle decorative elements --}}
    <div class="absolute top-20 right-0 w-72 h-72 rounded-full opacity-20" style="background: radial-gradient(circle, rgba(13,148,136,0.15), transparent 70%); filter: blur(40px);"></div>
    <div class="absolute bottom-10 left-0 w-56 h-56 rounded-full opacity-15" style="background: radial-gradient(circle, rgba(6,182,212,0.15), transparent 70%); filter: blur(40px);"></div>

    <div class="max-w-7xl mx-auto relative z-10">
        {{-- Header --}}
        <div class="flex flex-col md:flex-row md:items-end md:justify-between mb-14 scroll-reveal">
            <div>
                <span class="section-badge mb-4 inline-flex">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/></svg>
                    Berita & Konten
                </span>
                <h2 class="text-3xl md:text-4xl font-extrabold text-gray-900 mt-4">Informasi Terkini</h2>
                <p class="text-gray-500 mt-3 max-w-lg">Berita, artikel kesehatan, dan acara terbaru dari DelimaCare untuk keluarga Indonesia.</p>
            </div>
            <a href="{{ route('articles.index') }}" class="mt-6 md:mt-0 inline-flex items-center gap-2 px-5 py-2.5 rounded-xl text-sm font-semibold border-2 border-teal-600/20 text-teal-700 hover:bg-teal-600 hover:text-white hover:border-teal-600 transition-all duration-300 hover:-translate-y-0.5">
                Lihat Semua Konten
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
            </a>
        </div>

        {{-- Featured Article (Large) + 2 Small Cards --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
            {{-- Featured Large Card --}}
            <a href="{{ route('articles.show', 'tips-menjaga-kesehatan-ibu-hamil') }}" class="group relative rounded-2xl overflow-hidden h-full min-h-[380px] flex scroll-reveal delay-100" style="box-shadow: 0 8px 32px rgba(13,148,136,0.1);">
                <img src="https://images.unsplash.com/photo-1559839734-2b71ea197ec2?w=800&q=80" alt="Kesehatan Ibu Hamil" class="absolute inset-0 w-full h-full object-cover group-hover:scale-105 transition-transform duration-700">
                <div class="absolute inset-0 bg-gradient-to-t from-gray-900/90 via-gray-900/40 to-transparent"></div>
                <div class="relative z-10 mt-auto p-8 w-full">
                    <span class="cat-badge cat-artikel mb-3 inline-flex">Artikel</span>
                    <h3 class="text-2xl md:text-3xl font-extrabold text-white mb-3 leading-tight group-hover:text-teal-200 transition-colors">Tips Menjaga Kesehatan Ibu Hamil di Trimester Pertama</h3>
                    <p class="text-gray-300 text-sm leading-relaxed mb-4 max-w-lg">Trimester pertama kehamilan adalah periode krusial dalam perkembangan janin. Berikut tips penting yang perlu diperhatikan...</p>
                    <div class="flex items-center gap-4 text-xs text-gray-400">
                        <span class="flex items-center gap-1">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                            Dr. Siti Nurhaliza
                        </span>
                        <span class="flex items-center gap-1">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            25 Apr 2026
                        </span>
                        <span class="flex items-center gap-1">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3"/><circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="2" fill="none"/></svg>
                            5 menit baca
                        </span>
                    </div>
                </div>
            </a>

            {{-- Right Column: 2 stacked cards --}}
            <div class="flex flex-col gap-6">
                {{-- Card 2 --}}
                <a href="{{ route('articles.show', 'jadwal-vaksinasi-anak-2026') }}" class="article-card flex-1 scroll-reveal delay-200" style="background: rgba(255,255,255,0.85); backdrop-filter: blur(8px);">
                    <div class="flex flex-col sm:flex-row gap-4 h-full">
                        <div class="sm:w-44 h-40 sm:h-auto rounded-xl overflow-hidden flex-shrink-0">
                            <img src="https://images.unsplash.com/photo-1631217868264-e5b90bb7e133?w=400&q=80" alt="Vaksinasi Anak" class="w-full h-full object-cover">
                        </div>
                        <div class="flex flex-col justify-center flex-1 py-1">
                            <span class="cat-badge cat-berita mb-2 inline-flex self-start">Berita</span>
                            <h3 class="text-lg font-bold text-gray-900 mb-2 leading-snug hover:text-teal-600 transition-colors">Jadwal Vaksinasi Anak Tahun 2026 Telah Dibuka</h3>
                            <p class="text-gray-500 text-sm leading-relaxed mb-3 line-clamp-2">Pendaftaran vaksinasi untuk anak usia 0-5 tahun bulan ini telah dibuka. Segera daftarkan buah hati Anda...</p>
                            <div class="flex items-center gap-3 text-xs text-gray-400">
                                <span class="flex items-center gap-1">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                    22 Apr 2026
                                </span>
                                <span>3 menit baca</span>
                            </div>
                        </div>
                    </div>
                </a>

                {{-- Card 3 --}}
                <a href="{{ route('articles.show', 'seminar-kesehatan-keluarga') }}" class="article-card flex-1 scroll-reveal delay-300" style="background: rgba(255,255,255,0.85); backdrop-filter: blur(8px);">
                    <div class="flex flex-col sm:flex-row gap-4 h-full">
                        <div class="sm:w-44 h-40 sm:h-auto rounded-xl overflow-hidden flex-shrink-0">
                            <img src="https://images.unsplash.com/photo-1576091160550-2173dba999ef?w=400&q=80" alt="Seminar Kesehatan" class="w-full h-full object-cover">
                        </div>
                        <div class="flex flex-col justify-center flex-1 py-1">
                            <span class="cat-badge cat-acara mb-2 inline-flex self-start">Acara</span>
                            <h3 class="text-lg font-bold text-gray-900 mb-2 leading-snug hover:text-teal-600 transition-colors">Seminar Kesehatan Keluarga: Nutrisi & Tumbuh Kembang Anak</h3>
                            <p class="text-gray-500 text-sm leading-relaxed mb-3 line-clamp-2">Ikuti seminar gratis bersama pakar nutrisi dan dokter spesialis anak pada tanggal 10 Mei 2026...</p>
                            <div class="flex items-center gap-3 text-xs text-gray-400">
                                <span class="flex items-center gap-1">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                    18 Apr 2026
                                </span>
                                <span>4 menit baca</span>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
        </div>
    </div>
</section>
