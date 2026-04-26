{{-- ===== DASHBOARD OVERVIEW ===== --}}
<div class="space-y-8">

    {{-- Stat Cards --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-5">

        {{-- Card 1: Total Pasien --}}
        <div class="bg-white dark:bg-[#1E293B] rounded-2xl p-6 border border-gray-100 dark:border-gray-800 shadow-sm hover:shadow-md hover:-translate-y-0.5 transition-all duration-300 relative overflow-hidden group">
            <div class="absolute inset-x-0 top-0 h-0.5 rounded-t-2xl" style="background: var(--gradient-main);"></div>
            <div class="flex items-start justify-between mb-5">
                <div class="w-11 h-11 rounded-xl flex items-center justify-center bg-teal-50 dark:bg-teal-900/30 group-hover:scale-110 transition-transform duration-300">
                    <svg class="w-5 h-5 text-teal-600 dark:text-teal-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                </div>
                <span class="inline-flex items-center gap-1 text-xs font-bold text-emerald-600 dark:text-emerald-400 bg-emerald-50 dark:bg-emerald-900/30 px-2 py-1 rounded-lg">
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 10l7-7m0 0l7 7m-7-7v18"/></svg>
                    +12%
                </span>
            </div>
            <p class="text-sm font-semibold text-gray-500 dark:text-gray-400 mb-1">Total Pasien</p>
            <h3 class="text-3xl font-extrabold text-gray-900 dark:text-white tracking-tight">342</h3>
            <p class="text-xs text-gray-400 dark:text-gray-500 mt-1">dari bulan lalu</p>
        </div>

        {{-- Card 2: Reservasi Hari Ini --}}
        <div class="bg-white dark:bg-[#1E293B] rounded-2xl p-6 border border-gray-100 dark:border-gray-800 shadow-sm hover:shadow-md hover:-translate-y-0.5 transition-all duration-300 relative overflow-hidden group">
            <div class="absolute inset-x-0 top-0 h-0.5 rounded-t-2xl bg-cyan-400"></div>
            <div class="flex items-start justify-between mb-5">
                <div class="w-11 h-11 rounded-xl flex items-center justify-center bg-cyan-50 dark:bg-cyan-900/30 group-hover:scale-110 transition-transform duration-300">
                    <svg class="w-5 h-5 text-cyan-600 dark:text-cyan-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                </div>
                <span class="inline-flex items-center gap-1 text-xs font-bold text-emerald-600 dark:text-emerald-400 bg-emerald-50 dark:bg-emerald-900/30 px-2 py-1 rounded-lg">
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 10l7-7m0 0l7 7m-7-7v18"/></svg>
                    +5
                </span>
            </div>
            <p class="text-sm font-semibold text-gray-500 dark:text-gray-400 mb-1">Reservasi Hari Ini</p>
            <h3 class="text-3xl font-extrabold text-gray-900 dark:text-white tracking-tight">28</h3>
            <p class="text-xs text-gray-400 dark:text-gray-500 mt-1">dari kemarin</p>
        </div>

        {{-- Card 3: Stok Menipis --}}
        <div class="bg-white dark:bg-[#1E293B] rounded-2xl p-6 border border-gray-100 dark:border-gray-800 shadow-sm hover:shadow-md hover:-translate-y-0.5 transition-all duration-300 relative overflow-hidden group">
            <div class="absolute inset-x-0 top-0 h-0.5 rounded-t-2xl bg-amber-400"></div>
            <div class="flex items-start justify-between mb-5">
                <div class="w-11 h-11 rounded-xl flex items-center justify-center bg-amber-50 dark:bg-amber-900/30 group-hover:scale-110 transition-transform duration-300">
                    <svg class="w-5 h-5 text-amber-600 dark:text-amber-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                </div>
                <span class="inline-flex items-center gap-1 text-xs font-bold text-amber-600 dark:text-amber-400 bg-amber-50 dark:bg-amber-900/30 px-2 py-1 rounded-lg">
                    Perhatian
                </span>
            </div>
            <p class="text-sm font-semibold text-gray-500 dark:text-gray-400 mb-1">Stok Menipis</p>
            <h3 class="text-3xl font-extrabold text-gray-900 dark:text-white tracking-tight">7</h3>
            <p class="text-xs text-amber-500 dark:text-amber-400 mt-1 font-medium">Item perlu restock</p>
        </div>

        {{-- Card 4: Pendapatan Bulan Ini --}}
        <div class="bg-white dark:bg-[#1E293B] rounded-2xl p-6 border border-gray-100 dark:border-gray-800 shadow-sm hover:shadow-md hover:-translate-y-0.5 transition-all duration-300 relative overflow-hidden group">
            <div class="absolute inset-x-0 top-0 h-0.5 rounded-t-2xl bg-emerald-400"></div>
            <div class="flex items-start justify-between mb-5">
                <div class="w-11 h-11 rounded-xl flex items-center justify-center bg-emerald-50 dark:bg-emerald-900/30 group-hover:scale-110 transition-transform duration-300">
                    <svg class="w-5 h-5 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/></svg>
                </div>
                <span class="inline-flex items-center gap-1 text-xs font-bold text-emerald-600 dark:text-emerald-400 bg-emerald-50 dark:bg-emerald-900/30 px-2 py-1 rounded-lg">
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 10l7-7m0 0l7 7m-7-7v18"/></svg>
                    +18%
                </span>
            </div>
            <p class="text-sm font-semibold text-gray-500 dark:text-gray-400 mb-1">Pendapatan Bulan Ini</p>
            <h3 class="text-3xl font-extrabold text-gray-900 dark:text-white tracking-tight">Rp 45,2M</h3>
            <p class="text-xs text-gray-400 dark:text-gray-500 mt-1">dari bulan lalu</p>
        </div>
    </div>

    {{-- Charts Row --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Bar Chart - Kunjungan --}}
        <div class="lg:col-span-2 bg-white dark:bg-[#1E293B] rounded-2xl p-6 border border-gray-100 dark:border-gray-800 shadow-sm">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h4 class="font-bold text-gray-900 dark:text-white">Statistik Kunjungan Bulanan</h4>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-0.5">Data 6 bulan terakhir</p>
                </div>
                <div class="flex items-center gap-4 text-xs font-semibold text-gray-500 dark:text-gray-400">
                    <span class="flex items-center gap-1.5"><span class="w-3 h-3 rounded-sm bg-teal-500 inline-block"></span>Kunjungan</span>
                    <span class="flex items-center gap-1.5"><span class="w-3 h-3 rounded-sm bg-cyan-400 inline-block"></span>Pasien Baru</span>
                </div>
            </div>
            <div id="chartKunjungan" class="w-full h-64"></div>
        </div>

        {{-- Pie Chart - Distribusi --}}
        <div class="bg-white dark:bg-[#1E293B] rounded-2xl p-6 border border-gray-100 dark:border-gray-800 shadow-sm">
            <div class="mb-6">
                <h4 class="font-bold text-gray-900 dark:text-white">Distribusi Layanan</h4>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-0.5">Bulan ini</p>
            </div>
            <div id="chartDistribusi" class="w-full h-64 flex justify-center items-center"></div>
        </div>
    </div>

    {{-- Bottom Row: Activity + Quick Access --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- Recent Activity --}}
        <div class="lg:col-span-2 bg-white dark:bg-[#1E293B] rounded-2xl p-6 border border-gray-100 dark:border-gray-800 shadow-sm">
            <div class="flex items-center justify-between mb-5">
                <h4 class="font-bold text-gray-900 dark:text-white">Aktivitas Terkini</h4>
                <button class="text-xs font-semibold text-teal-600 dark:text-teal-400 hover:underline">Lihat Semua</button>
            </div>
            <div class="space-y-4">
                @php
                $activities = [
                    ['color' => 'teal', 'icon' => 'M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z', 'title' => 'Reservasi baru dari Siti Rahayu', 'time' => '2 menit lalu', 'desc' => 'Pemeriksaan Kehamilan — Dr. Nurhaliza'],
                    ['color' => 'emerald', 'icon' => 'M5 13l4 4L19 7', 'title' => 'Pembayaran dikonfirmasi', 'time' => '15 menit lalu', 'desc' => 'Rp 350.000 — Konsultasi KB'],
                    ['color' => 'amber', 'icon' => 'M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z', 'title' => 'Stok Amoksisilin menipis', 'time' => '1 jam lalu', 'desc' => 'Tersisa 12 unit — Segera restock'],
                    ['color' => 'cyan', 'icon' => 'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z', 'title' => 'Rekam medis diperbarui', 'time' => '2 jam lalu', 'desc' => 'Pasien: Dewi Lestari — Dr. Siti'],
                ];
                @endphp
                @foreach($activities as $act)
                <div class="flex items-start gap-4">
                    <div class="w-9 h-9 rounded-xl flex items-center justify-center flex-shrink-0 bg-{{ $act['color'] }}-50 dark:bg-{{ $act['color'] }}-900/30 mt-0.5">
                        <svg class="w-4 h-4 text-{{ $act['color'] }}-600 dark:text-{{ $act['color'] }}-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="{{ $act['icon'] }}"/></svg>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-semibold text-gray-900 dark:text-white truncate">{{ $act['title'] }}</p>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">{{ $act['desc'] }}</p>
                    </div>
                    <span class="text-xs text-gray-400 dark:text-gray-500 whitespace-nowrap flex-shrink-0 mt-0.5">{{ $act['time'] }}</span>
                </div>
                @endforeach
            </div>
        </div>

        {{-- Quick Access Modules --}}
        <div class="bg-white dark:bg-[#1E293B] rounded-2xl p-6 border border-gray-100 dark:border-gray-800 shadow-sm">
            <h4 class="font-bold text-gray-900 dark:text-white mb-5">Akses Cepat</h4>
            <div class="grid grid-cols-2 gap-3">
                @php
                $modules = [
                    ['menu' => 'konten', 'label' => 'Konten', 'color' => 'teal', 'icon' => 'M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z'],
                    ['menu' => 'inventori', 'label' => 'Inventori', 'color' => 'amber', 'icon' => 'M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4'],
                    ['menu' => 'keuangan', 'label' => 'Keuangan', 'color' => 'emerald', 'icon' => 'M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z'],
                    ['menu' => 'laporan', 'label' => 'Laporan', 'color' => 'cyan', 'icon' => 'M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z'],
                ];
                @endphp
                @foreach($modules as $mod)
                <button @click="switchMenu('{{ $mod['menu'] }}')"
                        class="flex flex-col items-center gap-2.5 p-4 rounded-xl border border-gray-100 dark:border-gray-700 hover:border-{{ $mod['color'] }}-200 dark:hover:border-{{ $mod['color'] }}-800 hover:bg-{{ $mod['color'] }}-50/50 dark:hover:bg-{{ $mod['color'] }}-900/20 transition-all duration-200 group text-center">
                    <div class="w-10 h-10 rounded-xl bg-{{ $mod['color'] }}-50 dark:bg-{{ $mod['color'] }}-900/30 flex items-center justify-center group-hover:scale-110 transition-transform">
                        <svg class="w-5 h-5 text-{{ $mod['color'] }}-600 dark:text-{{ $mod['color'] }}-400" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="{{ $mod['icon'] }}"/></svg>
                    </div>
                    <span class="text-xs font-bold text-gray-700 dark:text-gray-300">{{ $mod['label'] }}</span>
                </button>
                @endforeach
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const isDark = document.documentElement.classList.contains('dark');
    const textColor = isDark ? '#94A3B8' : '#64748B';
    const gridColor = isDark ? '#1E293B' : '#F1F5F9';

    // Bar Chart - Kunjungan
    new ApexCharts(document.querySelector('#chartKunjungan'), {
        series: [
            { name: 'Kunjungan', data: [240, 260, 180, 280, 310, 290] },
            { name: 'Pasien Baru', data: [50, 60, 40, 70, 65, 55] }
        ],
        chart: { type: 'bar', height: 256, toolbar: { show: false }, background: 'transparent', fontFamily: 'Inter, sans-serif' },
        plotOptions: { bar: { horizontal: false, columnWidth: '48%', borderRadius: 6, borderRadiusApplication: 'end' } },
        dataLabels: { enabled: false },
        stroke: { show: false },
        xaxis: { categories: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun'], labels: { style: { colors: textColor, fontSize: '12px', fontWeight: 500 } }, axisBorder: { show: false }, axisTicks: { show: false } },
        yaxis: { labels: { style: { colors: textColor, fontSize: '12px' } } },
        grid: { borderColor: gridColor, strokeDashArray: 4 },
        colors: ['#0D9488', '#06B6D4'],
        legend: { show: false },
        theme: { mode: isDark ? 'dark' : 'light' },
        tooltip: { theme: isDark ? 'dark' : 'light' },
    }).render();

    // Donut Chart - Distribusi
    new ApexCharts(document.querySelector('#chartDistribusi'), {
        series: [45, 30, 15, 10],
        chart: { type: 'donut', height: 256, background: 'transparent', fontFamily: 'Inter, sans-serif' },
        labels: ['Kehamilan', 'KB', 'Konsultasi', 'Kontrol'],
        colors: ['#0D9488', '#06B6D4', '#10B981', '#14B8A6'],
        dataLabels: { enabled: false },
        legend: { position: 'bottom', fontFamily: 'Inter, sans-serif', fontSize: '12px', labels: { colors: textColor } },
        plotOptions: { pie: { donut: { size: '68%', labels: { show: true, total: { show: true, label: 'Total', color: textColor, fontSize: '12px', fontWeight: 600 } } } } },
        theme: { mode: isDark ? 'dark' : 'light' },
        tooltip: { theme: isDark ? 'dark' : 'light' },
    }).render();
});
</script>
