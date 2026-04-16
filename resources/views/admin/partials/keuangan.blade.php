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

    <div class="bg-white border border-gray-200 rounded-2xl p-6 shadow-sm mb-8">
        <div class="flex justify-between items-center mb-4">
            <div>
                <h4 class="font-bold text-gray-900">Tren Keuangan 6 Bulan Terakhir</h4>
                <p class="text-sm text-gray-500">Perbandingan pendapatan, pengeluaran, dan laba</p>
            </div>
            <select class="border border-gray-300 rounded-md text-sm px-3 py-1.5 outline-none bg-gray-50">
                <option>Bulanan</option>
                <option>Mingguan</option>
            </select>
        </div>
        <div id="chartKeuangan" class="w-full h-[350px]"></div> </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Line Chart Keuangan yang Smooth
        var optionsKeuangan = {
            series: [
                { name: 'Pendapatan', data: [35000000, 38000000, 31000000, 40000000, 43000000, 41000000] },
                { name: 'Pengeluaran', data: [24000000, 25000000, 22000000, 26000000, 27000000, 26500000] },
                { name: 'Laba', data: [11000000, 13000000, 9000000, 14000000, 16000000, 14500000] }
            ],
            chart: { type: 'area', height: 350, toolbar: { show: false } },
            colors: ['#10b981', '#ef4444', '#3b82f6'], // Hijau, Merah, Biru
            dataLabels: { enabled: false },
            stroke: { curve: 'smooth', width: 3 },
            xaxis: { categories: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun'] },
            yaxis: {
                labels: { formatter: function (value) { return "Rp " + (value/1000000) + "M"; } }
            },
            legend: { position: 'bottom' }
        };
        new ApexCharts(document.querySelector("#chartKeuangan"), optionsKeuangan).render();
    });
</script>
