<div x-data="reportApp()" x-init="init()" class="relative">
    {{-- Include Libraries --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.28/jspdf.plugin.autotable.min.js"></script>

    {{-- Header & Export Actions --}}
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-6">
        <div>
            <h2 class="text-3xl font-black text-gray-900 dark:text-white tracking-tight">Laporan & Analitik</h2>
            <p class="text-gray-500 text-sm font-medium mt-1">Pelaporan otomatis periode <span x-text="filters.type === 'bulanan' ? selectedMonthName + ' ' + filters.year : 'Tahun ' + filters.year" class="font-bold text-teal-600 dark:text-teal-400"></span></p>
        </div>
        <div class="flex items-center gap-3 w-full md:w-auto">
            <button @click="exportPDF()" class="flex-1 md:flex-none bg-white/80 dark:bg-[#1E293B]/80 backdrop-blur-md border border-gray-200 dark:border-gray-700 text-gray-700 dark:text-gray-200 px-5 py-2.5 rounded-xl text-sm font-bold flex items-center justify-center gap-2 hover:bg-gray-50 dark:hover:bg-gray-800 hover:border-teal-500 hover:text-teal-600 transition-all shadow-sm">
                <svg class="w-5 h-5 text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg> 
                Export PDF
            </button>
            <button @click="window.print()" class="flex-1 md:flex-none bg-gradient-to-r from-teal-600 to-cyan-500 text-white px-5 py-2.5 rounded-xl text-sm font-bold flex items-center justify-center gap-2 hover:from-teal-700 hover:to-cyan-600 transition-all shadow-lg shadow-teal-500/20">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg> 
                Cetak Cetak
            </button>
        </div>
    </div>

    {{-- Filter Card (Glassmorphism) --}}
    <div class="bg-white/50 dark:bg-[#1E293B]/50 backdrop-blur-xl border border-gray-100 dark:border-gray-800 rounded-3xl p-6 shadow-sm mb-6">
        <div class="flex items-center gap-3 mb-5">
            <div class="w-8 h-8 rounded-full bg-teal-100 dark:bg-teal-900/50 flex items-center justify-center text-teal-600">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/></svg>
            </div>
            <h4 class="font-black text-gray-900 dark:text-white">Filter Laporan</h4>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-5 mb-6">
            <div>
                <label class="block text-[11px] font-black text-gray-400 dark:text-gray-500 mb-1.5 uppercase tracking-widest">Jenis Laporan</label>
                <select x-model="filters.type" @change="fetchData()" class="w-full bg-white dark:bg-[#0f172a] border border-gray-200 dark:border-gray-700 rounded-xl text-sm font-bold text-gray-700 dark:text-gray-200 px-4 py-3 outline-none focus:ring-4 focus:ring-teal-500/10 transition-all cursor-pointer">
                    <option value="bulanan">Bulanan (Spesifik)</option>
                    <option value="tahunan">Tahunan (Ringkasan)</option>
                </select>
            </div>
            <div>
                <label class="block text-[11px] font-black text-gray-400 dark:text-gray-500 mb-1.5 uppercase tracking-widest">Bulan</label>
                <select x-model="filters.month" @change="fetchData()" :disabled="filters.type === 'tahunan'" class="w-full bg-white dark:bg-[#0f172a] border border-gray-200 dark:border-gray-700 rounded-xl text-sm font-bold text-gray-700 dark:text-gray-200 px-4 py-3 outline-none focus:ring-4 focus:ring-teal-500/10 transition-all cursor-pointer disabled:opacity-50 disabled:cursor-not-allowed">
                    <template x-for="(month, index) in monthsList">
                        <option :value="index + 1" x-text="month"></option>
                    </template>
                </select>
            </div>
            <div>
                <label class="block text-[11px] font-black text-gray-400 dark:text-gray-500 mb-1.5 uppercase tracking-widest">Tahun</label>
                <select x-model="filters.year" @change="fetchData()" class="w-full bg-white dark:bg-[#0f172a] border border-gray-200 dark:border-gray-700 rounded-xl text-sm font-bold text-gray-700 dark:text-gray-200 px-4 py-3 outline-none focus:ring-4 focus:ring-teal-500/10 transition-all cursor-pointer">
                    <option value="2026">2026</option>
                    <option value="2025">2025</option>
                </select>
            </div>
        </div>

        <div class="flex gap-2 p-1 bg-gray-100/50 dark:bg-gray-800/50 rounded-xl w-fit">
            <button @click="activeTab = 'ringkasan'" :class="activeTab === 'ringkasan' ? 'bg-white dark:bg-[#0f172a] shadow-sm text-teal-600 dark:text-teal-400' : 'text-gray-500 hover:text-gray-700 dark:hover:text-gray-300'" class="px-5 py-2 rounded-lg font-bold text-sm flex items-center gap-2 transition-all">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg> 
                Ringkasan Data
            </button>
            <button @click="activeTab = 'grafik'" :class="activeTab === 'grafik' ? 'bg-white dark:bg-[#0f172a] shadow-sm text-teal-600 dark:text-teal-400' : 'text-gray-500 hover:text-gray-700 dark:hover:text-gray-300'" class="px-5 py-2 rounded-lg font-bold text-sm flex items-center gap-2 transition-all">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path></svg> 
                Grafik & Analitik
            </button>
        </div>
    </div>

    {{-- Summary Cards (Tab: Ringkasan) --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5 mb-6" x-show="activeTab === 'ringkasan'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0">
        <template x-for="card in statCards" :key="card.label">
            <div class="relative bg-white dark:bg-[#1E293B] p-6 rounded-3xl border border-gray-100 dark:border-gray-800 shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all duration-300 overflow-hidden group">
                <div class="absolute -right-6 -top-6 w-24 h-24 rounded-full opacity-10 group-hover:opacity-20 transition-opacity" :class="card.bg"></div>
                <div class="flex justify-between items-start mb-4 relative z-10">
                    <div :class="card.bg" class="p-2.5 rounded-xl shadow-sm" x-html="card.icon"></div>
                    <p class="text-[10px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-[0.2em]" x-text="card.label"></p>
                </div>
                <h3 class="text-4xl font-black text-gray-900 dark:text-white mb-1.5 relative z-10" x-text="card.value">0</h3>
                <p class="text-xs font-bold text-gray-500 dark:text-gray-400 relative z-10" x-html="card.desc"></p>
            </div>
        </template>
    </div>

    {{-- Detailed Table (Tab: Ringkasan) --}}
    <div class="bg-white dark:bg-[#1E293B] border border-gray-100 dark:border-gray-800 rounded-3xl p-6 shadow-sm overflow-hidden" x-show="activeTab === 'ringkasan'" x-transition:enter="transition ease-out duration-300 delay-100" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0">
        <div class="flex justify-between items-center mb-6">
            <h4 class="font-black text-gray-900 dark:text-white uppercase tracking-tight">Tabel Rincian Kunjungan</h4>
            <span class="text-[10px] font-black bg-teal-50 dark:bg-teal-900/30 text-teal-600 dark:text-teal-400 px-3 py-1.5 rounded-lg uppercase tracking-widest border border-teal-100 dark:border-teal-800">Tervalidasi</span>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse" id="reportTable">
                <thead>
                    <tr class="bg-gray-50/50 dark:bg-gray-800/50 border-b-2 border-gray-100 dark:border-gray-800 text-gray-500 dark:text-gray-400">
                        <th class="p-4 text-xs font-black uppercase tracking-widest">Bulan</th>
                        <th class="p-4 text-xs font-black text-center uppercase tracking-widest">Total Kunjungan</th>
                        <th class="p-4 text-xs font-black text-center uppercase tracking-widest">Kehamilan</th>
                        <th class="p-4 text-xs font-black text-center uppercase tracking-widest">Keluarga Berencana</th>
                        <th class="p-4 text-xs font-black text-right uppercase tracking-widest">Estimasi Pendapatan</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50 dark:divide-gray-800/50">
                    <template x-if="tableData.length === 0">
                        <tr>
                            <td colspan="5" class="p-8 text-center text-gray-400 font-bold">Tidak ada data untuk periode ini.</td>
                        </tr>
                    </template>
                    <template x-for="row in tableData" :key="row.month">
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/30 transition-colors group">
                            <td class="p-4 font-black text-gray-900 dark:text-white" x-text="row.month"></td>
                            <td class="p-4 text-center font-black text-teal-600 dark:text-teal-400 bg-teal-50/30 dark:bg-teal-900/10" x-text="row.total"></td>
                            <td class="p-4 text-center font-bold text-gray-600 dark:text-gray-400" x-text="row.hamil"></td>
                            <td class="p-4 text-center font-bold text-gray-600 dark:text-gray-400" x-text="row.kb"></td>
                            <td class="p-4 text-right font-black text-gray-900 dark:text-white group-hover:text-teal-600 transition-colors" x-text="'Rp ' + row.income"></td>
                        </tr>
                    </template>
                </tbody>
            </table>
        </div>
    </div>

    {{-- Chart Section (Tab: Grafik) --}}
    <div class="bg-white dark:bg-[#1E293B] border border-gray-100 dark:border-gray-800 rounded-3xl p-6 shadow-sm" x-show="activeTab === 'grafik'" style="display: none;">
        <div class="flex justify-between items-center mb-6">
            <h4 class="font-black text-gray-900 dark:text-white uppercase tracking-tight">Tren Kunjungan Pasien</h4>
        </div>
        <div class="w-full h-[400px] relative">
            <canvas id="trendChart"></canvas>
        </div>
    </div>

</div>

<script>
function reportApp() {
    return {
        activeTab: 'ringkasan',
        filters: {
            type: 'bulanan',
            month: new Date().getMonth() + 1,
            year: '2026'
        },
        monthsList: ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'],
        get selectedMonthName() {
            return this.monthsList[this.filters.month - 1];
        },
        statCards: [],
        tableData: [],
        chartInstance: null,

        init() {
            this.fetchData();
            // Watch tab changes to render chart correctly if hidden initially
            this.$watch('activeTab', value => {
                if(value === 'grafik') {
                    this.$nextTick(() => {
                        if (this.chartInstance) {
                            this.chartInstance.resize();
                            this.chartInstance.update();
                        }
                    });
                }
            });
        },

        async fetchData() {
            try {
                const response = await fetch(`/admin/report/stats?month=${this.filters.month}&year=${this.filters.year}&type=${this.filters.type}`);
                const data = await response.json();

                // Mapping data
                this.statCards = [
                    { label: 'Total Pasien', value: data.totalPasien, bg: 'bg-blue-50 text-blue-600 dark:bg-blue-900/30 dark:text-blue-400', icon: '<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>', desc: `Terdaftar di sistem <span class="text-emerald-500 font-black ml-1">+${data.pasienBaru} baru</span>` },
                    { label: 'Total Kunjungan', value: data.totalKunjungan, bg: 'bg-teal-50 text-teal-600 dark:bg-teal-900/30 dark:text-teal-400', icon: '<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>', desc: 'Selesai dilayani (Bulan ini)' },
                    { label: 'Kehamilan', value: data.totalHamil, bg: 'bg-rose-50 text-rose-500 dark:bg-rose-900/30 dark:text-rose-400', icon: '<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path></svg>', desc: 'Pasien Hamil Aktif' },
                    { label: 'Kel. Berencana', value: data.totalKB, bg: 'bg-indigo-50 text-indigo-500 dark:bg-indigo-900/30 dark:text-indigo-400', icon: '<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>', desc: 'Peserta KB Aktif' }
                ];

                this.tableData = data.table;
                this.renderChart(data.chartLabels, data.chartData);

            } catch (error) {
                console.error("Gagal memuat data laporan:", error);
            }
        },

        renderChart(labels, dataPoints) {
            const ctx = document.getElementById('trendChart');
            if (!ctx) return;

            if (this.chartInstance) {
                this.chartInstance.destroy();
            }

            this.chartInstance = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Total Kunjungan Pasien',
                        data: dataPoints,
                        borderColor: '#0d9488',
                        backgroundColor: 'rgba(13, 148, 136, 0.1)',
                        borderWidth: 3,
                        pointBackgroundColor: '#ffffff',
                        pointBorderColor: '#0d9488',
                        pointBorderWidth: 2,
                        pointRadius: 5,
                        pointHoverRadius: 7,
                        fill: true,
                        tension: 0.4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: { precision: 0, font: { family: 'Inter', weight: 'bold' } },
                            grid: { borderDash: [4, 4], color: '#e5e7eb' }
                        },
                        x: {
                            grid: { display: false },
                            ticks: { font: { family: 'Inter', weight: 'bold' } }
                        }
                    }
                }
            });
        },

        exportPDF() {
            // Cek ketersediaan jsPDF
            if (!window.jspdf || !window.jspdf.jsPDF) {
                alert("Library PDF belum siap. Silakan tunggu sebentar dan coba lagi.");
                return;
            }

            const { jsPDF } = window.jspdf;
            const doc = new jsPDF();

            const title = "LAPORAN KUNJUNGAN KLINIK DELIMACARE";
            const periode = this.filters.type === 'bulanan' 
                ? `Periode: ${this.selectedMonthName} ${this.filters.year}`
                : `Periode: Tahun ${this.filters.year}`;

            // Tambahkan Kop Surat
            doc.setFont("helvetica", "bold");
            doc.setFontSize(18);
            doc.setTextColor(13, 148, 136); // Teal 600
            doc.text("DELIMACARE CLINIC", 14, 22);
            
            doc.setFontSize(10);
            doc.setTextColor(100, 100, 100);
            doc.setFont("helvetica", "normal");
            doc.text("Jl. Kesehatan No. 123, Kota Medis, Indonesia", 14, 28);
            doc.text("Telp: (021) 1234-5678 | Email: info@delimacare.com", 14, 33);
            
            // Garis pembatas kop surat
            doc.setDrawColor(200, 200, 200);
            doc.setLineWidth(0.5);
            doc.line(14, 38, 196, 38);

            // Judul Laporan
            doc.setFontSize(14);
            doc.setTextColor(20, 20, 20);
            doc.setFont("helvetica", "bold");
            doc.text(title, 14, 50);
            
            doc.setFontSize(10);
            doc.setFont("helvetica", "normal");
            doc.text(periode, 14, 56);
            doc.text(`Tanggal Cetak: ${new Date().toLocaleDateString('id-ID')}`, 14, 62);

            // Menyusun Data untuk AutoTable
            const tableHeaders = [["Bulan", "Kunjungan", "Kehamilan", "KB", "Est. Pendapatan"]];
            const tableBody = this.tableData.map(row => [
                row.month,
                row.total.toString(),
                row.hamil.toString(),
                row.kb.toString(),
                "Rp " + row.income
            ]);

            // Total Baris
            const totalKunjungan = this.tableData.reduce((sum, row) => sum + row.total, 0);
            tableBody.push([
                { content: 'TOTAL KESELURUHAN', styles: { fontStyle: 'bold', halign: 'right' } },
                { content: totalKunjungan.toString(), styles: { fontStyle: 'bold', halign: 'center' } },
                "-", "-", "-"
            ]);

            // Buat Tabel
            doc.autoTable({
                startY: 70,
                head: tableHeaders,
                body: tableBody,
                theme: 'grid',
                headStyles: { fillColor: [13, 148, 136], textColor: 255, fontStyle: 'bold', halign: 'center' },
                columnStyles: {
                    0: { halign: 'left', fontStyle: 'bold' },
                    1: { halign: 'center' },
                    2: { halign: 'center' },
                    3: { halign: 'center' },
                    4: { halign: 'right' }
                },
                alternateRowStyles: { fillColor: [248, 250, 252] },
                styles: { fontSize: 9, cellPadding: 4, textColor: 40 },
                margin: { top: 70 }
            });

            // Footer / Tanda Tangan
            const finalY = doc.lastAutoTable.finalY || 70;
            if (finalY + 40 > doc.internal.pageSize.getHeight()) {
                doc.addPage();
            }
            doc.setFont("helvetica", "normal");
            doc.text("Mengetahui,", 150, finalY + 20);
            doc.setFont("helvetica", "bold");
            doc.text("Admin Klinik", 150, finalY + 45);

            // Simpan PDF
            doc.save(`Laporan_DelimaCare_${this.filters.year}.pdf`);
        }
    }
}
</script>
