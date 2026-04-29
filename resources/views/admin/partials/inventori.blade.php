<div x-data="{ 
    showModal: false, 
    editMode: false, 
    medicine: {
        id: '',
        name: '',
        brand: '',
        category: '',
        stock: 0,
        unit: 'pcs',
        price: 0,
        min_stock: 10
    },
    openAddModal() {
        this.editMode = false;
        this.medicine = { id: '', name: '', brand: '', category: '', stock: 0, unit: 'pcs', price: 0, min_stock: 10 };
        this.showModal = true;
    },
    openEditModal(med) {
        this.editMode = true;
        this.medicine = { ...med };
        this.showModal = true;
    },
    showDeleteModal: false,
    medicineToDelete: { id: '', name: '' },
    confirmDelete(id, name) {
        this.medicineToDelete = { id, name };
        this.showDeleteModal = true;
    },
    executeDelete() {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `{{ url('admin/medicines') }}/${this.medicineToDelete.id}`;
        
        const csrf = document.createElement('input');
        csrf.type = 'hidden';
        csrf.name = '_token';
        csrf.value = '{{ csrf_token() }}';
        
        const method = document.createElement('input');
        method.type = 'hidden';
        method.name = '_method';
        method.value = 'DELETE';
        
        form.appendChild(csrf);
        form.appendChild(method);
        document.body.appendChild(form);
        form.submit();
    }
}">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h2 class="text-3xl font-extrabold text-gray-900 dark:text-white mb-1">Manajemen Inventori</h2>
            <p class="text-gray-500 dark:text-gray-400">Kelola stok obat dan logistik medis</p>
        </div>
        <button @click="openAddModal()" class="bg-teal-600 text-white px-5 py-2.5 rounded-lg text-sm font-semibold flex items-center gap-2 hover:bg-teal-700 transition-all shadow-lg shadow-teal-500/20">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg> Tambah Item
        </button>
    </div>


    @php
        $lowStockCount = $medicines->filter(function($med) {
            return $med->stock <= $med->min_stock;
        })->count();
    @endphp

    @if($lowStockCount > 0)
    <div class="bg-amber-50 dark:bg-amber-900/20 border border-amber-200 dark:border-amber-800/50 rounded-xl p-4 mb-6 flex items-start gap-3">
        <svg class="w-5 h-5 text-amber-600 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
        <div>
            <h4 class="font-bold text-amber-800 dark:text-amber-400">Peringatan Stok Menipis</h4>
            <p class="text-sm text-amber-700 dark:text-amber-500/80">Terdapat {{ $lowStockCount }} item dengan stok di bawah minimum. Segera lakukan restock.</p>
        </div>
    </div>
    @endif

    <div class="bg-white dark:bg-[#1E293B] border border-gray-100 dark:border-gray-800 rounded-2xl shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50 dark:bg-gray-800/50 border-b border-gray-100 dark:border-gray-800 text-xs uppercase tracking-wider text-gray-500 dark:text-gray-400">
                        <th class="p-4 font-bold">Item</th>
                        <th class="p-4 font-bold">Kategori</th>
                        <th class="p-4 font-bold text-center">Stok</th>
                        <th class="p-4 font-bold">Status</th>
                        <th class="p-4 font-bold">Harga</th>
                        <th class="p-4 font-bold text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="text-sm divide-y divide-gray-100 dark:divide-gray-800">
                    @forelse($medicines as $med)
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/40 transition-colors group">
                        <td class="p-4">
                            <p class="font-bold text-gray-900 dark:text-white">{{ $med->name }}</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400">{{ $med->brand ?? '-' }}</p>
                        </td>
                        <td class="p-4">
                            <span class="px-2.5 py-1 bg-gray-100 dark:bg-gray-800 text-gray-600 dark:text-gray-400 rounded text-[10px] font-bold uppercase">{{ $med->category ?? 'Umum' }}</span>
                        </td>
                        <td class="p-4 text-center font-bold text-gray-900 dark:text-white">
                            {{ $med->stock }} <span class="text-[10px] font-medium text-gray-400">{{ $med->unit }}</span>
                        </td>
                        <td class="p-4">
                            @if($med->stock <= 0)
                                <span class="px-3 py-1 bg-rose-100 dark:bg-rose-900/30 text-rose-700 dark:text-rose-400 text-[10px] font-black uppercase rounded-full">Habis</span>
                            @elseif($med->stock <= $med->min_stock)
                                <span class="px-3 py-1 bg-amber-100 dark:bg-amber-900/30 text-amber-700 dark:text-amber-400 text-[10px] font-black uppercase rounded-full">Menipis</span>
                            @else
                                <span class="px-3 py-1 bg-emerald-100 dark:bg-emerald-900/30 text-emerald-700 dark:text-emerald-400 text-[10px] font-black uppercase rounded-full">Cukup</span>
                            @endif
                        </td>
                        <td class="p-4 font-bold text-teal-600 dark:text-teal-400">
                            Rp {{ number_format($med->price, 0, ',', '.') }}
                        </td>
                        <td class="p-4">
                            <div class="flex justify-end gap-2 transition-opacity">
                                <button @click="openEditModal({{ $med->toJson() }})" class="p-2 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 text-gray-600 dark:text-gray-400 rounded-lg hover:text-teal-600 dark:hover:text-teal-400 transition-colors shadow-sm" title="Edit">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                </button>
                                <button @click="confirmDelete({{ $med->id }}, {{ json_encode($med->name) }})" class="p-2 bg-white dark:bg-gray-800 border border-rose-100 dark:border-rose-900/50 text-rose-500 rounded-lg hover:bg-rose-50 dark:hover:bg-rose-900/20 transition-colors shadow-sm" title="Hapus">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="p-12 text-center text-gray-400 dark:text-gray-600 italic">
                            Belum ada data inventori.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Modal Form --}}
    <div x-show="showModal" x-cloak class="fixed inset-0 z-[100] flex items-center justify-center p-4">
        <div @click="showModal = false" class="absolute inset-0 bg-gray-900/60 backdrop-blur-sm transition-opacity"></div>
        
        <div class="relative bg-white dark:bg-[#1E293B] w-full max-w-lg rounded-3xl shadow-2xl overflow-hidden anim-up" x-transition>
            <div class="p-6 border-b border-gray-100 dark:border-gray-800 flex justify-between items-center">
                <h3 class="text-xl font-black text-gray-900 dark:text-white" x-text="editMode ? 'Edit Item' : 'Tambah Item Baru'"></h3>
                <button @click="showModal = false" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-200">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>

            <form :action="editMode ? '{{ url('admin/medicines') }}/' + medicine.id : '{{ route('admin.medicines.store') }}'" method="POST" class="p-6 space-y-4">
                @csrf
                <template x-if="editMode">
                    @method('PUT')
                </template>

                <div class="grid grid-cols-2 gap-4">
                    <div class="col-span-2">
                        <label class="block text-xs font-bold text-gray-500 dark:text-gray-400 mb-1 uppercase tracking-wider">Nama Obat</label>
                        <input type="text" name="name" x-model="medicine.name" required class="w-full px-4 py-2.5 bg-gray-50 dark:bg-gray-800 border border-gray-100 dark:border-gray-700 rounded-xl focus:ring-2 focus:ring-teal-500 outline-none transition-all dark:text-white">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-500 dark:text-gray-400 mb-1 uppercase tracking-wider">Brand/Pabrik</label>
                        <input type="text" name="brand" x-model="medicine.brand" class="w-full px-4 py-2.5 bg-gray-50 dark:bg-gray-800 border border-gray-100 dark:border-gray-700 rounded-xl focus:ring-2 focus:ring-teal-500 outline-none transition-all dark:text-white">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-500 dark:text-gray-400 mb-1 uppercase tracking-wider">Kategori</label>
                        <input type="text" name="category" x-model="medicine.category" class="w-full px-4 py-2.5 bg-gray-50 dark:bg-gray-800 border border-gray-100 dark:border-gray-700 rounded-xl focus:ring-2 focus:ring-teal-500 outline-none transition-all dark:text-white">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-500 dark:text-gray-400 mb-1 uppercase tracking-wider">Stok Awal</label>
                        <input type="number" name="stock" x-model="medicine.stock" required class="w-full px-4 py-2.5 bg-gray-50 dark:bg-gray-800 border border-gray-100 dark:border-gray-700 rounded-xl focus:ring-2 focus:ring-teal-500 outline-none transition-all dark:text-white">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-500 dark:text-gray-400 mb-1 uppercase tracking-wider">Satuan</label>
                        <input type="text" name="unit" x-model="medicine.unit" required placeholder="pcs, tablet, botol" class="w-full px-4 py-2.5 bg-gray-50 dark:bg-gray-800 border border-gray-100 dark:border-gray-700 rounded-xl focus:ring-2 focus:ring-teal-500 outline-none transition-all dark:text-white">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-500 dark:text-gray-400 mb-1 uppercase tracking-wider">Harga Satuan (Rp)</label>
                        <input type="number" name="price" x-model="medicine.price" required class="w-full px-4 py-2.5 bg-gray-50 dark:bg-gray-800 border border-gray-100 dark:border-gray-700 rounded-xl focus:ring-2 focus:ring-teal-500 outline-none transition-all dark:text-white">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-500 dark:text-gray-400 mb-1 uppercase tracking-wider">Batas Min. Stok</label>
                        <input type="number" name="min_stock" x-model="medicine.min_stock" required class="w-full px-4 py-2.5 bg-gray-50 dark:bg-gray-800 border border-gray-100 dark:border-gray-700 rounded-xl focus:ring-2 focus:ring-teal-500 outline-none transition-all dark:text-white">
                    </div>
                </div>

                <div class="pt-4 flex gap-3">
                    <button type="button" @click="showModal = false" class="flex-1 px-4 py-3 bg-gray-100 dark:bg-gray-800 text-gray-700 dark:text-gray-300 font-bold rounded-xl hover:bg-gray-200 dark:hover:bg-gray-700 transition-all">Batal</button>
                    <button type="submit" class="flex-1 px-4 py-3 bg-teal-600 text-white font-bold rounded-xl hover:bg-teal-700 shadow-lg shadow-teal-500/30 transition-all" x-text="editMode ? 'Simpan Perubahan' : 'Tambah Item'"></button>
                </div>
            </form>
        </div>
    </div>
    {{-- Custom Delete Confirmation Modal --}}
    <div x-show="showDeleteModal" x-cloak class="fixed inset-0 z-[110] flex items-center justify-center p-4">
        <div @click="showDeleteModal = false" class="absolute inset-0 bg-gray-900/60 backdrop-blur-sm transition-opacity"></div>
        
        <div class="relative bg-white dark:bg-[#1E293B] w-full max-w-md rounded-3xl shadow-2xl overflow-hidden anim-up" x-transition>
            <div class="p-8 text-center">
                <div class="w-20 h-20 bg-rose-100 dark:bg-rose-900/30 rounded-full flex items-center justify-center mx-auto mb-6">
                    <svg class="w-10 h-10 text-rose-600 dark:text-rose-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                    </svg>
                </div>
                
                <h3 class="text-2xl font-black text-gray-900 dark:text-white mb-2">Hapus Item?</h3>
                <p class="text-gray-500 dark:text-gray-400 mb-6">
                    Apakah Anda yakin ingin menghapus <span class="font-bold text-gray-900 dark:text-white" x-text="medicineToDelete.name"></span>? Tindakan ini tidak dapat dibatalkan.
                </p>

                <div class="flex gap-3">
                    <button @click="showDeleteModal = false" class="flex-1 px-4 py-3 bg-gray-100 dark:bg-gray-800 text-gray-700 dark:text-gray-300 font-bold rounded-xl hover:bg-gray-200 dark:hover:bg-gray-700 transition-all">
                        Batal
                    </button>
                    <button @click="executeDelete()" class="flex-1 px-4 py-3 bg-rose-600 text-white font-bold rounded-xl hover:bg-rose-700 shadow-lg shadow-rose-500/30 transition-all">
                        Hapus Sekarang
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
