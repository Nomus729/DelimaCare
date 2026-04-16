<div>
    <h2 class="text-3xl font-extrabold text-gray-900 mb-1">Dashboard</h2>
    <p class="text-gray-500 mb-8">Ringkasan operasional klinik DelimaCare</p>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <div class="bg-white p-6 rounded-2xl border border-gray-200 shadow-sm">
            <div class="flex justify-between items-start mb-4">
                <p class="text-sm font-semibold text-gray-600">Total Pasien</p>
                <div class="p-2 bg-blue-50 text-blue-600 rounded-lg"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg></div>
            </div>
            <h3 class="text-3xl font-bold text-gray-900 mb-1">342</h3>
            <p class="text-xs font-medium text-green-600">+12% dari bulan lalu</p>
        </div>
        <div class="bg-white p-6 rounded-2xl border border-gray-200 shadow-sm">
            <div class="flex justify-between items-start mb-4">
                <p class="text-sm font-semibold text-gray-600">Reservasi Hari Ini</p>
                <div class="p-2 bg-green-50 text-green-600 rounded-lg"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg></div>
            </div>
            <h3 class="text-3xl font-bold text-gray-900 mb-1">28</h3>
            <p class="text-xs font-medium text-green-600">+5 dari kemarin</p>
        </div>
        <div class="bg-white p-6 rounded-2xl border border-gray-200 shadow-sm">
            <div class="flex justify-between items-start mb-4">
                <p class="text-sm font-semibold text-gray-600">Stok Menipis</p>
                <div class="p-2 bg-orange-50 text-orange-600 rounded-lg"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg></div>
            </div>
            <h3 class="text-3xl font-bold text-gray-900 mb-1">7</h3>
            <p class="text-xs font-medium text-orange-600">Perlu Perhatian</p>
        </div>
        <div class="bg-white p-6 rounded-2xl border border-gray-200 shadow-sm">
            <div class="flex justify-between items-start mb-4">
                <p class="text-sm font-semibold text-gray-600">Pendapatan Bulan Ini</p>
                <div class="p-2 bg-purple-50 text-purple-600 rounded-lg"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path></svg></div>
            </div>
            <h3 class="text-3xl font-bold text-gray-900 mb-1">Rp 45.2M</h3>
            <p class="text-xs font-medium text-green-600">+18% dari bulan lalu</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
        <div class="bg-white border border-gray-200 rounded-2xl p-6 shadow-sm">
            <h4 class="font-bold text-gray-900 mb-1">Statistik Kunjungan Bulanan</h4>
            <p class="text-sm text-gray-500 mb-4">Data kunjungan dan pasien baru 6 bulan terakhir</p>
            <div id="chartKunjungan" class="w-full h-72"></div> </div>
        <div class="bg-white border border-gray-200 rounded-2xl p-6 shadow-sm">
            <h4 class="font-bold text-gray-900 mb-1">Distribusi Layanan</h4>
            <p class="text-sm text-gray-500 mb-4">Persentase jenis layanan bulan ini</p>
            <div id="chartDistribusi" class="w-full h-72 flex justify-center items-center"></div> </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        // 1. Chart Kunjungan (Bar Chart)
        var optionsKunjungan = {
            series: [{ name: 'Kunjungan', data: [240, 260, 180, 280, 310, 290] }, { name: 'Pasien Baru', data: [50, 60, 40, 70, 65, 55] }],
            chart: { type: 'bar', height: 300, toolbar: { show: false } },
            plotOptions: { bar: { horizontal: false, columnWidth: '55%', endingShape: 'rounded' } },
            dataLabels: { enabled: false },
            stroke: { show: true, width: 2, colors: ['transparent'] },
            xaxis: { categories: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun'] },
            colors: ['#3b82f6', '#10b981'], // Biru dan Hijau
            fill: { opacity: 1 }
        };
        new ApexCharts(document.querySelector("#chartKunjungan"), optionsKunjungan).render();

        // 2. Chart Distribusi (Pie Chart)
        var optionsDistribusi = {
            series: [45, 30, 15, 10],
            chart: { type: 'pie', height: 300 },
            labels: ['Kehamilan', 'KB', 'Konsultasi', 'Kontrol'],
            colors: ['#3b82f6', '#10b981', '#f59e0b', '#8b5cf6']
        };
        new ApexCharts(document.querySelector("#chartDistribusi"), optionsDistribusi).render();
    });
</script>
