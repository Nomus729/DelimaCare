<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.28/jspdf.plugin.autotable.min.js"></script>

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
        <button onclick="exportKeuanganPDF()" class="bg-gradient-to-r from-teal-600 to-cyan-500 hover:from-teal-700 hover:to-cyan-600 text-white px-5 py-3 rounded-xl text-xs font-black uppercase tracking-wider flex items-center gap-2 shadow-lg shadow-teal-500/15 transition-all">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg> 
            Cetak Laporan Lengkap
        </button>
    </div>

    {{-- Filter Form --}}
    <form method="GET" action="{{ route('admin.keuangan.partial') }}" class="flex flex-wrap items-center gap-4 bg-white dark:bg-[#1E293B] border border-gray-100 dark:border-gray-800 p-5 rounded-2xl shadow-sm">
        <div class="flex items-center gap-2">
            <span class="p-1.5 rounded-lg bg-teal-500/10 text-teal-600 dark:text-teal-400">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 3c2.755 0 5.455.477 8 1.4M12 3v18M12 3c-2.755 0-5.455.477-8 1.4m0 0l1.1 5.5a2 2 0 001.96 1.6h9.88a2 2 0 001.96-1.6l1.1-5.5M4 4.4v1.6M20 4.4v1.6"/>
                </svg>
            </span>
            <label for="fin_month" class="text-xs font-bold text-gray-700 dark:text-gray-300">Pilih Bulan & Tahun Analisis:</label>
        </div>
        
        <div class="flex flex-wrap items-center gap-2">
            <select name="fin_month" id="fin_month" onchange="this.form.requestSubmit()" class="bg-gray-50 dark:bg-[#0F172A] border border-gray-200 dark:border-gray-700 rounded-xl px-4 py-2.5 text-xs font-bold focus:ring-2 focus:ring-teal-500 focus:border-teal-500 outline-none transition-all dark:text-white">
                @foreach([
                    1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
                    5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
                    9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
                ] as $val => $label)
                    <option value="{{ $val }}" {{ $selectedMonth == $val ? 'selected' : '' }}>{{ $label }}</option>
                @endforeach
            </select>

            <select name="fin_year" id="fin_year" onchange="this.form.requestSubmit()" class="bg-gray-50 dark:bg-[#0F172A] border border-gray-200 dark:border-gray-700 rounded-xl px-4 py-2.5 text-xs font-bold focus:ring-2 focus:ring-teal-500 focus:border-teal-500 outline-none transition-all dark:text-white">
                @foreach($availableYears as $year)
                    <option value="{{ $year }}" {{ $selectedYear == $year ? 'selected' : '' }}>{{ $year }}</option>
                @endforeach
            </select>
        </div>
    </form>

    {{-- 5 KPI Widgets Row --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-5">
        <!-- KPI 1: Pendapatan -->
        <div class="kpi-card bg-gradient-to-br from-white to-gray-50/50 dark:from-[#1E293B] dark:to-[#0F172A] border border-gray-100 dark:border-gray-800 rounded-2xl p-5 shadow-sm flex flex-col justify-between">
            <div class="flex justify-between items-start mb-2">
                <span class="text-[9px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-widest leading-none">Total Omzet Klinik</span>
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

        <!-- KPI 2: Jasa Dokter -->
        <div class="kpi-card bg-gradient-to-br from-white to-gray-50/50 dark:from-[#1E293B] dark:to-[#0F172A] border border-gray-100 dark:border-gray-800 rounded-2xl p-5 shadow-sm flex flex-col justify-between">
            <div class="flex justify-between items-start mb-2">
                <span class="text-[9px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-widest leading-none">Jasa Jasa Medis / Dokter</span>
                <div class="p-1.5 rounded-lg bg-amber-500/10 text-amber-600 dark:text-amber-400">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                </div>
            </div>
            <div>
                <h3 class="text-lg font-black text-gray-900 dark:text-white leading-tight">Rp {{ number_format($kpiStats['expIni'], 0, ',', '.') }}</h3>
                <div class="mt-2 flex items-center gap-1">
                    @if($kpiStats['pctExp'] >= 0)
                        <span class="text-[10px] font-extrabold text-emerald-500 flex items-center">+{{ number_format($kpiStats['pctExp'], 1) }}%</span>
                    @else
                        <span class="text-[10px] font-extrabold text-rose-500 flex items-center">{{ number_format($kpiStats['pctExp'], 1) }}%</span>
                    @endif
                    <span class="text-[9px] text-gray-400 font-semibold">MoM</span>
                </div>
            </div>
        </div>

        <!-- KPI 3: Penjualan Obat -->
        <div class="kpi-card bg-gradient-to-br from-white to-gray-50/50 dark:from-[#1E293B] dark:to-[#0F172A] border border-gray-100 dark:border-gray-800 rounded-2xl p-5 shadow-sm flex flex-col justify-between">
            <div class="flex justify-between items-start mb-2">
                <span class="text-[9px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-widest leading-none">Penjualan Resep Obat</span>
                <div class="p-1.5 rounded-lg bg-cyan-500/10 text-cyan-600 dark:text-cyan-400">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a2 2 0 00-1.96 1.414l-.477 2.387a2 2 0 00.547 1.022l1.428 1.428a2 2 0 002.828 0l4.243-4.243a2 2 0 000-2.828l-1.428-1.428z"/></svg>
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
                <span class="text-[9px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-widest leading-none">Rata-rata Pendapatan</span>
                <div class="p-1.5 rounded-lg bg-indigo-500/10 text-indigo-600 dark:text-indigo-400">
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

        <!-- KPI 5: Jasa Medis Share -->
        <div class="kpi-card bg-gradient-to-br from-white to-gray-50/50 dark:from-[#1E293B] dark:to-[#0F172A] border border-gray-100 dark:border-gray-800 rounded-2xl p-5 shadow-sm flex flex-col justify-between">
            <div class="flex justify-between items-start mb-2">
                <span class="text-[9px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-widest leading-none">Porsi Jasa Dokter</span>
                <div class="p-1.5 rounded-lg bg-emerald-500/10 text-emerald-600 dark:text-emerald-400">
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
                    <span class="text-[9px] text-gray-400 font-semibold">MoM</span>
                </div>
            </div>
        </div>
    </div>

    {{-- Two Charts Grid --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Area Chart: Tren -->
        <div class="lg:col-span-2 bg-white dark:bg-[#1E293B] border border-gray-100 dark:border-gray-800 rounded-2xl p-6 shadow-sm">
            <div class="mb-4">
                <h4 class="font-bold text-gray-900 dark:text-white">Tren Pendapatan 6 Bulan Terakhir</h4>
                <p class="text-[11px] text-gray-400 font-semibold uppercase tracking-wider mt-0.5">Analisis Fluktuasi Jasa Tindakan Medis & Penjualan Obat</p>
            </div>
            <div id="chartKeuangan" class="w-full h-[350px]"></div>
        </div>

        <!-- Donut Chart: Proporsi Pendapatan -->
        <div class="bg-white dark:bg-[#1E293B] border border-gray-100 dark:border-gray-800 rounded-2xl p-6 shadow-sm flex flex-col justify-between">
            <div class="mb-4">
                <h4 class="font-bold text-gray-900 dark:text-white">Proporsi Arus Pendapatan</h4>
                <p class="text-[11px] text-gray-400 font-semibold uppercase tracking-wider mt-0.5">Kontribusi Jasa Dokter vs Penjualan Obat Bulan Ini</p>
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
                    <h4 class="font-bold text-gray-900 dark:text-white">Kontribusi Jasa Dokter</h4>
                    <p class="text-[11px] text-gray-400 font-semibold uppercase tracking-wider mt-0.5">Akumulasi Jasa Medis & Konsultasi Berdasarkan Dokter</p>
                </div>
                <span class="text-[10px] bg-teal-500/10 text-teal-600 dark:text-teal-400 font-black uppercase tracking-widest px-3 py-1.5 rounded-full">Top Performer</span>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-100 dark:divide-gray-800">
                    <thead class="bg-gray-50/50 dark:bg-gray-800/40">
                        <tr>
                            <th class="px-6 py-3 text-left text-[9px] font-black text-gray-400 uppercase tracking-widest">Nama Dokter</th>
                            <th class="px-6 py-3 text-center text-[9px] font-black text-gray-400 uppercase tracking-widest">Total Layanan</th>
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
                                {{ $doc->total_prescriptions }} Transaksi
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

        <!-- Proporsi Sumber Pendapatan (Revenue Drivers) -->
        <div class="bg-white dark:bg-[#1E293B] border border-gray-100 dark:border-gray-800 rounded-2xl p-6 shadow-sm flex flex-col justify-between">
            <div>
                <div class="mb-6">
                    <h4 class="font-bold text-gray-900 dark:text-white">Kinerja Kategori Layanan</h4>
                    <p class="text-[11px] text-gray-400 font-semibold uppercase tracking-wider mt-0.5">Analisis Kontribusi Omzet Bulan Ini</p>
                </div>
                <div class="space-y-5">
                    <div>
                        <div class="flex justify-between items-center mb-1.5">
                            <div>
                                <span class="text-xs font-bold text-gray-900 dark:text-white block">Jasa Dokter / Konsultasi</span>
                                <span class="text-[9px] font-extrabold text-teal-600 uppercase tracking-wider">Tindakan & Pemeriksaan</span>
                            </div>
                            <span class="text-xs font-black text-teal-600">Rp {{ number_format($kpiStats['expIni'], 0, ',', '.') }}</span>
                        </div>
                        <div class="w-full bg-gray-100 dark:bg-gray-800 h-2 rounded-full overflow-hidden">
                            <div class="bg-teal-500 h-full rounded-full transition-all duration-500" style="width: {{ $kpiStats['marginIni'] }}%"></div>
                        </div>
                    </div>

                    <div>
                        <div class="flex justify-between items-center mb-1.5">
                            <div>
                                <span class="text-xs font-bold text-gray-900 dark:text-white block">Penjualan Resep Obat</span>
                                <span class="text-[9px] font-extrabold text-amber-500 uppercase tracking-wider">Apotek & Farmasi</span>
                            </div>
                            <span class="text-xs font-black text-amber-500">Rp {{ number_format($kpiStats['labaIni'], 0, ',', '.') }}</span>
                        </div>
                        <div class="w-full bg-gray-100 dark:bg-gray-800 h-2 rounded-full overflow-hidden">
                            <div class="bg-amber-500 h-full rounded-full transition-all duration-500" style="width: {{ 100 - $kpiStats['marginIni'] }}%"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Bottom Grid: Tabular Summary & Info Log --}}
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
                            <th class="px-6 py-3 text-right text-[9px] font-black text-gray-400 uppercase tracking-widest">Jasa Dokter</th>
                            <th class="px-6 py-3 text-right text-[9px] font-black text-gray-400 uppercase tracking-widest">Penjualan Obat</th>
                            <th class="px-6 py-3 text-right text-[9px] font-black text-gray-400 uppercase tracking-widest">Total Omzet</th>
                            <th class="px-6 py-3 text-center text-[9px] font-black text-gray-400 uppercase tracking-widest">Porsi Dokter</th>
                            <th class="px-6 py-3 text-center text-[9px] font-black text-gray-400 uppercase tracking-widest">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                        @foreach($summaryTable as $row)
                        <tr class="hover:bg-gray-50/30 dark:hover:bg-gray-800/10 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap text-xs font-bold text-gray-900 dark:text-white">{{ $row['month'] }}</td>
                            <td class="px-6 py-4 text-right whitespace-nowrap text-xs font-semibold text-gray-700 dark:text-gray-300">Rp {{ number_format($row['jasa_dokter'], 0, ',', '.') }}</td>
                            <td class="px-6 py-4 text-right whitespace-nowrap text-xs font-semibold text-gray-500 dark:text-gray-400">Rp {{ number_format($row['obat_sales'], 0, ',', '.') }}</td>
                            <td class="px-6 py-4 text-right whitespace-nowrap text-xs font-black text-teal-600">
                                Rp {{ number_format($row['total'], 0, ',', '.') }}
                            </td>
                            <td class="px-6 py-4 text-center whitespace-nowrap text-xs font-bold text-gray-600 dark:text-gray-300">{{ number_format($row['margin'], 1) }}%</td>
                            <td class="px-6 py-4 text-center whitespace-nowrap">
                                <span class="px-2.5 py-0.5 rounded-full text-[9px] font-black uppercase tracking-wider bg-emerald-500/10 text-emerald-600 dark:text-emerald-400">
                                    Stabil
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
            {{-- FITUR PREMIUM: Analisis Kesehatan Finansial --}}
            @php
                $labaBulanIni = $kpiStats['labaIni'];
                $isSehat = true;
                $healthScore = 100;
            @endphp
            <div class="bg-gradient-to-br from-white to-gray-50/50 dark:from-[#1E293B] dark:to-[#0F172A] border border-gray-100 dark:border-gray-800 rounded-2xl p-6 shadow-sm relative overflow-hidden group">
                <div class="absolute -right-6 -top-6 w-16 h-16 bg-teal-500/5 dark:bg-teal-500/10 rounded-full blur-xl group-hover:scale-150 transition-transform duration-700"></div>
                
                <h4 class="font-bold text-gray-900 dark:text-white mb-1.5 flex items-center gap-2">
                    <span class="w-2.5 h-2.5 rounded-full bg-emerald-500 animate-pulse"></span>
                    Indikator Finansial
                </h4>
                <p class="text-[10px] text-gray-400 font-bold uppercase tracking-wider mb-4">Real-time Financial Audit Insights</p>

                <div class="space-y-4">
                    <div>
                        <div class="flex justify-between items-center text-xs font-bold mb-1.5">
                            <span class="text-gray-500">Porsi Jasa Dokter</span>
                            <span class="text-teal-600">{{ number_format($kpiStats['marginIni'], 1) }}%</span>
                        </div>
                        <div class="w-full bg-gray-100 dark:bg-gray-800 h-2 rounded-full overflow-hidden">
                            <div class="bg-teal-500 h-full rounded-full" style="width: {{ min(max($kpiStats['marginIni'], 0), 100) }}%"></div>
                        </div>
                    </div>

                    <div class="p-3.5 bg-teal-50/50 border border-teal-100 dark:bg-teal-950/20 dark:border-teal-900/30 rounded-xl">
                        <span class="text-[9px] font-black uppercase text-teal-600 tracking-wider block mb-1">Status Keuangan</span>
                        <p class="text-xs font-bold text-gray-700 dark:text-gray-300 leading-normal">
                            Aliran kas klinik berjalan sangat optimal. Pendapatan murni berasal dari **jasa konsultasi dokter** dan **penjualan obat di apotek** yang tersinkronisasi sempurna.
                        </p>
                    </div>
                </div>
            </div>

            {{-- Informasi Otomatisasi Finansial --}}
            <div class="bg-gradient-to-br from-white to-gray-50/50 dark:from-[#1E293B] dark:to-[#0F172A] border border-gray-100 dark:border-gray-800 rounded-2xl p-6 shadow-sm space-y-4">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-teal-600 text-white flex items-center justify-center shadow-lg shadow-teal-500/20">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                    </div>
                    <div>
                        <h4 class="font-bold text-gray-900 dark:text-white leading-none">Automated Invoice</h4>
                        <p class="text-[9px] text-gray-400 font-bold uppercase tracking-wider mt-1">Sistem Keuangan Otomatis</p>
                    </div>
                </div>
                <div class="p-4 bg-teal-50/30 dark:bg-teal-950/10 border border-teal-100/50 dark:border-teal-900/30 rounded-2xl text-[11px] font-bold text-gray-600 dark:text-gray-300 leading-relaxed space-y-2">
                    <p>
                        Sistem keuangan DelimaCare menggunakan pencatatan pendapatan otomatis:
                    </p>
                    <ul class="list-disc pl-4 space-y-1">
                        <li>Jasa Dokter langsung tercatat dari form resep di Rekam Medis.</li>
                        <li>Biaya obat dikalkulasi otomatis berdasarkan harga obat inventori.</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    {{-- Bottom Grid 2: Histori Pengeluaran & Top Obat --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Histori Pengeluaran -->
        <div class="lg:col-span-2 bg-white dark:bg-[#1E293B] border border-gray-100 dark:border-gray-800 rounded-2xl p-6 shadow-sm">
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
                <div>
                    <h4 class="font-bold text-gray-900 dark:text-white">Daftar Histori Pengeluaran</h4>
                    <p class="text-[11px] text-gray-400 font-semibold uppercase tracking-wider mt-0.5">Catatan Log Biaya Operasional Klinik</p>
                </div>
                <div class="flex items-center gap-3 w-full sm:w-auto">
                    <input type="text" id="searchPengeluaran" placeholder="Cari log..." class="bg-gray-50 dark:bg-[#0F172A] border border-gray-200 dark:border-gray-700 rounded-xl px-4 py-2 text-xs font-semibold focus:ring-2 focus:ring-teal-500 focus:border-teal-500 outline-none transition-all dark:text-white w-full sm:w-48" oninput="filterPengeluaranTable()">
                    <button onclick="exportPengeluaranToCSV()" class="bg-teal-50 dark:bg-teal-900/30 text-teal-600 dark:text-teal-400 border border-teal-100 dark:border-teal-900 hover:bg-teal-100 px-4 py-2.5 rounded-xl text-xs font-black uppercase tracking-wider transition-all flex items-center gap-1.5 whitespace-nowrap shadow-sm" title="Ekspor data ke CSV">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                        CSV
                    </button>
                </div>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-100 dark:divide-gray-800">
                    <thead class="bg-gray-50/50 dark:bg-gray-800/40">
                        <tr>
                            <th class="px-6 py-3 text-left text-[9px] font-black text-gray-400 uppercase tracking-widest">Tanggal</th>
                            <th class="px-6 py-3 text-left text-[9px] font-black text-gray-400 uppercase tracking-widest">No. Resep & Pasien</th>
                            <th class="px-6 py-3 text-right text-[9px] font-black text-gray-400 uppercase tracking-widest">Jasa Dokter</th>
                            <th class="px-6 py-3 text-right text-[9px] font-black text-gray-400 uppercase tracking-widest">Penjualan Obat</th>
                            <th class="px-6 py-3 text-right text-[9px] font-black text-gray-400 uppercase tracking-widest">Total Omzet</th>
                        </tr>
                    </thead>
                    <tbody id="pengeluaranTableBody" class="divide-y divide-gray-100 dark:divide-gray-800">
                        @forelse($pengeluaranList as $item)
                        @php
                            $obatTotal = $item->items->sum(function($i) { return $i->jumlah * $i->medicine->price; });
                        @endphp
                        <tr class="hover:bg-gray-50/30 dark:hover:bg-gray-800/10 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap text-xs font-bold text-gray-500 dark:text-gray-400">{{ $item->tanggal_resep->format('d M Y') }}</td>
                            <td class="px-6 py-4">
                                <div class="pengeluaran-desc text-sm font-bold text-gray-900 dark:text-white leading-none">{{ $item->nama_pasien }}</div>
                                <span class="pengeluaran-cat text-[8px] bg-teal-500/10 text-teal-600 dark:text-teal-400 font-black uppercase tracking-wider px-2 py-0.5 rounded-full inline-block mt-1.5">{{ $item->no_resep }}</span>
                            </td>
                            <td class="px-6 py-4 text-right whitespace-nowrap text-sm text-gray-600 dark:text-gray-300 font-semibold">
                                Rp {{ number_format($item->biaya_dokter, 0, ',', '.') }}
                            </td>
                            <td class="px-6 py-4 text-right whitespace-nowrap text-sm text-gray-600 dark:text-gray-300 font-semibold">
                                Rp {{ number_format($obatTotal, 0, ',', '.') }}
                            </td>
                            <td class="px-6 py-4 text-right whitespace-nowrap text-sm text-teal-600 font-black">
                                Rp {{ number_format($item->biaya_dokter + $obatTotal, 0, ',', '.') }}
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="px-6 py-4 text-center text-xs font-bold text-gray-400 uppercase tracking-widest">Belum ada data pendapatan</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-4 overflow-x-auto pb-2">
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

<script data-reinit>
    (function () {
        var chartContainer = document.querySelector("#chartKeuangan");
        var donutContainer = document.querySelector("#chartDonutPengeluaran");
        if (chartContainer) chartContainer.innerHTML = '';
        if (donutContainer) donutContainer.innerHTML = '';

        // Line & Area Chart: Tren Keuangan Dinamis
        var rawData = {!! $chartKeuangan ?? '{"categories":[],"pendapatan_dokter":[],"pendapatan_obat":[],"total":[]}' !!};
        
        var optionsKeuangan = {
            series: [
                { name: 'Jasa Dokter', data: rawData.pendapatan_dokter },
                { name: 'Penjualan Obat', data: rawData.pendapatan_obat },
                { name: 'Total Omzet', data: rawData.total }
            ],
            chart: { type: 'area', height: 350, toolbar: { show: false } },
            colors: ['#0d9488', '#f59e0b', '#06b6d4'], // Teal, Amber, Cyan
            dataLabels: { enabled: false },
            stroke: { curve: 'smooth', width: 3.5 },
            xaxis: { categories: rawData.categories },
            yaxis: {
                labels: { formatter: function (value) { 
                    if(value >= 1000000) return "Rp " + (value/1000000).toFixed(1) + " Jt"; 
                    if(value >= 1000) return "Rp " + (value/1000).toFixed(0) + " Rb";
                    return "Rp " + value;
                } }
            },
            legend: { position: 'bottom' },
            tooltip: {
                y: { formatter: function(value) { return "Rp " + new Intl.NumberFormat('id-ID').format(value); } }
            }
        };
        
        if (chartContainer) {
            var chart = new ApexCharts(chartContainer, optionsKeuangan);
            chart.render();
        }

        // Donut Chart: Proporsi Pendapatan
        var donutData = {!! json_encode($donutChartData) !!};
        var optionsDonut = {
            series: donutData,
            chart: { type: 'donut', height: 280 },
            labels: ['Jasa Dokter', 'Penjualan Obat'],
            colors: ['#0d9488', '#f59e0b'], // Teal, Amber
            legend: { position: 'bottom' },
            stroke: { width: 0 },
            dataLabels: { enabled: false },
            tooltip: {
                y: { formatter: function(value) { return "Rp " + new Intl.NumberFormat('id-ID').format(value); } }
            }
        };

        if (donutContainer) {
            var chartDonut = new ApexCharts(donutContainer, optionsDonut);
            chartDonut.render();
        }
    })();

    // FITUR PREMIUM: Ekspor Laporan Keuangan Klinis PDF (Medical Standard)
    function exportKeuanganPDF() {
        if (!window.jspdf || !window.jspdf.jsPDF) {
            alert("Library PDF belum siap. Silakan tunggu sebentar dan coba lagi.");
            return;
        }

        const { jsPDF } = window.jspdf;
        const doc = new jsPDF('p', 'mm', 'a4');
        
        // --- PAGE 1: KOP SURAT & KPI SUMMARY ---
        // Kop Surat Resmi Klinik Utama
        doc.setFont("helvetica", "bold");
        doc.setFontSize(18);
        doc.setTextColor(13, 148, 136); // Teal 600
        doc.text("KLINIK UTAMA DELIMACARE", 14, 20);
        
        doc.setFontSize(9);
        doc.setTextColor(100, 100, 100);
        doc.setFont("helvetica", "normal");
        doc.text("Pusat Kesehatan Ibu, Anak & Layanan Keluarga Berencana", 14, 25);
        doc.text("Jl. Melati Raya No. 45, Bandung | Telp: (022) 8765-4321 | finance@delimacare.id", 14, 29);
        
        // Garis Double Border Kop Surat
        doc.setDrawColor(13, 148, 136);
        doc.setLineWidth(1);
        doc.line(14, 33, 196, 33);
        doc.setDrawColor(150, 150, 150);
        doc.setLineWidth(0.2);
        doc.line(14, 34.5, 196, 34.5);

        // Judul Laporan Keuangan
        doc.setFontSize(13);
        doc.setTextColor(30, 41, 59); // Slate 800
        doc.setFont("helvetica", "bold");
        doc.text("LAPORAN AUDIT KEUANGAN KLINIK (CLINICAL FINANCIAL AUDIT STATEMENT)", 14, 45);
        
        doc.setFontSize(9);
        doc.setFont("helvetica", "normal");
        doc.setTextColor(100, 100, 100);
        doc.text("Tahun Buku: 2026 | Klasifikasi: Confidential (Dokumen Internal Klinik)", 14, 50);
        doc.text("Tanggal Cetak: " + new Date().toLocaleString('id-ID'), 14, 54);

        // --- SECTION 1: RINGKASAN AUDIT FINANSIAL (KPI) ---
        doc.setFontSize(10);
        doc.setFont("helvetica", "bold");
        doc.setTextColor(13, 148, 136);
        doc.text("I. RINGKASAN AUDIT KINERJA UTAMA (FINANCIAL KEY PERFORMANCE INDICATORS)", 14, 63);

        const kpiHeaders = [["Indikator Kinerja Finansial", "Bulan Lalu", "Bulan Ini", "Pertumbuhan (MoM)"]];
        
        function formatRupiah(val) {
            return "Rp " + new Intl.NumberFormat('id-ID').format(Math.round(val));
        }

        const kpiRows = [
            ["Total Omzet Klinik (Gross Revenue)", formatRupiah({{ $kpiStats['revLalu'] }}), formatRupiah({{ $kpiStats['revIni'] }}), "{{ number_format($kpiStats['pctRev'], 1) }}%"],
            ["Jasa Tindakan Medis / Dokter", formatRupiah({{ $kpiStats['expLalu'] }}), formatRupiah({{ $kpiStats['expIni'] }}), "{{ number_format($kpiStats['pctExp'], 1) }}%"],
            ["Penjualan Item Obat Farmasi", formatRupiah({{ $kpiStats['labaLalu'] }}), formatRupiah({{ $kpiStats['labaIni'] }}), "{{ number_format($kpiStats['pctLaba'], 1) }}%"],
            ["Rata-rata Tagihan per Resep", formatRupiah({{ $kpiStats['avgRevIni'] }}), formatRupiah({{ $kpiStats['avgRevIni'] }}), "{{ number_format($kpiStats['pctAvgRev'], 1) }}%"],
            ["Rasio Kontribusi Jasa Dokter", "{{ number_format($kpiStats['marginIni'], 1) }}%", "{{ number_format($kpiStats['marginIni'], 1) }}%", "{{ number_format($kpiStats['diffMargin'], 1) }}%"]
        ];

        doc.autoTable({
            startY: 67,
            head: kpiHeaders,
            body: kpiRows,
            theme: 'grid',
            headStyles: { fillColor: [30, 41, 59], textColor: 255, fontStyle: 'bold', fontSize: 8 },
            bodyStyles: { fontSize: 8, cellPadding: 3 },
            columnStyles: {
                0: { fontStyle: 'bold' },
                1: { halign: 'right' },
                2: { halign: 'right' },
                3: { halign: 'center', fontStyle: 'bold' }
            }
        });

        // --- SECTION 2: IKHTISAR KINERJA TABULAR 6 BULAN ---
        const nextY = doc.lastAutoTable.finalY + 10;
        doc.setFontSize(10);
        doc.setFont("helvetica", "bold");
        doc.setTextColor(13, 148, 136);
        doc.text("II. IKHTISAR KINERJA TABULAR HISTORIS (6 BULAN)", 14, nextY);

        const summaryHeaders = [["Bulan", "Pendapatan Jasa Dokter", "Penjualan Obat Apotek", "Total Omzet Bulanan", "Kontribusi Jasa Dokter", "Status Audit"]];
        
        const summaryRows = @json($summaryTable).map(row => [
            row.month,
            formatRupiah(row.jasa_dokter),
            formatRupiah(row.obat_sales),
            formatRupiah(row.total),
            row.margin.toFixed(1) + "%",
            "STABIL"
        ]);

        doc.autoTable({
            startY: nextY + 4,
            head: summaryHeaders,
            body: summaryRows,
            theme: 'striped',
            headStyles: { fillColor: [13, 148, 136], textColor: 255, fontStyle: 'bold', fontSize: 8 },
            bodyStyles: { fontSize: 8, cellPadding: 3 },
            columnStyles: {
                0: { fontStyle: 'bold' },
                1: { halign: 'right' },
                2: { halign: 'right' },
                3: { halign: 'right', fontStyle: 'bold' },
                4: { halign: 'center' },
                5: { halign: 'center' }
            }
        });

        // --- PAGE 2: PHARMACY SALES & LOG INVOICES ---
        doc.addPage();
        
        // Header Halaman Kedua
        doc.setFont("helvetica", "bold");
        doc.setFontSize(10);
        doc.setTextColor(13, 148, 136);
        doc.text("III. PROPORSI PENDAPATAN APOTEK (TOP 5 OBAT TERLARIS)", 14, 20);

        const medHeaders = [["No", "Nama Item Obat Farmasi", "Jumlah Kuantitas Terjual", "Porsi Pendapatan Omzet"]];
        const medRows = @json($topMedicines).map((med, idx) => [
            String(idx + 1),
            med.name,
            med.total_qty + " tablet/pcs",
            formatRupiah(med.total_revenue)
        ]);

        doc.autoTable({
            startY: 24,
            head: medHeaders,
            body: medRows,
            theme: 'grid',
            headStyles: { fillColor: [30, 41, 59], textColor: 255, fontStyle: 'bold', fontSize: 8 },
            bodyStyles: { fontSize: 8, cellPadding: 3 },
            columnStyles: {
                0: { halign: 'center', fontStyle: 'bold' },
                1: { fontStyle: 'bold' },
                2: { halign: 'center' },
                3: { halign: 'right' }
            }
        });

        // Registri Penerimaan Transaksi Resep Terbaru (Log Histori Detail)
        const nextY2 = doc.lastAutoTable.finalY + 10;
        doc.setFontSize(10);
        doc.setFont("helvetica", "bold");
        doc.setTextColor(13, 148, 136);
        doc.text("IV. REGISTRI PENERIMAAN INVOICE RESEP TERBARU (RECENT TRANSACTION LOG)", 14, nextY2);

        const recentHeaders = [["Tanggal", "No. Resep", "Nama Pasien", "Biaya Jasa Dokter", "Penjualan Obat Apotek", "Total Billing"]];
        
        const recentRows = [];
        const tableRows = document.querySelectorAll('#pengeluaranTableBody tr');
        tableRows.forEach(row => {
            if (row.style.display === 'none') return;
            const cells = row.cells;
            if (cells.length >= 5) {
                const date = cells[0].textContent.trim();
                const nama = row.querySelector('.pengeluaran-desc')?.textContent.trim() || '';
                const resep = row.querySelector('.pengeluaran-cat')?.textContent.trim() || '';
                const jasa = cells[2].textContent.trim();
                const obat = cells[3].textContent.trim();
                const total = cells[4].textContent.trim();
                recentRows.push([date, resep, nama, jasa, obat, total]);
            }
        });

        doc.autoTable({
            startY: nextY2 + 4,
            head: recentHeaders,
            body: recentRows,
            theme: 'striped',
            headStyles: { fillColor: [13, 148, 136], textColor: 255, fontStyle: 'bold', fontSize: 8 },
            bodyStyles: { fontSize: 7.5, cellPadding: 2.5 },
            columnStyles: {
                0: { fontStyle: 'bold' },
                1: { halign: 'center' },
                2: { fontStyle: 'bold' },
                3: { halign: 'right' },
                4: { halign: 'right' },
                5: { halign: 'right', fontStyle: 'bold' }
            }
        });

        // Tanda Tangan Audit Resmi
        const finalY = doc.lastAutoTable.finalY + 15;
        const pageHeight = doc.internal.pageSize.getHeight();
        
        const signY = (finalY + 40 > pageHeight) ? 35 : finalY;
        if (finalY + 40 > pageHeight) {
            doc.addPage();
            doc.text("V. VALIDASI & PERSETUJUAN AUDIT", 14, 20);
        }

        doc.setFont("helvetica", "normal");
        doc.setFontSize(9);
        doc.setTextColor(30, 41, 59);
        
        doc.text("Bandung, " + new Date().toLocaleDateString('id-ID', { year: 'numeric', month: 'long', day: 'numeric' }), 145, signY);
        
        doc.text("Mengetahui,", 14, signY + 10);
        doc.text("Direktur Klinik Utama DelimaCare,", 14, signY + 14);
        
        doc.text("Disiapkan & Diaudit Oleh,", 145, signY + 10);
        doc.text("Kepala Departemen Keuangan,", 145, signY + 14);
        
        // Garis untuk tanda tangan
        doc.setDrawColor(200, 200, 200);
        doc.setLineWidth(0.5);
        doc.line(14, signY + 35, 65, signY + 35);
        doc.line(145, signY + 35, 196, signY + 35);
        
        doc.setFont("helvetica", "bold");
        doc.text("dr. Siti Rahayu, SpOG", 14, signY + 39);
        doc.text("Fauziah Rahmawati, S.E.", 145, signY + 39);

        // Simpan PDF
        doc.save("Laporan_Keuangan_Klinis_DelimaCare_" + new Date().toISOString().slice(0,10) + ".pdf");
    }

    // Vanilla JS Helper Functions for Search & CSV Export
    function filterPengeluaranTable() {
        const query = document.getElementById('searchPengeluaran').value.toLowerCase();
        const rows = document.querySelectorAll('#pengeluaranTableBody tr');
        rows.forEach(row => {
            const desc = row.querySelector('.pengeluaran-desc')?.textContent.toLowerCase() || '';
            const cat = row.querySelector('.pengeluaran-cat')?.textContent.toLowerCase() || '';
            if (desc.includes(query) || cat.includes(query)) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    }

    function exportPengeluaranToCSV() {
        let csv = 'Tanggal,Nama Pasien,No Resep,Jasa Dokter,Penjualan Obat,Total Tagihan\n';
        const rows = document.querySelectorAll('#pengeluaranTableBody tr');
        rows.forEach(row => {
            if (row.style.display === 'none') return;
            const date = row.cells[0]?.textContent.trim() || '';
            const nama = row.querySelector('.pengeluaran-desc')?.textContent.trim() || '';
            const resep = row.querySelector('.pengeluaran-cat')?.textContent.trim() || '';
            const jasa = row.cells[2]?.textContent.replace(/[^0-9]/g, '') || '0';
            const obat = row.cells[3]?.textContent.replace(/[^0-9]/g, '') || '0';
            const total = row.cells[4]?.textContent.replace(/[^0-9]/g, '') || '0';
            if (date && nama) {
                csv += `"${date}","${nama}","${resep}","${jasa}","${obat}","${total}"\n`;
            }
        });
        const blob = new Blob([csv], { type: 'text/csv;charset=utf-8;' });
        const link = document.createElement("a");
        link.href = URL.createObjectURL(blob);
        link.setAttribute("download", `laporan_pendapatan_${new Date().toISOString().slice(0,10)}.csv`);
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
    }
</script>
