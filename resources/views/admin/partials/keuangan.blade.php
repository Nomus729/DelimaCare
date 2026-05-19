<style>
    .kpi-card {
        transition: transform 0.22s ease, box-shadow 0.22s ease;
    }
    .kpi-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.05), 0 8px 10px -6px rgba(0, 0, 0, 0.05);
    }
</style>

<div class="space-y-8 animate-up p-6 lg:p-8">
    {{-- Header --}}
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h2 class="text-3xl font-black text-gray-900 dark:text-white tracking-tight leading-none">Pusat Analisis Keuangan</h2>
            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1.5 font-extrabold uppercase tracking-widest">Advanced Clinical Financial Metrics & Resource Analytics</p>
        </div>
        <button onclick="window.print()" class="bg-gradient-to-r from-teal-600 to-cyan-500 hover:from-teal-700 hover:to-cyan-600 text-white px-5 py-3 rounded-xl text-xs font-black uppercase tracking-wider flex items-center gap-2 shadow-lg shadow-teal-500/15 transition-all">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg> 
            Cetak Laporan Lengkap
        </button>
    </div>

    {{-- 5 KPI Widgets Row --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-5">
        <!-- KPI 1: Pendapatan -->
        <div class="kpi-card bg-gradient-to-br from-white to-gray-50/50 dark:from-[#1E293B] dark:to-[#0F172A] border border-gray-100 dark:border-gray-800 rounded-2xl p-5 shadow-sm flex flex-col justify-between">
            <div class="flex justify-between items-start mb-2">
                <span class="text-[9px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-widest leading-none">Pendapatan Kotor</span>
                <div class="p-1.5 rounded-lg bg-teal-500/10 text-teal-600 dark:text-teal-400">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M12 16V5"/></svg>
                </div>
            </div>
            <div>
                <h3 class="text-lg font-black text-gray-900 dark:text-white leading-tight">Rp {{ number_format($kpiStats['revIni'], 0, ',', '.') }}</h3>
                <div class="mt-2 flex items-center gap-1">
                    @if($kpiStats['pctRev'] >= 0)
                        <span class="text-[10px] font-extrabold text-emerald-500 flex items-center">+{{ number_format($kpiStats['pctRev'], 1) }}%</span>
                    @else
                        <span class="text-[10px] font-extrabold text-rose-500 flex items-center">{{ number_format($kpiStats['pctRev'], 1) }}%</span>
                    @endif
                    <span class="text-[9px] text-gray-400 font-semibold">MoM</span>
                </div>
            </div>
        </div>

        <!-- KPI 2: Pengeluaran -->
        <div class="kpi-card bg-gradient-to-br from-white to-gray-50/50 dark:from-[#1E293B] dark:to-[#0F172A] border border-gray-100 dark:border-gray-800 rounded-2xl p-5 shadow-sm flex flex-col justify-between">
            <div class="flex justify-between items-start mb-2">
                <span class="text-[9px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-widest leading-none">Total Pengeluaran</span>
                <div class="p-1.5 rounded-lg bg-rose-500/10 text-rose-600 dark:text-rose-400">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
            </div>
            <div>
                <h3 class="text-lg font-black text-gray-900 dark:text-white leading-tight">Rp {{ number_format($kpiStats['expIni'], 0, ',', '.') }}</h3>
                <div class="mt-2 flex items-center gap-1">
                    @if($kpiStats['pctExp'] >= 0)
                        <span class="text-[10px] font-extrabold text-rose-500 flex items-center">+{{ number_format($kpiStats['pctExp'], 1) }}%</span>
                    @else
                        <span class="text-[10px] font-extrabold text-emerald-500 flex items-center">{{ number_format($kpiStats['pctExp'], 1) }}%</span>
                    @endif
                    <span class="text-[9px] text-gray-400 font-semibold">MoM</span>
                </div>
            </div>
        </div>

        <!-- KPI 3: Laba Bersih -->
        <div class="kpi-card bg-gradient-to-br from-white to-gray-50/50 dark:from-[#1E293B] dark:to-[#0F172A] border border-gray-100 dark:border-gray-800 rounded-2xl p-5 shadow-sm flex flex-col justify-between">
            <div class="flex justify-between items-start mb-2">
                <span class="text-[9px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-widest leading-none">Laba Bersih</span>
                <div class="p-1.5 rounded-lg bg-cyan-500/10 text-cyan-600 dark:text-cyan-400">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 002 2h2a2 2 0 002-2z"/></svg>
                </div>
            </div>
            <div>
                <h3 class="text-lg font-black text-gray-900 dark:text-white leading-tight">Rp {{ number_format($kpiStats['labaIni'], 0, ',', '.') }}</h3>
                <div class="mt-2 flex items-center gap-1">
                    @if($kpiStats['pctLaba'] >= 0)
                        <span class="text-[10px] font-extrabold text-emerald-500 flex items-center">+{{ number_format($kpiStats['pctLaba'], 1) }}%</span>
                    @else
                        <span class="text-[10px] font-extrabold text-rose-500 flex items-center">{{ number_format($kpiStats['pctLaba'], 1) }}%</span>
                    @endif
                    <span class="text-[9px] text-gray-400 font-semibold">MoM</span>
                </div>
            </div>
        </div>

        <!-- KPI 4: Rata-rata Nilai Resep -->
        <div class="kpi-card bg-gradient-to-br from-white to-gray-50/50 dark:from-[#1E293B] dark:to-[#0F172A] border border-gray-100 dark:border-gray-800 rounded-2xl p-5 shadow-sm flex flex-col justify-between">
            <div class="flex justify-between items-start mb-2">
                <span class="text-[9px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-widest leading-none">Rata-rata Resep</span>
                <div class="p-1.5 rounded-lg bg-amber-500/10 text-amber-600 dark:text-amber-400">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                </div>
            </div>
            <div>
                <h3 class="text-lg font-black text-gray-900 dark:text-white leading-tight">Rp {{ number_format($kpiStats['avgRevIni'], 0, ',', '.') }}</h3>
                <div class="mt-2 flex items-center gap-1">
                    @if($kpiStats['pctAvgRev'] >= 0)
                        <span class="text-[10px] font-extrabold text-emerald-500 flex items-center">+{{ number_format($kpiStats['pctAvgRev'], 1) }}%</span>
                    @else
                        <span class="text-[10px] font-extrabold text-rose-500 flex items-center">{{ number_format($kpiStats['pctAvgRev'], 1) }}%</span>
                    @endif
                    <span class="text-[9px] text-gray-400 font-semibold">MoM</span>
                </div>
            </div>
        </div>

        <!-- KPI 5: Profit Margin -->
        <div class="kpi-card bg-gradient-to-br from-white to-gray-50/50 dark:from-[#1E293B] dark:to-[#0F172A] border border-gray-100 dark:border-gray-800 rounded-2xl p-5 shadow-sm flex flex-col justify-between">
            <div class="flex justify-between items-start mb-2">
                <span class="text-[9px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-widest leading-none">Margin Keuntungan</span>
                <div class="p-1.5 rounded-lg bg-indigo-500/10 text-indigo-600 dark:text-indigo-400">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M11 3.055A9.003 9.003 0 1020.945 13H11V3.055z"/></svg>
                </div>
            </div>
            <div>
                <h3 class="text-lg font-black text-gray-900 dark:text-white leading-tight">{{ number_format($kpiStats['marginIni'], 1) }}%</h3>
                <div class="mt-2 flex items-center gap-1">
                    @if($kpiStats['diffMargin'] >= 0)
                        <span class="text-[10px] font-extrabold text-emerald-500 flex items-center">+{{ number_format($kpiStats['diffMargin'], 1) }} pp</span>
                    @else
                        <span class="text-[10px] font-extrabold text-rose-500 flex items-center">{{ number_format($kpiStats['diffMargin'], 1) }} pp</span>
                    @endif
                    <span class="text-[9px] text-gray-400 font-semibold">Poin</span>
                </div>
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
        <div class="bg-white dark:bg-[#1E293B] border border-gray-100 dark:border-gray-800 rounded-2xl p-6 shadow-sm flex flex-col justify-between">
            <div class="mb-4">
                <h4 class="font-bold text-gray-900 dark:text-white">Alokasi Pengeluaran</h4>
                <p class="text-[11px] text-gray-400 font-semibold uppercase tracking-wider mt-0.5">Distribusi Biaya Operasional Berdasarkan Kategori</p>
            </div>
            <div class="flex-grow flex items-center justify-center">
                <div id="chartDonutPengeluaran" class="w-full h-[280px]"></div>
            </div>
        </div>
    </div>

    {{-- Deep Financial Indicators Section --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Kontribusi Dokter Pemeriksa -->
        <div class="lg:col-span-2 bg-white dark:bg-[#1E293B] border border-gray-100 dark:border-gray-800 rounded-2xl p-6 shadow-sm">
            <div class="flex justify-between items-center mb-6">
                <div>
                    <h4 class="font-bold text-gray-900 dark:text-white">Kontribusi Omzet Dokter Pemeriksa</h4>
                    <p class="text-[11px] text-gray-400 font-semibold uppercase tracking-wider mt-0.5">Omzet Kotor Resep Obat Berdasarkan Dokter</p>
                </div>
                <span class="text-[10px] bg-teal-500/10 text-teal-600 dark:text-teal-400 font-black uppercase tracking-widest px-3 py-1.5 rounded-full">Top Performer</span>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-100 dark:divide-gray-800">
                    <thead class="bg-gray-50/50 dark:bg-gray-800/40">
                        <tr>
                            <th class="px-6 py-3 text-left text-[9px] font-black text-gray-400 uppercase tracking-widest">Nama Dokter</th>
                            <th class="px-6 py-3 text-center text-[9px] font-black text-gray-400 uppercase tracking-widest">Total Resep</th>
                            <th class="px-6 py-3 text-right text-[9px] font-black text-gray-400 uppercase tracking-widest">Total Kontribusi (Rp)</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                        @forelse($topDoctors as $idx => $doc)
                        <tr class="hover:bg-gray-50/30 dark:hover:bg-gray-800/10 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-lg bg-teal-500/10 text-teal-600 flex items-center justify-center font-black text-xs" x-text="'0' + ({{ $idx }} + 1)"></div>
                                    <span class="text-sm font-bold text-gray-900 dark:text-white">{{ $doc->name ?: 'Tidak Diketahui' }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-center whitespace-nowrap text-sm font-bold text-gray-500 dark:text-gray-400">
                                {{ $doc->total_prescriptions }} Resep
                            </td>
                            <td class="px-6 py-4 text-right whitespace-nowrap text-sm font-black text-teal-600">
                                Rp {{ number_format($doc->total_revenue, 0, ',', '.') }}
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3" class="px-6 py-4 text-center text-xs font-bold text-gray-400 uppercase tracking-widest">Belum ada kontribusi resep dokter</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- 3 Kebocoran Biaya Terbesar (Expense Drivers) -->
        <div class="bg-white dark:bg-[#1E293B] border border-gray-100 dark:border-gray-800 rounded-2xl p-6 shadow-sm flex flex-col justify-between">
            <div>
                <div class="mb-6">
                    <h4 class="font-bold text-gray-900 dark:text-white">Pos Pengeluaran Terbesar</h4>
                    <p class="text-[11px] text-gray-400 font-semibold uppercase tracking-wider mt-0.5">Top 3 Pembayaran Keluar Dengan Nominal Tertinggi</p>
                </div>
                <div class="space-y-5">
                    @forelse($topExpenses as $item)
                    <div>
                        <div class="flex justify-between items-center mb-1.5">
                            <div>
                                <span class="text-xs font-bold text-gray-900 dark:text-white block">{{ $item->judul }}</span>
                                <span class="text-[9px] font-extrabold text-rose-500 uppercase tracking-wider">{{ $item->kategori }}</span>
                            </div>
                            <span class="text-xs font-black text-rose-600">Rp {{ number_format($item->nominal, 0, ',', '.') }}</span>
                        </div>
                        <div class="w-full bg-gray-100 dark:bg-gray-800 h-2 rounded-full overflow-hidden">
                            @php
                                $totalOverallExpense = max($topExpenses->sum('nominal'), 1);
                                $percentage = ($item->nominal / $totalOverallExpense) * 100;
                            @endphp
                            <div class="bg-rose-500 h-full rounded-full transition-all duration-500" style="width: {{ $percentage }}%"></div>
                        </div>
                    </div>
                    @empty
                    <div class="text-center py-8">
                        <p class="text-xs font-black text-gray-400 uppercase tracking-widest">Belum ada data pengeluaran</p>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    {{-- Bottom Grid: Tabular Summary & CRUD Log --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Rangkuman Log Ikhtisar Bulanan (6 Bulan) -->
        <div class="lg:col-span-2 bg-white dark:bg-[#1E293B] border border-gray-100 dark:border-gray-800 rounded-2xl p-6 shadow-sm">
            <div class="mb-4">
                <h4 class="font-bold text-gray-900 dark:text-white">Ikhtisar Bulanan Tabular</h4>
                <p class="text-[11px] text-gray-400 font-semibold uppercase tracking-wider mt-0.5">Ringkasan Riwayat Performa Profitabilitas Tiap Bulan</p>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-100 dark:divide-gray-800">
                    <thead class="bg-gray-50/50 dark:bg-gray-800/40">
                        <tr>
                            <th class="px-6 py-3 text-left text-[9px] font-black text-gray-400 uppercase tracking-widest">Bulan</th>
                            <th class="px-6 py-3 text-right text-[9px] font-black text-gray-400 uppercase tracking-widest">Pendapatan</th>
                            <th class="px-6 py-3 text-right text-[9px] font-black text-gray-400 uppercase tracking-widest">Pengeluaran</th>
                            <th class="px-6 py-3 text-right text-[9px] font-black text-gray-400 uppercase tracking-widest">Laba Bersih</th>
                            <th class="px-6 py-3 text-center text-[9px] font-black text-gray-400 uppercase tracking-widest">Margin %</th>
                            <th class="px-6 py-3 text-center text-[9px] font-black text-gray-400 uppercase tracking-widest">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                        @foreach($summaryTable as $row)
                        <tr class="hover:bg-gray-50/30 dark:hover:bg-gray-800/10 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap text-xs font-bold text-gray-900 dark:text-white">{{ $row['month'] }}</td>
                            <td class="px-6 py-4 text-right whitespace-nowrap text-xs font-semibold text-gray-700 dark:text-gray-300">Rp {{ number_format($row['pendapatan'], 0, ',', '.') }}</td>
                            <td class="px-6 py-4 text-right whitespace-nowrap text-xs font-semibold text-gray-500 dark:text-gray-400">Rp {{ number_format($row['pengeluaran'], 0, ',', '.') }}</td>
                            <td class="px-6 py-4 text-right whitespace-nowrap text-xs font-black {{ $row['laba'] >= 0 ? 'text-teal-600' : 'text-rose-600' }}">
                                Rp {{ number_format($row['laba'], 0, ',', '.') }}
                            </td>
                            <td class="px-6 py-4 text-center whitespace-nowrap text-xs font-bold text-gray-600 dark:text-gray-300">{{ number_format($row['margin'], 1) }}%</td>
                            <td class="px-6 py-4 text-center whitespace-nowrap">
                                <span class="px-2.5 py-0.5 rounded-full text-[9px] font-black uppercase tracking-wider {{ $row['status'] === 'Surplus' ? 'bg-emerald-500/10 text-emerald-600 dark:text-emerald-400' : 'bg-rose-500/10 text-rose-600 dark:text-rose-400' }}">
                                    {{ $row['status'] }}
                                </span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Catat Transaksi Pengeluaran & Top Medicines (Obat Terlaris) -->
        <div class="lg:col-span-1 space-y-6">
            {{-- Form Input --}}
            <div class="bg-gradient-to-br from-white to-gray-50/50 dark:from-[#1E293B] dark:to-[#0F172A] border border-gray-100 dark:border-gray-800 rounded-2xl p-6 shadow-sm">
                <h4 class="font-bold text-gray-900 dark:text-white mb-4">Catat Transaksi Pengeluaran</h4>
                <form action="{{ route('admin.pengeluaran.store') }}" method="POST" class="space-y-4">
                    @csrf
                    <div>
                        <label class="block text-[10px] font-black text-gray-500 uppercase tracking-wider mb-1.5">Judul Pengeluaran</label>
                        <input type="text" name="judul" required placeholder="Contoh: Gaji Medis Dokter A" class="w-full bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl text-xs font-semibold focus:ring-2 focus:ring-teal-500 focus:border-teal-500 outline-none transition-all dark:text-white p-3">
                    </div>
                    <div>
                        <label class="block text-[10px] font-black text-gray-500 uppercase tracking-wider mb-1.5">Kategori Alokasi</label>
                        <select name="kategori" required class="w-full bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl text-xs font-semibold focus:ring-2 focus:ring-teal-500 focus:border-teal-500 outline-none transition-all dark:text-white p-3">
                            <option value="Operasional">Operasional</option>
                            <option value="Gaji Pegawai">Gaji Pegawai</option>
                            <option value="Pembelian Alat">Pembelian Alat</option>
                            <option value="Lainnya">Lainnya</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-[10px] font-black text-gray-500 uppercase tracking-wider mb-1.5">Nominal (Rupiah)</label>
                        <input type="number" name="nominal" required min="0" placeholder="Contoh: 1500000" class="w-full bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl text-xs font-semibold focus:ring-2 focus:ring-teal-500 focus:border-teal-500 outline-none transition-all dark:text-white p-3">
                    </div>
                    <div>
                        <label class="block text-[10px] font-black text-gray-500 uppercase tracking-wider mb-1.5">Tanggal Pembayaran</label>
                        <input type="date" name="tanggal" required value="{{ date('Y-m-d') }}" class="w-full bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl text-xs font-semibold focus:ring-2 focus:ring-teal-500 focus:border-teal-500 outline-none transition-all dark:text-white p-3">
                    </div>
                    <button type="submit" class="w-full bg-teal-600 hover:bg-teal-700 text-white font-black text-xs uppercase tracking-wider py-3.5 rounded-xl transition-all shadow-md">
                        Simpan Transaksi
                    </button>
                </form>
            </div>
        </div>
    </div>

    {{-- Bottom Grid 2: Histori Pengeluaran & Top Obat --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Histori Pengeluaran -->
        <div class="lg:col-span-2 bg-white dark:bg-[#1E293B] border border-gray-100 dark:border-gray-800 rounded-2xl p-6 shadow-sm">
            <div class="mb-4">
                <h4 class="font-bold text-gray-900 dark:text-white">Daftar Histori Pengeluaran</h4>
                <p class="text-[11px] text-gray-400 font-semibold uppercase tracking-wider mt-0.5">Catatan Log Biaya Operasional Klinik</p>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-100 dark:divide-gray-800">
                    <thead class="bg-gray-50/50 dark:bg-gray-800/40">
                        <tr>
                            <th class="px-6 py-3 text-left text-[9px] font-black text-gray-400 uppercase tracking-widest">Tanggal</th>
                            <th class="px-6 py-3 text-left text-[9px] font-black text-gray-400 uppercase tracking-widest">Deskripsi</th>
                            <th class="px-6 py-3 text-right text-[9px] font-black text-gray-400 uppercase tracking-widest">Nominal</th>
                            <th class="px-6 py-3 text-right text-[9px] font-black text-gray-400 uppercase tracking-widest">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                        @forelse($pengeluaranList as $item)
                        <tr class="hover:bg-gray-50/30 dark:hover:bg-gray-800/10 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap text-xs font-bold text-gray-500 dark:text-gray-400">{{ $item->tanggal->format('d M Y') }}</td>
                            <td class="px-6 py-4">
                                <div class="text-sm font-bold text-gray-900 dark:text-white leading-none">{{ $item->judul }}</div>
                                <span class="text-[8px] bg-teal-500/10 text-teal-600 dark:text-teal-400 font-black uppercase tracking-wider px-2 py-0.5 rounded-full inline-block mt-1.5">{{ $item->kategori }}</span>
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
                            <td colspan="4" class="px-6 py-4 text-center text-xs font-bold text-gray-400 uppercase tracking-widest">Belum ada transaksi keluar</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-4">
                {{ $pengeluaranList->links() }}
            </div>
        </div>

        <!-- Top 5 Obat Terlaris -->
        <div class="lg:col-span-1 bg-white dark:bg-[#1E293B] border border-gray-100 dark:border-gray-800 rounded-2xl p-6 shadow-sm">
            <div class="mb-4 flex justify-between items-center">
                <div>
                    <h4 class="font-bold text-gray-900 dark:text-white">Top 5 Obat</h4>
                    <p class="text-[11px] text-gray-400 font-semibold uppercase tracking-wider mt-0.5">Penyumbang Omzet Apotek Tertinggi</p>
                </div>
            </div>
            <div class="space-y-4">
                @forelse($topMedicines as $idx => $med)
                <div class="flex items-center justify-between gap-3 p-3 rounded-xl hover:bg-gray-50/50 dark:hover:bg-gray-800/10 transition-all border border-gray-50 dark:border-gray-800">
                    <div class="flex items-center gap-2.5 min-w-0">
                        <div class="w-6 h-6 rounded-lg bg-teal-500/10 text-teal-600 flex items-center justify-center font-black text-[11px]" x-text="'0' + ({{ $idx }} + 1)"></div>
                        <div class="min-w-0">
                            <span class="text-xs font-bold text-gray-900 dark:text-white block truncate">{{ $med->name }}</span>
                            <span class="text-[9px] text-gray-400 font-semibold">{{ $med->total_qty }} terjual</span>
                        </div>
                    </div>
                    <span class="text-xs font-black text-emerald-600 flex-shrink-0">Rp {{ number_format($med->total_revenue, 0, ',', '.') }}</span>
                </div>
                @empty
                <div class="text-center py-6">
                    <p class="text-xs font-black text-gray-400 uppercase tracking-widest">Belum ada resep obat</p>
                </div>
                @endforelse
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
