{{-- Tentang Kami --}}
<section id="tentang" class="py-24 px-6 md:px-16" style="background: linear-gradient(180deg, #FAFFFE 0%, #F0FDF4 100%);" aria-labelledby="about-heading">
    <div class="max-w-7xl mx-auto grid grid-cols-1 lg:grid-cols-2 gap-16 items-center">
        {{-- Left: Text --}}
        <div class="scroll-reveal-left">
            {{-- Badge dekoratif — informasinya duplikat dari heading --}}
            <span class="section-badge mb-4 inline-flex" aria-hidden="true">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true" focusable="false"><path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                Tentang Kami
            </span>
            {{-- h2 dengan id untuk aria-labelledby section. Hierarki: h1(hero) → h2(tentang) --}}
            <h2 id="about-heading" class="text-3xl md:text-4xl font-extrabold text-gray-900 mt-4 mb-6">
                Dedikasi Penuh untuk<br>Kesehatan Keluarga
            </h2>
            <p class="text-gray-600 leading-relaxed mb-6">DelimaCare hadir sebagai solusi digital terpadu untuk klinik kesehatan ibu dan anak. Kami menggabungkan teknologi modern dengan pelayanan kesehatan yang hangat dan profesional.</p>
            <p class="text-gray-600 leading-relaxed mb-8">Dengan sistem manajemen terintegrasi, kami membantu tenaga kesehatan memberikan pelayanan terbaik — mulai dari pemeriksaan kehamilan, keluarga berencana, hingga vaksinasi anak.</p>
            <div class="grid grid-cols-2 gap-4">
                <div class="flex items-center gap-3">
                    {{-- Ikon centang — dekoratif, makna ada di teks span berikutnya --}}
                    <div class="w-10 h-10 rounded-lg flex items-center justify-center flex-shrink-0" style="background: rgba(13,148,136,0.1);" aria-hidden="true">
                        <svg class="w-5 h-5" style="color:#0D9488;" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                    </div>
                    <span class="text-sm font-medium text-gray-700">Bersertifikat</span>
                </div>
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-lg flex items-center justify-center flex-shrink-0" style="background: rgba(6,182,212,0.1);" aria-hidden="true">
                        <svg class="w-5 h-5" style="color:#06B6D4;" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <span class="text-sm font-medium text-gray-700">24/7 Support</span>
                </div>
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-lg flex items-center justify-center flex-shrink-0" style="background: rgba(16,185,129,0.1);" aria-hidden="true">
                        <svg class="w-5 h-5" style="color:#10B981;" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    </div>
                    <span class="text-sm font-medium text-gray-700">Tim Profesional</span>
                </div>
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-lg flex items-center justify-center flex-shrink-0" style="background: rgba(139,92,246,0.1);" aria-hidden="true">
                        <svg class="w-5 h-5" style="color:#8B5CF6;" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                    </div>
                    <span class="text-sm font-medium text-gray-700">Data Terenkripsi</span>
                </div>
            </div>
        </div>

        {{-- Right: Decorative --}}
        {{-- Kartu dekoratif: data pasien dummy — murni visual, tidak boleh dibaca AT --}}
        <div class="scroll-reveal-right hidden lg:block" aria-hidden="true">
            <div class="relative">
                <div class="rounded-3xl p-8" style="background: var(--gradient-main); box-shadow: 0 20px 60px rgba(13,148,136,0.25);">
                    <div class="bg-white/15 backdrop-blur rounded-2xl p-6 mb-4">
                        <div class="flex items-center gap-3 mb-3">
                            <div class="w-10 h-10 rounded-full bg-white/20 flex items-center justify-center">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                            </div>
                            <div>
                                <p class="text-white font-bold text-sm">Ibu Sari Dewi</p>
                                <p class="text-teal-200 text-xs">Pasien - Kehamilan Trimester 2</p>
                            </div>
                        </div>
                        <div class="grid grid-cols-3 gap-2 text-center">
                            <div class="bg-white/10 rounded-lg p-2">
                                <p class="text-white font-bold text-sm">28</p>
                                <p class="text-teal-200 text-xs">Minggu</p>
                            </div>
                            <div class="bg-white/10 rounded-lg p-2">
                                <p class="text-white font-bold text-sm">Normal</p>
                                <p class="text-teal-200 text-xs">Status</p>
                            </div>
                            <div class="bg-white/10 rounded-lg p-2">
                                <p class="text-white font-bold text-sm">3</p>
                                <p class="text-teal-200 text-xs">Kunjungan</p>
                            </div>
                        </div>
                    </div>
                    <div class="bg-white/15 backdrop-blur rounded-2xl p-4">
                        <p class="text-teal-100 text-xs mb-2">Jadwal Berikutnya</p>
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-2">
                                <div class="w-8 h-8 rounded-lg bg-white/20 flex items-center justify-center">
                                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                </div>
                                <span class="text-white text-sm font-medium">Senin, 5 Mei 2026</span>
                            </div>
                            <span class="text-xs bg-emerald-400/30 text-emerald-100 px-2 py-1 rounded-full">Terkonfirmasi</span>
                        </div>
                    </div>
                </div>
                {{-- Accent dot --}}
                <div class="absolute -top-4 -left-4 w-16 h-16 rounded-2xl rotate-12" style="background: rgba(13,148,136,0.1);"></div>
                <div class="absolute -bottom-4 -right-4 w-12 h-12 rounded-xl -rotate-12" style="background: rgba(6,182,212,0.1);"></div>
            </div>
        </div>
    </div>
</section>
