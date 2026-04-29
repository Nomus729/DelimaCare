<div class="p-6">
    <div class="flex justify-between items-center mb-8">
        <div>
            <h2 class="text-3xl font-extrabold text-gray-900 mb-1">Jadwal Saya</h2>
            <p class="text-gray-500">Pantau status reservasi dan jadwal konsultasi Anda</p>
        </div>
        <div class="bg-teal-50 px-4 py-2 rounded-xl border border-teal-100">
            <span class="text-teal-700 font-bold text-sm">Total: {{ $jadwalPasien->count() }} Jadwal</span>
        </div>
    </div>

    @if(session('success'))
        <div class="mb-6 p-4 bg-green-100 text-green-700 rounded-xl border border-green-200 flex items-center gap-3">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
            <span class="font-bold">{{ session('success') }}</span>
        </div>
    @endif

    <div class="grid grid-cols-1 gap-6">
        @forelse($jadwalPasien as $jadwal)
            <div class="bg-white border border-gray-100 rounded-3xl p-6 shadow-sm hover:shadow-md transition-all">
                <div class="flex flex-col md:flex-row justify-between gap-6">
                    <div class="flex gap-5">
                        <div class="flex-shrink-0 w-20 h-20 bg-gray-50 rounded-2xl flex flex-col items-center justify-center border border-gray-100">
                            <span class="text-gray-400 text-[10px] font-bold uppercase">Waktu</span>
                            <span class="text-gray-900 font-extrabold text-lg">{{ $jadwal->waktu }}</span>
                        </div>

                        <div>
                            <div class="flex items-center gap-2 mb-2">
                                <span class="px-3 py-1 bg-blue-50 text-blue-600 text-[10px] font-bold rounded-full uppercase">{{ $jadwal->layanan }}</span>
                                <span class="text-gray-300">•</span>
                                <span class="text-gray-500 text-xs font-semibold">{{ \Carbon\Carbon::parse($jadwal->tanggal)->format('d F Y') }}</span>
                            </div>
                            <h3 class="text-xl font-bold text-gray-900 mb-1">Konsultasi dengan {{ $jadwal->dokter_id ?? 'Tenaga Medis' }}</h3>
                            <p class="text-sm text-gray-500 flex items-center gap-1">
                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                Klinik DelimaCare Pusat
                            </p>
                        </div>
                    </div>

                    <div class="flex flex-row md:flex-col justify-between items-center md:items-end gap-3">

                        <span class="px-4 py-1.5 {{ ($jadwal->status ?? 'Menunggu') == 'Menunggu' ? 'bg-yellow-100 text-yellow-700' : 'bg-green-100 text-green-700' }} text-xs font-bold rounded-full shadow-sm">
                            {{ ($jadwal->status ?? 'Menunggu') == 'Menunggu' ? 'Menunggu Konfirmasi' : 'Dikonfirmasi' }}
                        </span>

                        <div class="flex gap-2">
                            <form action="{{ route('reservasi.destroy', $jadwal->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Apakah Anda yakin ingin membatalkan jadwal ini?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="p-2.5 text-gray-400 hover:text-red-500 hover:bg-red-50 rounded-xl transition-all" title="Batalkan">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                </button>
                            </form>

                            <button onclick="document.getElementById('modal-{{ $jadwal->id }}').classList.remove('hidden')" class="px-5 py-2.5 bg-black text-white text-xs font-bold rounded-xl hover:bg-gray-800 transition-all shadow-sm">
                                Lihat Detail
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <div id="modal-{{ $jadwal->id }}" class="fixed inset-0 z-50 hidden bg-gray-900 bg-opacity-50 flex items-center justify-center p-4">
                <div class="bg-white rounded-3xl p-8 max-w-md w-full shadow-2xl relative">
                    <button onclick="document.getElementById('modal-{{ $jadwal->id }}').classList.add('hidden')" class="absolute top-4 right-4 text-gray-400 hover:text-gray-900">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                    <h3 class="text-2xl font-extrabold text-gray-900 mb-6">Detail Reservasi</h3>

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
                                <p class="text-gray-900 font-semibold">{{ \Carbon\Carbon::parse($jadwal->tanggal)->format('d M Y') }}</p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500 font-bold uppercase">Waktu</p>
                                <p class="text-gray-900 font-semibold">{{ $jadwal->waktu }}</p>
                            </div>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500 font-bold uppercase">Layanan & Dokter</p>
                            <p class="text-gray-900 font-semibold">{{ $jadwal->layanan }} ({{ $jadwal->dokter_id ?? '-' }})</p>
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
        @empty
            <div class="bg-gray-50 border-2 border-dashed border-gray-200 rounded-[2rem] p-16 text-center">
                <div class="w-20 h-20 bg-white shadow-sm rounded-3xl flex items-center justify-center mx-auto mb-6 text-gray-300">
                    <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-2">Belum Ada Jadwal</h3>
                <p class="text-gray-500 mb-8 max-w-xs mx-auto">Anda belum memiliki riwayat reservasi. Silakan buat janji temu melalui menu reservasi.</p>
                <a href="{{ route('portal') }}" class="inline-flex items-center gap-2 px-6 py-3 bg-teal-600 text-white font-bold rounded-2xl hover:bg-teal-700 transition-all">
                    Buat Reservasi Sekarang
                </a>
            </div>
        @endforelse
    </div>
</div>
