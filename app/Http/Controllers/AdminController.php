<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Article;
use App\Models\Medicine;
use App\Models\RekamMedis;
use App\Models\Reservasi;
use App\Models\Doctor;

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
        $rmDate      = $request->query('rm_date', '');

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

        // ─── Dashboard Stats ──────────────────────────────────────
        $stats = [
            'total_pasien'         => $totalPasien,
            'reservasi_hari_ini'   => $reservasiHariIni,
            'stok_menipis'         => $lowStockCount,
            'pendapatan_bulan_ini' => '45,2M', // Ini masih dummy karena butuh tabel transaksi
        ];

        $activeTab = $request->query('tab', 'dashboard');

        return view('admin.index', compact(
            'articles', 'categoryCounts', 'activeCategory', 'searchKonten', 'sortKonten',
            'medicines', 'lowStockCount', 'expiredCount', 'nearExpiryCount', 'totalMedicines', 'medSearch', 'medSort', 'medFilter',
            'rekamMedis', 'rmStats', 'rmKategoriCounts', 'rmSearch', 'rmKategori', 'rmDate',
            'semuaReservasi', 'pendingReservasiCount', 'doctors',
            'reservasiHariIni', 'reservasiMendatang', 'reservasiDikonfirmasi',
            'resFilter', 'resStatus', 'resSearch',
            'stats', 'activeTab'
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

        $resQuery = Reservasi::query();

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
}
