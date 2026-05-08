<div class="bg-white border border-gray-100 rounded-3xl p-6 md:p-8 shadow-sm max-w-4xl mx-auto dark:bg-[#1E293B] dark:border-gray-800">
    <div class="flex items-center gap-3 mb-8">
        <div class="w-10 h-10 rounded-xl flex items-center justify-center bg-teal-50 text-teal-600 dark:bg-teal-900/30 dark:text-teal-400">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
        </div>
        <div>
            <h3 class="font-bold text-gray-900 text-xl dark:text-white">Rekam Medis Digital</h3>
            <p class="text-sm text-gray-500 dark:text-gray-400">Riwayat pemeriksaan dan konsultasi kesehatan Anda</p>
        </div>
    </div>

    <div class="space-y-6">
        @forelse($rekamMedis as $rm)
        <div class="border border-gray-100 rounded-2xl p-5 hover:border-teal-200 transition-all dark:border-gray-700 dark:hover:border-teal-800 bg-white dark:bg-[#1E293B] relative overflow-hidden group">
            <div class="absolute left-0 top-0 bottom-0 w-1 bg-teal-500 rounded-l-2xl group-hover:w-1.5 transition-all"></div>

            <div class="flex flex-col md:flex-row md:items-start justify-between gap-4 mb-5 pl-3">
                <div>
                    <div class="flex items-center gap-2 mb-1">
                        <span class="px-2 py-0.5 bg-teal-50 text-teal-600 text-[10px] font-bold rounded uppercase tracking-wider dark:bg-teal-900/30 dark:text-teal-400">
                            {{ $rm->kategori }}
                        </span>
                        <span class="text-[10px] text-gray-400 font-bold uppercase tracking-widest">#{{ $rm->no_rekam_medis }}</span>
                    </div>
                    <h4 class="font-bold text-gray-900 text-lg dark:text-white line-clamp-1">{{ $rm->diagnosis ?? 'Pemeriksaan Rutin' }}</h4>
                    <p class="text-sm text-gray-600 mt-1 dark:text-gray-400 flex items-center gap-1.5">
                        <svg class="w-4 h-4 text-teal-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                        {{ $rm->dokter_pemeriksa }}
                    </p>
                </div>
                <div class="flex flex-col items-end gap-1">
                    <span class="inline-flex px-3 py-1.5 border border-gray-100 bg-gray-50 text-gray-700 text-xs font-semibold rounded-lg dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300">
                        {{ $rm->created_at->format('d M Y') }}
                    </span>
                    @if($rm->status_risiko === 'Tinggi')
                        <span class="text-[10px] font-black text-rose-500 uppercase tracking-widest">Risiko Tinggi</span>
                    @endif
                </div>
            </div>

            @if($rm->catatan_pasien)
            <div class="mb-5 ml-3 p-5 bg-gradient-to-br from-teal-600 to-teal-700 rounded-2xl text-white shadow-xl shadow-teal-500/20 relative overflow-hidden">
                <div class="absolute -right-4 -top-4 w-24 h-24 bg-white/10 rounded-full blur-2xl"></div>
                <div class="relative flex items-start gap-3">
                    <div class="w-8 h-8 rounded-lg bg-white/20 flex items-center justify-center flex-shrink-0">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"/></svg>
                    </div>
                    <div>
                        <span class="block text-[10px] font-black uppercase tracking-[0.2em] text-teal-100 mb-1">Pesan dari Dokter</span>
                        <p class="text-sm font-semibold leading-relaxed">{{ $rm->catatan_pasien }}</p>
                    </div>
                </div>
            </div>
            @endif

            @if($rm->jadwal_kontrol_berikutnya)
            <div class="mt-4 ml-3 flex items-center gap-2 px-3 py-2 bg-amber-50 text-amber-700 text-[10px] font-bold rounded-lg border border-amber-100 dark:bg-amber-900/20 dark:border-amber-900/30 dark:text-amber-400">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                Jadwal Kontrol Berikutnya: {{ $rm->jadwal_kontrol_berikutnya->format('d M Y') }}
            </div>
            @endif
        </div>
        @empty
        <div class="text-center py-16 px-4">
            <div class="w-20 h-20 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-4 dark:bg-gray-800">
                <svg class="w-10 h-10 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
            </div>
            <h4 class="text-gray-900 font-bold text-lg dark:text-white">Data Belum Tersedia</h4>
            <p class="text-gray-500 text-sm mt-1 max-w-xs mx-auto">Riwayat rekam medis Anda akan muncul di sini setelah Anda melakukan kunjungan pertama ke klinik.</p>
        </div>
        @endforelse
    </div>
</div>
