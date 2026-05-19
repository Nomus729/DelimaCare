<style>
    .kpi-card {
        transition: transform 0.25s ease, box-shadow 0.25s ease;
    }
    .kpi-card:hover {
        transform: translateY(-3px);
    }
</style>

<div class="space-y-8 animate-up">
    {{-- Header --}}
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h2 class="text-2xl font-black text-gray-900 dark:text-white tracking-tight leading-none">Pusat Analitis Keuangan</h2>
            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1 font-semibold uppercase tracking-wider">Metrik Finansial & Profitabilitas Klinik Terintegrasi</p>
        </div>
        <button onclick="window.print()" class="bg-teal-600 hover:bg-teal-700 text-white px-5 py-2.5 rounded-xl text-xs font-black uppercase tracking-wider flex items-center gap-2 shadow-md shadow-teal-500/10 transition-all">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg> 
            Cetak Laporan
        </button>
    </div>

    {{-- 3 KPI Cards --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Card 1: Pendapatan -->
        <div class="kpi-card bg-gradient-to-br from-white to-gray-50/50 dark:from-[#1E293B] dark:to-[#0F172A] border border-gray-100 dark:border-gray-800 rounded-2xl p-6 shadow-sm flex items-center justify-between">
            <div>
                <span class="text-[10px] font-black text-gray-400 uppercase tracking-widest block mb-1">Pendapatan Bulan Ini</span>
                <h3 class="text-xl font-black text-gray-900 dark:text-white">Rp {{ number_format($kpiStats['revIni'], 0, ',', '.') }}</h3>
                <div class="mt-2 flex items-center gap-1">
                    @if($kpiStats['pctRev'] >= 0)
                        <svg class="w-3.5 h-3.5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/></svg>
                        <span class="text-xs font-bold text-emerald-500">+{{ number_format($kpiStats['pctRev'], 1) }}%</span>
                    @else
                        <svg class="w-3.5 h-3.5 text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M13 17h8m0 0v-8m0 8l-8-8-4 4-6-6"/></svg>
                        <span class="text-xs font-bold text-rose-500">{{ number_format($kpiStats['pctRev'], 1) }}%</span>
                    @endif
                    <span class="text-[10px] text-gray-400 font-semibold ml-1">vs bulan lalu</span>
                </div>
            </div>
            <div class="w-12 h-12 rounded-xl bg-teal-500/10 text-teal-600 dark:text-teal-400 flex items-center justify-center">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M12 16V5"/></svg>
            </div>
        </div>

        <!-- Card 2: Pengeluaran -->
        <div class="kpi-card bg-gradient-to-br from-white to-gray-50/50 dark:from-[#1E293B] dark:to-[#0F172A] border border-gray-100 dark:border-gray-800 rounded-2xl p-6 shadow-sm flex items-center justify-between">
            <div>
                <span class="text-[10px] font-black text-gray-400 uppercase tracking-widest block mb-1">Pengeluaran Bulan Ini</span>
                <h3 class="text-xl font-black text-gray-900 dark:text-white">Rp {{ number_format($kpiStats['expIni'], 0, ',', '.') }}</h3>
                <div class="mt-2 flex items-center gap-1">
                    @if($kpiStats['pctExp'] >= 0)
                        <svg class="w-3.5 h-3.5 text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/></svg>
                        <span class="text-xs font-bold text-rose-500">+{{ number_format($kpiStats['pctExp'], 1) }}%</span>
                    @else
                        <svg class="w-3.5 h-3.5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M13 17h8m0 0v-8m0 8l-8-8-4 4-6-6"/></svg>
                        <span class="text-xs font-bold text-emerald-500">{{ number_format($kpiStats['pctExp'], 1) }}%</span>
                    @endif
                    <span class="text-[10px] text-gray-400 font-semibold ml-1">vs bulan lalu</span>
                </div>
            </div>
            <div class="w-12 h-12 rounded-xl bg-rose-500/10 text-rose-600 dark:text-rose-400 flex items-center justify-center">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 12H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
        </div>

        <!-- Card 3: Laba Bersih -->
        <div class="kpi-card bg-gradient-to-br from-white to-gray-50/50 dark:from-[#1E293B] dark:to-[#0F172A] border border-gray-100 dark:border-gray-800 rounded-2xl p-6 shadow-sm flex items-center justify-between">
            <div>
                <span class="text-[10px] font-black text-gray-400 uppercase tracking-widest block mb-1">Laba Bersih Bulan Ini</span>
                <h3 class="text-xl font-black text-gray-900 dark:text-white">Rp {{ number_format($kpiStats['labaIni'], 0, ',', '.') }}</h3>
                <div class="mt-2 flex items-center gap-1">
                    @if($kpiStats['pctLaba'] >= 0)
                        <svg class="w-3.5 h-3.5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/></svg>
                        <span class="text-xs font-bold text-emerald-500">+{{ number_format($kpiStats['pctLaba'], 1) }}%</span>
                    @else
                        <svg class="w-3.5 h-3.5 text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M13 17h8m0 0v-8m0 8l-8-8-4 4-6-6"/></svg>
                        <span class="text-xs font-bold text-rose-500">{{ number_format($kpiStats['pctLaba'], 1) }}%</span>
                    @endif
                    <span class="text-[10px] text-gray-400 font-semibold ml-1">vs bulan lalu</span>
                </div>
            </div>
            <div class="w-12 h-12 rounded-xl bg-cyan-500/10 text-cyan-600 dark:text-cyan-400 flex items-center justify-center">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 002 2h2a2 2 0 002-2z"/></svg>
            </div>
        </div>
    </div>

    {{-- Two Charts Grid --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Area Chart: Tren -->
        <div class="lg:col-span-2 bg-white dark:bg-[#1E293B] border border-gray-100 dark:border-gray-800 rounded-2xl p-6 shadow-sm">
            <div class="mb-4">
                <h4 class="font-bold text-gray-900 dark:text-white">Tren Finansial 6 Bulan Terakhir</h4>
                <p class="text-[11px] text-gray-400 font-semibold uppercase tracking-wider mt-0.5">Analisis Fluktuasi Pendapatan, Pengeluaran & Laba</p>
            </div>
            <div id="chartKeuangan" class="w-full h-[350px]"></div>
        </div>

        <!-- Donut Chart: Distribusi Pengeluaran -->
        <div class="bg-white dark:bg-[#1E293B] border border-gray-100 dark:border-gray-800 rounded-2xl p-6 shadow-sm flex flex-col">
            <div class="mb-4">
                <h4 class="font-bold text-gray-900 dark:text-white">Alokasi Pengeluaran</h4>
                <p class="text-[11px] text-gray-400 font-semibold uppercase tracking-wider mt-0.5">Distribusi Biaya Operasional Berdasarkan Kategori</p>
            </div>
            <div class="flex-grow flex items-center justify-center">
                <div id="chartDonutPengeluaran" class="w-full h-[280px]"></div>
            </div>
        </div>
    </div>

    {{-- Details Grid --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        {{-- Form Input Pengeluaran --}}
        <div class="lg:col-span-1">
            <div class="bg-gradient-to-br from-white to-gray-50/50 dark:from-[#1E293B] dark:to-[#0F172A] border border-gray-100 dark:border-gray-800 rounded-2xl p-6 shadow-sm">
                <h4 class="font-bold text-gray-900 dark:text-white mb-4">Catat Transaksi Pengeluaran</h4>
                <form action="{{ route('admin.pengeluaran.store') }}" method="POST" class="space-y-4">
                    @csrf
                    <div>
                        <label class="block text-[11px] font-black text-gray-500 uppercase tracking-wider mb-1.5">Judul Pengeluaran</label>
                        <input type="text" name="judul" required placeholder="Contoh: Pembelian Jarum Suntik" class="w-full bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl text-xs font-semibold focus:ring-2 focus:ring-teal-500 focus:border-teal-500 outline-none transition-all dark:text-white p-3">
                    </div>
                    <div>
                        <label class="block text-[11px] font-black text-gray-500 uppercase tracking-wider mb-1.5">Kategori Alokasi</label>
                        <select name="kategori" required class="w-full bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl text-xs font-semibold focus:ring-2 focus:ring-teal-500 focus:border-teal-500 outline-none transition-all dark:text-white p-3">
                            <option value="Operasional">Operasional</option>
                            <option value="Gaji Pegawai">Gaji Pegawai</option>
                            <option value="Pembelian Alat">Pembelian Alat</option>
                            <option value="Lainnya">Lainnya</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-[11px] font-black text-gray-500 uppercase tracking-wider mb-1.5">Nominal (Rupiah)</label>
                        <input type="number" name="nominal" required min="0" placeholder="Contoh: 150000" class="w-full bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl text-xs font-semibold focus:ring-2 focus:ring-teal-500 focus:border-teal-500 outline-none transition-all dark:text-white p-3">
                    </div>
                    <div class="grid grid-cols-1 gap-4">
                        <div>
                            <label class="block text-[11px] font-black text-gray-500 uppercase tracking-wider mb-1.5">Tanggal Pembayaran</label>
                            <input type="date" name="tanggal" required value="{{ date('Y-m-d') }}" class="w-full bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl text-xs font-semibold focus:ring-2 focus:ring-teal-500 focus:border-teal-500 outline-none transition-all dark:text-white p-3">
                        </div>
                    </div>
                    <div>
                        <label class="block text-[11px] font-black text-gray-500 uppercase tracking-wider mb-1.5">Keterangan Tambahan</label>
                        <textarea name="keterangan" rows="2" placeholder="Tuliskan catatan opsional..." class="w-full bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl text-xs font-semibold focus:ring-2 focus:ring-teal-500 focus:border-teal-500 outline-none transition-all dark:text-white p-3"></textarea>
                    </div>
                    <button type="submit" class="w-full bg-teal-600 hover:bg-teal-700 text-white font-black text-xs uppercase tracking-wider py-3.5 rounded-xl transition-all shadow-md shadow-teal-500/10">
                        Simpan & Integrasikan
                    </button>
                </form>
            </div>
        </div>

        {{-- Histori Pengeluaran & Top Obat --}}
        <div class="lg:col-span-2 space-y-6">
            
            {{-- Tabel Top 5 Obat Terlaris --}}
            <div class="bg-white dark:bg-[#1E293B] border border-gray-100 dark:border-gray-800 rounded-2xl p-6 shadow-sm">
                <div class="flex justify-between items-center mb-4">
                    <div>
                        <h4 class="font-bold text-gray-900 dark:text-white">Top 5 Obat Penyumbang Omzet</h4>
                        <p class="text-[11px] text-gray-400 font-semibold uppercase tracking-wider mt-0.5">Kontribusi Penjualan Obat Tertinggi</p>
                    </div>
                    <span class="text-xs bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 font-extrabold px-2.5 py-1 rounded-full">Daftar Terlaris</span>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-100 dark:divide-gray-800">
                        <thead class="bg-gray-50/50 dark:bg-gray-800/40">
                            <tr>
                                <th class="px-6 py-3 text-left text-[10px] font-black text-gray-400 uppercase tracking-widest">Nama Obat</th>
                                <th class="px-6 py-3 text-center text-[10px] font-black text-gray-400 uppercase tracking-widest">Jumlah Terjual</th>
                                <th class="px-6 py-3 text-right text-[10px] font-black text-gray-400 uppercase tracking-widest">Total Revenue (Kotor)</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                            @forelse($topMedicines as $idx => $med)
                            <tr class="hover:bg-gray-50/40 dark:hover:bg-gray-800/20 transition-colors">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center gap-2.5">
                                        <span class="w-6 h-6 rounded-lg bg-teal-500/10 text-teal-600 flex items-center justify-center font-black text-xs" x-text="{{ $idx + 1 }}"></span>
                                        <span class="text-sm font-bold text-gray-900 dark:text-white">{{ $med->name }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-center whitespace-nowrap text-sm font-bold text-gray-500 dark:text-gray-400">
                                    {{ $med->total_qty }} Unit
                                </td>
                                <td class="px-6 py-4 text-right whitespace-nowrap text-sm font-black text-emerald-600">
                                    Rp {{ number_format($med->total_revenue, 0, ',', '.') }}
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="3" class="px-6 py-4 text-center text-xs font-bold text-gray-400 uppercase tracking-widest">Belum ada rekam resep medis</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- Histori Pengeluaran --}}
            <div class="bg-white dark:bg-[#1E293B] border border-gray-100 dark:border-gray-800 rounded-2xl p-6 shadow-sm">
                <div class="flex justify-between items-center mb-4">
                    <div>
                        <h4 class="font-bold text-gray-900 dark:text-white">Daftar Histori Pengeluaran</h4>
                        <p class="text-[11px] text-gray-400 font-semibold uppercase tracking-wider mt-0.5">Catatan Log Biaya Operasional Klinik</p>
                    </div>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-100 dark:divide-gray-800">
                        <thead class="bg-gray-50/50 dark:bg-gray-800/40">
                            <tr>
                                <th class="px-6 py-3 text-left text-[10px] font-black text-gray-400 uppercase tracking-widest">Tanggal</th>
                                <th class="px-6 py-3 text-left text-[10px] font-black text-gray-400 uppercase tracking-widest">Kategori / Deskripsi</th>
                                <th class="px-6 py-3 text-right text-[10px] font-black text-gray-400 uppercase tracking-widest">Nominal</th>
                                <th class="px-6 py-3 text-right text-[10px] font-black text-gray-400 uppercase tracking-widest">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                            @forelse($pengeluaranList as $item)
                            <tr class="hover:bg-gray-50/40 dark:hover:bg-gray-800/20 transition-colors">
                                <td class="px-6 py-4 whitespace-nowrap text-xs font-bold text-gray-500 dark:text-gray-400">{{ $item->tanggal->format('d M Y') }}</td>
                                <td class="px-6 py-4">
                                    <div class="text-sm font-bold text-gray-900 dark:text-white">{{ $item->judul }}</div>
                                    <div class="text-[10px] font-extrabold text-teal-600 uppercase tracking-wider mt-0.5">{{ $item->kategori }}</div>
                                </td>
                                <td class="px-6 py-4 text-right whitespace-nowrap text-sm text-rose-600 font-black">
                                    Rp {{ number_format($item->nominal, 0, ',', '.') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-xs font-bold">
                                    <form action="{{ route('admin.pengeluaran.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Yakin hapus pengeluaran ini?');">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="text-rose-600 hover:text-rose-800 hover:underline">Hapus</button>
                                    </form>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="px-6 py-4 text-center text-xs font-bold text-gray-400 uppercase tracking-widest">Belum ada pengeluaran yang dicatat</td>
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
        // Line & Area Chart: Tren Keuangan Dinamis
        var rawData = {!! $chartKeuangan ?? '{"categories":[],"pendapatan":[],"pengeluaran":[],"laba":[]}' !!};
        
        var optionsKeuangan = {
            series: [
                { name: 'Pendapatan', data: rawData.pendapatan },
                { name: 'Pengeluaran', data: rawData.pengeluaran },
                { name: 'Laba Bersih', data: rawData.laba }
            ],
            chart: { type: 'area', height: 350, toolbar: { show: false } },
            colors: ['#0d9488', '#f43f5e', '#06b6d4'], // Teal, Rose, Cyan
            dataLabels: { enabled: false },
            stroke: { curve: 'smooth', width: 3.5 },
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

        // Donut Chart: Alokasi Distribusi Pengeluaran
        var donutData = {!! json_encode($donutChartData) !!};
        var optionsDonut = {
            series: donutData,
            chart: { type: 'donut', height: 280 },
            labels: ['Operasional', 'Gaji Pegawai', 'Pembelian Alat', 'Lainnya'],
            colors: ['#0d9488', '#f59e0b', '#3b82f6', '#ec4899'], // Teal, Amber, Blue, Pink
            legend: { position: 'bottom' },
            stroke: { width: 0 },
            dataLabels: { enabled: false },
            tooltip: {
                y: { formatter: function(value) { return "Rp " + new Intl.NumberFormat('id-ID').format(value); } }
            }
        };

        var chartDonut = new ApexCharts(document.querySelector("#chartDonutPengeluaran"), optionsDonut);
        chartDonut.render();
    });
</script>
