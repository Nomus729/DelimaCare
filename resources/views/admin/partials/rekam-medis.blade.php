<div>
    <div class="flex justify-between items-center mb-6">
        <div>
            <h2 class="text-3xl font-extrabold text-gray-900 mb-1">Rekam Medis</h2>
            <p class="text-gray-500">Manajemen data kesehatan pasien</p>
        </div>
        <button class="bg-black text-white px-5 py-2.5 rounded-lg text-sm font-semibold flex items-center gap-2 hover:bg-gray-800 transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg> Tambah Rekam Medis
        </button>
    </div>

    <div class="mb-6 space-y-4">
        <div class="relative w-full">
            <svg class="w-5 h-5 text-gray-400 absolute left-4 top-1/2 -translate-y-1/2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
            <input type="text" placeholder="Cari pasien berdasarkan nama..." class="w-full pl-12 pr-4 py-3 bg-white border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 outline-none text-sm">
        </div>
        <div class="flex bg-gray-100/80 p-1.5 rounded-xl border border-gray-200 text-sm font-medium text-gray-600">
            <button class="flex-1 py-2.5 bg-white text-gray-900 shadow-sm rounded-lg flex items-center justify-center gap-2">
                <svg class="w-4 h-4 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg> Kehamilan
            </button>
            <button class="flex-1 py-2.5 hover:text-gray-900 rounded-lg flex items-center justify-center gap-2 transition-colors">
                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path></svg> Keluarga Berencana
            </button>
        </div>
    </div>

    <div class="space-y-4">
        <div class="bg-white border border-gray-200 rounded-2xl p-6 shadow-sm hover:shadow-md transition-shadow">
            <div class="flex justify-between items-start mb-6">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 bg-pink-100 text-pink-600 rounded-full flex items-center justify-center">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    <div>
                        <h4 class="font-bold text-gray-900 text-lg">Siti Aminah</h4>
                        <p class="text-xs text-gray-500">Usia: 28 tahun</p>
                    </div>
                </div>
                <span class="px-3 py-1 bg-green-100 text-green-700 text-xs font-bold rounded">Risiko Rendah</span>
            </div>

            <div class="grid grid-cols-3 gap-4 mb-4">
                <div class="bg-gray-50 p-4 rounded-xl border border-gray-100">
                    <p class="text-xs text-gray-500 mb-1">Usia Kehamilan</p>
                    <p class="font-bold text-gray-900 text-lg">24 Minggu</p>
                </div>
                <div class="bg-gray-50 p-4 rounded-xl border border-gray-100">
                    <p class="text-xs text-gray-500 mb-1">Kunjungan Terakhir</p>
                    <p class="font-bold text-gray-900 text-lg">28 Feb</p>
                </div>
                <div class="bg-gray-50 p-4 rounded-xl border border-gray-100">
                    <p class="text-xs text-gray-500 mb-1">Kontrol Berikutnya</p>
                    <p class="font-bold text-gray-900 text-lg">14 Mar</p>
                </div>
            </div>

            <div class="bg-blue-50/50 border border-blue-100 rounded-xl p-4 mb-6">
                <p class="text-xs font-semibold text-blue-800 mb-1">Catatan Medis:</p>
                <p class="text-sm text-blue-900">Kondisi ibu dan janin sehat, perkembangan normal</p>
            </div>

            <div class="flex gap-3">
                <button class="px-4 py-2 bg-white border border-gray-300 text-gray-700 text-sm font-semibold rounded-lg hover:bg-gray-50 flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg> Lihat Riwayat Lengkap
                </button>
                <button class="px-4 py-2 bg-white border border-gray-300 text-gray-700 text-sm font-semibold rounded-lg hover:bg-gray-50 flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg> Jadwalkan Kontrol
                </button>
                <button class="px-4 py-2 bg-black text-white text-sm font-semibold rounded-lg hover:bg-gray-800 transition-colors">
                    Update Rekam Medis
                </button>
            </div>
        </div>

        <div class="bg-white border border-gray-200 rounded-2xl p-6 shadow-sm hover:shadow-md transition-shadow">
            <div class="flex justify-between items-start mb-6">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 bg-pink-100 text-pink-600 rounded-full flex items-center justify-center">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    <div>
                        <h4 class="font-bold text-gray-900 text-lg">Dewi Kartika</h4>
                        <p class="text-xs text-gray-500">Usia: 32 tahun</p>
                    </div>
                </div>
                <span class="px-3 py-1 bg-yellow-100 text-yellow-700 text-xs font-bold rounded">Risiko Sedang</span>
            </div>

            <div class="grid grid-cols-3 gap-4 mb-4">
                <div class="bg-gray-50 p-4 rounded-xl border border-gray-100">
                    <p class="text-xs text-gray-500 mb-1">Usia Kehamilan</p>
                    <p class="font-bold text-gray-900 text-lg">36 Minggu</p>
                </div>
                <div class="bg-gray-50 p-4 rounded-xl border border-gray-100">
                    <p class="text-xs text-gray-500 mb-1">Kunjungan Terakhir</p>
                    <p class="font-bold text-gray-900 text-lg">1 Mar</p>
                </div>
                <div class="bg-gray-50 p-4 rounded-xl border border-gray-100">
                    <p class="text-xs text-gray-500 mb-1">Kontrol Berikutnya</p>
                    <p class="font-bold text-gray-900 text-lg">8 Mar</p>
                </div>
            </div>

            <div class="bg-blue-50/50 border border-blue-100 rounded-xl p-4 mb-6">
                <p class="text-xs font-semibold text-blue-800 mb-1">Catatan Medis:</p>
                <p class="text-sm text-blue-900">Tekanan darah sedikit tinggi, perlu monitoring ketat</p>
            </div>

            <div class="flex gap-3">
                <button class="px-4 py-2 bg-white border border-gray-300 text-gray-700 text-sm font-semibold rounded-lg hover:bg-gray-50 flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg> Lihat Riwayat Lengkap
                </button>
                <button class="px-4 py-2 bg-white border border-gray-300 text-gray-700 text-sm font-semibold rounded-lg hover:bg-gray-50 flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg> Jadwalkan Kontrol
                </button>
                <button class="px-4 py-2 bg-black text-white text-sm font-semibold rounded-lg hover:bg-gray-800 transition-colors">
                    Update Rekam Medis
                </button>
            </div>
        </div>
    </div>
</div>
