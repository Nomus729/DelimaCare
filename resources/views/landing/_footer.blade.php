{{-- Footer — Landmark "contentinfo" implisit dari elemen <footer> di level dokumen.
     Screen Reader mengumumkan ini sebagai area "contentinfo" saat navigasi landmark.
     WCAG 1.3.1 & 2.4.1. --}}
<footer class="footer-section py-16 px-6 md:px-16">
    <div class="max-w-7xl mx-auto">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-12 mb-12">

            {{-- Kolom Brand --}}
            <div>
                {{-- Logo footer: link ke beranda --}}
                <a href="{{ route('home') }}" class="flex items-center gap-2 mb-4"
                   aria-label="DelimaCare — kembali ke beranda">
                    <div class="w-9 h-9 rounded-lg flex items-center justify-center" style="background: var(--gradient-main);">
                        {{-- Ikon dekoratif — makna ada di aria-label link --}}
                        <svg class="w-5 h-5 text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"
                             aria-hidden="true" focusable="false">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                        </svg>
                    </div>
                    <span class="text-xl font-extrabold text-white tracking-tight">DelimaCare</span>
                </a>
                <p class="text-sm text-gray-400 leading-relaxed mb-6 max-w-xs">
                    Platform manajemen klinik kesehatan ibu dan anak terintegrasi untuk pelayanan yang lebih baik.
                </p>

                {{-- Ikon sosial media: aria-label wajib karena hanya berisi ikon SVG --}}
                <div class="flex gap-3">
                    <a href="#" class="footer-social-icon" aria-label="Kunjungi halaman Facebook DelimaCare">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true" focusable="false">
                            <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                        </svg>
                    </a>
                    <a href="#" class="footer-social-icon" aria-label="Kunjungi halaman Instagram DelimaCare">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true" focusable="false">
                            <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zM12 0C8.741 0 8.333.014 7.053.072 2.695.272.273 2.69.073 7.052.014 8.333 0 8.741 0 12c0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98C8.333 23.986 8.741 24 12 24c3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98C15.668.014 15.259 0 12 0zm0 5.838a6.162 6.162 0 100 12.324 6.162 6.162 0 000-12.324zM12 16a4 4 0 110-8 4 4 0 010 8zm6.406-11.845a1.44 1.44 0 100 2.881 1.44 1.44 0 000-2.881z"/>
                        </svg>
                    </a>
                    <a href="#" class="footer-social-icon" aria-label="Hubungi DelimaCare via WhatsApp">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true" focusable="false">
                            <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
                        </svg>
                    </a>
                </div>
            </div>

            {{-- Kolom Layanan: nav dengan aria-label unik --}}
            <nav aria-label="Layanan klinik">
                <h3 class="text-white font-bold mb-5">Layanan</h3>
                <ul class="space-y-3 text-sm list-none p-0 m-0">
                    <li><a href="#">Pemeriksaan Kehamilan</a></li>
                    <li><a href="#">Keluarga Berencana</a></li>
                    <li><a href="#">Konsultasi Online</a></li>
                    <li><a href="#">Vaksinasi Anak</a></li>
                    <li><a href="#">Rekam Medis Digital</a></li>
                </ul>
            </nav>

            {{-- Kolom Navigasi: nav dengan aria-label unik agar beda dari nav utama --}}
            <nav aria-label="Navigasi halaman">
                <h3 class="text-white font-bold mb-5">Navigasi</h3>
                <ul class="space-y-3 text-sm list-none p-0 m-0">
                    <li><a href="#beranda">Beranda</a></li>
                    <li><a href="#fitur">Fitur</a></li>
                    <li><a href="#tentang">Tentang Kami</a></li>
                    <li><a href="#testimoni">Testimoni</a></li>
                    <li><a href="{{ route('login') }}">Portal Pasien</a></li>
                </ul>
            </nav>

            {{-- Kolom Kontak: menggunakan address + ul semantik --}}
            <div>
                <h3 class="text-white font-bold mb-5">Kontak</h3>
                {{-- <address> adalah elemen semantik HTML5 untuk informasi kontak.
                     Screen Reader mengumumkan konten ini sebagai "address". --}}
                <address class="not-italic">
                    <ul class="space-y-3 text-sm list-none p-0 m-0">
                        <li class="flex items-start gap-2">
                            {{-- Ikon email — dekoratif, teks "info@delimacare.id" sudah informatif --}}
                            <svg class="w-4 h-4 mt-0.5 flex-shrink-0" style="color:var(--primary-light);" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"
                                 aria-hidden="true" focusable="false">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                            {{-- mailto: membuat link email bisa langsung diklik/diaktifkan --}}
                            <a href="mailto:info@delimacare.id" class="text-gray-400 hover:text-teal-400 transition">
                                info@delimacare.id
                            </a>
                        </li>
                        <li class="flex items-start gap-2">
                            <svg class="w-4 h-4 mt-0.5 flex-shrink-0" style="color:var(--primary-light);" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"
                                 aria-hidden="true" focusable="false">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                            </svg>
                            {{-- tel: membuat nomor telepon bisa langsung diklik di mobile --}}
                            <a href="tel:+62211234567" class="text-gray-400 hover:text-teal-400 transition">
                                (021) 1234-5678
                            </a>
                        </li>
                        <li class="flex items-start gap-2">
                            <svg class="w-4 h-4 mt-0.5 flex-shrink-0" style="color:var(--primary-light);" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"
                                 aria-hidden="true" focusable="false">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                            <span class="text-gray-400">Jl. Kesehatan No. 123, Subang, Indonesia</span>
                        </li>
                    </ul>
                </address>
            </div>
        </div>

        {{-- Peta Google Maps --}}
        <div class="mb-12">
            <div class="flex items-center gap-2 mb-4">
                <div class="w-8 h-8 rounded-lg flex items-center justify-center" style="background: rgba(13,148,136,0.15);"
                     aria-hidden="true">
                    <svg class="w-4 h-4" style="color: var(--primary-light);" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"
                         aria-hidden="true" focusable="false">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                </div>
                <h3 class="text-white font-bold" id="map-heading">Lokasi Kami</h3>
            </div>
            <div class="relative rounded-2xl overflow-hidden border border-gray-700/50" style="height: 220px;">
                {{-- title="..." pada iframe sudah ada — WCAG 4.1.2 terpenuhi.
                     Ini adalah alternatif teks untuk pengguna AT yang tidak bisa
                     berinteraksi dengan peta interaktif. --}}
                <iframe
                    src="https://www.google.com/maps/embed?pb=!1m17!1m12!1m3!1d1980!2d107.82952331899749!3d-6.553052994773441!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m2!1m1!1s0x0%3A0x0!5e0!3m2!1sid!2sid!4v1714100000000!5m2!1sid!2sid&q=-6.553052994773441,107.82952331899749&z=16"
                    width="100%"
                    height="100%"
                    style="border:0; filter: brightness(0.85) contrast(1.1) saturate(0.3);"
                    allowfullscreen=""
                    loading="lazy"
                    referrerpolicy="no-referrer-when-downgrade"
                    title="Peta lokasi DelimaCare — Jl. Kesehatan No. 123, Subang, Jawa Barat">
                </iframe>
                {{-- Overlay label peta — dekoratif, informasinya sudah ada di kontak --}}
                <div class="absolute bottom-3 left-3 bg-gray-900/80 backdrop-blur-sm rounded-lg px-3 py-2 flex items-center gap-2 pointer-events-none"
                     aria-hidden="true">
                    <svg class="w-3.5 h-3.5 flex-shrink-0" style="color: var(--primary-light);" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z"/>
                    </svg>
                    <span class="text-xs text-gray-300 font-medium">Jl. Kesehatan No. 123, Subang</span>
                </div>
            </div>
        </div>

        {{-- Bottom bar --}}
        <div class="border-t border-gray-800 pt-8 flex flex-col md:flex-row justify-between items-center gap-4">
            <p class="text-sm text-gray-500">
                {{-- Simbol © diucapkan "copyright" oleh Screen Reader — tidak perlu alt --}}
                © {{ date('Y') }} DelimaCare. Semua hak dilindungi.
            </p>
            {{-- Nav legal dengan aria-label unik --}}
            <nav aria-label="Kebijakan dan ketentuan">
                <ul class="flex gap-6 text-sm list-none p-0 m-0">
                    <li><a href="#" class="text-gray-500 hover:text-teal-400 transition">Kebijakan Privasi</a></li>
                    <li><a href="#" class="text-gray-500 hover:text-teal-400 transition">Syarat & Ketentuan</a></li>
                </ul>
            </nav>
        </div>
    </div>
</footer>
