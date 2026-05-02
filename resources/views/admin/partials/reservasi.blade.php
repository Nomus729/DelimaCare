<div x-data="{ showAddModal: false }">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h2 class="text-3xl font-extrabold text-gray-900 mb-1">Manajemen Reservasi</h2>
            <p class="text-gray-500">Kelola jadwal dan antrean pasien secara otomatis</p>
        </div>

        <div class="flex items-center gap-3">
            <button @click="showAddModal = true" class="bg-black text-white px-5 py-2.5 rounded-xl text-sm font-bold flex items-center gap-2 hover:bg-gray-800 transition-all shadow-lg shadow-black/10">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"></path></svg>
                Tambah Reservasi
            </button>
            <span class="px-4 py-2.5 bg-blue-50 text-blue-600 text-sm font-black rounded-xl border border-blue-100 shadow-sm">
                Total: {{ isset($semuaReservasi) ? $semuaReservasi->count() : 0 }}
            </span>
        </div>
    </div>

    @if(session('success'))
        <div class="mb-6 p-4 bg-emerald-100 text-emerald-700 rounded-2xl border border-emerald-200 flex items-center gap-3 animate-fade-in shadow-sm">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
            <span class="font-bold">{{ session('success') }}</span>
        </div>
    @endif

    <div class="space-y-4">
        @if(isset($semuaReservasi) && $semuaReservasi->count() > 0)
            @foreach($semuaReservasi as $item)
            <div class="bg-white border border-gray-100 rounded-2xl p-6 shadow-sm hover:shadow-md transition-all group relative overflow-hidden">
                {{-- No Urut Badge --}}
                <div class="absolute right-0 top-0 w-16 h-16 bg-blue-50 dark:bg-blue-900/10 flex items-center justify-center rounded-bl-3xl">
                    <span class="text-2xl font-black text-blue-600">#{{ $item->queue_number ?? '-' }}</span>
                </div>

                <div class="flex justify-between items-start mb-4">
                    <div class="flex items-center gap-5">
                        <div class="p-4 bg-gradient-to-br from-blue-500 to-cyan-500 text-white rounded-2xl font-black text-center shadow-lg shadow-blue-500/20 min-w-[90px]">
                            <p class="text-[10px] uppercase tracking-widest opacity-80 mb-1">Estimasi</p>
                            <p class="text-xl leading-none">{{ $item->estimated_time ? \Carbon\Carbon::parse($item->estimated_time)->format('H:i') : $item->waktu }}</p>
                        </div>
                        <div>
                            <h4 class="font-black text-gray-900 text-2xl tracking-tight">{{ $item->nama }}</h4>
                            <div class="flex items-center gap-3 mt-2">
                                <span class="text-[10px] font-black text-blue-600 bg-blue-50 px-2.5 py-1 rounded-lg uppercase tracking-wider">{{ $item->layanan }}</span>
                                <span class="text-xs font-bold text-gray-400 flex items-center gap-1.5">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                    {{ \Carbon\Carbon::parse($item->tanggal)->format('d M Y') }}
                                </span>
                            </div>
                            <p class="text-xs font-bold text-teal-600 mt-2 flex items-center gap-1.5">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                                Dokter: {{ $item->dokter_id ?? 'Belum ditentukan' }}
                            </p>
                            @if($item->keluhan)
                                <p class="text-xs text-rose-500 mt-3 font-medium flex items-center gap-1.5 p-2 bg-rose-50 rounded-lg border border-rose-100 w-fit">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                                    Keluhan: {{ $item->keluhan }}
                                </p>
                            @endif
                        </div>
                    </div>
                    
                    <div class="text-right flex flex-col items-end gap-3 mt-1">
                        @php
                            $statusColor = match($item->status) {
                                'Menunggu' => 'amber',
                                'Dikonfirmasi' => 'blue',
                                'Datang' => 'emerald',
                                'Tidak Datang' => 'rose',
                                default => 'gray'
                            };
                        @endphp
                        <span class="px-4 py-1.5 bg-{{ $statusColor }}-50 text-{{ $statusColor }}-700 text-[10px] font-black uppercase tracking-widest rounded-full border border-{{ $statusColor }}-100">
                            {{ $item->status ?? 'Menunggu' }}
                        </span>

                        <a href="https://wa.me/{{ preg_replace('/^0/', '62', $item->phone) }}" target="_blank" 
                           class="px-3 py-1.5 rounded-xl border border-emerald-100 bg-emerald-50 text-emerald-600 text-xs font-black flex items-center gap-2 hover:bg-emerald-600 hover:text-white transition-all">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M12.031 6.172c-3.181 0-5.767 2.586-5.768 5.766-.001 1.298.38 2.27 1.019 3.287l-.582 2.128 2.182-.573c.978.58 1.911.928 3.145.929 3.178 0 5.767-2.587 5.768-5.766.001-3.187-2.575-5.77-5.764-5.771zm3.392 8.244c-.144.405-.837.774-1.17.824-.299.045-.677.063-1.092-.069-.252-.08-.575-.187-.988-.365-1.739-.751-2.874-2.502-2.961-2.617-.087-.116-.708-.94-.708-1.793s.448-1.273.607-1.446c.159-.173.346-.217.462-.217l.332.006c.106.005.249-.04.39.298.144.347.491 1.2.534 1.287.043.087.072.188.014.304-.058.116-.087.188-.173.289l-.26.304c-.087.086-.177.18-.076.354.101.174.449.741.964 1.201.662.591 1.221.774 1.394.86s.274.072.376-.043c.101-.116.433-.506.549-.68.116-.173.231-.145.39-.087s1.011.477 1.184.564.289.13.332.202c.045.072.045.419-.099.824z"/></svg>
                            Hubungi
                        </a>
                    </div>
                </div>

                <div class="flex gap-3 mt-6 pt-5 border-t border-gray-50">
                    @if(($item->status ?? 'Menunggu') == 'Menunggu')
                        <form action="{{ route('admin.reservasi.konfirmasi', $item->id) }}" method="POST">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="px-6 py-2.5 bg-black text-white text-xs font-black rounded-xl hover:bg-gray-800 transition-all shadow-md">
                                Konfirmasi Jadwal
                            </button>
                        </form>
                    @elseif($item->status == 'Dikonfirmasi')
                        <button @click="$dispatch('open-rm-modal', { reservasi_id: {{ $item->id }}, nama_pasien: '{{ addslashes($item->nama) }}', phone: '{{ $item->phone }}', layanan: '{{ $item->layanan }}' })" 
                                class="px-6 py-2.5 bg-emerald-600 text-white text-xs font-black rounded-xl hover:bg-emerald-700 transition-all shadow-md shadow-emerald-500/20">
                            Selesaikan & Buat RM
                        </button>
                    @endif

                    <form action="{{ route('admin.reservasi.batal', $item->id) }}" method="POST" onsubmit="return confirm('Yakin mau ngebatalin jadwal si {{ $item->nama }}?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="px-6 py-2.5 bg-rose-50 text-rose-600 text-xs font-black rounded-xl hover:bg-rose-100 transition-all">
                            Batalkan
                        </button>
                    </form>
                </div>
            </div>
            @endforeach
        @else
            <div class="bg-white border border-dashed border-gray-200 rounded-[2rem] p-20 text-center shadow-sm">
                <div class="w-24 h-24 bg-gray-50 text-gray-300 rounded-full flex items-center justify-center mx-auto mb-6">
                    <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                </div>
                <h3 class="text-2xl font-black text-gray-900 mb-2 tracking-tight">Belum Ada Reservasi</h3>
                <p class="text-gray-400 text-sm font-medium">Jadwal konsultasi pasien masih kosong untuk saat ini.</p>
            </div>
        @endif
    </div>

    {{-- MODAL TAMBAH RESERVASI --}}
    <template x-teleport="body">
        <div x-show="showAddModal" x-cloak
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="fixed inset-0 z-[999] flex items-center justify-center p-4">
            
            <div class="absolute inset-0 bg-gray-900/60 backdrop-blur-sm" @click="showAddModal = false"></div>
            
            <div class="relative bg-white dark:bg-[#1E293B] w-full max-w-lg rounded-[2.5rem] shadow-2xl overflow-hidden" @click.stop>
                <div class="px-10 py-8 border-b border-gray-100">
                    <h3 class="text-2xl font-black text-gray-900">Tambah Reservasi Manual</h3>
                    <p class="text-sm text-gray-400 font-medium mt-1">Masukkan data pasien untuk membuat jadwal antrean baru.</p>
                </div>

                <form action="{{ route('admin.reservasi.store_admin') }}" method="POST" class="p-10 space-y-6">
                    @csrf
                    <div class="grid grid-cols-2 gap-6">
                        <div class="col-span-2">
                            <label class="block text-[10px] font-black uppercase tracking-widest text-gray-400 mb-2">Nama Pasien</label>
                            <input type="text" name="nama" required class="w-full px-5 py-4 bg-gray-50 border border-gray-100 rounded-2xl outline-none focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 transition-all font-bold">
                        </div>
                        <div>
                            <label class="block text-[10px] font-black uppercase tracking-widest text-gray-400 mb-2">No. HP / WhatsApp</label>
                            <input type="text" name="phone" required class="w-full px-5 py-4 bg-gray-50 border border-gray-100 rounded-2xl outline-none focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 transition-all font-bold">
                        </div>
                        <div>
                            <label class="block text-[10px] font-black uppercase tracking-widest text-gray-400 mb-2">Layanan</label>
                            <select name="layanan" required class="w-full px-5 py-4 bg-gray-50 border border-gray-100 rounded-2xl outline-none focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 transition-all font-bold appearance-none">
                                <option value="Kontrol Kehamilan">Kontrol Kehamilan</option>
                                <option value="Keluarga Berencana">Keluarga Berencana</option>
                                <option value="Pemeriksaan Umum">Pemeriksaan Umum</option>
                                <option value="Imunisasi">Imunisasi</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-[10px] font-black uppercase tracking-widest text-gray-400 mb-2">Pilih Dokter</label>
                            <select name="dokter_id" class="w-full px-5 py-4 bg-gray-50 border border-gray-100 rounded-2xl outline-none focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 transition-all font-bold appearance-none">
                                <option value="">Pilih Dokter</option>
                                @foreach($doctors as $doc)
                                    <option value="{{ $doc->nama }}">{{ $doc->nama }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-span-2">
                            <label class="block text-[10px] font-black uppercase tracking-widest text-gray-400 mb-2">Tanggal Reservasi</label>
                            <input type="date" name="tanggal" required value="{{ date('Y-m-d') }}" class="w-full px-5 py-4 bg-gray-50 border border-gray-100 rounded-2xl outline-none focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 transition-all font-bold">
                        </div>
                        <div class="col-span-2">
                            <label class="block text-[10px] font-black uppercase tracking-widest text-gray-400 mb-2">Keluhan (Opsional)</label>
                            <textarea name="keluhan" rows="3" class="w-full px-5 py-4 bg-gray-50 border border-gray-100 rounded-2xl outline-none focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 transition-all font-bold" placeholder="Tulis keluhan jika ada..."></textarea>
                        </div>
                    </div>

                    <div class="flex gap-4 pt-6">
                        <button type="button" @click="showAddModal = false" class="flex-1 px-8 py-4 bg-gray-100 text-gray-500 font-black rounded-2xl hover:bg-gray-200 transition-all">Batal</button>
                        <button type="submit" class="flex-[2] px-8 py-4 bg-black text-white font-black rounded-2xl shadow-lg shadow-black/20 hover:bg-gray-800 transition-all">Simpan Jadwal</button>
                    </div>
                </form>
            </div>
        </div>
    </template>
</div>
