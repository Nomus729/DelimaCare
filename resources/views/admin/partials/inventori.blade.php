<div>
    <div class="flex justify-between items-center mb-6">
        <div>
            <h2 class="text-3xl font-extrabold text-gray-900 mb-1">Manajemen Inventori</h2>
            <p class="text-gray-500">Kelola stok obat dan logistik medis</p>
        </div>
        <button class="bg-black text-white px-5 py-2.5 rounded-lg text-sm font-semibold flex items-center gap-2 hover:bg-gray-800">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg> Tambah Item
        </button>
    </div>

    <div class="bg-orange-50 border border-orange-200 rounded-xl p-4 mb-6 flex items-start gap-3">
        <svg class="w-5 h-5 text-orange-600 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
        <div>
            <h4 class="font-bold text-orange-800">Peringatan Stok Menipis</h4>
            <p class="text-sm text-orange-700">Terdapat 4 item dengan stok di bawah minimum. Segera lakukan restock.</p>
        </div>
    </div>

    <div class="bg-white border border-gray-200 rounded-2xl shadow-sm overflow-hidden">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-gray-50 border-b border-gray-200 text-sm text-gray-600">
                    <th class="p-4 font-semibold">Item</th>
                    <th class="p-4 font-semibold">Kategori</th>
                    <th class="p-4 font-semibold">Stok</th>
                    <th class="p-4 font-semibold">Status</th>
                    <th class="p-4 font-semibold">Harga</th>
                    <th class="p-4 font-semibold">Aksi</th>
                </tr>
            </thead>
            <tbody class="text-sm divide-y divide-gray-100">
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="p-4">
                        <p class="font-bold text-gray-900">Asam Folat 400mg</p>
                        <p class="text-xs text-gray-500">PT Pharma Indo</p>
                    </td>
                    <td class="p-4"><span class="px-2 py-1 border border-gray-300 rounded text-xs font-medium">Vitamin</span></td>
                    <td class="p-4 font-bold text-gray-900">45 tablet</td>
                    <td class="p-4"><span class="px-3 py-1 bg-orange-100 text-orange-700 text-xs font-bold rounded-full">Menipis</span></td>
                    <td class="p-4 font-medium">Rp 500</td>
                    <td class="p-4 flex gap-2">
                        <button class="px-3 py-1.5 bg-black text-white text-xs font-bold rounded hover:bg-gray-800">Restock</button>
                    </td>
                </tr>
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="p-4">
                        <p class="font-bold text-gray-900">IUD Copper T</p>
                        <p class="text-xs text-gray-500">PT Alkes Medika</p>
                    </td>
                    <td class="p-4"><span class="px-2 py-1 border border-gray-300 rounded text-xs font-medium">Alat KB</span></td>
                    <td class="p-4 font-bold text-gray-900">25 pcs</td>
                    <td class="p-4"><span class="px-3 py-1 bg-green-100 text-green-700 text-xs font-bold rounded-full">Cukup</span></td>
                    <td class="p-4 font-medium">Rp 150.000</td>
                    <td class="p-4 flex gap-2">
                        <button class="px-3 py-1.5 border border-gray-300 text-gray-700 text-xs font-bold rounded hover:bg-gray-100">Edit</button>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
