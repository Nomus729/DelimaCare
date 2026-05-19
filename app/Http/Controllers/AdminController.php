<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Article;
use App\Models\Medicine;
use App\Models\RekamMedis;
use App\Models\Reservasi;
use App\Models\Doctor;
use App\Models\ResepMedisItem;
use App\Models\Pengeluaran;

class AdminController extends Controller
{
    public function index(Request $request)
    {
        // ─── Konten ───────────────────────────────────────────────
        $kontenData = $this->getKontenData($request);
        $articles       = $kontenData['articles'];
        $categoryCounts = $kontenData['categoryCounts'];
        $activeCategory = $kontenData['activeCategory'];
        $searchKonten   = $kontenData['searchKonten'];
        $sortKonten     = $kontenData['sortKonten'];

        // ─── Inventori ────────────────────────────────────────────
        $invData = $this->getInventoriData($request);
        $medicines        = $invData['medicines'];
        $lowStockCount    = $invData['lowStockCount'];
        $expiredCount     = $invData['expiredCount'];
        $nearExpiryCount  = $invData['nearExpiryCount'];
        $totalMedicines   = $invData['totalMedicines'];
        $medSearch        = $invData['medSearch'];
        $medSort          = $invData['medSort'];
        $medFilter        = $invData['medFilter'];

        // ─── Rekam Medis ──────────────────────────────────────────
        $rmSearch    = $request->query('rm_search', '');
        $rmKategori  = $request->query('rm_kategori', '');
        $rmDate      = $request->has('rm_date') ? $request->query('rm_date') : 'today';

        $rmQuery = RekamMedis::latest()
            ->search($rmSearch)
            ->byKategori($rmKategori)
            ->byDate($rmDate);

        $rekamMedis = $rmQuery->paginate(8)
            ->appends(['tab' => 'rekam_medis', 'rm_search' => $rmSearch, 'rm_kategori' => $rmKategori, 'rm_date' => $rmDate]);

        // Base query for stats (applies search and date filters, but NOT category filter)
        $statsBaseQuery = RekamMedis::search($rmSearch)->byDate($rmDate);

        // Optimized Stats queries (dynamic based on current filter)
        $rmStats = [
            'total'         => (clone $statsBaseQuery)->count(),
            'kehamilan'     => (clone $statsBaseQuery)->where('kategori', 'Kehamilan')->count(),
            'kb'            => (clone $statsBaseQuery)->where('kategori', 'Keluarga Berencana')->count(),
            'risiko_tinggi' => (clone $statsBaseQuery)->where('status_risiko', 'Tinggi')->count(),
        ];

        $rmKategoriCounts = [
            ''                   => $rmStats['total'],
            'Kehamilan'          => $rmStats['kehamilan'],
            'Keluarga Berencana' => $rmStats['kb'],
            'Kontrol Umum'       => (clone $statsBaseQuery)->where('kategori', 'Kontrol Umum')->count(),
        ];

        // ─── Reservasi ─────────────────────────────────────────────
        $resData = $this->getReservasiData($request);
        $semuaReservasi         = $resData['semuaReservasi'];
        $pendingReservasiCount  = $resData['pendingReservasiCount'];
        $reservasiHariIni       = $resData['reservasiHariIni'];
        $reservasiMendatang     = $resData['reservasiMendatang'];
        $reservasiDikonfirmasi  = $resData['reservasiDikonfirmasi'];
        $resFilter              = $resData['resFilter'];
        $resStatus              = $resData['resStatus'];
        $resSearch              = $resData['resSearch'];

        $totalPasien = RekamMedis::count();
        $doctors = Doctor::all();

        // ─── Keuangan ──────────────────────────────────────────────
        $keuanganData = $this->getKeuanganData($request);
        $chartKeuangan = $keuanganData['chartData'];
        $pengeluaranList = $keuanganData['pengeluaranList'];
        $kpiStats = $keuanganData['kpiStats'];
        $donutChartData = $keuanganData['donutChartData'];
        $topMedicines = $keuanganData['topMedicines'];
        $topDoctors = $keuanganData['topDoctors'];
        $topExpenses = $keuanganData['topExpenses'];
        $summaryTable = $keuanganData['summaryTable'];

        // ─── Dashboard Stats ──────────────────────────────────────
        // Kalkulasi pendapatan dinamis dari resep medis (harga obat × jumlah)
        $pendapatanBulanIni = ResepMedisItem::whereHas('resepMedis', function ($q) {
                $q->whereMonth('tanggal_resep', now()->month)
                  ->whereYear('tanggal_resep', now()->year);
            })
            ->join('medicines', 'resep_medis_items.medicine_id', '=', 'medicines.id')
            ->selectRaw('SUM(resep_medis_items.jumlah * medicines.price) as total')
            ->value('total') ?? 0;

        // Format pendapatan (contoh: 1500000 → "1,5Jt", 45000000 → "45Jt")
        $pendapatanFormatted = $this->formatRupiah($pendapatanBulanIni);

        $stats = [
            'total_pasien'         => $totalPasien,
            'reservasi_hari_ini'   => $reservasiHariIni,
            'stok_menipis'         => $lowStockCount,
            'pendapatan_bulan_ini' => $pendapatanFormatted,
        ];

        $activeTab = $request->query('tab', 'dashboard');

        return view('admin.index', compact(
            'articles', 'categoryCounts', 'activeCategory', 'searchKonten', 'sortKonten',
            'medicines', 'lowStockCount', 'expiredCount', 'nearExpiryCount', 'totalMedicines', 'medSearch', 'medSort', 'medFilter',
            'rekamMedis', 'rmStats', 'rmKategoriCounts', 'rmSearch', 'rmKategori', 'rmDate',
            'semuaReservasi', 'pendingReservasiCount', 'doctors',
            'reservasiHariIni', 'reservasiMendatang', 'reservasiDikonfirmasi',
            'resFilter', 'resStatus', 'resSearch',
            'stats', 'activeTab', 'chartKeuangan', 'pengeluaranList',
            'kpiStats', 'donutChartData', 'topMedicines',
            'topDoctors', 'topExpenses', 'summaryTable'
        ));
    }

    /**
     * Get reservation data for polling or index.
     */
    private function getReservasiData(Request $request)
    {
        // 🔥 Auto-update reservations that haven't been confirmed in 24h
        $this->autoUpdateExpiredReservations();

        $resFilter = $request->query('res_filter', 'today');
        $resStatus = $request->query('res_status', '');
        $resSearch = $request->query('res_search', '');

        $resQuery = Reservasi::with('doctor');

        if ($resFilter === 'today') {
            $resQuery->whereDate('tanggal', today());
        } elseif ($resFilter === 'upcoming') {
            $resQuery->whereDate('tanggal', '>', today());
        }

        if ($resStatus) {
            $resQuery->where('status', $resStatus);
        }

        if ($resSearch) {
            $resQuery->where('nama', 'like', "%{$resSearch}%");
        }

        $semuaReservasi = $resQuery->orderBy('tanggal')->orderBy('queue_number')->get();

        return [
            'semuaReservasi'        => $semuaReservasi,
            'pendingReservasiCount' => Reservasi::where('status', 'Menunggu')->count(),
            'reservasiHariIni'      => Reservasi::whereDate('tanggal', today())->count(),
            'reservasiMendatang'    => Reservasi::whereDate('tanggal', '>', today())->count(),
            'reservasiDikonfirmasi' => Reservasi::where('status', 'Dikonfirmasi')->whereDate('tanggal', '>=', today())->count(),
            'resFilter'             => $resFilter,
            'resStatus'             => $resStatus,
            'resSearch'             => $resSearch,
            'doctors'               => Doctor::all(),
        ];
    }

    /**
     * Return only the reservasi partial for auto-update.
     */
    public function getReservasiPartial(Request $request)
    {
        $data = $this->getReservasiData($request);
        return view('admin.partials.reservasi', $data);
    }

    /**
     * Auto-mark reservations as "Tidak Datang" if not confirmed in 24 hours of creation.
     */
    private function autoUpdateExpiredReservations()
    {
        Reservasi::where('status', 'Menunggu')
            ->where('created_at', '<', now()->subHours(24))
            ->update(['status' => 'Tidak Datang']);
    }

    /**
     * Get inventory data.
     */
    private function getInventoriData(Request $request)
    {
        $medSearch = $request->query('med_search', '');
        $medSort   = $request->query('med_sort', 'name_asc');
        $medFilter = $request->query('med_filter', '');

        $medicines = Medicine::search($medSearch)
            ->filter($medFilter)
            ->sort($medSort)
            ->get();

        return [
            'medicines'       => $medicines,
            'lowStockCount'   => Medicine::whereRaw('stock <= min_stock')->count(),
            'expiredCount'    => Medicine::where('expired_at', '<', now())->count(),
            'nearExpiryCount' => Medicine::whereBetween('expired_at', [now(), now()->addDays(30)])->count(),
            'totalMedicines'  => Medicine::count(),
            'medSearch'       => $medSearch,
            'medSort'         => $medSort,
            'medFilter'       => $medFilter,
        ];
    }

    /**
     * Return only the inventori partial for AJAX update.
     */
    public function getInventoriPartial(Request $request)
    {
        $data = $this->getInventoriData($request);
        return view('admin.partials.inventori', $data);
    }

    /**
     * Get content data (articles) for dashboard.
     */
    private function getKontenData(Request $request)
    {
        $activeCategory = $request->query('category', '');
        $searchKonten   = $request->query('search_konten', '');
        $sortKonten     = $request->query('sort_konten', 'latest');

        $query = Article::query();

        // Sorting logic
        if ($sortKonten === 'oldest') {
            $query->oldest();
        } elseif ($sortKonten === 'title_asc') {
            $query->orderBy('title', 'asc');
        } elseif ($sortKonten === 'title_desc') {
            $query->orderBy('title', 'desc');
        } else {
            $query->latest();
        }

        if ($activeCategory && in_array($activeCategory, ['Artikel', 'Berita', 'Acara'])) {
            $query->where('category', $activeCategory);
        }

        if ($searchKonten) {
            $query->where('title', 'like', '%' . $searchKonten . '%');
        }

        $articles = $query->paginate(9)
            ->appends([
                'tab' => 'konten',
                'category' => $activeCategory,
                'search_konten' => $searchKonten,
                'sort_konten' => $sortKonten
            ]);

        $categoryCounts = Article::selectRaw('category, count(*) as total')
            ->groupBy('category')
            ->pluck('total', 'category');

        return [
            'articles'       => $articles,
            'categoryCounts' => $categoryCounts,
            'activeCategory' => $activeCategory,
            'searchKonten'   => $searchKonten,
            'sortKonten'     => $sortKonten,
        ];
    }

    /**
     * Return only the konten partial for AJAX update.
     */
    public function getKontenPartial(Request $request)
    {
        $data = $this->getKontenData($request);
        return view('admin.partials.konten', $data);
    }

    /**
     * Get lightweight stats for global polling (notifications).
     */
    public function getPollingStats()
    {
        return response()->json([
            'pendingReservasiCount' => Reservasi::where('status', 'Menunggu')->count(),
            'lowStockCount'         => Medicine::whereRaw('stock <= min_stock')->count(),
        ]);
    }

    /**
     * Format angka ke format rupiah ringkas.
     * Contoh: 1500000 → "1,5Jt", 45200000 → "45,2Jt", 500000 → "500Rb"
     */
    private function formatRupiah($amount)
    {
        if ($amount >= 1000000000) {
            return number_format($amount / 1000000000, 1, ',', '.') . 'M';
        } elseif ($amount >= 1000000) {
            return number_format($amount / 1000000, 1, ',', '.') . 'Jt';
        } elseif ($amount >= 1000) {
            return number_format($amount / 1000, 0, ',', '.') . 'Rb';
        }
        return number_format($amount, 0, ',', '.');
    }

    /**
     * Get financial data for the last 6 months.
     */
    private function getKeuanganData(Request $request)
    {
        $months = [];
        $pendapatanData = [];
        $pengeluaranData = [];
        $labaData = [];

        // Last 6 months
        for ($i = 5; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $months[] = $date->isoFormat('MMM');

            // 1. Pendapatan (Revenue)
            $pendapatan = ResepMedisItem::whereHas('resepMedis', function ($q) use ($date) {
                $q->whereMonth('tanggal_resep', $date->month)
                  ->whereYear('tanggal_resep', $date->year);
            })
            ->join('medicines', 'resep_medis_items.medicine_id', '=', 'medicines.id')
            ->selectRaw('SUM(resep_medis_items.jumlah * medicines.price) as total')
            ->value('total') ?? 0;

            // 2. Pengeluaran (Expenses)
            $pengeluaran = Pengeluaran::whereMonth('tanggal', $date->month)
                ->whereYear('tanggal', $date->year)
                ->sum('nominal');

            $pendapatanData[] = (float) $pendapatan;
            $pengeluaranData[] = (float) $pengeluaran;
            $labaData[] = (float) ($pendapatan - $pengeluaran);
        }

        $chartData = [
            'categories' => $months,
            'pendapatan' => $pendapatanData,
            'pengeluaran' => $pengeluaranData,
            'laba' => $labaData,
        ];

        // --- KPI STATS (Bulan Ini vs Bulan Lalu) ---
        $dateIni = now();
        $dateLalu = now()->subMonth();

        $revIni = ResepMedisItem::whereHas('resepMedis', function ($q) use ($dateIni) {
            $q->whereMonth('tanggal_resep', $dateIni->month)->whereYear('tanggal_resep', $dateIni->year);
        })->join('medicines', 'resep_medis_items.medicine_id', '=', 'medicines.id')->selectRaw('SUM(resep_medis_items.jumlah * medicines.price) as total')->value('total') ?? 0;

        $revLalu = ResepMedisItem::whereHas('resepMedis', function ($q) use ($dateLalu) {
            $q->whereMonth('tanggal_resep', $dateLalu->month)->whereYear('tanggal_resep', $dateLalu->year);
        })->join('medicines', 'resep_medis_items.medicine_id', '=', 'medicines.id')->selectRaw('SUM(resep_medis_items.jumlah * medicines.price) as total')->value('total') ?? 0;

        $expIni = Pengeluaran::whereMonth('tanggal', $dateIni->month)->whereYear('tanggal', $dateIni->year)->sum('nominal');
        $expLalu = Pengeluaran::whereMonth('tanggal', $dateLalu->month)->whereYear('tanggal', $dateLalu->year)->sum('nominal');

        $labaIni = $revIni - $expIni;
        $labaLalu = $revLalu - $expLalu;

        $pctRev = $revLalu > 0 ? (($revIni - $revLalu) / $revLalu) * 100 : ($revIni > 0 ? 100 : 0);
        $pctExp = $expLalu > 0 ? (($expIni - $expLalu) / $expLalu) * 100 : ($expIni > 0 ? 100 : 0);
        $pctLaba = $labaLalu != 0 ? (($labaIni - $labaLalu) / abs($labaLalu)) * 100 : ($labaIni > 0 ? 100 : 0);

        // --- Tambahan: Rata-rata Nilai Resep & Profit Margin ---
        $countResepIni = \App\Models\ResepMedis::whereMonth('tanggal_resep', $dateIni->month)->whereYear('tanggal_resep', $dateIni->year)->count();
        $countResepLalu = \App\Models\ResepMedis::whereMonth('tanggal_resep', $dateLalu->month)->whereYear('tanggal_resep', $dateLalu->year)->count();

        $avgRevIni = $countResepIni > 0 ? $revIni / $countResepIni : 0;
        $avgRevLalu = $countResepLalu > 0 ? $revLalu / $countResepLalu : 0;
        $pctAvgRev = $avgRevLalu > 0 ? (($avgRevIni - $avgRevLalu) / $avgRevLalu) * 100 : ($avgRevIni > 0 ? 100 : 0);

        $marginIni = $revIni > 0 ? ($labaIni / $revIni) * 100 : 0;
        $marginLalu = $revLalu > 0 ? ($labaLalu / $revLalu) * 100 : 0;
        $diffMargin = $marginIni - $marginLalu;

        $kpiStats = [
            'revIni' => $revIni,
            'revLalu' => $revLalu,
            'pctRev' => $pctRev,
            'expIni' => $expIni,
            'expLalu' => $expLalu,
            'pctExp' => $pctExp,
            'labaIni' => $labaIni,
            'labaLalu' => $labaLalu,
            'pctLaba' => $pctLaba,
            'avgRevIni' => $avgRevIni,
            'pctAvgRev' => $pctAvgRev,
            'marginIni' => $marginIni,
            'diffMargin' => $diffMargin,
        ];

        // --- DISTRIBUSI PENGELUARAN (Donut Chart) ---
        $kategoriDistribution = Pengeluaran::selectRaw('kategori, SUM(nominal) as total')
            ->groupBy('kategori')
            ->pluck('total', 'kategori')
            ->toArray();

        $categoriesList = ['Operasional', 'Gaji Pegawai', 'Pembelian Alat', 'Lainnya'];
        $donutChartData = [];
        foreach ($categoriesList as $cat) {
            $donutChartData[] = (float) ($kategoriDistribution[$cat] ?? 0);
        }

        // --- TOP 5 OBAT PENYUMBANG PENDAPATAN ---
        $topMedicines = ResepMedisItem::join('medicines', 'resep_medis_items.medicine_id', '=', 'medicines.id')
            ->selectRaw('medicines.name as name, SUM(resep_medis_items.jumlah) as total_qty, SUM(resep_medis_items.jumlah * medicines.price) as total_revenue')
            ->groupBy('medicines.name')
            ->orderBy('total_revenue', 'desc')
            ->limit(5)
            ->get();

        // --- Tambahan: Top 3 Dokter Kontributor Omzet ---
        $topDoctors = ResepMedisItem::join('resep_medis', 'resep_medis_items.resep_medis_id', '=', 'resep_medis.id')
            ->join('medicines', 'resep_medis_items.medicine_id', '=', 'medicines.id')
            ->selectRaw('resep_medis.dokter_pemeriksa as name, SUM(resep_medis_items.jumlah * medicines.price) as total_revenue, COUNT(DISTINCT resep_medis.id) as total_prescriptions')
            ->groupBy('resep_medis.dokter_pemeriksa')
            ->orderBy('total_revenue', 'desc')
            ->limit(3)
            ->get();

        // --- Tambahan: Top 3 Pengeluaran Terbesar (Expense Drivers) ---
        $topExpenses = Pengeluaran::orderBy('nominal', 'desc')
            ->limit(3)
            ->get();

        // --- Tambahan: Tabel Ikhtisar Tabular 6 Bulan ---
        $summaryTable = [];
        for ($i = 5; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $p = $pendapatanData[5 - $i] ?? 0;
            $e = $pengeluaranData[5 - $i] ?? 0;
            $l = $labaData[5 - $i] ?? 0;
            $m = $p > 0 ? ($l / $p) * 100 : 0;
            $summaryTable[] = [
                'month' => $date->isoFormat('MMMM YYYY'),
                'pendapatan' => $p,
                'pengeluaran' => $e,
                'laba' => $l,
                'margin' => $m,
                'status' => $l >= 0 ? 'Surplus' : 'Defisit'
            ];
        }

        // Fetch recent expenses
        $pengeluaranList = Pengeluaran::orderBy('tanggal', 'desc')->paginate(10, ['*'], 'page_keuangan')->appends(['tab' => 'keuangan']);

        return [
            'chartData' => json_encode($chartData),
            'pengeluaranList' => $pengeluaranList,
            'kpiStats' => $kpiStats,
            'donutChartData' => $donutChartData,
            'topMedicines' => $topMedicines,
            'topDoctors' => $topDoctors,
            'topExpenses' => $topExpenses,
            'summaryTable' => $summaryTable
        ];
    }
}
