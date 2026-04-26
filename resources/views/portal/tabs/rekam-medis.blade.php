<div class="bg-white border border-gray-100 rounded-3xl p-6 md:p-8 shadow-sm max-w-4xl mx-auto dark:bg-[#1E293B] dark:border-gray-800">
    <div class="flex items-center gap-3 mb-6">
        <div class="w-10 h-10 rounded-xl flex items-center justify-center bg-teal-50 text-teal-600 dark:bg-teal-900/30 dark:text-teal-400">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
        </div>
        <div>
            <h3 class="font-bold text-gray-900 text-xl dark:text-white">Rekam Medis Digital</h3>
            <p class="text-sm text-gray-500 dark:text-gray-400">Riwayat pemeriksaan dan konsultasi kesehatan Anda</p>
        </div>
    </div>

    <div class="space-y-6">
        {{-- Rekam Medis 1 --}}
        <div class="border border-gray-100 rounded-2xl p-5 hover:border-teal-200 transition-all dark:border-gray-700 dark:hover:border-teal-800 bg-white dark:bg-[#1E293B] relative overflow-hidden group">
            <div class="absolute left-0 top-0 bottom-0 w-1 bg-teal-500 rounded-l-2xl group-hover:w-1.5 transition-all"></div>
            <div class="flex flex-col md:flex-row md:items-start justify-between gap-4 mb-4 pl-3">
                <div>
                    <h4 class="font-bold text-gray-900 text-lg dark:text-white">Konsultasi KB</h4>
                    <p class="text-sm text-gray-600 mt-1 dark:text-gray-400 flex items-center gap-1.5">
                        <span class="w-1.5 h-1.5 rounded-full bg-teal-500"></span>
                        Dr. Dewi Lestari, Sp.OG
                    </p>
                </div>
                <span class="inline-flex px-3 py-1.5 border border-gray-100 bg-gray-50 text-gray-700 text-xs font-semibold rounded-lg dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300">
                    15 Feb 2026
                </span>
            </div>
            <div class="bg-gray-50/80 rounded-xl p-4 text-sm text-gray-700 border border-gray-100 dark:bg-[#0F172A] dark:border-gray-800 dark:text-gray-300 ml-3">
                <span class="font-semibold text-gray-900 dark:text-white">Catatan Dokter:</span> Pemasangan IUD berhasil, kontrol 1 bulan. Tidak ada keluhan pendarahan.
            </div>
        </div>

        {{-- Rekam Medis 2 --}}
        <div class="border border-gray-100 rounded-2xl p-5 hover:border-teal-200 transition-all dark:border-gray-700 dark:hover:border-teal-800 bg-white dark:bg-[#1E293B] relative overflow-hidden group">
            <div class="absolute left-0 top-0 bottom-0 w-1 bg-teal-500 rounded-l-2xl group-hover:w-1.5 transition-all"></div>
            <div class="flex flex-col md:flex-row md:items-start justify-between gap-4 mb-4 pl-3">
                <div>
                    <h4 class="font-bold text-gray-900 text-lg dark:text-white">Pemeriksaan Kehamilan</h4>
                    <p class="text-sm text-gray-600 mt-1 dark:text-gray-400 flex items-center gap-1.5">
                        <span class="w-1.5 h-1.5 rounded-full bg-teal-500"></span>
                        Dr. Siti Nurhaliza, Sp.OG
                    </p>
                </div>
                <span class="inline-flex px-3 py-1.5 border border-gray-100 bg-gray-50 text-gray-700 text-xs font-semibold rounded-lg dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300">
                    20 Jan 2026
                </span>
            </div>
            <div class="bg-gray-50/80 rounded-xl p-4 text-sm text-gray-700 border border-gray-100 dark:bg-[#0F172A] dark:border-gray-800 dark:text-gray-300 ml-3">
                <span class="font-semibold text-gray-900 dark:text-white">Catatan Dokter:</span> Usia kehamilan 12 minggu, kondisi janin baik. Detak jantung normal. Diresepkan suplemen asam folat dan kalsium tambahan.
            </div>
        </div>
    </div>
</div>
