<div x-data="reportApp()" x-init="init()">
    {{-- Header & Export Actions --}}
    <div class="flex justify-between items-center mb-6">
        <div>
            <h2 class="text-3xl font-extrabold text-gray-900 dark:text-white mb-1">Laporan & Analitik</h2>
            <p class="text-gray-500">Pelaporan otomatis periode <span x-text="selectedMonthName"></span> <span x-text="filters.year"></span></p>
        </div>
        <div class="flex gap-3">
            <button class="bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-700 text-gray-700 dark:text-gray-300 px-4 py-2 rounded-lg text-sm font-semibold flex items-center gap-2 hover:bg-gray-50 transition-all">
                <svg class="w-4 h-4 text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg> Export PDF
            </button>
            <button class="bg-black text-white px-4 py-2 rounded-lg text-sm font-semibold flex items-center gap-2 hover:bg-gray-800 transition-all shadow-lg shadow-black/10">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg> Cetak
            </button>
        </div>
    </div>

    {{-- Filter Card --}}
    <div class="bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 rounded-2xl p-6 shadow-sm mb-6">
        <h4 class="font-bold text-gray-900 dark:text-white mb-4">Filter Laporan</h4>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
            <div>
                <label class="block text-xs font-semibold text-gray-500 mb-1 uppercase tracking-wider">Jenis Laporan</label>
                <select x-model="filters.type" @change="fetchData()" class="w-full border border-gray-300 dark:border-gray-700 rounded-lg text-sm px-3 py-2.5 outline-none bg-gray-50 dark:bg-gray-800 dark:text-white">
                    <option value="bulanan">Bulanan</option>
                    <option value="tahunan">Tahunan</option>
                </select>
            </div>
            <div>
                <label class="block text-xs font-semibold text-gray-500 mb-1 uppercase tracking-wider">Bulan</label>
                <select x-model="filters.month" @change="fetchData()" class="w-full border border-gray-300 dark:border-gray-700 rounded-lg text-sm px-3 py-2.5 outline-none bg-gray-50 dark:bg-gray-800 dark:text-white">
                    <template x-for="(month, index) in monthsList">
                        <option :value="index + 1" x-text="month"></option>
                    </template>
                </select>
            </div>
            <div>
                <label class="block text-xs font-semibold text-gray-500 mb-1 uppercase tracking-wider">Tahun</label>
                <select x-model="filters.year" @change="fetchData()" class="w-full border border-gray-300 dark:border-gray-700 rounded-lg text-sm px-3 py-2.5 outline-none bg-gray-50 dark:bg-gray-800 dark:text-white">
                    <option value="2026">2026</option>
                    <option value="2025">2025</option>
                </select>
            </div>
        </div>

        <div class="flex gap-6 border-b border-gray-200 dark:border-gray-800">
            <button @click="activeTab = 'ringkasan'" :class="activeTab === 'ringkasan' ? 'border-teal-500 text-teal-600' : 'border-transparent text-gray-500'" class="pb-3 border-b-2 font-bold text-sm flex items-center gap-2 transition-all">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg> Ringkasan
            </button>
            <button @click="activeTab = 'grafik'" :class="activeTab === 'grafik' ? 'border-teal-500 text-teal-600' : 'border-transparent text-gray-500'" class="pb-3 border-b-2 font-bold text-sm flex items-center gap-2 transition-all">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path></svg> Grafik & Analitik
            </button>
        </div>
    </div>

    {{-- Summary Cards --}}
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6" x-show="activeTab === 'ringkasan'" x-transition>
        <template x-for="card in statCards" :key="card.label">
            <div class="bg-white dark:bg-gray-900 p-6 rounded-2xl border border-gray-200 dark:border-gray-800 shadow-sm hover:shadow-md transition-shadow">
                <div class="flex justify-between items-start mb-4">
                    <div :class="card.bg" class="p-2 rounded-lg" x-html="card.icon"></div>
                    <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest" x-text="card.label"></p>
                </div>
                <h3 class="text-3xl font-black text-gray-900 dark:text-white mb-1" x-text="card.value">0</h3>
                <p class="text-[11px] font-bold text-gray-500" x-html="card.desc"></p>
            </div>
        </template>
    </div>

    {{-- Detailed Table --}}
    <div class="bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 rounded-2xl p-6 shadow-sm" x-show="activeTab === 'ringkasan'" x-transition>
        <div class="flex justify-between items-center mb-6">
            <h4 class="font-black text-gray-900 dark:text-white uppercase tracking-tight">Rincian Kunjungan - <span x-text="selectedMonthName"></span></h4>
            <span class="text-[10px] font-black bg-teal-50 dark:bg-teal-900/30 text-teal-600 px-3 py-1 rounded-full uppercase tracking-widest">Data Real-time</span>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-sm">
                <thead>
                    <tr class="bg-gray-50 dark:bg-gray-800/50 border-b border-gray-200 dark:border-gray-800 text-gray-600 dark:text-gray-400">
                        <th class="p-4 font-black uppercase tracking-tighter">Bulan</th>
                        <th class="p-4 font-black text-right uppercase tracking-tighter">Total Kunjungan</th>
                        <th class="p-4 font-black text-right uppercase tracking-tighter">Kehamilan</th>
                        <th class="p-4 font-black text-right uppercase tracking-tighter">KB</th>
                        <th class="p-4 font-black text-right uppercase tracking-tighter">Pendapatan</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                    <template x-for="row in tableData" :key="row.month">
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/40 transition-colors">
                            <td class="p-4 font-bold text-gray-900 dark:text-white" x-text="row.month"></td>
                            <td class="p-4 text-right font-black text-gray-900 dark:text-white" x-text="row.total"></td>
                            <td class="p-4 text-right text-gray-600 dark:text-gray-400" x-text="row.hamil"></td>
                            <td class="p-4 text-right text-gray-600 dark:text-gray-400" x-text="row.kb"></td>
                            <td class="p-4 text-right font-bold text-teal-600" x-text="'Rp ' + row.income"></td>
                        </tr>
                    </template>
                </tbody>
            </table>
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

        init() {
            this.fetchData();
        },

        async fetchData() {
            try {
                const response = await fetch(`/admin/report/stats?month=${this.filters.month}&year=${this.filters.year}&type=${this.filters.type}`);
                const data = await response.json();

                // Mapping data dari backend ke UI
                this.statCards = [
                    { label: 'Total', value: data.totalPasien, bg: 'bg-blue-50 text-blue-600', icon: '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>', desc: `Total Pasien <span class="text-green-600 text-xs ml-1">+${data.pasienBaru} baru</span>` },
                    { label: 'Kehamilan', value: data.totalHamil, bg: 'bg-pink-50 text-pink-600', icon: '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>', desc: 'Pasien Hamil Aktif' },
                    { label: 'KB', value: data.totalKB, bg: 'bg-red-50 text-red-600', icon: '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path></svg>', desc: 'Peserta KB Aktif' },
                    { label: 'Kunjungan', value: data.totalKunjungan, bg: 'bg-purple-50 text-purple-600', icon: '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>', desc: 'Total Kunjungan' }
                ];

                this.tableData = data.table;
            } catch (error) {
                console.error("Gagal memuat data laporan:", error);
            }
        }
    }
}
</script>
