<style>
.inv-stat { contain: layout paint style; }
.inv-row { transition: background .15s; }
.inv-row:hover { background: rgba(13,148,136,.04); }
.dark .inv-row:hover { background: rgba(13,148,136,.07); }
.inv-modal-inner {
    background: #fff;
    border-radius: 1.25rem;
    width: 100%;
    max-width: 34rem;
    max-height: calc(100dvh - 3rem);
    overflow-y: auto;
    display: flex;
    flex-direction: column;
    box-shadow: 0 30px 70px rgba(0,0,0,.22);
    animation: invModalIn .22s cubic-bezier(.16,1,.3,1) both;
}
.dark .inv-modal-inner { background: #1E293B; }
@keyframes invModalIn {
    from { opacity:0; transform:scale(.95) translateY(10px); }
    to   { opacity:1; transform:scale(1) translateY(0); }
}
.inv-field label {
    display:block; font-size:.7rem; font-weight:800;
    text-transform:uppercase; letter-spacing:.07em;
    color:#64748b; margin-bottom:.3rem;
}
.dark .inv-field label { color:#94a3b8; }
.inv-field input, .inv-field select {
    width:100%; padding:.6rem .9rem;
    background:#f8fafc; border:1.5px solid #e2e8f0;
    border-radius:.65rem; font-size:.875rem; outline:none;
    transition:border-color .15s, box-shadow .15s;
    color:#111827;
}
.dark .inv-field input, .dark .inv-field select {
    background:#0f172a; border-color:#334155; color:#f1f5f9;
}
.inv-field input:focus, .inv-field select:focus {
    border-color:#0d9488;
    box-shadow:0 0 0 3px rgba(13,148,136,.15);
}
    .inv-loading-overlay {
        position: absolute; inset: 0; 
        background: rgba(255,255,255,0.4);
        display: flex; flex-direction: column; align-items: center; justify-content: center; z-index: 50;
        backdrop-filter: blur(6px); border-radius: 1.5rem;
        animation: invFadeIn 0.3s ease-out;
    }
    .dark .inv-loading-overlay { background: rgba(15,23,42,0.4); }
    
    .inv-spinner-container {
        position: relative; width: 3.5rem; height: 3.5rem;
    }
    .inv-spinner-ring {
        position: absolute; width: 100%; height: 100%;
        border: 3px solid transparent;
        border-top-color: #0d9488;
        border-radius: 50%;
        animation: invSpin 1.2s cubic-bezier(0.5, 0, 0.5, 1) infinite;
    }
    .inv-spinner-ring:nth-child(2) { animation-delay: -0.45s; border-top-color: #06b6d4; opacity: 0.7; }
    .inv-spinner-ring:nth-child(3) { animation-delay: -0.3s; border-top-color: #2dd4bf; opacity: 0.4; }
    
    .inv-loading-text {
        margin-top: 1.25rem; font-size: 0.65rem; font-weight: 900;
        color: #0d9488; text-transform: uppercase; letter-spacing: 0.2em;
        animation: invPulse 1.5s ease-in-out infinite;
    }
    .dark .inv-loading-text { color: #2dd4bf; }

    @keyframes invSpin { from { transform: rotate(0deg); } to { transform: rotate(360deg); } }
    @keyframes invFadeIn { from { opacity: 0; } to { opacity: 1; } }
    @keyframes invPulse { 0%, 100% { opacity: 1; transform: scale(1); } 50% { opacity: 0.5; transform: scale(0.98); } }
</style>
@php
    $getSortLink = function($column) use ($medSort, $medSearch, $medFilter) {
        $direction = ($medSort == $column . '_asc') ? 'desc' : 'asc';
        return route('admin.dashboard', [
            'tab' => 'inventori',
            'med_search' => $medSearch,
            'med_filter' => $medFilter,
            'med_sort' => $column . '_' . $direction
        ]);
    };
@endphp

<div x-data="{
    showModal: false,
    editMode: false,
    medicine: { id:'', name:'', brand:'', category:'', stock:0, unit:'pcs', price:0, min_stock:10, expired_at:'' },
    openAdd() {
        this.editMode = false;
        this.medicine = { id:'', name:'', brand:'', category:'', stock:0, unit:'pcs', price:0, min_stock:10, expired_at:'' };
        this.showModal = true;
    },
    openEdit(med) { 
        this.editMode = true; 
        this.medicine = {...med};
        // Format date for input type=date
        if (this.medicine.expired_at) {
            this.medicine.expired_at = this.medicine.expired_at.split('T')[0];
        }
        this.showModal = true; 
    },
    showDeleteModal: false,
    medicineToDelete: { id:'', name:'' },
    confirmDelete(id, name) { this.medicineToDelete = {id, name}; this.showDeleteModal = true; },
    executeDelete() {
        const f = document.createElement('form'); f.method='POST';
        f.action=`{{ url('admin/medicines') }}/${this.medicineToDelete.id}`;
        const c=document.createElement('input'); c.type='hidden'; c.name='_token'; c.value='{{ csrf_token() }}';
        const m=document.createElement('input'); m.type='hidden'; m.name='_method'; m.value='DELETE';
        f.appendChild(c); f.appendChild(m); document.body.appendChild(f); f.submit();
    },
    formatRupiah(val) {
        if (!val || val === 0) return '';
        return new Intl.NumberFormat('id-ID').format(val);
    },
    loading: false,
    async updateInventori(url = null) {
        if (this.loading) return;
        this.loading = true;
        
        // If url is not provided, build it from current form values
        if (!url) {
            const form = document.getElementById('inv_search_form');
            const formData = new FormData(form);
            const params = new URLSearchParams(formData);
            url = `{{ route('admin.inventori.partial') }}?${params.toString()}`;
        } else {
            // Replace full dashboard URL with partial URL
            url = url.replace('{{ route('admin.dashboard') }}', '{{ route('admin.inventori.partial') }}');
        }

        try {
            const res = await fetch(url);
            const html = await res.text();
            
            // Extract content inside the x-data div and update the current view
            const parser = new DOMParser();
            const doc = parser.parseFromString(html, 'text/html');
            const newContent = doc.querySelector('[data-inv-container]').innerHTML;
            document.querySelector('[data-inv-container]').innerHTML = newContent;
            
            // Re-intercept pagination links and sorting headers with HTMX
            if (typeof enhanceTabContent === 'function') {
                enhanceTabContent(document.getElementById('tab-inventori'), 'inventori');
            }
            
            // Update URL in browser without reload
            window.history.pushState({}, '', url.replace('{{ route('admin.inventori.partial') }}', '{{ route('admin.dashboard') }}'));
        } catch (e) {
            console.error('Failed to update inventory:', e);
        } finally {
            this.loading = false;
        }
    }
}">

    {{-- Main Container with relative positioning for loading overlay --}}
    <div data-inv-container class="relative">
        <template x-if="loading">
            <div class="inv-loading-overlay text-center">
                <div class="inv-spinner-container mx-auto">
                    <div class="inv-spinner-ring"></div>
                    <div class="inv-spinner-ring"></div>
                    <div class="inv-spinner-ring"></div>
                </div>
                <div class="inv-loading-text">Sinkronisasi Data</div>
            </div>
        </template>

    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
        <div>
            <h2 class="text-2xl font-extrabold text-gray-900 dark:text-white mb-0.5">Inventori Obat</h2>
            <p class="text-sm text-gray-500 dark:text-gray-400">Kelola stok obat dan logistik medis &nbsp;·&nbsp;
                <span class="font-semibold text-teal-600 dark:text-teal-400">{{ $medicines->total() }} item</span>
            </p>
        </div>
        <button @click="openAdd()"
            class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl text-sm font-bold text-white
                   bg-gradient-to-r from-teal-600 to-cyan-500 hover:from-teal-700 hover:to-cyan-600
                   shadow-lg shadow-teal-500/30 hover:-translate-y-0.5 transition-all duration-200 flex-shrink-0">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
            </svg>
            Tambah Item
        </button>
    </div>

    {{-- Stat cards --}}
    @php
        $total      = $totalCount;
        $menipis    = $menipisCount;
        $habis      = $habisCount;
    @endphp
    <div class="grid grid-cols-2 md:grid-cols-5 gap-3 mb-6">
        @foreach([
            ['label'=>'Total Item',    'val'=>$total,       'color'=>'teal',   'icon'=>'M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4'],
            ['label'=>'Stok Menipis',  'val'=>$menipis,     'color'=>'amber',  'icon'=>'M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z'],
            ['label'=>'Habis',         'val'=>$habis,       'color'=>'rose',   'icon'=>'M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z'],
            ['label'=>'Kadaluarsa',    'val'=>$expiredCount,'color'=>'red',    'icon'=>'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z'],
            ['label'=>'Near Expiry',   'val'=>$nearExpiryCount, 'color'=>'orange','icon'=>'M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z'],
        ] as $s)
        <div class="inv-stat bg-white dark:bg-[#1E293B] border border-gray-100 dark:border-gray-800 rounded-xl p-4 shadow-sm">
            <div class="flex items-center gap-2 mb-2">
                <div class="w-8 h-8 rounded-lg bg-{{ $s['color'] }}-100 dark:bg-{{ $s['color'] }}-900/30 flex items-center justify-center flex-shrink-0">
                    <svg class="w-4 h-4 text-{{ $s['color'] }}-600 dark:text-{{ $s['color'] }}-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $s['icon'] }}"/>
                    </svg>
                </div>
                <p class="text-xs font-bold text-gray-500 dark:text-gray-400">{{ $s['label'] }}</p>
            </div>
            <p class="text-2xl font-black text-gray-900 dark:text-white">{{ $s['val'] }}</p>
        </div>
        @endforeach
    </div>

    {{-- Priority Alerts --}}
    <div class="space-y-3 mb-6">
        @if($expiredCount > 0)
        <div class="bg-rose-50 dark:bg-rose-900/20 border border-rose-200 dark:border-rose-800/40 rounded-xl p-4 flex items-center gap-3">
            <svg class="w-5 h-5 text-rose-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <p class="text-sm font-semibold text-rose-800 dark:text-rose-400">
                <span class="font-bold underline">{{ $expiredCount }} item telah kadaluarsa!</span> Segera pindahkan dari rak aktif.
            </p>
        </div>
        @endif

        @if($nearExpiryCount > 0)
        <div class="bg-orange-50 dark:bg-orange-900/20 border border-orange-200 dark:border-orange-800/40 rounded-xl p-4 flex items-center gap-3">
            <svg class="w-5 h-5 text-orange-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
            </svg>
            <p class="text-sm font-semibold text-orange-800 dark:text-orange-400">
                {{ $nearExpiryCount }} item mendekati kadaluarsa dalam 30 hari ke depan.
            </p>
        </div>
        @endif

        @if($menipis > 0 || $habis > 0)
        <div class="bg-amber-50 dark:bg-amber-900/20 border border-amber-200 dark:border-amber-800/40 rounded-xl p-4 flex items-center gap-3">
            <svg class="w-5 h-5 text-amber-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
            </svg>
            <p class="text-sm font-semibold text-amber-800 dark:text-amber-400">
                {{ $menipis + $habis }} item stok menipis atau habis — segera lakukan restock.
            </p>
        </div>
        @endif
    </div>

    {{-- Search & Filter --}}
    <div class="mb-5 flex flex-col md:flex-row gap-4 items-center justify-between">
        <form id="inv_search_form" @submit.prevent="updateInventori()" action="{{ route('admin.dashboard') }}" method="GET" class="flex flex-col sm:flex-row gap-3 w-full md:w-auto">
            <input type="hidden" name="tab" value="inventori">
            
            <div class="relative group flex-1 sm:w-72">
                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                    <svg class="w-4 h-4 text-gray-400 group-focus-within:text-teal-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                </div>
                <input type="text" name="med_search" value="{{ $medSearch }}"
                    @input.debounce.500ms="updateInventori()"
                    placeholder="Cari nama obat atau brand..."
                    class="w-full pl-10 pr-4 py-2.5 bg-white dark:bg-[#1E293B] border border-gray-100 dark:border-gray-800
                           rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-teal-500/20 focus:border-teal-500
                           dark:text-white transition-all shadow-sm">
            </div>

            <div class="flex gap-2">
                <select name="med_sort" @change="updateInventori()"
                    class="pl-4 pr-10 py-2.5 bg-white dark:bg-[#1E293B] border border-gray-100 dark:border-gray-800
                           rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-teal-500/20 focus:border-teal-500
                           dark:text-white transition-all shadow-sm appearance-none cursor-pointer">
                    <option value="name_asc" {{ $medSort == 'name_asc' ? 'selected' : '' }}>Nama (A-Z)</option>
                    <option value="name_desc" {{ $medSort == 'name_desc' ? 'selected' : '' }}>Nama (Z-A)</option>
                    <option value="stock_asc" {{ $medSort == 'stock_asc' ? 'selected' : '' }}>Stok Terendah</option>
                    <option value="stock_desc" {{ $medSort == 'stock_desc' ? 'selected' : '' }}>Stok Tertinggi</option>
                    <option value="price_asc" {{ $medSort == 'price_asc' ? 'selected' : '' }}>Harga Termurah</option>
                    <option value="price_desc" {{ $medSort == 'price_desc' ? 'selected' : '' }}>Harga Termahal</option>
                    <option value="expired_asc" {{ $medSort == 'expired_asc' ? 'selected' : '' }}>Kadaluarsa (Terdekat)</option>
                    <option value="expired_desc" {{ $medSort == 'expired_desc' ? 'selected' : '' }}>Kadaluarsa (Terjauh)</option>
                    <option value="latest" {{ $medSort == 'latest' ? 'selected' : '' }}>Terbaru</option>
                </select>

                <input type="hidden" name="med_filter" id="med_filter_input" value="{{ $medFilter }}">
                <button type="button" 
                    onclick="document.getElementById('med_filter_input').value = (document.getElementById('med_filter_input').value === 'low_stock' ? '' : 'low_stock');"
                    @click="updateInventori()"
                    class="px-4 py-2.5 rounded-xl text-sm font-bold transition-all flex items-center gap-2
                           {{ $medFilter == 'low_stock' ? 'bg-amber-100 text-amber-700 border-amber-200 shadow-amber-100' : 'bg-white dark:bg-[#1E293B] text-gray-600 dark:text-gray-400 border-gray-100 dark:border-gray-800' }} border shadow-sm hover:shadow-md">
                    Stok Menipis
                </button>

                <button type="button" 
                    onclick="document.getElementById('med_filter_input').value = (document.getElementById('med_filter_input').value === 'expired' ? '' : 'expired');"
                    @click="updateInventori()"
                    class="px-4 py-2.5 rounded-xl text-sm font-bold transition-all flex items-center gap-2
                           {{ $medFilter == 'expired' ? 'bg-red-100 text-red-700 border-red-200 shadow-red-100' : 'bg-white dark:bg-[#1E293B] text-gray-600 dark:text-gray-400 border-gray-100 dark:border-gray-800' }} border shadow-sm hover:shadow-md">
                    Kadaluarsa
                </button>

                <button type="button" 
                    onclick="document.getElementById('med_filter_input').value = (document.getElementById('med_filter_input').value === 'near_expiry' ? '' : 'near_expiry');"
                    @click="updateInventori()"
                    class="px-4 py-2.5 rounded-xl text-sm font-bold transition-all flex items-center gap-2
                           {{ $medFilter == 'near_expiry' ? 'bg-orange-100 text-orange-700 border-orange-200 shadow-orange-100' : 'bg-white dark:bg-[#1E293B] text-gray-600 dark:text-gray-400 border-gray-100 dark:border-gray-800' }} border shadow-sm hover:shadow-md">
                    Hampir Exp
                </button>
                
                @if($medSearch || $medSort != 'name_asc' || $medFilter)
                <a href="{{ route('admin.dashboard', ['tab' => 'inventori']) }}" 
                    class="px-4 py-2.5 bg-gray-100 dark:bg-gray-800 text-gray-500 dark:text-gray-400 rounded-xl hover:bg-gray-200 dark:hover:bg-gray-700 transition-all flex items-center justify-center"
                    title="Reset Filter">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </a>
                @endif
            </div>
        </form>
    </div>

    {{-- Table --}}
    <div class="bg-white dark:bg-[#1E293B] border border-gray-100 dark:border-gray-800 rounded-2xl shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50 dark:bg-gray-800/60 border-b border-gray-100 dark:border-gray-800 text-[10px] uppercase tracking-widest text-gray-500 dark:text-gray-400 font-black">
                        <th class="px-5 py-3.5">
                            <a href="{{ $getSortLink('name') }}" @click.prevent="updateInventori($el.href)" class="flex items-center gap-1.5 hover:text-teal-600 transition-colors group">
                                Item
                                <div class="flex flex-col opacity-30 group-hover:opacity-100 transition-opacity">
                                    <svg class="w-2 h-2 {{ str_contains($medSort, 'name_asc') ? 'text-teal-500 opacity-100' : '' }}" fill="currentColor" viewBox="0 0 24 24"><path d="M12 4l-8 8h16l-8-8z"/></svg>
                                    <svg class="w-2 h-2 -mt-0.5 {{ str_contains($medSort, 'name_desc') ? 'text-teal-500 opacity-100' : '' }}" fill="currentColor" viewBox="0 0 24 24"><path d="M12 20l8-8H4l8 8z"/></svg>
                                </div>
                            </a>
                        </th>
                        <th class="px-5 py-3.5">Kategori</th>
                        <th class="px-5 py-3.5">
                            <a href="{{ $getSortLink('stock') }}" @click.prevent="updateInventori($el.href)" class="flex items-center justify-center gap-1.5 hover:text-teal-600 transition-colors group">
                                Stok
                                <div class="flex flex-col opacity-30 group-hover:opacity-100 transition-opacity">
                                    <svg class="w-2 h-2 {{ str_contains($medSort, 'stock_asc') ? 'text-teal-500 opacity-100' : '' }}" fill="currentColor" viewBox="0 0 24 24"><path d="M12 4l-8 8h16l-8-8z"/></svg>
                                    <svg class="w-2 h-2 -mt-0.5 {{ str_contains($medSort, 'stock_desc') ? 'text-teal-500 opacity-100' : '' }}" fill="currentColor" viewBox="0 0 24 24"><path d="M12 20l8-8H4l8 8z"/></svg>
                                </div>
                            </a>
                        </th>
                        <th class="px-5 py-3.5">Status Stok</th>
                        <th class="px-5 py-3.5">
                            <a href="{{ $getSortLink('expired') }}" @click.prevent="updateInventori($el.href)" class="flex items-center gap-1.5 hover:text-teal-600 transition-colors group">
                                Kadaluarsa
                                <div class="flex flex-col opacity-30 group-hover:opacity-100 transition-opacity">
                                    <svg class="w-2 h-2 {{ str_contains($medSort, 'expired_asc') ? 'text-teal-500 opacity-100' : '' }}" fill="currentColor" viewBox="0 0 24 24"><path d="M12 4l-8 8h16l-8-8z"/></svg>
                                    <svg class="w-2 h-2 -mt-0.5 {{ str_contains($medSort, 'expired_desc') ? 'text-teal-500 opacity-100' : '' }}" fill="currentColor" viewBox="0 0 24 24"><path d="M12 20l8-8H4l8 8z"/></svg>
                                </div>
                            </a>
                        </th>
                        <th class="px-5 py-3.5">
                            <a href="{{ $getSortLink('price') }}" @click.prevent="updateInventori($el.href)" class="flex items-center gap-1.5 hover:text-teal-600 transition-colors group">
                                Harga
                                <div class="flex flex-col opacity-30 group-hover:opacity-100 transition-opacity">
                                    <svg class="w-2 h-2 {{ str_contains($medSort, 'price_asc') ? 'text-teal-500 opacity-100' : '' }}" fill="currentColor" viewBox="0 0 24 24"><path d="M12 4l-8 8h16l-8-8z"/></svg>
                                    <svg class="w-2 h-2 -mt-0.5 {{ str_contains($medSort, 'price_desc') ? 'text-teal-500 opacity-100' : '' }}" fill="currentColor" viewBox="0 0 24 24"><path d="M12 20l8-8H4l8 8z"/></svg>
                                </div>
                            </a>
                        </th>
                        <th class="px-5 py-3.5 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-800 text-sm">
                    @forelse($medicines as $med)
                    <tr class="inv-row">
                        <td class="px-5 py-3.5">
                            <p class="font-bold text-gray-900 dark:text-white text-sm">{{ $med->name }}</p>
                            <p class="text-xs text-gray-400 dark:text-gray-500 mt-0.5">{{ $med->brand ?? '—' }}</p>
                        </td>
                        <td class="px-5 py-3.5">
                            <span class="px-2.5 py-1 bg-gray-100 dark:bg-gray-800 text-gray-600 dark:text-gray-400 rounded-lg text-[10px] font-black uppercase tracking-wider">
                                {{ $med->category ?? 'Umum' }}
                            </span>
                        </td>
                        <td class="px-5 py-3.5 text-center">
                            <span class="font-black text-gray-900 dark:text-white">{{ $med->stock }}</span>
                            <span class="text-[10px] text-gray-400 ml-0.5">{{ $med->unit }}</span>
                        </td>
                        <td class="px-5 py-3.5">
                            @if($med->stock <= 0)
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 bg-rose-100 dark:bg-rose-900/30 text-rose-700 dark:text-rose-400 text-[10px] font-black uppercase rounded-full">
                                    Habis
                                </span>
                            @elseif($med->stock <= $med->min_stock)
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 bg-amber-100 dark:bg-amber-900/30 text-amber-700 dark:text-amber-400 text-[10px] font-black uppercase rounded-full">
                                    Menipis
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 bg-emerald-100 dark:bg-emerald-900/30 text-emerald-700 dark:text-emerald-400 text-[10px] font-black uppercase rounded-full">
                                    Cukup
                                </span>
                            @endif
                        </td>
                        <td class="px-5 py-3.5">
                            @php
                                $expStatus = $med->expiration_status;
                            @endphp
                            @if($expStatus === 'expired')
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 bg-red-100 dark:bg-red-900/30 text-red-700 dark:text-red-400 text-[10px] font-black uppercase rounded-full">
                                    Kadaluarsa
                                </span>
                            @elseif($expStatus === 'near_expiry')
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 bg-orange-100 dark:bg-orange-900/30 text-orange-700 dark:text-orange-400 text-[10px] font-black uppercase rounded-full">
                                    Hampir Exp
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 bg-emerald-100 dark:bg-emerald-900/30 text-emerald-700 dark:text-emerald-400 text-[10px] font-black uppercase rounded-full">
                                    Aman
                                </span>
                            @endif
                            <p class="text-[10px] text-gray-400 mt-1 font-bold">{{ $med->expired_at ? $med->expired_at->format('d/m/Y') : '—' }}</p>
                        </td>
                        <td class="px-5 py-3.5 font-bold text-teal-600 dark:text-teal-400 text-sm">
                            Rp {{ number_format($med->price, 0, ',', '.') }}
                        </td>
                        <td class="px-5 py-3.5">
                            <div class="flex justify-end gap-2">
                                <button @click="openEdit({{ $med->toJson() }})"
                                    class="p-2 rounded-lg border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800
                                           text-gray-500 dark:text-gray-400 hover:text-teal-600 dark:hover:text-teal-400
                                           hover:border-teal-300 dark:hover:border-teal-700 transition-all" title="Edit">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                    </svg>
                                </button>
                                <button @click="confirmDelete({{ $med->id }}, {{ json_encode($med->name) }})"
                                    class="p-2 rounded-lg border border-rose-100 dark:border-rose-900/50 bg-white dark:bg-gray-800
                                           text-rose-500 hover:bg-rose-50 dark:hover:bg-rose-900/20 transition-all" title="Hapus">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                    </svg>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-5 py-16 text-center">
                            <svg class="w-10 h-10 text-gray-300 dark:text-gray-600 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                            </svg>
                            <p class="text-sm font-bold text-gray-400 dark:text-gray-600">Belum ada data inventori</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    @if(isset($medicines) && $medicines->hasPages())
        <div class="mt-6 pt-6 border-t border-gray-100 dark:border-gray-800">
            {{ $medicines->links() }}
        </div>
    @endif

    {{-- ===== FORM MODAL — teleport ke body agar tidak terhalang navbar ===== --}}
    <template x-teleport="body">
        <div x-show="showModal" x-cloak
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="fixed inset-0 z-[999] flex items-center justify-center p-4"
             style="font-family:'Inter',sans-serif;">

            {{-- Backdrop --}}
            <div class="absolute inset-0 bg-gray-900/65 backdrop-blur-sm" @click="showModal = false"></div>

            {{-- Modal box --}}
            <div class="inv-modal-inner relative" @click.stop>

                {{-- Header --}}
                <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100 dark:border-gray-700 flex-shrink-0">
                    <div>
                        <h3 class="text-lg font-black text-gray-900 dark:text-white"
                            x-text="editMode ? 'Edit Item Obat' : 'Tambah Item Baru'"></h3>
                        <p class="text-xs text-gray-400 mt-0.5"
                           x-text="editMode ? 'Perbarui data item inventori' : 'Isi form untuk menambah item baru'"></p>
                    </div>
                    <button @click="showModal = false"
                        class="w-8 h-8 rounded-lg flex items-center justify-center text-gray-400
                               hover:bg-gray-100 dark:hover:bg-gray-700 hover:text-gray-600 transition-all">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>

                {{-- Form body (scrollable) --}}
                <form :action="editMode ? '{{ url('admin/medicines') }}/' + medicine.id : '{{ route('admin.medicines.store') }}'"
                      method="POST" class="p-6 flex-1 overflow-y-auto">
                    @csrf
                    <template x-if="editMode">
                        <input type="hidden" name="_method" value="PUT">
                    </template>

                    <div class="grid grid-cols-2 gap-4">
                        <div class="col-span-2 inv-field">
                            <label>Nama Obat <span class="text-rose-500">*</span></label>
                            <input type="text" name="name" x-model="medicine.name" required
                                   placeholder="Contoh: Paracetamol 500mg">
                        </div>
                        <div class="inv-field">
                            <label>Brand / Pabrik</label>
                            <input type="text" name="brand" x-model="medicine.brand" list="brand-suggestions"
                                   placeholder="Contoh: Kimia Farma">
                            <datalist id="brand-suggestions">
                                @if(isset($suggestedBrands))
                                    @foreach($suggestedBrands as $brand)
                                        <option value="{{ $brand }}"></option>
                                    @endforeach
                                @endif
                            </datalist>
                        </div>
                        <div class="inv-field">
                            <label>Kategori <span class="text-rose-500">*</span></label>
                            <input type="text" name="category" x-model="medicine.category" list="category-suggestions" required
                                   placeholder="Contoh: Vitamin, Antibiotik">
                            <datalist id="category-suggestions">
                                @if(isset($suggestedCategories))
                                    @foreach($suggestedCategories as $cat)
                                        <option value="{{ $cat }}"></option>
                                    @endforeach
                                @endif
                            </datalist>
                        </div>
                        <div class="inv-field">
                            <label>Stok <span class="text-rose-500">*</span></label>
                            <input type="number" name="stock" x-model="medicine.stock" required min="0">
                        </div>
                        <div class="inv-field">
                            <label>Satuan <span class="text-rose-500">*</span></label>
                            <select name="unit" x-model="medicine.unit" required>
                                <option value="" disabled selected>Pilih Satuan</option>
                                <option value="pcs">pcs</option>
                                <option value="tablet">tablet</option>
                                <option value="botol">botol</option>
                            </select>
                        </div>
                        <div class="inv-field">
                            <label>Harga Satuan <span class="text-rose-500">*</span></label>
                            <div class="relative">
                                <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm font-bold">Rp</span>
                                <input type="text" 
                                       :value="formatRupiah(medicine.price)"
                                       @input="medicine.price = $event.target.value.replace(/[^0-9]/g, '')"
                                       class="!pl-10" required placeholder="0">
                                <input type="hidden" name="price" :value="medicine.price">
                            </div>
                        </div>
                        <div class="inv-field">
                            <label>Min. Stok <span class="text-rose-500">*</span></label>
                            <input type="number" name="min_stock" x-model="medicine.min_stock" required min="0">
                        </div>
                        <div class="inv-field">
                            <label>Tgl Kadaluarsa</label>
                            <input type="date" name="expired_at" x-model="medicine.expired_at">
                        </div>
                    </div>

                    <div class="flex gap-3 mt-6 pt-4 border-t border-gray-100 dark:border-gray-700">
                        <button type="button" @click="showModal = false"
                            class="flex-1 px-4 py-2.5 bg-gray-100 dark:bg-gray-800 text-gray-700 dark:text-gray-300
                                   font-bold rounded-xl hover:bg-gray-200 dark:hover:bg-gray-700 transition-all text-sm">
                            Batal
                        </button>
                        <button type="submit"
                            class="flex-1 px-4 py-2.5 text-white font-bold rounded-xl transition-all text-sm
                                   bg-gradient-to-r from-teal-600 to-cyan-500 hover:from-teal-700 hover:to-cyan-600
                                   shadow-lg shadow-teal-500/30"
                            x-text="editMode ? 'Simpan Perubahan' : 'Tambah Item'">
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </template>

    {{-- ===== DELETE MODAL — teleport ke body ===== --}}
    <template x-teleport="body">
        <div x-show="showDeleteModal" x-cloak
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="fixed inset-0 z-[999] flex items-center justify-center p-4"
             style="font-family:'Inter',sans-serif;">

            <div class="absolute inset-0 bg-gray-900/65 backdrop-blur-sm" @click="showDeleteModal = false"></div>

            <div class="relative bg-white dark:bg-[#1E293B] w-full max-w-md rounded-2xl shadow-2xl overflow-hidden"
                 style="animation:invModalIn .22s cubic-bezier(.16,1,.3,1) both;" @click.stop>
                <div class="p-8 text-center">
                    <div class="w-16 h-16 bg-rose-100 dark:bg-rose-900/30 rounded-full flex items-center justify-center mx-auto mb-5">
                        <svg class="w-8 h-8 text-rose-600 dark:text-rose-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-black text-gray-900 dark:text-white mb-2">Hapus Item?</h3>
                    <p class="text-gray-500 dark:text-gray-400 text-sm mb-6">
                        Yakin ingin menghapus <span class="font-bold text-gray-900 dark:text-white" x-text="medicineToDelete.name"></span>?
                        Tindakan ini tidak dapat dibatalkan.
                    </p>
                    <div class="flex gap-3">
                        <button @click="showDeleteModal = false"
                            class="flex-1 px-4 py-2.5 bg-gray-100 dark:bg-gray-800 text-gray-700 dark:text-gray-300
                                   font-bold rounded-xl hover:bg-gray-200 dark:hover:bg-gray-700 transition-all text-sm">
                            Batal
                        </button>
                        <button @click="executeDelete()"
                            class="flex-1 px-4 py-2.5 bg-rose-600 text-white font-bold rounded-xl
                                   hover:bg-rose-700 shadow-lg shadow-rose-500/30 transition-all text-sm">
                            Hapus Sekarang
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </template>

    </div> {{-- Close data-inv-container --}}
</div>
