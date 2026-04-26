<div class="bg-white border border-gray-100 rounded-3xl p-6 md:p-8 shadow-sm max-w-4xl mx-auto dark:bg-[#1E293B] dark:border-gray-800">
    <div class="flex items-center gap-3 mb-6">
        <div class="w-10 h-10 rounded-xl flex items-center justify-center bg-teal-50 text-teal-600 dark:bg-teal-900/30 dark:text-teal-400">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        </div>
        <div>
            <h3 class="font-bold text-gray-900 text-xl dark:text-white">Jadwal Kunjungan Anda</h3>
            <p class="text-sm text-gray-500 dark:text-gray-400">Daftar reservasi janji temu medis yang telah Anda buat</p>
        </div>
    </div>

    <div class="space-y-4">
        {{-- Jadwal Card 1 (Upcoming) --}}
        <div class="border border-gray-100 rounded-2xl p-5 hover:border-teal-200 transition-all dark:border-gray-700 dark:hover:border-teal-800 bg-white dark:bg-[#1E293B] relative overflow-hidden group">
            <div class="absolute left-0 top-0 bottom-0 w-1 bg-teal-500 rounded-l-2xl group-hover:w-1.5 transition-all"></div>
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-4 pl-3">
                <div>
                    <h4 class="font-bold text-gray-900 text-lg dark:text-white">Pemeriksaan Rutin Kehamilan</h4>
                    <p class="text-sm text-gray-500 mt-1 dark:text-gray-400 flex items-center gap-1.5">
                        <span class="w-1.5 h-1.5 rounded-full bg-teal-500"></span>
                        Dr. Siti Nurhaliza, Sp.OG
                    </p>
                </div>
                <span class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-teal-50 text-teal-700 text-xs font-bold rounded-lg w-max dark:bg-teal-900/30 dark:text-teal-400">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                    Dikonfirmasi
                </span>
            </div>
            <div class="flex flex-wrap items-center gap-x-6 gap-y-3 text-sm text-gray-600 pt-3 border-t border-gray-50 dark:border-gray-800 dark:text-gray-400 pl-3">
                <div class="flex items-center gap-2">
                    <svg class="w-4 h-4 text-teal-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    Selasa, 10 Maret 2026
                </div>
                <div class="flex items-center gap-2">
                    <svg class="w-4 h-4 text-teal-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    10:00 WIB
                </div>
                <div class="flex items-center gap-2">
                    <svg class="w-4 h-4 text-teal-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    Klinik Utama (Lt. 2)
                </div>
            </div>
        </div>

        {{-- Jadwal Card 2 (Completed) --}}
        <div class="border border-gray-100 rounded-2xl p-5 bg-gray-50/50 dark:bg-[#0F172A]/50 dark:border-gray-800 relative overflow-hidden group opacity-75">
            <div class="absolute left-0 top-0 bottom-0 w-1 bg-gray-300 rounded-l-2xl dark:bg-gray-600"></div>
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-4 pl-3">
                <div>
                    <h4 class="font-bold text-gray-900 text-lg dark:text-gray-300">Konsultasi KB</h4>
                    <p class="text-sm text-gray-500 mt-1 dark:text-gray-500 flex items-center gap-1.5">
                        <span class="w-1.5 h-1.5 rounded-full bg-gray-400"></span>
                        Dr. Dewi Lestari, Sp.OG
                    </p>
                </div>
                <span class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-gray-100 text-gray-600 text-xs font-bold rounded-lg w-max dark:bg-gray-800 dark:text-gray-400">
                    Selesai
                </span>
            </div>
            <div class="flex flex-wrap items-center gap-x-6 gap-y-3 text-sm text-gray-500 pt-3 border-t border-gray-100 dark:border-gray-800 pl-3">
                <div class="flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    Minggu, 15 Februari 2026
                </div>
                <div class="flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    14:00 WIB
                </div>
            </div>
        </div>
    </div>
</div>
