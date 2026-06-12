<section aria-labelledby="jadwal-heading" class="p-6" x-data="{ showConfirmCancel: false, cancelRoute: '' }">
    <header class="flex justify-between items-center mb-8">
        <div>
            <h2 id="jadwal-heading" class="text-3xl font-extrabold text-gray-900 dark:text-white mb-1">Jadwal Saya</h2>
            <p class="text-gray-500 dark:text-gray-400">Pantau status reservasi dan jadwal konsultasi Anda</p>
        </div>
        <div class="bg-teal-50 dark:bg-teal-950/45 px-4 py-2 rounded-xl border border-teal-100 dark:border-teal-900/40">
            <span class="text-teal-700 dark:text-teal-400 font-bold text-sm">Total: {{ $jadwalPasien->count() }} Jadwal</span>
        </div>
    </header>


    <ul class="grid grid-cols-1 gap-6 list-none m-0 p-0">
        @forelse($jadwalPasien as $jadwal)
            <li class="list-none">
                <!-- 1. LAYOUT DESKTOP/TABLET (Tampak di md ke atas) -->
                <article class="hidden md:block bg-white dark:bg-[#1E293B] border border-gray-100 dark:border-gray-800 rounded-3xl p-6 shadow-sm hover:shadow-md transition-all">
                    <div class="flex flex-col md:flex-row justify-between gap-6">
                        <div class="flex gap-5">
                            <div class="flex-shrink-0 w-20 h-20 bg-teal-50 dark:bg-teal-950/40 rounded-2xl flex flex-col items-center justify-center border border-teal-100 dark:border-teal-900/30 shadow-sm">
                                <span class="text-teal-500 dark:text-teal-400 text-[9px] font-black uppercase tracking-widest">No. Urut</span>
                                <span class="text-teal-900 dark:text-teal-100 font-black text-2xl">#{{ $jadwal->queue_number ?? '-' }}</span>
                            </div>

                            <div>
                                <div class="flex items-center gap-3 mb-2">
                                    <span class="px-2.5 py-1 bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 text-[10px] font-black rounded-lg uppercase tracking-wider">{{ $jadwal->layanan }}</span>
                                    <span class="text-gray-400 dark:text-gray-500 font-medium text-xs flex items-center gap-1.5">
                                        <svg aria-hidden="true" class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                        {{ \Carbon\Carbon::parse($jadwal->tanggal)->locale('id')->translatedFormat('d F Y') }}
                                    </span>
                                </div>
                                <div class="flex items-baseline gap-2">
                                    <span class="text-xs font-bold text-teal-600 dark:text-teal-400 uppercase">Perkiraan Waktu</span>
                                    <h3 class="text-2xl font-black text-gray-900 dark:text-white tracking-tight">
                                        @if($jadwal->estimated_time)
                                            {{ \Carbon\Carbon::parse($jadwal->estimated_time)->format('H:i') }}
                                            <span class="text-gray-400 dark:text-gray-500 text-sm font-medium"> - {{ \Carbon\Carbon::parse($jadwal->estimated_time)->addMinutes(30)->format('H:i') }}</span>
                                        @else
                                            {{ $jadwal->waktu }}
                                        @endif
                                    </h3>
                                </div>
                                <div class="flex items-center gap-1.5 mt-1 text-xs text-gray-600 dark:text-gray-400 font-bold">
                                    <svg aria-hidden="true" class="w-4 h-4 text-teal-500 dark:text-teal-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                                    <span>Dokter: {{ $jadwal->dokter_nama ?? '-' }}</span>
                                </div>
                                <p class="text-sm text-gray-600 dark:text-gray-400 font-bold flex items-center gap-1.5 mt-2 bg-teal-50 dark:bg-teal-950/20 px-3 py-1 rounded-lg border border-teal-100 dark:border-teal-900/30 inline-flex">
                                    <svg aria-hidden="true" class="w-4 h-4 text-teal-500 dark:text-teal-400" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    Mohon hadir 15 menit sebelum estimasi jam di atas.
                                </p>
                            </div>
                        </div>

                        <div class="flex flex-row md:flex-col justify-between items-center md:items-end gap-3">
                            @php
                                $statusColor = match($jadwal->status) {
                                    'Menunggu' => 'amber',
                                    'Dikonfirmasi' => 'blue',
                                    'Datang', 'Selesai' => 'emerald',
                                    'Tidak Datang', 'Batal' => 'rose',
                                    default => 'gray'
                                };
                            @endphp
                            <span class="px-4 py-1.5 bg-{{ $statusColor }}-50 dark:bg-{{ $statusColor }}-950/30 text-{{ $statusColor }}-700 dark:text-{{ $statusColor }}-400 text-[10px] font-black uppercase tracking-widest rounded-full border border-{{ $statusColor }}-100 dark:border-{{ $statusColor }}-900/30 shadow-sm">
                                {{ $jadwal->status ?? 'Menunggu Konfirmasi' }}
                            </span>

                            <div class="flex gap-2">
                                @if($jadwal->status === 'Menunggu')
                                <button aria-label="Batalkan jadwal ini"
                                        type="button"
                                        @click="cancelRoute = '{{ route('reservasi.destroy', $jadwal->id) }}'; showConfirmCancel = true"
                                        class="p-2.5 text-gray-400 dark:text-gray-500 hover:text-red-500 dark:hover:text-red-400 hover:bg-red-50 dark:hover:bg-red-950/20 rounded-xl transition-all"
                                        title="Batalkan">
                                    <svg aria-hidden="true" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                </button>
                                @endif

                                <button aria-haspopup="dialog" aria-controls="modal-{{ $jadwal->id }}" onclick="document.getElementById('modal-{{ $jadwal->id }}').classList.remove('hidden')" class="px-5 py-2.5 bg-black dark:bg-gray-800 text-white text-xs font-bold rounded-xl hover:bg-gray-800 dark:hover:bg-gray-700 transition-all shadow-sm">
                                    Lihat Detail
                                </button>
                            </div>
                        </div>
                    </div>
                </article>

                <!-- 2. LAYOUT MOBILE (Tampak di bawah md) -->
                <article class="block md:hidden bg-white dark:bg-[#1E293B] border border-gray-100 dark:border-gray-800 rounded-3xl p-5 shadow-sm hover:shadow-md transition-all relative overflow-hidden">
                    <!-- Status border stripe -->
                    @php
                        $statusColor = match($jadwal->status) {
                            'Menunggu' => 'amber',
                            'Dikonfirmasi' => 'blue',
                            'Datang', 'Selesai' => 'emerald',
                            'Tidak Datang', 'Batal' => 'rose',
                            default => 'gray'
                        };
                    @endphp
                    <div class="absolute left-0 top-0 bottom-0 w-1.5 bg-{{ $statusColor }}-500"></div>

                    <!-- Header Card: Date, Time & Queue Number -->
                    <div class="flex justify-between items-start mb-4 pl-2">
                        <div class="space-y-1">
                            <div class="flex items-center gap-2 text-xs font-semibold text-gray-500 dark:text-gray-400">
                                <svg aria-hidden="true" class="w-4 h-4 text-teal-500 dark:text-teal-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                                <span>{{ \Carbon\Carbon::parse($jadwal->tanggal)->locale('id')->translatedFormat('d F Y') }}</span>
                            </div>
                            <div class="flex items-center gap-2 text-base font-extrabold text-gray-900 dark:text-white">
                                <svg aria-hidden="true" class="w-4 h-4 text-teal-500 dark:text-teal-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <span>
                                    @if($jadwal->estimated_time)
                                        {{ \Carbon\Carbon::parse($jadwal->estimated_time)->format('H:i') }}
                                        <span class="text-xs font-normal text-gray-400 dark:text-gray-500"> - {{ \Carbon\Carbon::parse($jadwal->estimated_time)->addMinutes(30)->format('H:i') }}</span>
                                    @else
                                        {{ $jadwal->waktu }}
                                    @endif
                                </span>
                            </div>
                        </div>

                        <!-- Queue Number Badge -->
                        <div class="flex flex-col items-center bg-teal-50 dark:bg-teal-950/40 px-3 py-1.5 rounded-2xl border border-teal-100 dark:border-teal-900/30 shadow-inner">
                            <span class="text-teal-600 dark:text-teal-400 text-[8px] font-bold uppercase tracking-wider">No. Antrean</span>
                            <span class="text-teal-900 dark:text-teal-200 font-black text-sm">#{{ $jadwal->queue_number ?? '-' }}</span>
                        </div>
                    </div>

                    <!-- Divider -->
                    <div class="border-t border-gray-100 dark:border-gray-800/80 my-3 pl-2"></div>

                    <!-- Body Card: Doctor Name & Poli/Keahlian -->
                    <div class="space-y-3 pl-2">
                        <!-- Doctor Name -->
                        <div class="flex items-start gap-3">
                            <div class="w-8 h-8 rounded-lg bg-teal-50 dark:bg-teal-950/45 flex items-center justify-center text-teal-600 dark:text-teal-400 flex-shrink-0 mt-0.5">
                                <svg aria-hidden="true" class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                            </div>
                            <div>
                                <span class="block text-[10px] font-bold text-gray-400 dark:text-gray-500 uppercase tracking-wider">Dokter</span>
                                <h4 class="font-bold text-gray-900 dark:text-white text-sm">{{ $jadwal->dokter_nama ?? '-' }}</h4>
                            </div>
                        </div>

                        <!-- Poli / Service -->
                        <div class="flex items-start gap-3">
                            <div class="w-8 h-8 rounded-lg bg-teal-50 dark:bg-teal-950/45 flex items-center justify-center text-teal-600 dark:text-teal-400 flex-shrink-0 mt-0.5">
                                <svg aria-hidden="true" class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z"></path>
                                </svg>
                            </div>
                            <div>
                                <span class="block text-[10px] font-bold text-gray-400 dark:text-gray-500 uppercase tracking-wider">Layanan / Poli</span>
                                <span class="px-2.5 py-0.5 bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 text-[10px] font-extrabold rounded-md uppercase tracking-wider inline-block">
                                    {{ $jadwal->doctor->spesialisasi ?? $jadwal->layanan }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Status Badge & Estimasi Info -->
                    <div class="mt-4 pl-2 flex flex-col gap-2">
                        <div class="flex justify-between items-center">
                            <span class="text-xs text-gray-400 dark:text-gray-500 font-semibold">Status</span>
                            <span class="px-3 py-1 bg-{{ $statusColor }}-50 dark:bg-{{ $statusColor }}-950/30 text-{{ $statusColor }}-700 dark:text-{{ $statusColor }}-400 text-[10px] font-extrabold uppercase tracking-wider rounded-full border border-{{ $statusColor }}-100 dark:border-{{ $statusColor }}-900/30 shadow-sm">
                                {{ $jadwal->status ?? 'Menunggu Konfirmasi' }}
                            </span>
                        </div>

                        <!-- Perkiraan Waktu Info Text -->
                        <p class="text-[11px] text-teal-600 dark:text-teal-400 font-medium bg-teal-50/50 dark:bg-teal-950/20 px-2.5 py-1.5 rounded-lg border border-teal-100/30 dark:border-teal-900/20 flex items-center gap-1.5 mt-1">
                            <svg aria-hidden="true" class="w-3.5 h-3.5 text-teal-500 dark:text-teal-400 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <span>Mohon hadir 15 menit sebelum estimasi jam.</span>
                        </p>
                    </div>

                    <!-- Footer Actions: Full Width & Touch Friendly -->
                    <div class="mt-5 space-y-2">
                        <button aria-haspopup="dialog" aria-controls="modal-{{ $jadwal->id }}" onclick="document.getElementById('modal-{{ $jadwal->id }}').classList.remove('hidden')" class="w-full py-3 bg-black dark:bg-gray-800 text-white text-xs font-bold rounded-xl hover:bg-gray-800 dark:hover:bg-gray-750 transition-all shadow-sm flex items-center justify-center gap-1.5">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                            </svg>
                            Lihat Detail
                        </button>

                        @if($jadwal->status === 'Menunggu')
                        <button type="button"
                                @click="cancelRoute = '{{ route('reservasi.destroy', $jadwal->id) }}'; showConfirmCancel = true"
                                class="w-full py-3 border border-red-200 dark:border-red-900/30 text-red-600 dark:text-red-400 bg-white dark:bg-transparent hover:bg-red-50 dark:hover:bg-red-950/20 text-xs font-bold rounded-xl transition-all flex items-center justify-center gap-1.5">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                            </svg>
                            Batalkan Reservasi
                        </button>
                        @endif
                    </div>
                </article>

                <!-- 3. DETAIL MODAL (Shared / Diperbaiki dengan dukungan dark mode) -->
                <div id="modal-{{ $jadwal->id }}" role="dialog" aria-modal="true" aria-labelledby="modal-title-{{ $jadwal->id }}" class="fixed inset-0 z-50 hidden bg-gray-900 bg-opacity-50 flex items-center justify-center p-4">
                    <div class="bg-white dark:bg-[#1E293B] rounded-3xl p-8 max-w-md w-full shadow-2xl relative border border-transparent dark:border-gray-800">
                        <button aria-label="Tutup detail reservasi" onclick="document.getElementById('modal-{{ $jadwal->id }}').classList.add('hidden')" class="absolute top-4 right-4 text-gray-400 hover:text-gray-900 dark:hover:text-white">
                            <svg aria-hidden="true" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                        </button>
                        <h3 id="modal-title-{{ $jadwal->id }}" class="text-2xl font-extrabold text-gray-900 dark:text-white mb-6">Detail Reservasi</h3>

                        <div class="space-y-4">
                            <div>
                                <p class="text-xs text-gray-500 dark:text-gray-400 font-bold uppercase">Nama Pasien</p>
                                <p class="text-gray-900 dark:text-white font-semibold">{{ $jadwal->nama }}</p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500 dark:text-gray-400 font-bold uppercase">No. WhatsApp</p>
                                <p class="text-gray-900 dark:text-white font-semibold">{{ $jadwal->phone }}</p>
                            </div>
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <p class="text-xs text-gray-500 dark:text-gray-400 font-bold uppercase">Tanggal</p>
                                    <p class="text-gray-900 dark:text-white font-semibold">{{ \Carbon\Carbon::parse($jadwal->tanggal)->locale('id')->translatedFormat('d M Y') }}</p>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-500 dark:text-gray-400 font-bold uppercase">No. Antrean</p>
                                    <p class="text-teal-600 dark:text-teal-400 font-black text-xl">#{{ $jadwal->queue_number ?? '-' }}</p>
                                </div>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500 dark:text-gray-400 font-bold uppercase">Layanan & Dokter</p>
                                <p class="text-gray-900 dark:text-white font-semibold">{{ $jadwal->layanan }} ({{ $jadwal->dokter_nama ?? $jadwal->dokter_id ?? '-' }})</p>
                            </div>
                            <div class="p-4 bg-gray-50 dark:bg-gray-800/40 rounded-xl border border-gray-100 dark:border-gray-800">
                                <p class="text-xs text-gray-500 dark:text-gray-400 font-bold uppercase mb-1">Keluhan / Catatan</p>
                                <p class="text-gray-700 dark:text-gray-300 text-sm italic">"{{ $jadwal->keluhan ?? 'Tidak ada catatan.' }}"</p>
                            </div>
                        </div>

                        <button onclick="document.getElementById('modal-{{ $jadwal->id }}').classList.add('hidden')" class="mt-8 w-full py-3 bg-teal-600 dark:bg-teal-500 text-white font-bold rounded-xl hover:bg-teal-700 dark:hover:bg-teal-600 transition-all">
                            Tutup
                        </button>
                    </div>
                </div>
            </li>
        @empty
            <li class="bg-gray-50 dark:bg-gray-900/20 border-2 border-dashed border-gray-200 dark:border-gray-850 rounded-[2rem] p-16 text-center">
                <div class="w-20 h-20 bg-white dark:bg-[#1E293B] shadow-sm rounded-3xl flex items-center justify-center mx-auto mb-6 text-gray-300 dark:text-gray-650">
                    <svg aria-hidden="true" class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                </div>
                <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2">Belum Ada Jadwal</h3>
                <p class="text-gray-500 dark:text-gray-400 mb-8 max-w-xs mx-auto">Anda belum memiliki riwayat reservasi. Silakan buat janji temu melalui menu reservasi.</p>
                <a href="{{ route('portal') }}" class="inline-flex items-center gap-2 px-6 py-3 bg-teal-600 dark:bg-teal-500 text-white font-bold rounded-2xl hover:bg-teal-700 dark:hover:bg-teal-600 transition-all">
                    Buat Reservasi Sekarang
                </a>
            </li>
        @endforelse
    </ul>

    <!-- Custom Confirmation Modal for Cancellation -->
    <div id="confirm-cancel-modal"
         x-show="showConfirmCancel"
         x-cloak
         class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-gray-900/60 backdrop-blur-sm"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         @click="showConfirmCancel = false">
        
        <div class="relative bg-white dark:bg-[#1E293B] w-full max-w-sm rounded-3xl shadow-2xl p-6 text-center border border-gray-100 dark:border-gray-800"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 translate-y-4 scale-95"
             x-transition:enter-end="opacity-100 translate-y-0 scale-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100 translate-y-0 scale-100"
             x-transition:leave-end="opacity-0 translate-y-4 scale-95"
             @click.stop>
            
            <!-- Warning/Danger Icon -->
            <div class="w-16 h-16 bg-red-50 dark:bg-red-950/35 rounded-full flex items-center justify-center mx-auto mb-4 border border-red-100 dark:border-red-900/30">
                <svg class="w-8 h-8 text-red-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                </svg>
            </div>

            <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2">Batalkan Reservasi?</h3>
            <p class="text-sm text-gray-500 dark:text-gray-400 mb-6 font-semibold">Apakah Anda yakin ingin membatalkan jadwal reservasi ini? Tindakan ini tidak dapat dibatalkan.</p>

            <div class="flex gap-3">
                <button @click="showConfirmCancel = false"
                        class="flex-1 py-3 bg-gray-100 dark:bg-gray-800 hover:bg-gray-200 dark:hover:bg-gray-700 text-gray-700 dark:text-gray-200 font-bold rounded-xl text-xs transition-all">
                    Batal
                </button>
                <form :action="cancelRoute" method="POST" class="flex-1 m-0">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                            class="w-full py-3 bg-red-600 hover:bg-red-700 text-white font-bold rounded-xl text-xs transition-all shadow-md shadow-red-500/10">
                        Ya, Batalkan
                    </button>
                </form>
            </div>
        </div>
    </div>
</section>
