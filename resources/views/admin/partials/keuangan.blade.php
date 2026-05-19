<div>
    <div class="flex justify-between items-center mb-6">
        <div>
            <h2 class="text-3xl font-extrabold text-gray-900 mb-1">Manajemen Keuangan</h2>
            <p class="text-gray-500">Pantau pendapatan dan pengeluaran klinik</p>
        </div>
        <button class="bg-black text-white px-5 py-2.5 rounded-lg text-sm font-semibold flex items-center gap-2 hover:bg-gray-800">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg> Export Laporan
        </button>
    </div>

    <!-- Chart -->
    <div class="bg-white border border-gray-200 rounded-2xl p-6 shadow-sm mb-8">
        <div class="flex justify-between items-center mb-4">
            <div>
                <h4 class="font-bold text-gray-900">Tren Keuangan 6 Bulan Terakhir</h4>
                <p class="text-sm text-gray-500">Perbandingan pendapatan, pengeluaran, dan laba bersih</p>
            </div>
        </div>
        <div id="chartKeuangan" class="w-full h-[350px]"></div>
    </div>

    <!-- Tabel dan Form Pengeluaran -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-1">
            <div class="bg-white border border-gray-200 rounded-2xl p-6 shadow-sm">
                <h4 class="font-bold text-gray-900 mb-4">Catat Pengeluaran Baru</h4>
                <form action="{{ route('admin.pengeluaran.store') }}" method="POST">
                    @csrf
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Judul Pengeluaran</label>
                            <input type="text" name="judul" required class="w-full border-gray-300 rounded-lg shadow-sm focus:border-teal-500 focus:ring-teal-500 sm:text-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Kategori</label>
                            <select name="kategori" required class="w-full border-gray-300 rounded-lg shadow-sm focus:border-teal-500 focus:ring-teal-500 sm:text-sm">
                                <option value="Operasional">Operasional</option>
                                <option value="Gaji Pegawai">Gaji Pegawai</option>
                                <option value="Pembelian Alat">Pembelian Alat</option>
                                <option value="Lainnya">Lainnya</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Nominal (Rp)</label>
                            <input type="number" name="nominal" required min="0" class="w-full border-gray-300 rounded-lg shadow-sm focus:border-teal-500 focus:ring-teal-500 sm:text-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal</label>
                            <input type="date" name="tanggal" required value="{{ date('Y-m-d') }}" class="w-full border-gray-300 rounded-lg shadow-sm focus:border-teal-500 focus:ring-teal-500 sm:text-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Keterangan</label>
                            <textarea name="keterangan" rows="2" class="w-full border-gray-300 rounded-lg shadow-sm focus:border-teal-500 focus:ring-teal-500 sm:text-sm"></textarea>
                        </div>
                        <button type="submit" class="w-full bg-teal-600 hover:bg-teal-700 text-white font-bold py-2 px-4 rounded-lg">
                            Simpan Pengeluaran
                        </button>
                    </div>
                </form>
            </div>
        </div>
        
        <div class="lg:col-span-2">
            <div class="bg-white border border-gray-200 rounded-2xl p-6 shadow-sm overflow-hidden">
                <h4 class="font-bold text-gray-900 mb-4">Histori Pengeluaran</h4>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kategori / Judul</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nominal</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($pengeluaranList as $item)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $item->tanggal->format('d M Y') }}</td>
                                <td class="px-6 py-4">
                                    <div class="text-sm font-medium text-gray-900">{{ $item->judul }}</div>
                                    <div class="text-xs text-gray-500">{{ $item->kategori }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-red-600 font-semibold">
                                    Rp {{ number_format($item->nominal, 0, ',', '.') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <form action="{{ route('admin.pengeluaran.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Yakin hapus pengeluaran ini?');">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-900">Hapus</button>
                                    </form>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="px-6 py-4 text-center text-sm text-gray-500">Belum ada data pengeluaran.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="mt-4">
                    {{ $pengeluaranList->links() }}
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Line Chart Keuangan Dinamis
        var rawData = {!! $chartKeuangan ?? '{"categories":[],"pendapatan":[],"pengeluaran":[],"laba":[]}' !!};
        
        var optionsKeuangan = {
            series: [
                { name: 'Pendapatan', data: rawData.pendapatan },
                { name: 'Pengeluaran', data: rawData.pengeluaran },
                { name: 'Laba Bersih', data: rawData.laba }
            ],
            chart: { type: 'area', height: 350, toolbar: { show: false } },
            colors: ['#10b981', '#ef4444', '#3b82f6'], // Hijau, Merah, Biru
            dataLabels: { enabled: false },
            stroke: { curve: 'smooth', width: 3 },
            xaxis: { categories: rawData.categories },
            yaxis: {
                labels: { formatter: function (value) { 
                    if(value >= 1000000) return "Rp " + (value/1000000).toFixed(1) + "M"; 
                    if(value >= 1000) return "Rp " + (value/1000).toFixed(0) + "K";
                    return "Rp " + value;
                } }
            },
            legend: { position: 'bottom' },
            tooltip: {
                y: { formatter: function(value) { return "Rp " + new Intl.NumberFormat('id-ID').format(value); } }
            }
        };
        
        var chart = new ApexCharts(document.querySelector("#chartKeuangan"), optionsKeuangan);
        chart.render();
    });
</script>
