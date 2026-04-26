<section class="hero-section" id="beranda">
    {{-- Floating Particles --}}
    <div class="particles-container">
        <div class="particle particle-1"></div>
        <div class="particle particle-2"></div>
        <div class="particle particle-3"></div>
        <div class="particle particle-4"></div>
        <div class="particle particle-5"></div>
        <div class="particle particle-6"></div>
        <div class="particle particle-7"></div>
        <div class="particle particle-8"></div>
    </div>

    {{-- Decorative Rings --}}
    <div class="hero-ring hero-ring-1"></div>
    <div class="hero-ring hero-ring-2"></div>
    <div class="hero-ring hero-ring-3"></div>

    {{-- Content --}}
    <div class="hero-content relative z-10 w-full px-6 md:px-16 lg:px-24 py-20 max-w-7xl mx-auto">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
            {{-- Left --}}
            <div>
                <div class="section-badge mb-6 animate-fade-in-up">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/></svg>
                    Klinik Kesehatan Ibu & Anak
                </div>
                <h1 class="text-4xl md:text-5xl lg:text-6xl font-extrabold text-white leading-tight mb-6 animate-fade-in-up delay-100" style="text-shadow: 0 2px 30px rgba(0,0,0,0.15);">
                    Layanan<br>Kesehatan<br>
                    <span style="background: linear-gradient(90deg, #A7F3D0, #67E8F9); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">Terpercaya</span>
                </h1>
                <p class="text-lg text-teal-100 mb-10 max-w-lg leading-relaxed animate-fade-in-up delay-200" style="opacity:0;">
                    Platform digital terintegrasi untuk manajemen klinik kesehatan ibu hamil dan keluarga berencana. Akses mudah, data aman, layanan profesional.
                </p>
                <div class="flex flex-wrap gap-4 animate-fade-in-up delay-300" style="opacity:0;">
                    <a href="{{ route('login') }}" class="btn-primary">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        Buat Reservasi
                    </a>
                    <a href="#fitur" class="btn-outline">
                        Pelajari Lebih Lanjut
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
                    </a>
                </div>
            </div>

            {{-- Right: Decorative Card Stack --}}
            <div class="hidden lg:flex justify-center animate-fade-in-up delay-400" style="opacity:0;">
                <div class="relative w-80">
                    {{-- Card 1 --}}
                    <div class="bg-white/15 backdrop-blur-md rounded-2xl p-6 border border-white/20 shadow-xl">
                        <div class="flex items-center gap-3 mb-4">
                            <div class="w-12 h-12 rounded-xl bg-white/20 flex items-center justify-center">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
                            </div>
                            <div>
                                <p class="text-white font-bold text-sm">Pemeriksaan Rutin</p>
                                <p class="text-teal-200 text-xs">Jadwal Hari Ini</p>
                            </div>
                        </div>
                        <div class="space-y-2">
                            <div class="bg-white/10 rounded-lg p-3 flex justify-between items-center">
                                <span class="text-white text-sm">dr. Siti Aminah</span>
                                <span class="text-xs text-teal-200 bg-white/10 px-2 py-1 rounded-full">09:00</span>
                            </div>
                            <div class="bg-white/10 rounded-lg p-3 flex justify-between items-center">
                                <span class="text-white text-sm">dr. Rina Kartika</span>
                                <span class="text-xs text-teal-200 bg-white/10 px-2 py-1 rounded-full">10:30</span>
                            </div>
                        </div>
                    </div>

                    {{-- Floating mini card --}}
                    <div class="absolute -bottom-6 -left-8 bg-white rounded-xl p-4 shadow-2xl border border-gray-100 animate-fade-in-up delay-500" style="opacity:0;">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-full bg-emerald-100 flex items-center justify-center">
                                <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                            </div>
                            <div>
                                <p class="text-gray-900 font-bold text-sm">Reservasi Berhasil!</p>
                                <p class="text-gray-500 text-xs">Senin, 28 April 2026</p>
                            </div>
                        </div>
                    </div>

                    {{-- Floating stat --}}
                    <div class="absolute -top-4 -right-6 bg-white rounded-xl p-3 shadow-2xl border border-gray-100 animate-fade-in-up delay-600" style="opacity:0;">
                        <div class="flex items-center gap-2">
                            <div class="w-8 h-8 rounded-lg bg-amber-100 flex items-center justify-center">
                                <svg class="w-4 h-4 text-amber-600" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
                            </div>
                            <div>
                                <p class="text-gray-900 font-extrabold text-lg leading-none">4.9</p>
                                <p class="text-gray-500 text-xs">Rating</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Wave Divider --}}
    <div class="wave-divider">
        <svg viewBox="0 0 1440 80" preserveAspectRatio="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M0,40 C360,80 720,0 1080,40 C1260,60 1380,50 1440,40 L1440,80 L0,80 Z" fill="#FAFFFE"/>
        </svg>
    </div>
</section>
