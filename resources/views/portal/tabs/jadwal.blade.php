<section aria-labelledby="jadwal-heading" class="p-6">
    <header class="flex justify-between items-center mb-8">
        <div>
            <h2 id="jadwal-heading" class="text-3xl font-extrabold text-gray-900 mb-1">Jadwal Saya</h2>
            <p class="text-gray-500">Pantau status reservasi dan jadwal konsultasi Anda</p>
        </div>
        <div class="bg-teal-50 px-4 py-2 rounded-xl border border-teal-100">
            <span class="text-teal-700 font-bold text-sm">Total: {{ $jadwalPasien->count() }} Jadwal</span>
        </div>
    </header>

    @if(session('success'))
        <div class="mb-6 p-4 bg-green-100 text-green-700 rounded-xl border border-green-200 flex items-center gap-3">
            <svg aria-hidden="true" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
            <span class="font-bold">{{ session('success') }}</span>
        </div>
    @endif

    <ul class="grid grid-cols-1 gap-6 list-none m-0 p-0">
        @forelse($jadwalPasien as $jadwal)
            <li>
                <article class="bg-white border border-gray-100 rounded-3xl p-6 shadow-sm hover:shadow-md transition-all">
                <div class="flex flex-col md:flex-row justify-between gap-6">
                    <div class="flex gap-5">
                        <div class="flex-shrink-0 w-20 h-20 bg-teal-50 rounded-2xl flex flex-col items-center justify-center border border-teal-100 shadow-sm">
                            <span class="text-teal-500 text-[9px] font-black uppercase tracking-widest">No. Urut</span>
                            <span class="text-teal-900 font-black text-2xl">#{{ $jadwal->queue_number ?? '-' }}</span>
                        </div>

                        <div>
                            <div class="flex items-center gap-3 mb-2">
                                <span class="px-2.5 py-1 bg-blue-50 text-blue-600 text-[10px] font-black rounded-lg uppercase tracking-wider">{{ $jadwal->layanan }}</span>
                                <span class="text-gray-400 font-medium text-xs flex items-center gap-1.5">
                                    <svg aria-hidden="true" class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                    {{ \Carbon\Carbon::parse($jadwal->tanggal)->locale('id')->translatedFormat('d F Y') }}
                                </span>
                            </div>
                            <div class="flex items-baseline gap-2">
                                <span class="text-xs font-bold text-teal-600 uppercase">Perkiraan Waktu</span>
                                <h3 class="text-2xl font-black text-gray-900 tracking-tight">
                                    @if($jadwal->estimated_time)
                                        {{ \Carbon\Carbon::parse($jadwal->estimated_time)->format('H:i') }}
                                        <span class="text-gray-400 text-sm font-medium"> - {{ \Carbon\Carbon::parse($jadwal->estimated_time)->addMinutes(30)->format('H:i') }}</span>
                                    @else
                                        {{ $jadwal->waktu }}
                                    @endif
                                </h3>
                            </div>
                            <p class="text-sm text-gray-600 font-bold flex items-center gap-1.5 mt-1 bg-teal-50 px-3 py-1 rounded-lg border border-teal-100 inline-flex">
                                <svg aria-hidden="true" class="w-4 h-4 text-teal-500" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                Mohon hadir 15 menit sebelum estimasi jam di atas.
                            </p>
                        </div>
                    </div>

                    <div class="flex flex-row md:flex-col justify-between items-center md:items-end gap-3">
                        @php
                            $statusColor = match($jadwal->status) {
                                'Menunggu' => 'amber',
                                'Dikonfirmasi' => 'blue',
                                'Datang' => 'emerald',
                                'Tidak Datang' => 'rose',
                                default => 'gray'
                            };
                        @endphp
                        <span class="px-4 py-1.5 bg-{{ $statusColor }}-50 text-{{ $statusColor }}-700 text-[10px] font-black uppercase tracking-widest rounded-full border border-{{ $statusColor }}-100 shadow-sm">
                            {{ $jadwal->status ?? 'Menunggu Konfirmasi' }}
                        </span>

                        <div class="flex gap-2">
                            <form action="{{ route('reservasi.destroy', $jadwal->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Apakah Anda yakin ingin membatalkan jadwal ini?');">
                                @csrf
                                @method('DELETE')
                                <button aria-label="Batalkan jadwal ini" type="submit" class="p-2.5 text-gray-400 hover:text-red-500 hover:bg-red-50 rounded-xl transition-all" title="Batalkan">
                                    <svg aria-hidden="true" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                </button>
                            </form>

                            <button aria-haspopup="dialog" aria-controls="modal-{{ $jadwal->id }}" onclick="document.getElementById('modal-{{ $jadwal->id }}').classList.remove('hidden')" class="px-5 py-2.5 bg-black text-white text-xs font-bold rounded-xl hover:bg-gray-800 transition-all shadow-sm">
                                Lihat Detail
                            </button>
                        </div>
                    </div>
                </article>

            <div id="modal-{{ $jadwal->id }}" role="dialog" aria-modal="true" aria-labelledby="modal-title-{{ $jadwal->id }}" class="fixed inset-0 z-50 hidden bg-gray-900 bg-opacity-50 flex items-center justify-center p-4">
                <div class="bg-white rounded-3xl p-8 max-w-md w-full shadow-2xl relative">
                    <button aria-label="Tutup detail reservasi" onclick="document.getElementById('modal-{{ $jadwal->id }}').classList.add('hidden')" class="absolute top-4 right-4 text-gray-400 hover:text-gray-900">
                        <svg aria-hidden="true" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                    <h3 id="modal-title-{{ $jadwal->id }}" class="text-2xl font-extrabold text-gray-900 mb-6">Detail Reservasi</h3>

                    <div class="space-y-4">
                        <div>
                            <p class="text-xs text-gray-500 font-bold uppercase">Nama Pasien</p>
                            <p class="text-gray-900 font-semibold">{{ $jadwal->nama }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500 font-bold uppercase">No. WhatsApp</p>
                            <p class="text-gray-900 font-semibold">{{ $jadwal->phone }}</p>
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <p class="text-xs text-gray-500 font-bold uppercase">Tanggal</p>
                                <p class="text-gray-900 font-semibold">{{ \Carbon\Carbon::parse($jadwal->tanggal)->locale('id')->translatedFormat('d M Y') }}</p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500 font-bold uppercase">No. Antrean</p>
                                <p class="text-teal-600 font-black text-xl">#{{ $jadwal->queue_number ?? '-' }}</p>
                            </div>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500 font-bold uppercase">Layanan & Dokter</p>
                            <p class="text-gray-900 font-semibold">{{ $jadwal->layanan }} ({{ $jadwal->dokter_nama ?? $jadwal->dokter_id ?? '-' }})</p>
                        </div>
                        <div class="p-4 bg-gray-50 rounded-xl border border-gray-100">
                            <p class="text-xs text-gray-500 font-bold uppercase mb-1">Keluhan / Catatan</p>
                            <p class="text-gray-700 text-sm italic">"{{ $jadwal->keluhan ?? 'Tidak ada catatan.' }}"</p>
                        </div>
                    </div>

                    <button onclick="document.getElementById('modal-{{ $jadwal->id }}').classList.add('hidden')" class="mt-8 w-full py-3 bg-teal-600 text-white font-bold rounded-xl hover:bg-teal-700 transition-all">
                        Tutup
                    </button>
                </div>
            </div>
            </li>
        @empty
            <li class="bg-gray-50 border-2 border-dashed border-gray-200 rounded-[2rem] p-16 text-center">
                <div class="w-20 h-20 bg-white shadow-sm rounded-3xl flex items-center justify-center mx-auto mb-6 text-gray-300">
                    <svg aria-hidden="true" class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-2">Belum Ada Jadwal</h3>
                <p class="text-gray-500 mb-8 max-w-xs mx-auto">Anda belum memiliki riwayat reservasi. Silakan buat janji temu melalui menu reservasi.</p>
                <a href="{{ route('portal') }}" class="inline-flex items-center gap-2 px-6 py-3 bg-teal-600 text-white font-bold rounded-2xl hover:bg-teal-700 transition-all">
                    Buat Reservasi Sekarang
                </a>
            </li>
        @endforelse
    </ul>
</section>
