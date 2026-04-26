<div class="grid grid-cols-1 lg:grid-cols-12 gap-6 md:gap-8">
    {{-- Left Column: Doctor Availability --}}
    <div class="lg:col-span-5 bg-white border border-gray-100 rounded-3xl p-6 md:p-8 shadow-sm dark:bg-[#1E293B] dark:border-gray-800">
        <div class="flex items-center gap-3 mb-6">
            <div class="w-10 h-10 rounded-xl flex items-center justify-center bg-teal-50 text-teal-600 dark:bg-teal-900/30 dark:text-teal-400">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
            </div>
            <div>
                <h3 class="font-bold text-gray-900 text-lg dark:text-white">Jadwal Hari Ini</h3>
                <p class="text-sm text-gray-500 dark:text-gray-400">Dokter yang tersedia sekarang</p>
            </div>
        </div>

        <div class="space-y-4">
            {{-- Doctor Card 1 --}}
            <div class="flex items-center justify-between p-4 border border-gray-100 rounded-2xl hover:border-teal-200 hover:bg-teal-50/50 transition-all cursor-pointer group dark:border-gray-700 dark:hover:border-teal-800 dark:hover:bg-teal-900/20">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 bg-teal-100 text-teal-600 rounded-full flex items-center justify-center font-bold dark:bg-teal-900/50 dark:text-teal-400">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                    </div>
                    <div>
                        <h4 class="font-bold text-gray-900 group-hover:text-teal-700 transition-colors dark:text-white dark:group-hover:text-teal-400">Dr. Siti Nurhaliza, Sp.OG</h4>
                        <p class="text-xs text-gray-500 dark:text-gray-400">Kebidanan & Kandungan</p>
                    </div>
                </div>
                <span class="px-3 py-1 bg-teal-100 text-teal-700 text-xs font-bold rounded-full dark:bg-teal-900/50 dark:text-teal-400">Tersedia</span>
            </div>

            {{-- Doctor Card 2 --}}
            <div class="flex items-center justify-between p-4 border border-gray-100 rounded-2xl hover:border-teal-200 hover:bg-teal-50/50 transition-all cursor-pointer group dark:border-gray-700 dark:hover:border-teal-800 dark:hover:bg-teal-900/20">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 bg-teal-100 text-teal-600 rounded-full flex items-center justify-center font-bold dark:bg-teal-900/50 dark:text-teal-400">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                    </div>
                    <div>
                        <h4 class="font-bold text-gray-900 group-hover:text-teal-700 transition-colors dark:text-white dark:group-hover:text-teal-400">Dr. Dewi Lestari, Sp.OG</h4>
                        <p class="text-xs text-gray-500 dark:text-gray-400">Kebidanan & Kandungan</p>
                    </div>
                </div>
                <span class="px-3 py-1 bg-teal-100 text-teal-700 text-xs font-bold rounded-full dark:bg-teal-900/50 dark:text-teal-400">Tersedia</span>
            </div>

            {{-- Doctor Card 3 (Unavailable) --}}
            <div class="flex items-center justify-between p-4 border border-gray-100 rounded-2xl opacity-60 bg-gray-50 cursor-not-allowed dark:bg-gray-800 dark:border-gray-700">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 bg-gray-200 text-gray-500 rounded-full flex items-center justify-center font-bold dark:bg-gray-700 dark:text-gray-400">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                    </div>
                    <div>
                        <h4 class="font-bold text-gray-900 dark:text-gray-300">Bidan Ani Wijaya</h4>
                        <p class="text-xs text-gray-500 dark:text-gray-500">Bidan</p>
                    </div>
                </div>
                <span class="px-3 py-1 bg-gray-200 text-gray-600 text-xs font-bold rounded-full dark:bg-gray-700 dark:text-gray-400">Istirahat</span>
            </div>
        </div>
    </div>

    {{-- Right Column: Reservation Form --}}
    <div class="lg:col-span-7 bg-white border border-gray-100 rounded-3xl p-6 md:p-8 shadow-sm dark:bg-[#1E293B] dark:border-gray-800">
        <h3 class="font-bold text-gray-900 mb-2 text-xl dark:text-white">Form Reservasi</h3>
        <p class="text-sm text-gray-500 mb-8 dark:text-gray-400">Isi formulir dengan lengkap untuk membuat janji temu baru. Data Anda dienkripsi.</p>

        <form action="#" method="POST" class="space-y-5">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2 dark:text-gray-300">Nama Lengkap Pasien</label>
                    <input type="text" name="nama" placeholder="Masukkan nama lengkap" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-teal-500/20 focus:border-teal-500 outline-none transition-all dark:bg-[#0F172A] dark:border-gray-700 dark:text-white">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2 dark:text-gray-300">Nomor Telepon / WA</label>
                    <input type="tel" name="phone" placeholder="08xx-xxxx-xxxx" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-teal-500/20 focus:border-teal-500 outline-none transition-all dark:bg-[#0F172A] dark:border-gray-700 dark:text-white">
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2 dark:text-gray-300">Jenis Layanan</label>
                    <div class="relative">
                        <select name="layanan" class="w-full px-4 py-3 appearance-none rounded-xl border border-gray-200 focus:ring-2 focus:ring-teal-500/20 focus:border-teal-500 outline-none bg-white transition-all dark:bg-[#0F172A] dark:border-gray-700 dark:text-white">
                            <option disabled selected>Pilih layanan</option>
                            <option>Pemeriksaan Kehamilan</option>
                            <option>Konsultasi KB</option>
                            <option>Imunisasi Anak</option>
                            <option>Lainnya</option>
                        </select>
                        <div class="absolute inset-y-0 right-0 flex items-center px-4 pointer-events-none text-gray-500">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
                        </div>
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2 dark:text-gray-300">Pilih Dokter</label>
                    <div class="relative">
                        <select name="dokter_id" class="w-full px-4 py-3 appearance-none rounded-xl border border-gray-200 focus:ring-2 focus:ring-teal-500/20 focus:border-teal-500 outline-none bg-white transition-all dark:bg-[#0F172A] dark:border-gray-700 dark:text-white">
                            <option disabled selected>Pilih dokter</option>
                            <option>Dr. Siti Nurhaliza, Sp.OG</option>
                            <option>Dr. Dewi Lestari, Sp.OG</option>
                        </select>
                        <div class="absolute inset-y-0 right-0 flex items-center px-4 pointer-events-none text-gray-500">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
                        </div>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2 dark:text-gray-300">Tanggal Kunjungan</label>
                    <input type="date" name="tanggal" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-teal-500/20 focus:border-teal-500 outline-none transition-all text-gray-700 dark:bg-[#0F172A] dark:border-gray-700 dark:text-white dark:color-scheme-dark">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2 dark:text-gray-300">Waktu</label>
                    <div class="relative">
                        <select name="waktu" class="w-full px-4 py-3 appearance-none rounded-xl border border-gray-200 focus:ring-2 focus:ring-teal-500/20 focus:border-teal-500 outline-none bg-white transition-all dark:bg-[#0F172A] dark:border-gray-700 dark:text-white">
                            <option disabled selected>Pilih waktu</option>
                            <option>09:00 WIB</option>
                            <option>10:00 WIB</option>
                            <option>13:00 WIB</option>
                            <option>14:00 WIB</option>
                        </select>
                        <div class="absolute inset-y-0 right-0 flex items-center px-4 pointer-events-none text-gray-500">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
                        </div>
                    </div>
                </div>
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2 dark:text-gray-300">Keluhan / Catatan (Opsional)</label>
                <textarea name="keluhan" rows="3" placeholder="Jelaskan keluhan atau catatan khusus untuk dokter" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-teal-500/20 focus:border-teal-500 outline-none transition-all resize-none dark:bg-[#0F172A] dark:border-gray-700 dark:text-white"></textarea>
            </div>

            <button type="submit" class="w-full py-4 rounded-xl text-white font-bold text-sm transition-all duration-300 hover:-translate-y-0.5 mt-2" style="background: var(--gradient-main); box-shadow: 0 4px 16px rgba(13,148,136,0.25);">
                Kirim Permintaan Reservasi
            </button>
            <p class="text-xs text-center text-gray-400 mt-3 flex items-center justify-center gap-1">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                Data medis Anda dilindungi oleh enkripsi end-to-end.
            </p>
        </form>
    </div>
</div>
