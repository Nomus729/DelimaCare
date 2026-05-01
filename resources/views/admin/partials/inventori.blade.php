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
</style>

<div x-data="{
    showModal: false,
    editMode: false,
    medicine: { id:'', name:'', brand:'', category:'', stock:0, unit:'pcs', price:0, min_stock:10 },
    openAdd() {
        this.editMode = false;
        this.medicine = { id:'', name:'', brand:'', category:'', stock:0, unit:'pcs', price:0, min_stock:10 };
        this.showModal = true;
    },
    openEdit(med) { this.editMode = true; this.medicine = {...med}; this.showModal = true; },
    showDeleteModal: false,
    medicineToDelete: { id:'', name:'' },
    confirmDelete(id, name) { this.medicineToDelete = {id, name}; this.showDeleteModal = true; },
    executeDelete() {
        const f = document.createElement('form'); f.method='POST';
        f.action=`{{ url('admin/medicines') }}/${this.medicineToDelete.id}`;
        const c=document.createElement('input'); c.type='hidden'; c.name='_token'; c.value='{{ csrf_token() }}';
        const m=document.createElement('input'); m.type='hidden'; m.name='_method'; m.value='DELETE';
        f.appendChild(c); f.appendChild(m); document.body.appendChild(f); f.submit();
    }
}">

    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
        <div>
            <h2 class="text-2xl font-extrabold text-gray-900 dark:text-white mb-0.5">Inventori Obat</h2>
            <p class="text-sm text-gray-500 dark:text-gray-400">Kelola stok obat dan logistik medis &nbsp;·&nbsp;
                <span class="font-semibold text-teal-600 dark:text-teal-400">{{ $medicines->count() }} item</span>
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
        $total      = $medicines->count();
        $cukup      = $medicines->filter(fn($m) => $m->stock > $m->min_stock)->count();
        $menipis    = $medicines->filter(fn($m) => $m->stock > 0 && $m->stock <= $m->min_stock)->count();
        $habis      = $medicines->filter(fn($m) => $m->stock <= 0)->count();
    @endphp
    <div class="grid grid-cols-2 md:grid-cols-4 gap-3 mb-6">
        @foreach([
            ['label'=>'Total Item',    'val'=>$total,   'color'=>'teal',   'icon'=>'M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4'],
            ['label'=>'Stok Cukup',    'val'=>$cukup,   'color'=>'emerald','icon'=>'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z'],
            ['label'=>'Stok Menipis',  'val'=>$menipis, 'color'=>'amber',  'icon'=>'M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z'],
            ['label'=>'Habis',         'val'=>$habis,   'color'=>'rose',   'icon'=>'M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z'],
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

    {{-- Low stock alert --}}
    @if($menipis > 0 || $habis > 0)
    <div class="bg-amber-50 dark:bg-amber-900/20 border border-amber-200 dark:border-amber-800/40 rounded-xl p-4 mb-5 flex items-center gap-3">
        <svg class="w-5 h-5 text-amber-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
        </svg>
        <p class="text-sm font-semibold text-amber-800 dark:text-amber-400">
            {{ $menipis + $habis }} item perlu perhatian — segera lakukan restock.
        </p>
    </div>
    @endif

    {{-- Search & Filter --}}
    <div class="mb-5 flex flex-col md:flex-row gap-4 items-center justify-between">
        <form action="{{ route('admin.dashboard') }}" method="GET" class="flex flex-col sm:flex-row gap-3 w-full md:w-auto">
            <input type="hidden" name="tab" value="inventori">
            
            <div class="relative group flex-1 sm:w-72">
                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                    <svg class="w-4 h-4 text-gray-400 group-focus-within:text-teal-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                </div>
                <input type="text" name="med_search" value="{{ $medSearch }}"
                    placeholder="Cari nama obat atau brand..."
                    class="w-full pl-10 pr-4 py-2.5 bg-white dark:bg-[#1E293B] border border-gray-100 dark:border-gray-800
                           rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-teal-500/20 focus:border-teal-500
                           dark:text-white transition-all shadow-sm">
            </div>

            <div class="flex gap-2">
                <select name="med_sort" onchange="this.form.submit()"
                    class="pl-4 pr-10 py-2.5 bg-white dark:bg-[#1E293B] border border-gray-100 dark:border-gray-800
                           rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-teal-500/20 focus:border-teal-500
                           dark:text-white transition-all shadow-sm appearance-none cursor-pointer">
                    <option value="name_asc" {{ $medSort == 'name_asc' ? 'selected' : '' }}>Nama (A-Z)</option>
                    <option value="name_desc" {{ $medSort == 'name_desc' ? 'selected' : '' }}>Nama (Z-A)</option>
                    <option value="stock_asc" {{ $medSort == 'stock_asc' ? 'selected' : '' }}>Stok Terendah</option>
                    <option value="stock_desc" {{ $medSort == 'stock_desc' ? 'selected' : '' }}>Stok Tertinggi</option>
                    <option value="price_asc" {{ $medSort == 'price_asc' ? 'selected' : '' }}>Harga Termurah</option>
                    <option value="price_desc" {{ $medSort == 'price_desc' ? 'selected' : '' }}>Harga Termahal</option>
                    <option value="latest" {{ $medSort == 'latest' ? 'selected' : '' }}>Terbaru</option>
                </select>

                <input type="hidden" name="med_filter" id="med_filter_input" value="{{ $medFilter }}">
                <button type="button" 
                    onclick="document.getElementById('med_filter_input').value = (document.getElementById('med_filter_input').value === 'low_stock' ? '' : 'low_stock'); this.form.submit();"
                    class="px-4 py-2.5 rounded-xl text-sm font-bold transition-all flex items-center gap-2
                           {{ $medFilter == 'low_stock' ? 'bg-rose-100 text-rose-700 border-rose-200 shadow-rose-100' : 'bg-white dark:bg-[#1E293B] text-gray-600 dark:text-gray-400 border-gray-100 dark:border-gray-800' }} border shadow-sm hover:shadow-md">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                    </svg>
                    Stok Menipis
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
                        <th class="px-5 py-3.5">Item</th>
                        <th class="px-5 py-3.5">Kategori</th>
                        <th class="px-5 py-3.5 text-center">Stok</th>
                        <th class="px-5 py-3.5">Status</th>
                        <th class="px-5 py-3.5">Harga</th>
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
                                    <span class="w-1.5 h-1.5 rounded-full bg-rose-500 animate-pulse"></span>Habis
                                </span>
                            @elseif($med->stock <= $med->min_stock)
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 bg-amber-100 dark:bg-amber-900/30 text-amber-700 dark:text-amber-400 text-[10px] font-black uppercase rounded-full">
                                    <span class="w-1.5 h-1.5 rounded-full bg-amber-500 animate-pulse"></span>Menipis
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 bg-emerald-100 dark:bg-emerald-900/30 text-emerald-700 dark:text-emerald-400 text-[10px] font-black uppercase rounded-full">
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>Cukup
                                </span>
                            @endif
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
                            <input type="text" name="brand" x-model="medicine.brand"
                                   placeholder="Contoh: Kimia Farma">
                        </div>
                        <div class="inv-field">
                            <label>Kategori</label>
                            <input type="text" name="category" x-model="medicine.category"
                                   placeholder="Contoh: Analgesik">
                        </div>
                        <div class="inv-field">
                            <label>Stok <span class="text-rose-500">*</span></label>
                            <input type="number" name="stock" x-model="medicine.stock" required min="0">
                        </div>
                        <div class="inv-field">
                            <label>Satuan <span class="text-rose-500">*</span></label>
                            <input type="text" name="unit" x-model="medicine.unit" required
                                   placeholder="pcs, tablet, botol">
                        </div>
                        <div class="inv-field">
                            <label>Harga Satuan (Rp) <span class="text-rose-500">*</span></label>
                            <input type="number" name="price" x-model="medicine.price" required min="0">
                        </div>
                        <div class="inv-field">
                            <label>Min. Stok <span class="text-rose-500">*</span></label>
                            <input type="number" name="min_stock" x-model="medicine.min_stock" required min="0">
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

</div>
