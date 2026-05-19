<style>
    .rm-stat { contain: layout paint style; }
    .rm-row { transition: background .15s; }
    .rm-row:hover { background: rgba(13,148,136,.04); }
    .dark .rm-row:hover { background: rgba(13,148,136,.07); }

    .rm-modal-inner {
        background: #fff;
        border-radius: 2rem;
        width: 100%;
        max-width: 38rem;
        max-height: calc(100dvh - 2rem);
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
    isSubmitting: false,
    errors: {},
    activeFilter: 'all',
    rm: {
        id: '',
        reservasi_id: '',
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
        catatan_pasien: '',
        diagnosis: '',
        tindakan: '',
        jadwal_kontrol_berikutnya: '',
        dokter_pemeriksa: ''
    },
    openAdd(prefill = null) {
        this.editMode = false;
        this.rm = {
            id: '', reservasi_id: '', nama_pasien: '', usia: '', no_telepon: '', alamat: '', golongan_darah: '',
            kategori: 'Kontrol Umum', usia_kehamilan_minggu: '', hpht: '', taksiran_persalinan: '',
            status_risiko: 'Rendah', status_kunjungan: 'Aktif', tekanan_darah: '', berat_badan: '',
            tinggi_badan: '', catatan_medis: '', catatan_pasien: '', diagnosis: '', tindakan: '', jadwal_kontrol_berikutnya: '',
            dokter_pemeriksa: ''
        };
        if (prefill) {
            this.rm.reservasi_id = prefill.reservasi_id || prefill.id || '';
            this.rm.nama_pasien  = prefill.nama_pasien  || '';
            this.rm.no_telepon   = prefill.phone        || '';

            // 🔥 OTOMATIS NGAMBIL NAMA DOKTER 🔥
            this.rm.dokter_pemeriksa = prefill.dokter_id || prefill.dokter?.nama_dokter || '';

            // Mapping nama layanan reservasi → kategori rekam medis
            const layananMap = {
                'Kontrol Kehamilan':       'Kehamilan',
                'Pemeriksaan Kehamilan':   'Kehamilan',
                'ANC':                     'Kehamilan',
                'Keluarga Berencana':      'Keluarga Berencana',
                'KB':                      'Keluarga Berencana',
                'Imunisasi':               'Imunisasi',
                'Pemeriksaan Umum':        'Kontrol Umum',
                'Umum':                    'Kontrol Umum',
                'Konsultasi':              'Konsultasi',
            };
            const layanan = prefill.layanan || '';
            this.rm.kategori = layananMap[layanan] || 'Kontrol Umum';
        }
        this.showModal = true;
    },
    async submitForm(e) {
        this.isSubmitting = true;
        this.errors = {};
        const url = this.editMode ? `{{ url('admin/rekam-medis') }}/${this.rm.id}` : `{{ route('admin.rekam-medis.store') }}`;
        const formData = new FormData(e.target);
        if(this.editMode) formData.set('_method', 'PUT');

        try {
            const resp = await fetch(url, {
                method: 'POST',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                },
                body: formData
            });

            if (resp.status === 422) {
                const data = await resp.json();
                this.errors = data.errors;
            } else if (resp.ok) {
                const result = await resp.json();
                this.showModal = false; // Tutup form rekam medis
                
                // Buka otomatis form resep medis dengan sedikit delay untuk transisi
                if (result.data) {
                    setTimeout(() => {
                        this.openResep(result.data);
                    }, 300);
                } else {
                    window.location.reload();
                }
            } else {
                alert('Terjadi kesalahan sistem.');
            }
        } catch (err) {
            console.error(err);
        }
        this.isSubmitting = false;
    },
    openEdit(data) {
        this.editMode = true;
        this.rm = { ...data };
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
    },

    // Resep Medis Features
    showResepModal: false,
    resep: {
        rekam_medis_id: '',
        nama_pasien: '',
        dokter_pemeriksa: '',
        tanggal_resep: '{{ date("Y-m-d") }}',
        catatan_apoteker: ''
    },
    resepItems: [],
    medSearch: '',
    medResults: [],
    isSearching: false,

    openResep(data) {
        this.resep.rekam_medis_id = data.id;
        this.resep.nama_pasien = data.nama_pasien;
        this.resep.dokter_pemeriksa = data.dokter_pemeriksa || '';
        this.resepItems = [];
        this.medSearch = '';
        this.searchMed(); // Load default list
        this.showResepModal = true;
    },

    async searchMed() {
        this.isSearching = true;
        try {
            const q = encodeURIComponent(this.medSearch);
            const resp = await fetch(`{{ route('admin.api.medicines.search') }}?q=${q}`);
            this.medResults = await resp.json();
        } catch (e) { console.error(e); }
        this.isSearching = false;
    },

    addMed(med) {
        // Prevent duplicate
        if (this.resepItems.find(i => i.medicine_id === med.id)) return;

        this.resepItems.push({
            medicine_id: med.id,
            name: med.name,
            brand: med.brand,
            unit: med.unit,
            stock: med.stock,
            jumlah: 1,
            aturan_pakai: ''
        });
    },

    removeMed(index) {
        this.resepItems.splice(index, 1);
    },

    // Detail Modal
    showDetailModal: false,
    detailData: null,
    openDetail(item) {
        this.detailData = item;
        this.showDetailModal = true;
    }
}" @open-rm-modal.window="switchMenu('rekam_medis'); openAdd($event.detail)">

    {{-- ── Header & Action ──────────────────────────────────────── --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-8">
        <div>
            <h2 class="text-4xl font-black text-gray-900 dark:text-white tracking-tighter">Rekam Medis</h2>
            <div class="flex items-center gap-2 mt-2">
                <span class="w-2 h-2 rounded-full bg-teal-500 animate-pulse"></span>
                <p class="text-[11px] font-black uppercase tracking-[0.2em] text-gray-400 dark:text-gray-500">
                    Medical Record Center & Patient History
                </p>
            </div>
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
            <div class="bg-white dark:bg-[#1E293B] border border-gray-100 dark:border-gray-800 rounded-[1.5rem] p-6 shadow-sm relative overflow-hidden group hover:shadow-xl hover:shadow-{{ $s['color'] }}-500/10 hover:-translate-y-1 transition-all duration-300">
                <div class="absolute -right-4 -top-4 w-20 h-20 bg-{{ $s['color'] }}-500/5 rounded-full blur-xl group-hover:bg-{{ $s['color'] }}-500/20 transition-all duration-500"></div>
                <div class="flex items-center gap-5 relative">
                    <div class="w-14 h-14 rounded-2xl bg-{{ $s['color'] }}-50 dark:bg-{{ $s['color'] }}-900/20 flex items-center justify-center flex-shrink-0 group-hover:rotate-6 group-hover:scale-110 transition-all duration-300">
                        <svg class="w-7 h-7 text-{{ $s['color'] }}-600 dark:text-{{ $s['color'] }}-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="{{ $s['icon'] }}"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-[10px] font-black uppercase tracking-[0.2em] text-gray-400 dark:text-gray-500 mb-1">{{ $s['label'] }}</p>
                        <p class="text-3xl font-black text-gray-900 dark:text-white tabular-nums leading-none">{{ $rmStats[$s['key']] }}</p>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    {{-- ── Search & Filters ────────────────────────────────────── --}}
    <div class="bg-white/50 dark:bg-[#1E293B]/50 backdrop-blur-md border border-gray-100 dark:border-gray-800 rounded-3xl p-5 mb-6">
        <form action="{{ route('admin.dashboard') }}" method="GET" class="flex flex-col gap-5">
            <input type="hidden" name="tab" value="rekam_medis">

            {{-- Top Row: Search & Date Filter --}}
            <div class="flex flex-col md:flex-row gap-4 items-center justify-between w-full">
                {{-- Search & Reset --}}
                <div class="relative w-full md:max-w-md lg:max-w-lg flex items-center gap-2">
                    <div class="relative w-full">
                        <svg class="w-5 h-5 text-gray-400 absolute left-4 top-1/2 -translate-y-1/2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                        <input type="text" name="rm_search" value="{{ $rmSearch }}" placeholder="Cari No. RM, Nama Pasien, atau Dokter..."
                               class="w-full pl-12 pr-4 py-3 bg-white dark:bg-[#0f172a] border border-gray-200 dark:border-gray-700 rounded-2xl focus:ring-4 focus:ring-teal-500/10 outline-none text-sm font-medium transition-all">
                    </div>
                    
                    @if($rmSearch || $rmKategori || $rmDate !== 'today')
                        <a href="{{ route('admin.dashboard', ['tab' => 'rekam_medis']) }}" 
                           class="shrink-0 w-12 h-12 flex items-center justify-center bg-rose-50 dark:bg-rose-900/20 text-rose-500 dark:text-rose-400 rounded-2xl hover:bg-rose-500 hover:text-white transition-all shadow-sm"
                           title="Reset Semua Filter">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </a>
                    @endif
                </div>

                {{-- Date Filter Group --}}
                <div class="flex items-center gap-2 w-full md:w-auto shrink-0" 
                     x-data="{ dateType: '{{ in_array($rmDate ?? '', ['', 'today', 'week', 'month']) ? 'preset' : (preg_match('/^\d{4}-\d{2}$/', $rmDate ?? '') ? 'month' : 'date') }}' }">
                    <select x-model="dateType" @change="$dispatch('change-date-type')"
                            class="px-4 py-3 bg-white dark:bg-[#0f172a] border border-gray-200 dark:border-gray-700 rounded-2xl focus:ring-4 focus:ring-teal-500/10 outline-none text-sm font-bold text-gray-600 dark:text-gray-300 transition-all cursor-pointer hover:border-teal-400">
                        <option value="preset">Periode</option>
                        <option value="date">Tanggal</option>
                        <option value="month">Bulan</option>
                    </select>
                    
                    <template x-if="dateType === 'preset'">
                        <select name="rm_date" onchange="this.form.submit()"
                                class="w-full md:w-40 px-4 py-3 bg-white dark:bg-[#0f172a] border border-gray-200 dark:border-gray-700 rounded-2xl focus:ring-4 focus:ring-teal-500/10 outline-none text-sm font-bold text-gray-700 dark:text-gray-200 transition-all cursor-pointer hover:border-teal-400">
                            <option value="">Semua Waktu</option>
                            <option value="today" {{ ($rmDate ?? '') == 'today' ? 'selected' : '' }}>Hari Ini</option>
                            <option value="week" {{ ($rmDate ?? '') == 'week' ? 'selected' : '' }}>Minggu Ini</option>
                            <option value="month" {{ ($rmDate ?? '') == 'month' ? 'selected' : '' }}>Bulan Ini</option>
                        </select>
                    </template>

                    <template x-if="dateType === 'date'">
                        <input type="date" name="rm_date" value="{{ preg_match('/^\d{4}-\d{2}-\d{2}$/', $rmDate ?? '') ? $rmDate : '' }}" onchange="this.form.submit()"
                               class="w-full md:w-40 px-4 py-3 bg-white dark:bg-[#0f172a] border border-gray-200 dark:border-gray-700 rounded-2xl focus:ring-4 focus:ring-teal-500/10 outline-none text-sm font-bold text-gray-700 dark:text-gray-200 transition-all hover:border-teal-400">
                    </template>

                    <template x-if="dateType === 'month'">
                        <input type="month" name="rm_date" value="{{ preg_match('/^\d{4}-\d{2}$/', $rmDate ?? '') ? $rmDate : '' }}" onchange="this.form.submit()"
                               class="w-full md:w-40 px-4 py-3 bg-white dark:bg-[#0f172a] border border-gray-200 dark:border-gray-700 rounded-2xl focus:ring-4 focus:ring-teal-500/10 outline-none text-sm font-bold text-gray-700 dark:text-gray-200 transition-all hover:border-teal-400">
                    </template>
                </div>
            </div>

            {{-- Bottom Row: Categories --}}
            <div class="flex flex-wrap items-center gap-2 w-full pt-2 border-t border-gray-100 dark:border-gray-800">
                @foreach($rmKategoriCounts as $kat => $count)
                    <a href="{{ route('admin.dashboard', ['tab' => 'rekam_medis', 'rm_kategori' => $kat, 'rm_search' => $rmSearch, 'rm_date' => $rmDate ?? '']) }}"
                       class="rm-filter-pill px-4 py-2 rounded-xl text-[11px] font-bold flex items-center gap-2 border transition-all
                              {{ $rmKategori === (string)$kat
                                 ? 'bg-teal-600 border-teal-600 text-white shadow-md shadow-teal-500/20 scale-105'
                                 : 'bg-white dark:bg-[#0f172a] border-gray-200 dark:border-gray-700 text-gray-500 dark:text-gray-400 hover:border-teal-500 hover:text-teal-600 hover:bg-teal-50/50' }}">
                        {{ $kat ?: 'Semua Kategori' }}
                        <span class="px-1.5 py-0.5 rounded-md text-[9px] {{ $rmKategori === (string)$kat ? 'bg-white/20 text-white' : 'bg-gray-100 dark:bg-gray-800 text-gray-400' }}">
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
                <tbody class="divide-y divide-gray-50 dark:divide-gray-800/50 text-sm">
                    @forelse($rekamMedis as $item)
                        <tr class="group hover:bg-gray-50/80 dark:hover:bg-[#0f172a] transition-all duration-200">
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
                                <div class="flex flex-col gap-1.5">
                                    <span class="text-[11px] font-bold {{ $item->status_kunjungan === 'Aktif' ? 'text-teal-600' : 'text-gray-400' }}">
                                        {{ $item->status_kunjungan }}
                                    </span>
                                    @if($item->resepMedis)
                                        <span class="inline-flex items-center gap-1 text-[9px] font-black uppercase text-emerald-600 bg-emerald-50 px-2 py-0.5 rounded-md border border-emerald-100">
                                            <svg class="w-2.5 h-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                                            Resep Ada
                                        </span>
                                    @endif
                                </div>
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
                                <div class="flex justify-end gap-2 opacity-100 lg:opacity-70 group-hover:opacity-100 transition-opacity">
                                    <button @click="openDetail({{ $item->load('resepMedis.items')->toJson() }})"
                                            class="w-9 h-9 rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-400 hover:text-cyan-600 hover:border-cyan-200 hover:bg-cyan-50 dark:hover:border-cyan-700 dark:hover:bg-cyan-900/20 flex items-center justify-center transition-all shadow-sm hover:scale-105"
                                            title="Lihat Detail">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                        </svg>
                                    </button>
                                    <button @click="openResep({{ $item->toJson() }})"
                                            class="w-9 h-9 rounded-xl border border-teal-200 dark:border-teal-800 bg-teal-50 dark:bg-teal-900/20 text-teal-600 dark:text-teal-400 hover:bg-teal-600 hover:text-white dark:hover:bg-teal-500 dark:hover:text-white flex items-center justify-center transition-all shadow-sm hover:scale-105"
                                            title="Buat Resep">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/>
                                        </svg>
                                    </button>
                                    <button @click="openEdit({{ $item->toJson() }})"
                                            class="w-9 h-9 rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-400 hover:text-teal-600 hover:border-teal-200 hover:bg-teal-50 dark:hover:border-teal-700 dark:hover:bg-teal-900/20 flex items-center justify-center transition-all shadow-sm hover:scale-105"
                                            title="Edit Data">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                        </svg>
                                    </button>
                                    <button @click="confirmDelete({{ $item->id }}, '{{ addslashes($item->nama_pasien) }}')"
                                            class="w-9 h-9 rounded-xl border border-rose-100 dark:border-rose-900/50 bg-white dark:bg-gray-800 text-rose-400 hover:bg-rose-500 hover:text-white dark:hover:bg-rose-500 flex items-center justify-center transition-all shadow-sm hover:scale-105"
                                            title="Hapus Data">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
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
                <div class="flex items-center justify-between px-7 py-5 border-b border-gray-100 dark:border-gray-800 bg-gray-50/50 dark:bg-gray-800/30">
                    <div>
                        <h3 class="text-lg font-black text-gray-900 dark:text-white" x-text="editMode ? 'Edit Rekam Medis' : 'Tambah Rekam Medis'"></h3>
                        <p class="text-[9px] font-bold text-gray-400 dark:text-gray-500 uppercase tracking-widest mt-0.5">Data Kesehatan Pasien</p>
                    </div>
                    <button @click="showModal = false" class="w-9 h-9 rounded-xl flex items-center justify-center text-gray-400 hover:bg-gray-200/50 dark:hover:bg-gray-800 transition-all">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>

                {{-- Form --}}
                <form @submit.prevent="submitForm" class="p-7 space-y-6">
                    @csrf
                    <template x-if="editMode">
                        <input type="hidden" name="_method" value="PUT">
                    </template>
                    <input type="hidden" name="reservasi_id" x-model="rm.reservasi_id">

                    <div class="space-y-6">
                        {{-- Section 1: Identitas --}}
                        <div class="bg-gray-50/50 dark:bg-gray-800/20 p-5 md:p-6 rounded-[2rem] border border-gray-100 dark:border-gray-800">
                            <h4 class="text-[10px] font-black uppercase tracking-[0.15em] text-teal-600 dark:text-teal-400 mb-5 flex items-center gap-2">
                                <span class="w-1.5 h-4 bg-teal-500 rounded-full"></span>
                                Identitas Pasien
                            </h4>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                                <div class="rm-field md:col-span-2">
                                    <label>Nama Lengkap <span class="text-rose-500">*</span></label>
                                    <input type="text" name="nama_pasien" x-model="rm.nama_pasien" required placeholder="Contoh: Siti Aminah" class="text-xs py-2.5" :class="{'border-rose-500 bg-rose-50 dark:bg-rose-900/10': errors.nama_pasien}">
                                    <span x-show="errors.nama_pasien" x-text="errors.nama_pasien?.[0]" class="text-[10px] font-bold text-rose-500 mt-1.5 block"></span>
                                </div>
                                <div class="rm-field">
                                    <label>Usia <span class="text-rose-500">*</span></label>
                                    <input type="number" name="usia" x-model="rm.usia" required placeholder="Usia" class="text-xs py-2.5" :class="{'border-rose-500 bg-rose-50 dark:bg-rose-900/10': errors.usia}">
                                    <span x-show="errors.usia" x-text="errors.usia?.[0]" class="text-[10px] font-bold text-rose-500 mt-1.5 block"></span>
                                </div>
                                <div class="rm-field">
                                    <label>No. Telepon</label>
                                    <input type="text" name="no_telepon" x-model="rm.no_telepon" placeholder="08xxxx" class="text-xs py-2.5" :class="{'border-rose-500 bg-rose-50 dark:bg-rose-900/10': errors.no_telepon}">
                                    <span x-show="errors.no_telepon" x-text="errors.no_telepon?.[0]" class="text-[10px] font-bold text-rose-500 mt-1.5 block"></span>
                                </div>
                                <div class="rm-field">
                                    <label>Golongan Darah</label>
                                    <select name="golongan_darah" x-model="rm.golongan_darah" class="text-xs py-2.5" :class="{'border-rose-500 bg-rose-50 dark:bg-rose-900/10': errors.golongan_darah}">
                                        <option value="">Pilih</option>
                                        <option value="A">A</option>
                                        <option value="B">B</option>
                                        <option value="AB">AB</option>
                                        <option value="O">O</option>
                                    </select>
                                    <span x-show="errors.golongan_darah" x-text="errors.golongan_darah?.[0]" class="text-[10px] font-bold text-rose-500 mt-1.5 block"></span>
                                </div>
                                <div class="rm-field">
                                    <label>Kategori <span class="text-rose-500">*</span></label>
                                    <select name="kategori" x-model="rm.kategori" required class="text-xs py-2.5" :class="{'border-rose-500 bg-rose-50 dark:bg-rose-900/10': errors.kategori}">
                                        <option value="Kehamilan">Kehamilan</option>
                                        <option value="Keluarga Berencana">Keluarga Berencana</option>
                                        <option value="Kontrol Umum">Kontrol Umum</option>
                                        <option value="Konsultasi">Konsultasi</option>
                                        <option value="Imunisasi">Imunisasi</option>
                                    </select>
                                    <span x-show="errors.kategori" x-text="errors.kategori?.[0]" class="text-[10px] font-bold text-rose-500 mt-1.5 block"></span>
                                </div>
                            </div>
                        </div>

                        {{-- Section 2: Pemeriksaan Fisik --}}
                        <div class="bg-gray-50/50 dark:bg-gray-800/20 p-5 md:p-6 rounded-[2rem] border border-gray-100 dark:border-gray-800">
                            <h4 class="text-[10px] font-black uppercase tracking-[0.15em] text-teal-600 dark:text-teal-400 mb-5 flex items-center gap-2">
                                <span class="w-1.5 h-4 bg-teal-500 rounded-full"></span>
                                Pemeriksaan Fisik & Status
                            </h4>
                            <div class="grid grid-cols-2 md:grid-cols-4 gap-5">
                                <div class="rm-field">
                                    <label>TD (mmHg)</label>
                                    <input type="text" name="tekanan_darah" x-model="rm.tekanan_darah" placeholder="120/80" class="text-xs py-2.5" :class="{'border-rose-500 bg-rose-50 dark:bg-rose-900/10': errors.tekanan_darah}">
                                    <span x-show="errors.tekanan_darah" x-text="errors.tekanan_darah?.[0]" class="text-[10px] font-bold text-rose-500 mt-1.5 block"></span>
                                </div>
                                <div class="rm-field">
                                    <label>BB (kg)</label>
                                    <input type="number" step="0.1" name="berat_badan" x-model="rm.berat_badan" placeholder="BB" class="text-xs py-2.5" :class="{'border-rose-500 bg-rose-50 dark:bg-rose-900/10': errors.berat_badan}">
                                    <span x-show="errors.berat_badan" x-text="errors.berat_badan?.[0]" class="text-[10px] font-bold text-rose-500 mt-1.5 block"></span>
                                </div>
                                <div class="rm-field">
                                    <label>TB (cm)</label>
                                    <input type="number" step="0.1" name="tinggi_badan" x-model="rm.tinggi_badan" placeholder="TB" class="text-xs py-2.5" :class="{'border-rose-500 bg-rose-50 dark:bg-rose-900/10': errors.tinggi_badan}">
                                    <span x-show="errors.tinggi_badan" x-text="errors.tinggi_badan?.[0]" class="text-[10px] font-bold text-rose-500 mt-1.5 block"></span>
                                </div>
                                <div class="rm-field">
                                    <label>Risiko <span class="text-rose-500">*</span></label>
                                    <select name="status_risiko" x-model="rm.status_risiko" required class="text-xs py-2.5" :class="{'border-rose-500 bg-rose-50 dark:bg-rose-900/10': errors.status_risiko}">
                                        <option value="Rendah">Rendah</option>
                                        <option value="Sedang">Sedang</option>
                                        <option value="Tinggi">Tinggi</option>
                                    </select>
                                    <span x-show="errors.status_risiko" x-text="errors.status_risiko?.[0]" class="text-[10px] font-bold text-rose-500 mt-1.5 block"></span>
                                </div>
                                <div class="rm-field md:col-span-2">
                                    <label>Status Kunjungan <span class="text-rose-500">*</span></label>
                                    <select name="status_kunjungan" x-model="rm.status_kunjungan" required class="text-xs py-2.5" :class="{'border-rose-500 bg-rose-50 dark:bg-rose-900/10': errors.status_kunjungan}">
                                        <option value="Aktif">Aktif</option>
                                        <option value="Selesai">Selesai</option>
                                        <option value="Dirujuk">Dirujuk</option>
                                    </select>
                                    <span x-show="errors.status_kunjungan" x-text="errors.status_kunjungan?.[0]" class="text-[10px] font-bold text-rose-500 mt-1.5 block"></span>
                                </div>
                                {{-- Conditional Pregnancy Fields --}}
                                <template x-if="rm.kategori === 'Kehamilan'">
                                    <div class="grid grid-cols-2 gap-5 md:col-span-2">
                                        <div class="rm-field">
                                            <label>UK (Minggu)</label>
                                            <input type="number" name="usia_kehamilan_minggu" x-model="rm.usia_kehamilan_minggu" class="text-xs py-2.5" :class="{'border-rose-500 bg-rose-50 dark:bg-rose-900/10': errors.usia_kehamilan_minggu}">
                                            <span x-show="errors.usia_kehamilan_minggu" x-text="errors.usia_kehamilan_minggu?.[0]" class="text-[10px] font-bold text-rose-500 mt-1.5 block"></span>
                                        </div>
                                        <div class="rm-field">
                                            <label>HPHT</label>
                                            <input type="date" name="hpht" x-model="rm.hpht" class="text-xs py-2.5" :class="{'border-rose-500 bg-rose-50 dark:bg-rose-900/10': errors.hpht}">
                                            <span x-show="errors.hpht" x-text="errors.hpht?.[0]" class="text-[10px] font-bold text-rose-500 mt-1.5 block"></span>
                                        </div>
                                    </div>
                                </template>
                            </div>
                        </div>

                        {{-- Section 3: Analisis & Tindakan --}}
                        <div class="bg-gray-50/50 dark:bg-gray-800/20 p-5 md:p-6 rounded-[2rem] border border-gray-100 dark:border-gray-800">
                            <h4 class="text-[10px] font-black uppercase tracking-[0.15em] text-teal-600 dark:text-teal-400 mb-5 flex items-center gap-2">
                                <span class="w-1.5 h-4 bg-teal-500 rounded-full"></span>
                                Analisis & Tindakan
                            </h4>
                            <div class="space-y-5">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                                    <div class="rm-field">
                                        <label>Diagnosis</label>
                                        <textarea name="diagnosis" x-model="rm.diagnosis" rows="2" placeholder="Diagnosis..." class="text-xs" :class="{'border-rose-500 bg-rose-50 dark:bg-rose-900/10': errors.diagnosis}"></textarea>
                                        <span x-show="errors.diagnosis" x-text="errors.diagnosis?.[0]" class="text-[10px] font-bold text-rose-500 mt-1.5 block"></span>
                                    </div>
                                    <div class="rm-field">
                                        <label>Tindakan / Terapi</label>
                                        <textarea name="tindakan" x-model="rm.tindakan" rows="2" placeholder="Tindakan..." class="text-xs" :class="{'border-rose-500 bg-rose-50 dark:bg-rose-900/10': errors.tindakan}"></textarea>
                                        <span x-show="errors.tindakan" x-text="errors.tindakan?.[0]" class="text-[10px] font-bold text-rose-500 mt-1.5 block"></span>
                                    </div>
                                </div>
                                <div class="rm-field">
                                    <label class="text-teal-600 font-bold dark:text-teal-400">Catatan Khusus untuk Pasien</label>
                                    <textarea name="catatan_pasien" x-model="rm.catatan_pasien" rows="3"
                                              placeholder="Tulis pesan atau instruksi yang bisa dibaca langsung oleh pasien di portal..."
                                              class="text-xs border-teal-200 focus:ring-teal-500/20 focus:border-teal-500 dark:border-teal-900/30" :class="{'border-rose-500 bg-rose-50 dark:bg-rose-900/10': errors.catatan_pasien}"></textarea>
                                    <span x-show="errors.catatan_pasien" x-text="errors.catatan_pasien?.[0]" class="text-[10px] font-bold text-rose-500 mt-1.5 block"></span>
                                </div>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                                    <div class="rm-field">
                                        <label>Dokter Pemeriksa</label>
                                        <input type="text" name="dokter_pemeriksa" x-model="rm.dokter_pemeriksa" placeholder="Nama Dokter" class="text-xs py-2.5" :class="{'border-rose-500 bg-rose-50 dark:bg-rose-900/10': errors.dokter_pemeriksa}">
                                        <span x-show="errors.dokter_pemeriksa" x-text="errors.dokter_pemeriksa?.[0]" class="text-[10px] font-bold text-rose-500 mt-1.5 block"></span>
                                    </div>
                                    <div class="rm-field">
                                        <label>Kontrol Berikutnya</label>
                                        <input type="date" name="jadwal_kontrol_berikutnya" x-model="rm.jadwal_kontrol_berikutnya" class="text-xs py-2.5" :class="{'border-rose-500 bg-rose-50 dark:bg-rose-900/10': errors.jadwal_kontrol_berikutnya}">
                                        <span x-show="errors.jadwal_kontrol_berikutnya" x-text="errors.jadwal_kontrol_berikutnya?.[0]" class="text-[10px] font-bold text-rose-500 mt-1.5 block"></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Footer --}}
                    <div class="flex gap-3 pt-2">
                        <button type="button" @click="showModal = false" :disabled="isSubmitting"
                                class="flex-1 px-5 py-3 bg-gray-50 dark:bg-gray-800 text-gray-500 font-bold rounded-xl hover:bg-gray-100 disabled:opacity-50 transition-all text-xs">
                            Batal
                        </button>
                        <button type="submit" :disabled="isSubmitting"
                                class="flex-[2] px-5 py-3 bg-gradient-to-r from-teal-600 to-cyan-500 text-white font-black rounded-xl shadow-lg shadow-teal-500/20 hover:shadow-teal-500/40 disabled:opacity-50 transition-all text-xs flex items-center justify-center gap-2">
                            <svg x-show="isSubmitting" class="animate-spin h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                            <span x-text="isSubmitting ? 'Menyimpan...' : 'Simpan Data'"></span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </template>

    {{-- ── Resep Medis Modal ────────────────────────────────────── --}}
    <template x-teleport="body">
        <div x-show="showResepModal" x-cloak
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="fixed inset-0 z-[1000] flex items-center justify-center p-4">

            <div class="absolute inset-0 bg-teal-900/40 backdrop-blur-md" @click="showResepModal = false"></div>

            <div class="relative bg-white dark:bg-[#1E293B] w-full max-w-5xl rounded-[2.5rem] shadow-2xl flex flex-col overflow-hidden q-anim h-[85vh]" @click.stop>
                {{-- Header --}}
                <div class="px-8 py-7 bg-white dark:bg-[#1E293B] border-b border-gray-100 dark:border-gray-800 flex items-center justify-between shrink-0">
                    <div>
                        <div class="flex items-center gap-3 mb-1">
                            <div class="w-8 h-8 rounded-xl bg-teal-600 flex items-center justify-center text-white shadow-lg shadow-teal-500/20">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                            </div>
                            <h3 class="text-xl font-black tracking-tight text-gray-900 dark:text-white uppercase">Buat Resep</h3>
                        </div>
                        <p class="text-[10px] font-black uppercase tracking-[0.2em] text-teal-600 dark:text-teal-400">Inventory Sync Active</p>
                    </div>
                    <button @click="showResepModal = false" class="w-10 h-10 rounded-2xl bg-white/20 hover:bg-white/30 flex items-center justify-center transition-all">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>

                <div class="flex flex-1 overflow-hidden">
                    {{-- Left Pane: Inventory Browser --}}
                    <div class="w-2/5 border-r border-gray-100 dark:border-gray-800 bg-gray-50/50 dark:bg-gray-800/20 flex flex-col">
                        <div class="p-6 space-y-4">
                            <div class="relative">
                                <div class="absolute inset-y-0 left-4 flex items-center pointer-events-none">
                                    <svg class="w-4 h-4 text-teal-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                                </div>
                                <input type="text" x-model="medSearch" @input.debounce.300ms="searchMed()"
                                       placeholder="Cari obat di inventori..."
                                       class="w-full pl-11 pr-4 py-3 bg-white dark:bg-[#0f172a] border border-gray-200 dark:border-gray-700 rounded-2xl focus:border-teal-500 focus:ring-4 focus:ring-teal-500/5 transition-all outline-none font-bold text-xs">
                            </div>

                            <h4 class="text-[10px] font-black uppercase tracking-widest text-gray-400">Hasil Pencarian</h4>
                        </div>

                        <div class="flex-1 overflow-y-auto px-6 pb-6 space-y-3">
                            <template x-if="medResults.length === 0 && !isSearching">
                                <div class="flex flex-col items-center justify-center h-40 text-center">
                                    <div class="w-12 h-12 bg-gray-100 dark:bg-gray-800 rounded-full flex items-center justify-center mb-3 text-gray-300">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    </div>
                                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest leading-relaxed">Tidak ada obat ditemukan<br>coba kata kunci lain</p>
                                </div>
                            </template>

                            <template x-for="med in medResults" :key="med.id">
                                <button type="button" @click="addMed(med)"
                                        :class="resepItems.find(i => i.medicine_id === med.id) ? 'border-teal-500 bg-teal-50/30 dark:bg-teal-900/10 opacity-70' : 'border-gray-100 dark:border-gray-800 bg-white dark:bg-[#0f172a]'"
                                        class="w-full p-4 border rounded-2xl flex items-center justify-between hover:border-teal-500 hover:shadow-lg hover:shadow-teal-500/5 transition-all group q-anim">
                                    <div class="text-left flex items-center gap-3">
                                        <template x-if="resepItems.find(i => i.medicine_id === med.id)">
                                            <div class="w-5 h-5 rounded-full bg-teal-500 flex items-center justify-center text-white">
                                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                                            </div>
                                        </template>
                                        <div>
                                            <p class="text-xs font-black text-gray-900 dark:text-white group-hover:text-teal-600" x-text="med.name"></p>
                                            <p class="text-[9px] font-bold text-gray-400 uppercase tracking-wider" x-text="med.brand || 'Umum'"></p>
                                        </div>
                                    </div>
                                    <div class="text-right">
                                        <span class="px-2 py-1 bg-teal-50 dark:bg-teal-900/30 text-teal-600 rounded-lg text-[10px] font-black" x-text="med.stock + ' ' + med.unit"></span>
                                    </div>
                                </button>
                            </template>
                        </div>
                    </div>

                    {{-- Right Pane: Prescription Cart --}}
                    <div class="flex-1 flex flex-col">
                        <form action="{{ route('admin.resep-medis.store') }}" method="POST" class="flex flex-col h-full">
                            @csrf
                            <input type="hidden" name="rekam_medis_id" x-model="resep.rekam_medis_id">
                            <input type="hidden" name="nama_pasien" x-model="resep.nama_pasien">
                            <input type="hidden" name="dokter_pemeriksa" x-model="resep.dokter_pemeriksa">
                            <input type="hidden" name="tanggal_resep" x-model="resep.tanggal_resep">

                            <div class="p-6 flex-1 overflow-y-auto space-y-6">
                                {{-- Patient Context --}}
                                <div class="flex items-center justify-between px-5 py-4 bg-teal-50/50 dark:bg-teal-900/10 rounded-2xl border border-teal-100/50 dark:border-teal-900/30">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-xl bg-teal-600 text-white flex items-center justify-center font-black">
                                            <span x-text="resep.nama_pasien ? resep.nama_pasien.charAt(0).toUpperCase() : ''"></span>
                                        </div>
                                        <div>
                                            <p class="text-[9px] font-black uppercase tracking-widest text-teal-600">Pasien Terpilih</p>
                                            <p class="text-sm font-black text-gray-900 dark:text-white" x-text="resep.nama_pasien"></p>
                                        </div>
                                    </div>
                                    <div class="text-right">
                                        <p class="text-[9px] font-black uppercase tracking-widest text-teal-600">ID Rekam Medis</p>
                                        <p class="text-sm font-bold text-gray-700 dark:text-gray-300" x-text="'#' + resep.rekam_medis_id"></p>
                                    </div>
                                </div>

                                {{-- Cart Items --}}
                                <div class="space-y-3">
                                    <h4 class="text-[10px] font-black uppercase tracking-widest text-gray-400">Rencana Pengobatan</h4>
                                    <template x-if="resepItems.length === 0">
                                        <div class="py-16 border-2 border-dashed border-gray-100 dark:border-gray-800 rounded-[2.5rem] text-center">
                                            <p class="text-[11px] font-bold text-gray-400 uppercase tracking-widest leading-relaxed">Pilih obat dari daftar kiri<br>untuk memulai resep</p>
                                        </div>
                                    </template>
                                    <div class="space-y-4">
                                        <template x-for="(item, index) in resepItems" :key="item.medicine_id">
                                            <div class="bg-white dark:bg-[#0f172a] border border-gray-100 dark:border-gray-800 rounded-3xl p-5 shadow-sm q-anim relative group">
                                                <button type="button" @click="removeMed(index)"
                                                        class="absolute -top-2 -right-2 w-7 h-7 bg-rose-500 text-white rounded-full flex items-center justify-center opacity-0 group-hover:opacity-100 transition-all shadow-xl hover:scale-110">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M6 18L18 6M6 6l12 12"/></svg>
                                                </button>

                                                <div class="space-y-4">
                                                    <div class="flex items-center gap-3">
                                                        <div class="w-9 h-9 rounded-xl bg-gray-50 dark:bg-gray-800 flex items-center justify-center text-teal-600">
                                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a2 2 0 00-1.96 1.414l-.477 2.387a2 2 0 00.547 1.022l1.428 1.428a2 2 0 002.828 0l4.243-4.243a2 2 0 000-2.828l-1.428-1.428z"/></svg>
                                                        </div>
                                                        <div class="flex-1">
                                                            <p class="text-xs font-black text-gray-900 dark:text-white" x-text="item.name"></p>
                                                            <p class="text-[9px] font-bold text-gray-400 uppercase tracking-widest" x-text="item.brand"></p>
                                                        </div>
                                                    </div>
                                                    <div class="grid grid-cols-12 gap-3">
                                                        <div class="col-span-3">
                                                            <label class="block text-[8px] font-black uppercase text-gray-400 mb-1">Jumlah</label>
                                                            <input type="number" :name="'items['+index+'][jumlah]'" x-model="item.jumlah" required min="1" :max="item.stock"
                                                                   class="w-full px-3 py-2 bg-gray-50 dark:bg-[#1E293B] border border-gray-100 dark:border-gray-800 rounded-xl font-black text-xs outline-none focus:border-teal-500">
                                                        </div>
                                                        <div class="col-span-9">
                                                            <label class="block text-[8px] font-black uppercase text-gray-400 mb-1">Aturan Pakai</label>
                                                            <input type="text" :name="'items['+index+'][aturan_pakai]'" x-model="item.aturan_pakai" placeholder="Contoh: 3 x 1 sehari sesudah makan"
                                                                   class="w-full px-3 py-2 bg-gray-50 dark:bg-[#1E293B] border border-gray-100 dark:border-gray-800 rounded-xl font-bold text-xs outline-none focus:border-teal-500">
                                                        </div>
                                                    </div>
                                                    <input type="hidden" :name="'items['+index+'][medicine_id]'" x-model="item.medicine_id">
                                                </div>
                                            </div>
                                        </template>
                                    </div>
                                </div>
                            </div>

                            {{-- Actions --}}
                            <div class="p-6 border-t border-gray-100 dark:border-gray-800 bg-gray-50/50 dark:bg-gray-800/20 flex gap-3">
                                <button type="button" @click="showResepModal = false"
                                        class="flex-1 px-5 py-4 bg-white dark:bg-gray-800 text-gray-500 font-bold rounded-2xl hover:bg-gray-100 border border-gray-200 dark:border-gray-700 transition-all text-xs">
                                    Batal
                                </button>
                                <button type="submit" :disabled="resepItems.length === 0"
                                        class="flex-[2] px-5 py-4 bg-gradient-to-r from-teal-600 to-cyan-500 text-white font-black rounded-2xl shadow-xl shadow-teal-500/20 hover:shadow-teal-500/40 transition-all disabled:opacity-50 text-xs uppercase tracking-widest">
                                    Konfirmasi Resep & Stok
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </template>

    {{-- ── Detail Modal ────────────────────────────────────────── --}}
    <template x-teleport="body">
        <div x-show="showDetailModal" x-cloak
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="fixed inset-0 z-[1000] flex items-center justify-center p-4">

            <div class="absolute inset-0 bg-gray-900/60 backdrop-blur-sm" @click="showDetailModal = false"></div>

            <div class="relative bg-white dark:bg-[#1E293B] w-full max-w-2xl rounded-[2.5rem] shadow-2xl flex flex-col overflow-hidden q-anim" @click.stop>
                {{-- Header --}}
                <div class="px-8 py-6 bg-gray-50/50 dark:bg-gray-800/50 border-b border-gray-100 dark:border-gray-800 flex items-center justify-between">
                    <div>
                        <h3 class="text-xl font-black text-gray-900 dark:text-white">Detail Rekam Medis</h3>
                        <p class="text-[9px] font-bold text-gray-400 uppercase tracking-[0.2em] mt-0.5" x-text="'No. RM: ' + detailData?.no_rekam_medis"></p>
                    </div>
                    <button @click="showDetailModal = false" class="w-10 h-10 rounded-2xl flex items-center justify-center text-gray-400 hover:bg-gray-200 dark:hover:bg-gray-800 transition-all">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>

                <div class="p-8 space-y-8 overflow-y-auto max-h-[75vh]" x-if="detailData">
                    {{-- Patient Info --}}
                    <div class="flex items-center gap-6 pb-6 border-b border-gray-100 dark:border-gray-800">
                        <div class="w-16 h-16 rounded-3xl bg-gradient-to-br from-teal-500 to-cyan-500 text-white flex items-center justify-center text-2xl font-black shadow-lg shadow-teal-500/20">
                            <span x-text="detailData?.nama_pasien?.charAt(0).toUpperCase()"></span>
                        </div>
                        <div>
                            <h4 class="text-2xl font-black text-gray-900 dark:text-white" x-text="detailData?.nama_pasien"></h4>
                            <div class="flex flex-wrap gap-4 mt-2">
                                <span class="text-xs font-bold text-gray-500 flex items-center gap-1.5">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                    <span x-text="detailData?.usia + ' Tahun'"></span>
                                </span>
                                <span class="text-xs font-bold text-gray-500 flex items-center gap-1.5">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                                    <span x-text="detailData?.no_telepon || '—'"></span>
                                </span>
                                <span class="text-xs font-bold text-gray-500 flex items-center gap-1.5">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                                    <span x-text="detailData?.golongan_darah || '—'"></span>
                                </span>
                            </div>
                        </div>
                    </div>

                    {{-- Data Grid --}}
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                        <div class="bg-blue-50/50 dark:bg-blue-900/10 p-4 rounded-2xl border border-blue-100 dark:border-blue-900/30">
                            <p class="text-[9px] font-black uppercase tracking-widest text-blue-500 dark:text-blue-400 mb-1">Tensi Darah</p>
                            <p class="text-sm font-black text-gray-900 dark:text-white" x-text="detailData?.tekanan_darah || '—'"></p>
                        </div>
                        <div class="bg-emerald-50/50 dark:bg-emerald-900/10 p-4 rounded-2xl border border-emerald-100 dark:border-emerald-900/30">
                            <p class="text-[9px] font-black uppercase tracking-widest text-emerald-500 dark:text-emerald-400 mb-1">Berat Badan</p>
                            <p class="text-sm font-black text-gray-900 dark:text-white"><span x-text="detailData?.berat_badan || '-'"></span> <span class="text-xs font-medium text-gray-500">kg</span></p>
                        </div>
                        <div class="bg-amber-50/50 dark:bg-amber-900/10 p-4 rounded-2xl border border-amber-100 dark:border-amber-900/30">
                            <p class="text-[9px] font-black uppercase tracking-widest text-amber-500 dark:text-amber-400 mb-1">Tinggi Badan</p>
                            <p class="text-sm font-black text-gray-900 dark:text-white"><span x-text="detailData?.tinggi_badan || '-'"></span> <span class="text-xs font-medium text-gray-500">cm</span></p>
                        </div>
                        <div class="bg-rose-50/50 dark:bg-rose-900/10 p-4 rounded-2xl border border-rose-100 dark:border-rose-900/30">
                            <p class="text-[9px] font-black uppercase tracking-widest text-rose-500 dark:text-rose-400 mb-1">Risiko</p>
                            <p class="text-sm font-black text-rose-600 dark:text-rose-400" x-text="detailData?.status_risiko"></p>
                        </div>
                    </div>

                    {{-- Diagnosis & Tindakan --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-4">
                            <h5 class="text-[10px] font-black uppercase tracking-[0.2em] text-teal-600 flex items-center gap-2">
                                <span class="w-5 h-5 rounded-lg bg-teal-50 dark:bg-teal-900/30 flex items-center justify-center">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M9 12l2 2 4-4"/></svg>
                                </span>
                                Hasil Diagnosis
                            </h5>
                            <div class="p-5 bg-white dark:bg-[#0f172a] border border-gray-100 dark:border-gray-800 rounded-2xl shadow-[0_2px_10px_rgba(0,0,0,0.02)] h-[120px] overflow-y-auto rm-scroll">
                                <p class="text-xs font-bold text-gray-700 dark:text-gray-300 leading-relaxed italic" x-text="detailData?.diagnosis || 'Tidak ada diagnosis tercatat.'"></p>
                            </div>
                        </div>
                        <div class="space-y-4">
                            <h5 class="text-[10px] font-black uppercase tracking-[0.2em] text-teal-600 flex items-center gap-2">
                                <span class="w-5 h-5 rounded-lg bg-teal-50 dark:bg-teal-900/30 flex items-center justify-center">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
                                </span>
                                Tindakan Medis
                            </h5>
                            <div class="p-5 bg-white dark:bg-[#0f172a] border border-gray-100 dark:border-gray-800 rounded-2xl shadow-[0_2px_10px_rgba(0,0,0,0.02)] h-[120px] overflow-y-auto rm-scroll">
                                <p class="text-xs font-bold text-gray-700 dark:text-gray-300 leading-relaxed" x-text="detailData?.tindakan || 'Tidak ada tindakan tercatat.'"></p>
                            </div>
                        </div>
                    </div>

                    {{-- Pesan untuk Pasien (New Section in Detail) --}}
                    <template x-if="detailData?.catatan_pasien">
                        <div class="mt-8 space-y-4">
                            <h5 class="text-[10px] font-black uppercase tracking-[0.2em] text-cyan-600 flex items-center gap-2">
                                <span class="w-5 h-5 rounded-lg bg-cyan-50 dark:bg-cyan-900/30 flex items-center justify-center">
                                    <svg class="w-3 h-3 text-cyan-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"/></svg>
                                </span>
                                Pesan Terkirim ke Pasien
                            </h5>
                            <div class="p-5 bg-cyan-50/50 dark:bg-cyan-900/20 border border-cyan-100 dark:border-cyan-900/30 rounded-[2rem]">
                                <p class="text-xs font-bold text-cyan-700 dark:text-cyan-400 leading-relaxed" x-text="detailData.catatan_pasien"></p>
                            </div>
                        </div>
                    </template>

                    {{-- Prescription View --}}
                    <div class="space-y-4 pt-4">
                        <h5 class="text-[10px] font-black uppercase tracking-[0.2em] text-cyan-600 flex items-center gap-2">
                            <span class="w-5 h-5 rounded-lg bg-cyan-50 dark:bg-cyan-900/30 flex items-center justify-center">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                            </span>
                            Resep Obat yang Diberikan
                        </h5>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <template x-if="!detailData?.resep_medis">
                                <div class="col-span-full py-8 bg-gray-50/50 dark:bg-gray-800/20 rounded-[2rem] border-2 border-dashed border-gray-100 dark:border-gray-800 text-center">
                                    <p class="text-[10px] font-bold text-gray-400 uppercase">Belum ada resep untuk kunjungan ini</p>
                                </div>
                            </template>

                            <template x-for="item in detailData?.resep_medis?.items" :key="item.id">
                                <div class="p-4 bg-white dark:bg-[#0f172a] border border-gray-100 dark:border-gray-800 rounded-2xl flex items-center gap-4">
                                    <div class="w-10 h-10 rounded-xl bg-cyan-50 dark:bg-cyan-900/20 flex items-center justify-center text-cyan-600">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a2 2 0 00-1.96 1.414l-.477 2.387a2 2 0 00.547 1.022l1.428 1.428a2 2 0 002.828 0l4.243-4.243a2 2 0 000-2.828l-1.428-1.428z"/></svg>
                                    </div>
                                    <div class="flex-1">
                                        <p class="text-xs font-black text-gray-900 dark:text-white" x-text="item.nama_obat"></p>
                                        <p class="text-[9px] font-bold text-cyan-600" x-text="item.jumlah + ' ' + item.satuan + ' — ' + (item.aturan_pakai || 'Aturan pakai tidak ditentukan')"></p>
                                    </div>
                                </div>
                            </template>
                        </div>
                    </div>

                    {{-- Footer Detail --}}
                    <div class="pt-6 border-t border-gray-100 dark:border-gray-800 flex justify-between items-center">
                        <div>
                            <p class="text-[9px] font-bold text-gray-400 uppercase tracking-widest">Dokter Pemeriksa</p>
                            <p class="text-xs font-black text-gray-900 dark:text-white" x-text="detailData?.dokter_pemeriksa || '—'"></p>
                        </div>
                        <div class="text-right">
                            <p class="text-[9px] font-bold text-gray-400 uppercase tracking-widest">Kontrol Berikutnya</p>
                            <p class="text-xs font-black text-teal-600" x-text="detailData?.jadwal_kontrol_berikutnya ? new Date(detailData.jadwal_kontrol_berikutnya).toLocaleDateString('id-ID', {day:'numeric', month:'long', year:'numeric'}) : '—'"></p>
                        </div>
                    </div>
                </div>
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
