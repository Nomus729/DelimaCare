{{-- ====================================================
     CTA Section — dipisah dari footer agar struktur
     landmark <footer> murni berisi informasi kontak/nav,
     bukan konten promosi. Memenuhi WCAG 1.3.1.
     ==================================================== --}}
<section
    class="cta-section py-24 px-6 md:px-16 relative"
    aria-labelledby="cta-heading">

    {{-- Partikel dekoratif — aria-hidden agar tidak dibaca Screen Reader --}}
    <div class="particles-container" aria-hidden="true">
        <div class="particle particle-1"></div>
        <div class="particle particle-3"></div>
        <div class="particle particle-5"></div>
        <div class="particle particle-7"></div>
    </div>

    <div class="max-w-3xl mx-auto text-center relative z-10 scroll-reveal">
        {{-- Badge dekoratif — aria-hidden karena informasinya duplikat dari heading --}}
        <div class="inline-flex items-center gap-2 bg-white/10 backdrop-blur-sm px-4 py-2 rounded-full mb-6 border border-white/15"
             aria-hidden="true">
            <svg class="w-4 h-4 text-teal-200" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true" focusable="false">
                <path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/>
            </svg>
            <span class="text-teal-100 text-sm font-medium">Mulai Sekarang</span>
        </div>

        {{-- id="cta-heading" sebagai target aria-labelledby section ini --}}
        <h2 id="cta-heading" class="text-3xl md:text-5xl font-extrabold text-white mb-6 leading-tight">
            Siap Memberikan yang<br>Terbaik untuk Keluarga?
        </h2>
        <p class="text-teal-100 text-lg mb-10 max-w-xl mx-auto leading-relaxed">
            Bergabunglah dengan ribuan keluarga yang telah mempercayakan kesehatan mereka kepada DelimaCare.
        </p>

        <div class="flex flex-wrap justify-center gap-4">
            <a href="{{ route('login') }}" class="btn-primary">
                {{-- Ikon kalender — dekoratif, makna sudah ada di teks link --}}
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"
                     aria-hidden="true" focusable="false">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
                Buat Reservasi Sekarang
            </a>
            <a href="{{ route('register') }}" class="btn-outline">
                Daftar Gratis
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"
                     aria-hidden="true" focusable="false">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                </svg>
            </a>
        </div>
    </div>
</section>
