<div>
    <div class="flex justify-between items-center mb-6">
        <div>
            <h2 class="text-3xl font-extrabold text-gray-900 mb-1">Laporan & Analitik</h2>
            <p class="text-gray-500">Pelaporan otomatis untuk Pusat dan Bidan Desa</p>
        </div>
        <div class="flex gap-3">
            <button class="bg-white border border-gray-300 text-gray-700 px-4 py-2 rounded-lg text-sm font-semibold flex items-center gap-2 hover:bg-gray-50">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg> Export PDF
            </button>
            <button class="bg-white border border-gray-300 text-gray-700 px-4 py-2 rounded-lg text-sm font-semibold flex items-center gap-2 hover:bg-gray-50">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg> Export Excel
            </button>
            <button class="bg-black text-white px-4 py-2 rounded-lg text-sm font-semibold flex items-center gap-2 hover:bg-gray-800">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg> Cetak
            </button>
        </div>
    </div>

    <div class="bg-white border border-gray-200 rounded-2xl p-6 shadow-sm mb-6">
        <h4 class="font-bold text-gray-900 mb-4">Filter Laporan</h4>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
            <div>
                <label class="block text-xs font-semibold text-gray-500 mb-1">Jenis Laporan</label>
                <select class="w-full border border-gray-300 rounded-lg text-sm px-3 py-2.5 outline-none bg-gray-50">
                    <option>Bulanan</option>
                    <option>Tahunan</option>
                </select>
            </div>
            <div>
                <label class="block text-xs font-semibold text-gray-500 mb-1">Bulan</label>
                <select class="w-full border border-gray-300 rounded-lg text-sm px-3 py-2.5 outline-none bg-gray-50">
                    <option>Februari</option>
                    <option>Maret</option>
                </select>
            </div>
            <div>
                <label class="block text-xs font-semibold text-gray-500 mb-1">Tahun</label>
                <select class="w-full border border-gray-300 rounded-lg text-sm px-3 py-2.5 outline-none bg-gray-50">
                    <option>2026</option>
                    <option>2025</option>
                </select>
            </div>
        </div>

        <div class="flex gap-4 border-b border-gray-200">
            <button class="pb-3 border-b-2 border-black font-semibold text-sm text-gray-900 flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg> Ringkasan
            </button>
            <button class="pb-3 border-b-2 border-transparent font-medium text-sm text-gray-500 hover:text-gray-900 flex items-center gap-2 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path></svg> Grafik & Analitik
            </button>
            <button class="pb-3 border-b-2 border-transparent font-medium text-sm text-gray-500 hover:text-gray-900 flex items-center gap-2 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg> Laporan Bidan Desa
            </button>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
        <div class="bg-white p-6 rounded-2xl border border-gray-200 shadow-sm">
            <div class="flex justify-between items-start mb-4">
                <div class="p-2 bg-blue-50 text-blue-600 rounded-lg"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg></div>
                <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Total</p>
            </div>
            <h3 class="text-3xl font-bold text-gray-900 mb-1">342</h3>
            <p class="text-sm font-medium text-gray-500">Total Pasien <span class="text-green-600 text-xs ml-1">+67 pasien baru</span></p>
        </div>
        <div class="bg-white p-6 rounded-2xl border border-gray-200 shadow-sm">
            <div class="flex justify-between items-start mb-4">
                <div class="p-2 bg-pink-50 text-pink-600 rounded-lg"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg></div>
                <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Kehamilan</p>
            </div>
            <h3 class="text-3xl font-bold text-gray-900 mb-1">186</h3>
            <p class="text-sm font-medium text-gray-500">Pasien Hamil <span class="text-gray-400 text-xs ml-1 block mt-1">Aktif dipantau</span></p>
        </div>
        <div class="bg-white p-6 rounded-2xl border border-gray-200 shadow-sm">
            <div class="flex justify-between items-start mb-4">
                <div class="p-2 bg-red-50 text-red-600 rounded-lg"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path></svg></div>
                <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">KB</p>
            </div>
            <h3 class="text-3xl font-bold text-gray-900 mb-1">156</h3>
            <p class="text-sm font-medium text-gray-500">Peserta KB <span class="text-gray-400 text-xs ml-1 block mt-1">Aktif program</span></p>
        </div>
        <div class="bg-white p-6 rounded-2xl border border-gray-200 shadow-sm">
            <div class="flex justify-between items-start mb-4">
                <div class="p-2 bg-purple-50 text-purple-600 rounded-lg"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg></div>
                <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Kunjungan</p>
            </div>
            <h3 class="text-3xl font-bold text-gray-900 mb-1">1289</h3>
            <p class="text-sm font-medium text-gray-500">Total Bulan Ini <span class="text-gray-400 text-xs ml-1 block mt-1">~43 per hari</span></p>
        </div>
    </div>

    <div class="bg-white border border-gray-200 rounded-2xl p-6 shadow-sm">
        <h4 class="font-bold text-gray-900 mb-4">Laporan Kunjungan Detail - Februari 2026</h4>
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-sm">
                <thead>
                    <tr class="bg-gray-50 border-b border-gray-200 text-gray-600">
                        <th class="p-4 font-semibold">Bulan</th>
                        <th class="p-4 font-semibold text-right">Total Kunjungan</th>
                        <th class="p-4 font-semibold text-right">Kehamilan</th>
                        <th class="p-4 font-semibold text-right">KB</th>
                        <th class="p-4 font-semibold text-right">Pendapatan</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="p-4">Jan</td>
                        <td class="p-4 text-right font-bold text-gray-900">245</td>
                        <td class="p-4 text-right text-gray-600">145</td>
                        <td class="p-4 text-right text-gray-600">100</td>
                        <td class="p-4 text-right text-gray-600">Rp 38.5M</td>
                    </tr>
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="p-4">Feb</td>
                        <td class="p-4 text-right font-bold text-gray-900">267</td>
                        <td class="p-4 text-right text-gray-600">156</td>
                        <td class="p-4 text-right text-gray-600">111</td>
                        <td class="p-4 text-right text-gray-600">Rp 42.3M</td>
                    </tr>
                    <tr class="bg-gray-50 border-t-2 border-gray-200">
                        <td class="p-4 font-extrabold text-gray-900">TOTAL</td>
                        <td class="p-4 text-right font-extrabold text-gray-900">1609</td>
                        <td class="p-4 text-right font-extrabold text-gray-900">974</td>
                        <td class="p-4 text-right font-extrabold text-gray-900">635</td>
                        <td class="p-4 text-right font-extrabold text-gray-900">Rp 254.8M</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
