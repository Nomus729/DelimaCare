<style>
    .rm-stat { contain: layout paint style; }
    .rm-row { transition: background .15s; }
    .rm-row:hover { background: rgba(13,148,136,.04); }
    .dark .rm-row:hover { background: rgba(13,148,136,.07); }
    
    .rm-modal-inner {
        background: #fff;
        border-radius: 1.5rem;
        width: 100%;
        max-width: 42rem;
        max-height: calc(100dvh - 3rem);
        overflow-y: auto;
        display: flex;
        flex-direction: column;
        box-shadow: 0 30px 70px rgba(0,0,0,.22);
        animation: rmModalIn .25s cubic-bezier(.16,1,.3,1) both;
    }
    .dark .rm-modal-inner { background: #1E293B; }
    @keyframes rmModalIn {
        from { opacity:0; transform:scale(.95) translateY(15px); }
        to   { opacity:1; transform:scale(1) translateY(0); }
    }

    .rm-field label {
        display:block; font-size:.75rem; font-weight:800;
        text-transform:uppercase; letter-spacing:.05em;
        color:#64748b; margin-bottom:.4rem;
    }
    .dark .rm-field label { color:#94a3b8; }
    
    .rm-field input, .rm-field select, .rm-field textarea {
        width:100%; padding:.75rem 1rem;
        background:#f8fafc; border:1.5px solid #e2e8f0;
        border-radius:.75rem; font-size:.875rem; outline:none;
        transition:all .2s ease;
        color:#111827;
    }
    .dark .rm-field input, .dark .rm-field select, .dark .rm-field textarea {
        background:#0f172a; border-color:#334155; color:#f1f5f9;
    }
    .rm-field input:focus, .rm-field select:focus, .rm-field textarea:focus {
        border-color:#0d9488;
        background: #fff;
        box-shadow: 0 0 0 4px rgba(13,148,136,.1);
    }
    .dark .rm-field input:focus, .dark .rm-field select:focus, .dark .rm-field textarea:focus {
        background: #0f172a;
    }

    /* Custom filter pill style */
    .rm-filter-pill {
        transition: all .2s cubic-bezier(0.4, 0, 0.2, 1);
    }
    .rm-filter-pill:hover {
        transform: translateY(-1px);
    }
</style>

<div x-data="{
    showModal: false,
    editMode: false,
    rm: {
        id: '',
        nama_pasien: '',
        usia: '',
        no_telepon: '',
        alamat: '',
        golongan_darah: '',
        kategori: 'Kontrol Umum',
        usia_kehamilan_minggu: '',
        hpht: '',
        taksiran_persalinan: '',
        status_risiko: 'Rendah',
        status_kunjungan: 'Aktif',
        tekanan_darah: '',
        berat_badan: '',
        tinggi_badan: '',
        catatan_medis: '',
        diagnosis: '',
        tindakan: '',
        jadwal_kontrol_berikutnya: '',
        dokter_pemeriksa: ''
    },
    openAdd() {
        this.editMode = false;
        this.rm = {
            id: '', nama_pasien: '', usia: '', no_telepon: '', alamat: '', golongan_darah: '',
            kategori: 'Kontrol Umum', usia_kehamilan_minggu: '', hpht: '', taksiran_persalinan: '',
            status_risiko: 'Rendah', status_kunjungan: 'Aktif', tekanan_darah: '', berat_badan: '',
            tinggi_badan: '', catatan_medis: '', diagnosis: '', tindakan: '', jadwal_kontrol_berikutnya: '',
            dokter_pemeriksa: ''
        };
        this.showModal = true;
    },
    openEdit(data) {
        this.editMode = true;
        this.rm = { ...data };
        // Format dates if they exist
        if(this.rm.hpht) this.rm.hpht = this.rm.hpht.split('T')[0];
        if(this.rm.taksiran_persalinan) this.rm.taksiran_persalinan = this.rm.taksiran_persalinan.split('T')[0];
        if(this.rm.jadwal_kontrol_berikutnya) this.rm.jadwal_kontrol_berikutnya = this.rm.jadwal_kontrol_berikutnya.split('T')[0];
        this.showModal = true;
    },
    showDeleteModal: false,
    rmToDelete: { id: '', name: '' },
    confirmDelete(id, name) {
        this.rmToDelete = { id, name };
        this.showDeleteModal = true;
    },
    executeDelete() {
        const f = document.createElement('form'); f.method='POST';
        f.action=`{{ url('admin/rekam-medis') }}/${this.rmToDelete.id}`;
        const c=document.createElement('input'); c.type='hidden'; c.name='_token'; c.value='{{ csrf_token() }}';
        const m=document.createElement('input'); m.type='hidden'; m.name='_method'; m.value='DELETE';
        f.appendChild(c); f.appendChild(m); document.body.appendChild(f); f.submit();
    }
}">

    {{-- ── Header & Action ──────────────────────────────────────── --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-8">
        <div>
            <h2 class="text-3xl font-black text-gray-900 dark:text-white tracking-tight">Rekam Medis</h2>
            <p class="text-sm font-medium text-gray-500 dark:text-gray-400 mt-1">
                Pusat manajemen data kesehatan dan riwayat medis pasien DelimaCare.
            </p>
        </div>
        <button @click="openAdd()"
            class="inline-flex items-center gap-2.5 px-6 py-3 rounded-2xl text-sm font-bold text-white
                   bg-gradient-to-r from-teal-600 to-cyan-500 hover:from-teal-700 hover:to-cyan-600
                   shadow-lg shadow-teal-500/25 hover:shadow-teal-500/40 hover:-translate-y-0.5 transition-all duration-300">
            <div class="w-5 h-5 rounded-lg bg-white/20 flex items-center justify-center">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 4v16m8-8H4"/>
                </svg>
            </div>
            Tambah Rekam Medis
        </button>
    </div>

    {{-- ── Stats Cards ──────────────────────────────────────────── --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
        @php
            $statConfig = [
                ['label' => 'Total Rekam Medis', 'key' => 'total', 'color' => 'teal', 'icon' => 'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z'],
                ['label' => 'Pasien Kehamilan', 'key' => 'kehamilan', 'color' => 'rose', 'icon' => 'M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z'],
                ['label' => 'Layanan KB', 'key' => 'kb', 'color' => 'cyan', 'icon' => 'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z'],
                ['label' => 'Risiko Tinggi', 'key' => 'risiko_tinggi', 'color' => 'orange', 'icon' => 'M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z'],
            ];
        @endphp
        @foreach($statConfig as $s)
            <div class="rm-stat bg-white dark:bg-[#1E293B] border border-gray-100 dark:border-gray-800 rounded-3xl p-5 shadow-sm relative overflow-hidden group">
                <div class="absolute -right-4 -top-4 w-24 h-24 bg-{{ $s['color'] }}-500/5 rounded-full blur-2xl group-hover:bg-{{ $s['color'] }}-500/10 transition-colors duration-500"></div>
                <div class="flex items-center gap-4 relative">
                    <div class="w-12 h-12 rounded-2xl bg-{{ $s['color'] }}-50 dark:bg-{{ $s['color'] }}-900/20 flex items-center justify-center flex-shrink-0 group-hover:scale-110 transition-transform duration-300">
                        <svg class="w-6 h-6 text-{{ $s['color'] }}-600 dark:text-{{ $s['color'] }}-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $s['icon'] }}"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-[10px] font-black uppercase tracking-widest text-gray-400 dark:text-gray-500 mb-0.5">{{ $s['label'] }}</p>
                        <p class="text-2xl font-black text-gray-900 dark:text-white">{{ $rmStats[$s['key']] }}</p>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    {{-- ── Search & Filters ────────────────────────────────────── --}}
    <div class="bg-white/50 dark:bg-[#1E293B]/50 backdrop-blur-md border border-gray-100 dark:border-gray-800 rounded-3xl p-4 mb-6">
        <form action="{{ route('admin.dashboard') }}" method="GET" class="flex flex-col lg:flex-row gap-4 items-center">
            <input type="hidden" name="tab" value="rekam_medis">
            
            {{-- Search --}}
            <div class="relative w-full lg:flex-1">
                <svg class="w-5 h-5 text-gray-400 absolute left-4 top-1/2 -translate-y-1/2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
                <input type="text" name="rm_search" value="{{ $rmSearch }}" placeholder="Cari No. RM, Nama Pasien, atau Dokter..." 
                       class="w-full pl-12 pr-4 py-3 bg-white dark:bg-[#0f172a] border border-gray-200 dark:border-gray-700 rounded-2xl focus:ring-4 focus:ring-teal-500/10 outline-none text-sm font-medium transition-all">
            </div>

            {{-- Categories --}}
            <div class="flex flex-wrap items-center gap-2 w-full lg:w-auto">
                @foreach($rmKategoriCounts as $kat => $count)
                    <a href="{{ route('admin.dashboard', ['tab' => 'rekam_medis', 'rm_kategori' => $kat, 'rm_search' => $rmSearch]) }}"
                       class="rm-filter-pill px-4 py-2.5 rounded-xl text-xs font-bold flex items-center gap-2 border transition-all
                              {{ $rmKategori === (string)$kat 
                                 ? 'bg-teal-600 border-teal-600 text-white shadow-md shadow-teal-500/20' 
                                 : 'bg-white dark:bg-[#0f172a] border-gray-200 dark:border-gray-700 text-gray-500 dark:text-gray-400 hover:border-teal-500 hover:text-teal-600' }}">
                        {{ $kat ?: 'Semua' }}
                        <span class="px-1.5 py-0.5 rounded-md text-[10px] {{ $rmKategori === (string)$kat ? 'bg-white/20 text-white' : 'bg-gray-100 dark:bg-gray-800 text-gray-400' }}">
                            {{ $count }}
                        </span>
                    </a>
                @endforeach
            </div>
        </form>
    </div>

    {{-- ── Main Table ──────────────────────────────────────────── --}}
    <div class="bg-white dark:bg-[#1E293B] border border-gray-100 dark:border-gray-800 rounded-[2rem] shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50/50 dark:bg-gray-800/40 border-b border-gray-100 dark:border-gray-800">
                        <th class="px-6 py-5 text-[10px] font-black uppercase tracking-widest text-gray-400 dark:text-gray-500">Pasien & No. RM</th>
                        <th class="px-6 py-5 text-[10px] font-black uppercase tracking-widest text-gray-400 dark:text-gray-500 text-center">Kategori</th>
                        <th class="px-6 py-5 text-[10px] font-black uppercase tracking-widest text-gray-400 dark:text-gray-500">Risiko</th>
                        <th class="px-6 py-5 text-[10px] font-black uppercase tracking-widest text-gray-400 dark:text-gray-500">Status</th>
                        <th class="px-6 py-5 text-[10px] font-black uppercase tracking-widest text-gray-400 dark:text-gray-500">Kontrol</th>
                        <th class="px-6 py-5 text-[10px] font-black uppercase tracking-widest text-gray-400 dark:text-gray-500 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-800 text-sm">
                    @forelse($rekamMedis as $item)
                        <tr class="rm-row group transition-colors">
                            <td class="px-6 py-5">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-full bg-gradient-to-br from-teal-50 to-teal-100 dark:from-teal-900/30 dark:to-teal-900/10 flex items-center justify-center text-teal-600 dark:text-teal-400 font-bold text-xs shadow-inner">
                                        {{ strtoupper(substr($item->nama_pasien, 0, 2)) }}
                                    </div>
                                    <div>
                                        <p class="font-bold text-gray-900 dark:text-white leading-none">{{ $item->nama_pasien }}</p>
                                        <p class="text-[10px] font-bold text-gray-400 dark:text-gray-500 mt-1.5 flex items-center gap-1.5">
                                            <span class="bg-gray-100 dark:bg-gray-800 px-1.5 py-0.5 rounded uppercase">{{ $item->no_rekam_medis }}</span>
                                            <span>•</span>
                                            <span>{{ $item->usia }} Tahun</span>
                                        </p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-5 text-center">
                                <span class="px-3 py-1.5 bg-gray-100 dark:bg-gray-800 text-gray-600 dark:text-gray-400 rounded-xl text-[10px] font-black uppercase tracking-wider">
                                    {{ $item->kategori }}
                                </span>
                            </td>
                            <td class="px-6 py-5">
                                @php
                                    $risikoColor = match($item->status_risiko) {
                                        'Tinggi' => 'rose',
                                        'Sedang' => 'amber',
                                        default => 'emerald'
                                    };
                                @endphp
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-{{ $risikoColor }}-50 dark:bg-{{ $risikoColor }}-900/20 text-{{ $risikoColor }}-700 dark:text-{{ $risikoColor }}-400 text-[10px] font-black uppercase rounded-full border border-{{ $risikoColor }}-100 dark:border-{{ $risikoColor }}-900/30">
                                    <span class="w-1.5 h-1.5 rounded-full bg-{{ $risikoColor }}-500 {{ $item->status_risiko === 'Tinggi' ? 'animate-pulse' : '' }}"></span>
                                    {{ $item->status_risiko }}
                                </span>
                            </td>
                            <td class="px-6 py-5">
                                <span class="text-[11px] font-bold {{ $item->status_kunjungan === 'Aktif' ? 'text-teal-600' : 'text-gray-400' }}">
                                    {{ $item->status_kunjungan }}
                                </span>
                            </td>
                            <td class="px-6 py-5">
                                <p class="text-xs font-bold text-gray-900 dark:text-white">
                                    {{ $item->jadwal_kontrol_berikutnya ? $item->jadwal_kontrol_berikutnya->format('d M Y') : '—' }}
                                </p>
                                <p class="text-[10px] text-gray-400 mt-1 truncate max-w-[120px]">
                                    {{ $item->dokter_pemeriksa ?: 'Belum ditentukan' }}
                                </p>
                            </td>
                            <td class="px-6 py-5">
                                <div class="flex justify-end gap-2">
                                    <button @click="openEdit({{ $item->toJson() }})" 
                                            class="p-2.5 rounded-xl border border-gray-100 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-400 hover:text-teal-600 hover:border-teal-200 dark:hover:border-teal-700 transition-all shadow-sm">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                        </svg>
                                    </button>
                                    <button @click="confirmDelete({{ $item->id }}, '{{ addslashes($item->nama_pasien) }}')"
                                            class="p-2.5 rounded-xl border border-rose-50 dark:border-rose-900/50 bg-white dark:bg-gray-800 text-rose-400 hover:bg-rose-50 dark:hover:bg-rose-900/20 transition-all shadow-sm">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                        </svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-20 text-center">
                                <div class="w-20 h-20 bg-gray-50 dark:bg-gray-800/50 rounded-full flex items-center justify-center mx-auto mb-4">
                                    <svg class="w-10 h-10 text-gray-200 dark:text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                    </svg>
                                </div>
                                <h3 class="text-base font-black text-gray-900 dark:text-white mb-1">Belum Ada Rekam Medis</h3>
                                <p class="text-xs text-gray-400 dark:text-gray-500">Mulai buat rekam medis untuk mengelola riwayat kesehatan pasien.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        {{-- Pagination --}}
        @if($rekamMedis->hasPages())
            <div class="px-6 py-5 bg-gray-50/50 dark:bg-gray-800/40 border-t border-gray-100 dark:border-gray-800">
                {{ $rekamMedis->links() }}
            </div>
        @endif
    </div>

    {{-- ── Form Modal ──────────────────────────────────────────── --}}
    <template x-teleport="body">
        <div x-show="showModal" x-cloak
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="fixed inset-0 z-[999] flex items-center justify-center p-4">
            
            <div class="absolute inset-0 bg-gray-900/60 backdrop-blur-sm" @click="showModal = false"></div>
            
            <div class="rm-modal-inner relative" @click.stop>
                {{-- Header --}}
                <div class="flex items-center justify-between px-8 py-6 border-b border-gray-100 dark:border-gray-800">
                    <div>
                        <h3 class="text-xl font-black text-gray-900 dark:text-white" x-text="editMode ? 'Edit Rekam Medis' : 'Tambah Rekam Medis'"></h3>
                        <p class="text-[11px] font-bold text-gray-400 dark:text-gray-500 uppercase tracking-widest mt-1">Lengkapi informasi data kesehatan pasien</p>
                    </div>
                    <button @click="showModal = false" class="w-10 h-10 rounded-2xl flex items-center justify-center text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-800 transition-all">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>

                {{-- Form --}}
                <form :action="editMode ? '{{ url('admin/rekam-medis') }}/' + rm.id : '{{ route('admin.rekam-medis.store') }}'" 
                      method="POST" class="p-8 space-y-8">
                    @csrf
                    <template x-if="editMode">
                        <input type="hidden" name="_method" value="PUT">
                    </template>

                    {{-- Section 1: Identitas --}}
                    <div>
                        <h4 class="text-[10px] font-black uppercase tracking-widest text-teal-600 mb-5 pb-2 border-b border-teal-500/10">1. Identitas Pasien</h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                            <div class="rm-field">
                                <label>Nama Lengkap <span class="text-rose-500">*</span></label>
                                <input type="text" name="nama_pasien" x-model="rm.nama_pasien" required placeholder="Contoh: Siti Aminah">
                            </div>
                            <div class="rm-field">
                                <label>Usia <span class="text-rose-500">*</span></label>
                                <input type="number" name="usia" x-model="rm.usia" required placeholder="Contoh: 28">
                            </div>
                            <div class="rm-field">
                                <label>No. Telepon</label>
                                <input type="text" name="no_telepon" x-model="rm.no_telepon" placeholder="08xxxx">
                            </div>
                            <div class="rm-field">
                                <label>Golongan Darah</label>
                                <select name="golongan_darah" x-model="rm.golongan_darah">
                                    <option value="">Pilih</option>
                                    <option value="A">A</option>
                                    <option value="B">B</option>
                                    <option value="AB">AB</option>
                                    <option value="O">O</option>
                                </select>
                            </div>
                            <div class="rm-field md:col-span-2">
                                <label>Alamat Pasien</label>
                                <textarea name="alamat" x-model="rm.alamat" rows="2" placeholder="Alamat lengkap..."></textarea>
                            </div>
                        </div>
                    </div>

                    {{-- Section 2: Layanan --}}
                    <div>
                        <h4 class="text-[10px] font-black uppercase tracking-widest text-teal-600 mb-5 pb-2 border-b border-teal-500/10">2. Kategori Layanan</h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                            <div class="rm-field">
                                <label>Kategori <span class="text-rose-500">*</span></label>
                                <select name="kategori" x-model="rm.kategori" required>
                                    <option value="Kehamilan">Kehamilan</option>
                                    <option value="Keluarga Berencana">Keluarga Berencana</option>
                                    <option value="Kontrol Umum">Kontrol Umum</option>
                                    <option value="Konsultasi">Konsultasi</option>
                                </select>
                            </div>
                            <div class="rm-field">
                                <label>Status Risiko <span class="text-rose-500">*</span></label>
                                <select name="status_risiko" x-model="rm.status_risiko" required>
                                    <option value="Rendah">Rendah</option>
                                    <option value="Sedang">Sedang</option>
                                    <option value="Tinggi">Tinggi</option>
                                </select>
                            </div>
                            
                            {{-- Conditional Pregnancy Fields --}}
                            <template x-if="rm.kategori === 'Kehamilan'">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-5 md:col-span-2">
                                    <div class="rm-field">
                                        <label>Usia Kehamilan (Minggu)</label>
                                        <input type="number" name="usia_kehamilan_minggu" x-model="rm.usia_kehamilan_minggu">
                                    </div>
                                    <div class="rm-field">
                                        <label>HPHT</label>
                                        <input type="date" name="hpht" x-model="rm.hpht">
                                    </div>
                                </div>
                            </template>
                        </div>
                    </div>

                    {{-- Section 3: Pemeriksaan --}}
                    <div>
                        <h4 class="text-[10px] font-black uppercase tracking-widest text-teal-600 mb-5 pb-2 border-b border-teal-500/10">3. Hasil Pemeriksaan</h4>
                        <div class="grid grid-cols-3 gap-5 mb-5">
                            <div class="rm-field">
                                <label>TD (mmHg)</label>
                                <input type="text" name="tekanan_darah" x-model="rm.tekanan_darah" placeholder="120/80">
                            </div>
                            <div class="rm-field">
                                <label>BB (kg)</label>
                                <input type="number" step="0.1" name="berat_badan" x-model="rm.berat_badan" placeholder="60">
                            </div>
                            <div class="rm-field">
                                <label>TB (cm)</label>
                                <input type="number" step="0.1" name="tinggi_badan" x-model="rm.tinggi_badan" placeholder="160">
                            </div>
                        </div>
                        <div class="space-y-5">
                            <div class="rm-field">
                                <label>Diagnosis</label>
                                <textarea name="diagnosis" x-model="rm.diagnosis" rows="2" placeholder="Diagnosis dokter..."></textarea>
                            </div>
                            <div class="rm-field">
                                <label>Tindakan / Terapi</label>
                                <textarea name="tindakan" x-model="rm.tindakan" rows="2" placeholder="Tindakan yang dilakukan..."></textarea>
                            </div>
                        </div>
                    </div>

                    {{-- Footer --}}
                    <div class="flex gap-4 pt-4 border-t border-gray-100 dark:border-gray-800">
                        <button type="button" @click="showModal = false"
                                class="flex-1 px-6 py-4 bg-gray-50 dark:bg-gray-800 text-gray-500 font-bold rounded-2xl hover:bg-gray-100 transition-all">
                            Batal
                        </button>
                        <button type="submit"
                                class="flex-[2] px-6 py-4 bg-gradient-to-r from-teal-600 to-cyan-500 text-white font-black rounded-2xl shadow-lg shadow-teal-500/20 hover:shadow-teal-500/40 transition-all">
                            Simpan Rekam Medis
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </template>

    {{-- ── Delete Modal ────────────────────────────────────────── --}}
    <template x-teleport="body">
        <div x-show="showDeleteModal" x-cloak
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="fixed inset-0 z-[999] flex items-center justify-center p-4">
            
            <div class="absolute inset-0 bg-gray-900/60 backdrop-blur-sm" @click="showDeleteModal = false"></div>
            
            <div class="relative bg-white dark:bg-[#1E293B] w-full max-w-sm rounded-[2rem] shadow-2xl p-8 text-center" @click.stop>
                <div class="w-20 h-20 bg-rose-50 dark:bg-rose-900/20 rounded-full flex items-center justify-center mx-auto mb-6">
                    <svg class="w-10 h-10 text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                    </svg>
                </div>
                <h3 class="text-xl font-black text-gray-900 dark:text-white mb-2">Hapus Rekam Medis?</h3>
                <p class="text-xs font-medium text-gray-500 dark:text-gray-400 mb-8 leading-relaxed">
                    Anda akan menghapus rekam medis pasien <span class="text-gray-900 dark:text-white font-bold" x-text="rmToDelete.name"></span>. Data yang dihapus tidak dapat dikembalikan.
                </p>
                <div class="flex gap-3">
                    <button @click="showDeleteModal = false" 
                            class="flex-1 px-4 py-3 bg-gray-50 dark:bg-gray-800 text-gray-500 font-bold rounded-xl hover:bg-gray-100 transition-all text-xs">
                        Batal
                    </button>
                    <button @click="executeDelete()" 
                            class="flex-1 px-4 py-3 bg-rose-600 text-white font-bold rounded-xl shadow-lg shadow-rose-500/20 hover:bg-rose-700 transition-all text-xs">
                        Hapus Data
                    </button>
                </div>
            </div>
        </div>
    </template>

</div>
