{{-- ===== DASHBOARD OVERVIEW ===== --}}
<div class="space-y-7">

    {{-- Welcome Banner --}}
    <div class="relative overflow-hidden rounded-3xl p-7 text-white" style="background: linear-gradient(135deg, #0D9488 0%, #0891B2 60%, #06B6D4 100%);">
        <div class="absolute inset-0 opacity-10">
            <svg viewBox="0 0 400 200" class="w-full h-full"><circle cx="350" cy="-20" r="120" fill="white"/><circle cx="50" cy="220" r="100" fill="white"/></svg>
        </div>
        <div class="absolute right-8 top-1/2 -translate-y-1/2 hidden lg:block opacity-20">
            <svg class="w-32 h-32" viewBox="0 0 24 24" fill="currentColor"><path d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
        </div>
        <div class="relative z-10">
            <p class="text-teal-100 text-sm font-semibold mb-1">Selamat datang kembali 👋</p>
            <h3 class="text-2xl font-black tracking-tight mb-1">
                @auth {{ Auth::user()->username }} @else Administrator @endauth
            </h3>
            <p class="text-teal-100 text-sm">{{ now()->locale('id')->isoFormat('dddd, D MMMM YYYY') }} — Semoga harimu menyenangkan!</p>
        </div>
        <div class="relative z-10 mt-5 flex flex-wrap gap-3">
            <span class="inline-flex items-center gap-2 bg-white/15 backdrop-blur-sm border border-white/20 px-4 py-2 rounded-xl text-xs font-bold">
                <span class="w-2 h-2 bg-green-300 rounded-full animate-pulse"></span> Sistem Online
            </span>
            <span class="inline-flex items-center gap-2 bg-white/15 backdrop-blur-sm border border-white/20 px-4 py-2 rounded-xl text-xs font-bold">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                {{ $stats['reservasi_hari_ini'] }} Reservasi Hari Ini
            </span>
            <span class="inline-flex items-center gap-2 bg-white/15 backdrop-blur-sm border border-white/20 px-4 py-2 rounded-xl text-xs font-bold">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                {{ $stats['total_pasien'] }} Total Pasien
            </span>
        </div>
    </div>

    {{-- Stat Cards --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-5">
        @php
        $statsData = [
            ['label'=>'Total Pasien','value'=>$stats['total_pasien'],'sub'=>'dari bulan lalu','badge'=>'+12%','badge_color'=>'emerald','color'=>'teal','icon'=>'M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z','up'=>true],
            ['label'=>'Reservasi Hari Ini','value'=>$stats['reservasi_hari_ini'],'sub'=>'dari kemarin','badge'=>'+5','badge_color'=>'emerald','color'=>'cyan','icon'=>'M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z','up'=>true],
            ['label'=>'Stok Menipis','value'=>$stats['stok_menipis'],'sub'=>'item perlu restock','badge'=>$stats['stok_menipis'] > 0 ? 'Perhatian' : 'Aman','badge_color'=>$stats['stok_menipis'] > 0 ? 'amber' : 'emerald','color'=>'amber','icon'=>'M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4','up'=>false],
            ['label'=>'Pendapatan Bulan Ini','value'=>'Rp '.$stats['pendapatan_bulan_ini'],'sub'=>'total resep bulan ini','badge'=>'Live','badge_color'=>'teal','color'=>'emerald','icon'=>'M13 7h8m0 0v8m0-8l-8 8-4-4-6 6','up'=>false],
        ];
        @endphp
        @foreach($statsData as $i => $s)
        <div class="relative bg-white dark:bg-[#1E293B] rounded-2xl p-6 border border-gray-100 dark:border-gray-800 shadow-sm hover:-translate-y-1.5 hover:shadow-lg transition-all duration-300 overflow-hidden group"
             style="animation: fadeInUp 0.5s cubic-bezier(0.16,1,0.3,1) {{ $i * 100 }}ms both;">
            <div class="absolute inset-x-0 top-0 h-1 bg-{{ $s['color'] }}-500 rounded-t-2xl"></div>
            <div class="absolute -right-4 -bottom-4 w-20 h-20 bg-{{ $s['color'] }}-50 dark:bg-{{ $s['color'] }}-900/20 rounded-full blur-2xl group-hover:scale-150 transition-transform duration-700"></div>
            <div class="flex items-start justify-between mb-5 relative">
                <div class="w-11 h-11 rounded-xl flex items-center justify-center bg-{{ $s['color'] }}-50 dark:bg-{{ $s['color'] }}-900/30 group-hover:scale-110 transition-transform duration-300">
                    <svg class="w-5 h-5 text-{{ $s['color'] }}-600 dark:text-{{ $s['color'] }}-400" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="{{ $s['icon'] }}"/></svg>
                </div>
                <span class="inline-flex items-center gap-1 text-xs font-bold text-{{ $s['badge_color'] }}-700 dark:text-{{ $s['badge_color'] }}-400 bg-{{ $s['badge_color'] }}-50 dark:bg-{{ $s['badge_color'] }}-900/30 px-2.5 py-1.5 rounded-lg">
                    @if($s['up'])<svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 10l7-7m0 0l7 7m-7-7v18"/></svg>@endif
                    {{ $s['badge'] }}
                </span>
            </div>
            <p class="text-xs font-bold text-gray-500 dark:text-gray-400 mb-1 uppercase tracking-wider relative">{{ $s['label'] }}</p>
            <h3 class="text-3xl font-black text-gray-900 dark:text-white tracking-tight relative counter" data-target="{{ preg_replace('/[^0-9]/', '', $s['value']) }}">{{ $s['value'] }}</h3>
            <p class="text-xs font-medium text-gray-400 dark:text-gray-500 mt-2 relative">{{ $s['sub'] }}</p>
        </div>
        @endforeach
    </div>

    {{-- Charts Row --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 bg-white dark:bg-[#1E293B] rounded-2xl p-6 border border-gray-100 dark:border-gray-800 shadow-sm">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h4 class="text-base font-black text-gray-900 dark:text-white">Statistik Kunjungan</h4>
                    <p class="text-xs font-medium text-gray-500 dark:text-gray-400 mt-0.5">Tren pasien 6 bulan terakhir</p>
                </div>
                <div class="flex items-center gap-4 text-xs font-bold text-gray-500 dark:text-gray-400">
                    <span class="flex items-center gap-1.5"><span class="w-3 h-3 rounded bg-teal-500 inline-block"></span>Kunjungan</span>
                    <span class="flex items-center gap-1.5"><span class="w-3 h-3 rounded bg-cyan-400 inline-block"></span>Pasien Baru</span>
                </div>
            </div>
            <div id="chartKunjungan" class="w-full h-64"></div>
        </div>
        <div class="bg-white dark:bg-[#1E293B] rounded-2xl p-6 border border-gray-100 dark:border-gray-800 shadow-sm">
            <h4 class="text-base font-black text-gray-900 dark:text-white mb-1">Distribusi Layanan</h4>
            <p class="text-xs font-medium text-gray-500 dark:text-gray-400 mb-4">Data bulan ini</p>
            <div id="chartDistribusi" class="w-full h-64 flex justify-center items-center"></div>
        </div>
    </div>

    {{-- Health Metrics Strip --}}
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        @php
        $metrics = [
            ['label'=>'Tingkat Kepuasan','value'=>'96%','icon'=>'M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z','color'=>'yellow'],
            ['label'=>'Pasien Sembuh','value'=>'98,5%','icon'=>'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z','color'=>'emerald'],
            ['label'=>'Waktu Tunggu Rata-rata','value'=>'12 mnt','icon'=>'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z','color'=>'cyan'],
            ['label'=>'Dokter Aktif','value'=>'5','icon'=>'M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z','color'=>'teal'],
        ];
        @endphp
        @foreach($metrics as $m)
        <div class="bg-white dark:bg-[#1E293B] rounded-2xl p-5 border border-gray-100 dark:border-gray-800 shadow-sm flex items-center gap-4 group hover:-translate-y-1 hover:shadow-md transition-all duration-300">
            <div class="w-10 h-10 rounded-xl bg-{{ $m['color'] }}-50 dark:bg-{{ $m['color'] }}-900/30 flex items-center justify-center flex-shrink-0 group-hover:scale-110 transition-transform duration-300">
                <svg class="w-5 h-5 text-{{ $m['color'] }}-600 dark:text-{{ $m['color'] }}-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="{{ $m['icon'] }}"/></svg>
            </div>
            <div>
                <p class="text-xs font-semibold text-gray-500 dark:text-gray-400">{{ $m['label'] }}</p>
                <p class="text-lg font-black text-gray-900 dark:text-white">{{ $m['value'] }}</p>
            </div>
        </div>
        @endforeach
    </div>

    {{-- Bottom Row: Activity + Quick Access --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 bg-white dark:bg-[#1E293B] rounded-2xl p-6 border border-gray-100 dark:border-gray-800 shadow-sm">
            <div class="flex items-center justify-between mb-6">
                <h4 class="text-base font-black text-gray-900 dark:text-white">Aktivitas Terkini</h4>
                <button class="text-xs font-bold text-teal-600 dark:text-teal-400 hover:text-teal-700 dark:hover:text-teal-300 bg-teal-50 dark:bg-teal-900/30 px-3 py-1.5 rounded-lg transition-colors">Lihat Semua</button>
            </div>
            <div class="relative pl-4">
                <div class="absolute left-[27px] top-4 bottom-4 w-px bg-gradient-to-b from-teal-200 via-gray-200 to-transparent dark:from-teal-800 dark:via-gray-700"></div>
                <div class="space-y-5">
                    @php
                    $activities = [
                        ['color'=>'teal','icon'=>'M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z','title'=>'Reservasi baru dari Siti Rahayu','time'=>'2 menit lalu','desc'=>'Pemeriksaan Kehamilan — Dr. Nurhaliza'],
                        ['color'=>'emerald','icon'=>'M5 13l4 4L19 7','title'=>'Pembayaran dikonfirmasi','time'=>'15 menit lalu','desc'=>'Rp 350.000 — Konsultasi KB'],
                        ['color'=>'amber','icon'=>'M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z','title'=>'Stok Amoksisilin menipis','time'=>'1 jam lalu','desc'=>'Tersisa 12 unit — Segera restock'],
                        ['color'=>'cyan','icon'=>'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z','title'=>'Rekam medis diperbarui','time'=>'2 jam lalu','desc'=>'Pasien: Dewi Lestari — Dr. Siti'],
                    ];
                    @endphp
                    @foreach($activities as $act)
                    <div class="relative flex items-start gap-4 group">
                        <div class="w-10 h-10 rounded-xl flex items-center justify-center flex-shrink-0 bg-{{ $act['color'] }}-50 dark:bg-{{ $act['color'] }}-900/30 z-10 ring-4 ring-white dark:ring-[#1E293B] group-hover:scale-110 transition-transform duration-300">
                            <svg class="w-4 h-4 text-{{ $act['color'] }}-600 dark:text-{{ $act['color'] }}-400" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="{{ $act['icon'] }}"/></svg>
                        </div>
                        <div class="flex-1 min-w-0 pt-1.5">
                            <div class="flex items-center justify-between gap-3 mb-0.5">
                                <p class="text-sm font-bold text-gray-900 dark:text-white truncate">{{ $act['title'] }}</p>
                                <span class="text-[10px] font-bold text-gray-400 whitespace-nowrap bg-gray-50 dark:bg-gray-800/60 px-2 py-1 rounded-md">{{ $act['time'] }}</span>
                            </div>
                            <p class="text-xs text-gray-500 dark:text-gray-400">{{ $act['desc'] }}</p>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-[#1E293B] rounded-2xl p-6 border border-gray-100 dark:border-gray-800 shadow-sm">
            <h4 class="text-base font-black text-gray-900 dark:text-white mb-5">Akses Cepat</h4>
            <div class="grid grid-cols-2 gap-3">
                @php
                $modules = [
                    ['menu'=>'konten','label'=>'Konten','color'=>'teal','icon'=>'M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z'],
                    ['menu'=>'inventori','label'=>'Inventori','color'=>'amber','icon'=>'M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4'],
                    ['menu'=>'keuangan','label'=>'Keuangan','color'=>'emerald','icon'=>'M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z'],
                    ['menu'=>'laporan','label'=>'Laporan','color'=>'cyan','icon'=>'M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z'],
                    ['menu'=>'reservasi','label'=>'Reservasi','color'=>'violet','icon'=>'M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z'],
                    ['menu'=>'rekam_medis','label'=>'Rekam Medis','color'=>'rose','icon'=>'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z'],
                ];
                @endphp
                @foreach($modules as $mod)
                <button @click="switchMenu('{{ $mod['menu'] }}')"
                        class="flex flex-col items-center justify-center gap-2.5 p-4 rounded-xl border border-gray-100 dark:border-gray-800 hover:border-{{ $mod['color'] }}-300 dark:hover:border-{{ $mod['color'] }}-700 hover:bg-{{ $mod['color'] }}-50 dark:hover:bg-{{ $mod['color'] }}-900/20 hover:-translate-y-1 hover:shadow-md transition-all duration-300 group">
                    <div class="w-10 h-10 rounded-xl bg-{{ $mod['color'] }}-50 dark:bg-{{ $mod['color'] }}-900/30 flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                        <svg class="w-5 h-5 text-{{ $mod['color'] }}-600 dark:text-{{ $mod['color'] }}-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="{{ $mod['icon'] }}"/></svg>
                    </div>
                    <span class="text-xs font-bold text-gray-700 dark:text-gray-300 group-hover:text-{{ $mod['color'] }}-600 dark:group-hover:text-{{ $mod['color'] }}-400 text-center leading-tight">{{ $mod['label'] }}</span>
                </button>
                @endforeach
            </div>
        </div>
    </div>
</div>

<script>
(function () {
    const isDark = document.documentElement.classList.contains('dark');
    const textColor = isDark ? '#94A3B8' : '#64748B';
    const gridColor = isDark ? '#1E293B' : '#F1F5F9';

    const chartKunjunganEl = document.querySelector('#chartKunjungan');
    const chartDistribusiEl = document.querySelector('#chartDistribusi');
    if (chartKunjunganEl) chartKunjunganEl.innerHTML = '';
    if (chartDistribusiEl) chartDistribusiEl.innerHTML = '';

    if (chartKunjunganEl) {
        var rawKunjungan = {!! $chartKunjunganData ?? '{"categories":[],"kunjungan":[],"pasien_baru":[]}' !!};
        new ApexCharts(chartKunjunganEl, {
            series: [
                { name: 'Kunjungan', data: rawKunjungan.kunjungan },
                { name: 'Pasien Baru', data: rawKunjungan.pasien_baru }
            ],
            chart: { type: 'bar', height: 220, toolbar: { show: false }, background: 'transparent', fontFamily: 'Inter, sans-serif', animations: { enabled: true, easing: 'easeinout', speed: 800 } },
            plotOptions: { bar: { horizontal: false, columnWidth: '44%', borderRadius: 7, borderRadiusApplication: 'end' } },
            dataLabels: { enabled: false },
            xaxis: { categories: rawKunjungan.categories, labels: { style: { colors: textColor, fontSize: '12px', fontWeight: 600 } }, axisBorder: { show: false }, axisTicks: { show: false } },
            yaxis: { labels: { style: { colors: textColor, fontSize: '12px' } } },
            grid: { borderColor: gridColor, strokeDashArray: 4, padding: { left: 0, right: 0 } },
            colors: ['#0D9488', '#06B6D4'],
            legend: { show: false },
            theme: { mode: isDark ? 'dark' : 'light' },
            tooltip: { theme: isDark ? 'dark' : 'light', y: { formatter: val => val + ' pasien' } },
        }).render();
    }

    if (chartDistribusiEl) {
        new ApexCharts(chartDistribusiEl, {
            series: {!! $chartDistribusiData ?? '[0, 0, 0, 0]' !!},
            chart: { type: 'donut', height: 220, background: 'transparent', fontFamily: 'Inter, sans-serif', animations: { enabled: true, easing: 'easeinout', speed: 900 } },
            labels: ['Kehamilan', 'KB', 'Konsultasi', 'Lainnya'],
            colors: ['#0D9488', '#06B6D4', '#10B981', '#14B8A6'],
            dataLabels: { enabled: false },
            legend: { position: 'bottom', fontFamily: 'Inter, sans-serif', fontSize: '11px', labels: { colors: textColor } },
            plotOptions: { pie: { donut: { size: '70%', labels: { show: true, total: { show: true, label: 'Total', color: textColor, fontSize: '11px', fontWeight: 700 } } } } },
            theme: { mode: isDark ? 'dark' : 'light' },
            tooltip: { theme: isDark ? 'dark' : 'light' },
        }).render();
    }
})();
</script>
