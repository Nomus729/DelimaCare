{{-- Testimoni
     WCAG 1.3.1: Menggunakan <blockquote> untuk kutipan testimoni.
     WCAG 2.4.6: Section dilabeli dengan aria-labelledby.
     WCAG 1.1.1: Rating bintang diberi teks tersembunyi yang terbaca AT. --}}
<section id="testimoni" class="py-24 px-6 md:px-16" style="background: linear-gradient(180deg, #F0FDF4 0%, #FAFFFE 100%);"
         aria-labelledby="testimonials-heading">
    <div class="max-w-7xl mx-auto">
        <div class="text-center mb-16 scroll-reveal">
            <span class="section-badge mb-4 inline-flex" aria-hidden="true">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"
                     aria-hidden="true" focusable="false">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                </svg>
                Testimoni
            </span>
            <h2 id="testimonials-heading" class="text-3xl md:text-4xl font-extrabold text-gray-900 mt-4">
                Apa Kata Mereka?
            </h2>
            <p class="text-gray-500 mt-4 max-w-lg mx-auto">
                Pengalaman nyata dari pasien yang telah merasakan layanan kami.
            </p>
        </div>

        {{-- Daftar testimoni: <ul>/<li> untuk semantik list --}}
        <ul class="grid grid-cols-1 md:grid-cols-3 gap-6 list-none p-0 m-0"
            aria-label="Testimoni pasien DelimaCare">

            {{-- Testimoni 1 --}}
            <li>
                <article class="testimonial-card scroll-reveal delay-100">
                    {{-- Rating bintang: aria-label pada container agar AT bisa
                         mengumumkan "5 dari 5 bintang" tanpa membaca 5 SVG terpisah.
                         WCAG 1.1.1 (Non-text Content). --}}
                    <div class="flex gap-1 mb-4"
                         role="img"
                         aria-label="Rating: 5 dari 5 bintang">
                        @for ($i = 0; $i < 5; $i++)
                            {{-- aria-hidden karena makna sudah di aria-label container --}}
                            <svg class="w-4 h-4 star-filled" fill="currentColor" viewBox="0 0 24 24"
                                 aria-hidden="true" focusable="false">
                                <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                            </svg>
                        @endfor
                    </div>

                    {{-- <blockquote> adalah elemen semantik yang tepat untuk kutipan.
                         WCAG 1.3.1: Screen Reader mengumumkan ini sebagai "blockquote". --}}
                    <blockquote>
                        <p class="text-gray-600 text-sm leading-relaxed mb-6">
                            "Pelayanan di DelimaCare sangat ramah dan profesional. Saya merasa tenang melakukan pemeriksaan kehamilan di sini. Sistem reservasi online-nya juga sangat memudahkan."
                        </p>
                        <footer class="flex items-center gap-3 pt-4 border-t border-gray-100">
                            {{-- Avatar inisial — dekoratif, nama sudah ada di cite --}}
                            <div class="testimonial-avatar" aria-hidden="true">SA</div>
                            <div>
                                {{-- <cite> adalah elemen semantik untuk nama pemberi testimoni --}}
                                <cite class="not-italic font-bold text-gray-900 text-sm block">Sari Anggraeni</cite>
                                <p class="text-gray-500 text-xs">Ibu Hamil — Trimester 3</p>
                            </div>
                        </footer>
                    </blockquote>
                </article>
            </li>

            {{-- Testimoni 2 --}}
            <li>
                <article class="testimonial-card scroll-reveal delay-200">
                    <div class="flex gap-1 mb-4"
                         role="img"
                         aria-label="Rating: 5 dari 5 bintang">
                        @for ($i = 0; $i < 5; $i++)
                            <svg class="w-4 h-4 star-filled" fill="currentColor" viewBox="0 0 24 24"
                                 aria-hidden="true" focusable="false">
                                <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                            </svg>
                        @endfor
                    </div>
                    <blockquote>
                        <p class="text-gray-600 text-sm leading-relaxed mb-6">
                            "Dokter-dokternya sangat kompeten dan sabar menjelaskan. Anak saya selalu ditangani dengan baik. Rekam medis digital memudahkan saya melacak riwayat vaksinasi anak."
                        </p>
                        <footer class="flex items-center gap-3 pt-4 border-t border-gray-100">
                            <div class="testimonial-avatar" aria-hidden="true">NR</div>
                            <div>
                                <cite class="not-italic font-bold text-gray-900 text-sm block">Nia Rahmawati</cite>
                                <p class="text-gray-500 text-xs">Ibu — 2 Anak</p>
                            </div>
                        </footer>
                    </blockquote>
                </article>
            </li>

            {{-- Testimoni 3 —  rating 4/5 --}}
            <li>
                <article class="testimonial-card scroll-reveal delay-300">
                    <div class="flex gap-1 mb-4"
                         role="img"
                         aria-label="Rating: 4 dari 5 bintang">
                        @for ($i = 0; $i < 4; $i++)
                            <svg class="w-4 h-4 star-filled" fill="currentColor" viewBox="0 0 24 24"
                                 aria-hidden="true" focusable="false">
                                <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                            </svg>
                        @endfor
                        <svg class="w-4 h-4 star-empty" fill="currentColor" viewBox="0 0 24 24"
                             aria-hidden="true" focusable="false">
                            <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                        </svg>
                    </div>
                    <blockquote>
                        <p class="text-gray-600 text-sm leading-relaxed mb-6">
                            "Konsultasi KB di sini sangat informatif. Bidan dan dokter memberikan penjelasan lengkap tentang pilihan kontrasepsi. Sistemnya modern dan mudah digunakan."
                        </p>
                        <footer class="flex items-center gap-3 pt-4 border-t border-gray-100">
                            <div class="testimonial-avatar" aria-hidden="true">DW</div>
                            <div>
                                <cite class="not-italic font-bold text-gray-900 text-sm block">Dian Wulandari</cite>
                                <p class="text-gray-500 text-xs">Pasien KB</p>
                            </div>
                        </footer>
                    </blockquote>
                </article>
            </li>

        </ul>
    </div>
</section>
