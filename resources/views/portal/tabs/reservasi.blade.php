<div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
    <div class="lg:col-span-5 bg-white border border-gray-200 rounded-2xl p-6 shadow-sm">
        <h3 class="font-bold text-gray-900 mb-1">Jadwal Dokter Hari Ini</h3>
        <p class="text-sm text-gray-500 mb-6">Dokter yang tersedia untuk konsultasi</p>

        <div class="space-y-4">
            <div class="flex items-center justify-between p-4 border border-gray-200 rounded-xl hover:border-blue-300 hover:bg-blue-50/30 transition-colors cursor-pointer group">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center font-bold">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                    </div>
                    <div>
                        <h4 class="font-bold text-gray-900 group-hover:text-blue-700 transition-colors">Dr. Siti Nurhaliza, Sp.OG</h4>
                        <p class="text-xs text-gray-500">Kebidanan & Kandungan</p>
                    </div>
                </div>
                <span class="px-3 py-1 bg-green-100 text-green-700 text-xs font-bold rounded-full">Tersedia</span>
            </div>

            <div class="flex items-center justify-between p-4 border border-gray-200 rounded-xl hover:border-blue-300 hover:bg-blue-50/30 transition-colors cursor-pointer group">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center font-bold">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                    </div>
                    <div>
                        <h4 class="font-bold text-gray-900 group-hover:text-blue-700 transition-colors">Dr. Dewi Lestari, Sp.OG</h4>
                        <p class="text-xs text-gray-500">Kebidanan & Kandungan</p>
                    </div>
                </div>
                <span class="px-3 py-1 bg-green-100 text-green-700 text-xs font-bold rounded-full">Tersedia</span>
            </div>

            <div class="flex items-center justify-between p-4 border border-gray-200 rounded-xl opacity-60 bg-gray-50 cursor-not-allowed">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 bg-gray-200 text-gray-500 rounded-full flex items-center justify-center font-bold">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                    </div>
                    <div>
                        <h4 class="font-bold text-gray-900">Bidan Ani Wijaya</h4>
                        <p class="text-xs text-gray-500">Bidan</p>
                    </div>
                </div>
                <span class="px-3 py-1 bg-gray-200 text-gray-600 text-xs font-bold rounded-full">Tidak Tersedia</span>
            </div>
        </div>
    </div>

    <div class="lg:col-span-7 bg-white border border-gray-200 rounded-2xl p-6 md:p-8 shadow-sm">
        <h3 class="font-bold text-gray-900 mb-1">Form Reservasi</h3>
        <p class="text-sm text-gray-500 mb-8">Isi formulir untuk membuat janji temu</p>

        <form class="space-y-5">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">Nama Lengkap</label>
                    <input type="text" placeholder="Masukkan nama lengkap" class="w-full px-4 py-2.5 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">Nomor Telepon</label>
                    <input type="tel" placeholder="08xx-xxxx-xxxx" class="w-full px-4 py-2.5 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all">
                </div>
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1.5">Jenis Layanan</label>
                <select class="w-full px-4 py-2.5 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none bg-white transition-all">
                    <option disabled selected>Pilih jenis layanan</option>
                    <option>Pemeriksaan Kehamilan</option>
                    <option>Konsultasi KB</option>
                    <option>Pemeriksaan Anak</option>
                </select>
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1.5">Pilih Dokter</label>
                <select class="w-full px-4 py-2.5 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none bg-white transition-all">
                    <option disabled selected>Pilih dokter</option>
                    <option>Dr. Siti Nurhaliza, Sp.OG</option>
                    <option>Dr. Dewi Lestari, Sp.OG</option>
                </select>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">Tanggal Kunjungan</label>
                    <input type="date" class="w-full px-4 py-2.5 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all text-gray-600">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">Waktu</label>
                    <select class="w-full px-4 py-2.5 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none bg-white transition-all">
                        <option disabled selected>Pilih waktu</option>
                        <option>09:00 WIB</option>
                        <option>10:00 WIB</option>
                        <option>14:00 WIB</option>
                    </select>
                </div>
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1.5">Keluhan (Opsional)</label>
                <textarea rows="3" placeholder="Jelaskan keluhan atau catatan khusus" class="w-full px-4 py-2.5 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all resize-none"></textarea>
            </div>

            <button type="button" class="w-full py-3.5 bg-black text-white font-bold rounded-xl hover:bg-blue-700 hover:shadow-lg transform active:scale-[0.98] transition-all duration-200 mt-4">
                Buat Reservasi
            </button>
        </form>
    </div>
</div>
