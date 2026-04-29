<div>
    <div class="flex justify-between items-center mb-6">
        <div>
            <h2 class="text-3xl font-extrabold text-gray-900 mb-1">Manajemen Reservasi</h2>
            <p class="text-gray-500">Kelola jadwal dan reservasi pasien</p>
        </div>

        <span class="px-4 py-2 bg-blue-50 text-blue-600 text-sm font-bold rounded-lg border border-blue-100 shadow-sm">
            Total: {{ isset($semuaReservasi) ? $semuaReservasi->count() : 0 }} Pasien
        </span>
    </div>

    @if(session('success'))
        <div class="mb-6 p-4 bg-green-100 text-green-700 rounded-xl border border-green-200 flex items-center gap-3 animate-fade-in">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
            <span class="font-bold">{{ session('success') }}</span>
        </div>
    @endif

    <div class="space-y-4">
        @if(isset($semuaReservasi) && $semuaReservasi->count() > 0)
            @foreach($semuaReservasi as $item)
            <div class="bg-white border-l-4 border-blue-500 border-t border-r border-b border-gray-200 rounded-xl p-5 shadow-sm hover:shadow-md transition-shadow">
                <div class="flex justify-between items-start mb-4">
                    <div class="flex items-center gap-4">
                        <div class="p-3 bg-blue-50 text-blue-600 rounded-lg font-extrabold text-lg">
                            {{ $item->waktu }}
                        </div>
                        <div>
                            <h4 class="font-bold text-gray-900 text-xl">{{ $item->nama }}</h4>
                            <div class="flex items-center gap-2 mt-1">
                                <span class="text-xs font-semibold text-gray-600 bg-gray-100 px-2 py-1 rounded-md">{{ $item->layanan }}</span>
                                <span class="text-xs text-gray-500">• {{ \Carbon\Carbon::parse($item->tanggal)->format('d M Y') }}</span>
                            </div>
                            @if($item->keluhan)
                                <p class="text-xs text-red-500 mt-2 italic flex items-center gap-1">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                                    {{ $item->keluhan }}
                                </p>
                            @endif
                        </div>
                    </div>
                    <div class="text-right flex flex-col items-end gap-2">
                        <span class="px-3 py-1 {{ ($item->status ?? 'Menunggu') == 'Menunggu' ? 'bg-yellow-100 text-yellow-700' : 'bg-green-100 text-green-700' }} text-xs font-bold rounded-full">
                            {{ $item->status ?? 'Menunggu' }}
                        </span>

                        <a href="https://wa.me/{{ preg_replace('/^0/', '62', $item->phone) }}" target="_blank" class="text-[11px] font-semibold text-green-600 hover:underline flex items-center gap-1">
                            <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 24 24"><path d="M12.031 6.172c-3.181 0-5.767 2.586-5.768 5.766-.001 1.298.38 2.27 1.019 3.287l-.582 2.128 2.182-.573c.978.58 1.911.928 3.145.929 3.178 0 5.767-2.587 5.768-5.766.001-3.187-2.575-5.77-5.764-5.771zm3.392 8.244c-.144.405-.837.774-1.17.824-.299.045-.677.063-1.092-.069-.252-.08-.575-.187-.988-.365-1.739-.751-2.874-2.502-2.961-2.617-.087-.116-.708-.94-.708-1.793s.448-1.273.607-1.446c.159-.173.346-.217.462-.217l.332.006c.106.005.249-.04.39.298.144.347.491 1.2.534 1.287.043.087.072.188.014.304-.058.116-.087.188-.173.289l-.26.304c-.087.086-.177.18-.076.354.101.174.449.741.964 1.201.662.591 1.221.774 1.394.86s.274.072.376-.043c.101-.116.433-.506.549-.68.116-.173.231-.145.39-.087s1.011.477 1.184.564.289.13.332.202c.045.072.045.419-.099.824z"/></svg>
                            {{ $item->phone }}
                        </a>
                    </div>
                </div>

                <div class="flex gap-3 mt-4 pt-4 border-t border-gray-100">
                    @if(($item->status ?? 'Menunggu') == 'Menunggu')
                        <form action="{{ route('admin.reservasi.konfirmasi', $item->id) }}" method="POST">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="px-4 py-2 bg-black text-white text-xs font-bold rounded-lg hover:bg-gray-800 transition-colors">
                                Konfirmasi
                            </button>
                        </form>
                    @endif

                    <form action="{{ route('admin.reservasi.batal', $item->id) }}" method="POST" onsubmit="return confirm('Yakin mau ngebatalin/ngapus jadwal si {{ $item->nama }}?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="px-4 py-2 bg-red-50 text-red-600 text-xs font-bold rounded-lg hover:bg-red-100 transition-colors">
                            Batalkan
                        </button>
                    </form>
                </div>
            </div>
            @endforeach
        @else
            <div class="bg-gray-50 border border-dashed border-gray-300 rounded-2xl p-12 text-center">
                <div class="w-16 h-16 bg-gray-200 text-gray-400 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                </div>
                <h3 class="text-lg font-bold text-gray-900 mb-1">Belum Ada Reservasi</h3>
                <p class="text-gray-500 text-sm">Jadwal konsultasi pasien masih kosong untuk saat ini.</p>
            </div>
        @endif
    </div>
</div>
