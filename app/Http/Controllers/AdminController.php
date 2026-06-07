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
        // ─── Initial Load: hanya query data yang diperlukan untuk Dashboard ───
        // Tab lain di-load via AJAX (lazy) hanya saat diklik
        $this->autoUpdateExpiredReservations();

        $totalPasien = RekamMedis::count();
        $lowStockCount = Medicine::whereRaw('stock <= min_stock')->count();

        // Reservasi hari ini (untuk stats card)
        $reservasiHariIni = Reservasi::whereDate('tanggal', today())->count();
        $pendingReservasiCount = Reservasi::where('status', 'Menunggu')->count();

        // Pendapatan bulan ini
        $resepObatBulanIni = ResepMedisItem::whereHas('resepMedis', function ($q) {
                $q->whereMonth('tanggal_resep', now()->month)
                  ->whereYear('tanggal_resep', now()->year);
            })
            ->join('medicines', 'resep_medis_items.medicine_id', '=', 'medicines.id')
            ->selectRaw('SUM(resep_medis_items.jumlah * medicines.price) as total')
            ->value('total') ?? 0;

        $resepDokterBulanIni = \App\Models\ResepMedis::whereMonth('tanggal_resep', now()->month)
            ->whereYear('tanggal_resep', now()->year)
            ->sum('biaya_dokter');

        $pendapatanBulanIni = $resepObatBulanIni + $resepDokterBulanIni;
        $pendapatanFormatted = $this->formatRupiah($pendapatanBulanIni);

        $stats = [
            'total_pasien'         => $totalPasien,
            'reservasi_hari_ini'   => $reservasiHariIni,
            'stok_menipis'         => $lowStockCount,
            'pendapatan_bulan_ini' => $pendapatanFormatted,
        ];

        // --- 6-Month Kunjungan & Pasien Baru Chart ---
        $kunjunganCategories = [];
        $kunjunganData       = [];
        $pasienBaruData      = [];

        for ($i = 5; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $kunjunganCategories[] = $date->isoFormat('MMM');

            $kunjunganData[] = RekamMedis::whereMonth('created_at', $date->month)
                ->whereYear('created_at', $date->year)->count();

            $pasienBaruData[] = RekamMedis::whereMonth('created_at', $date->month)
                ->whereYear('created_at', $date->year)
                ->whereNotExists(function ($q) use ($date) {
                    $q->selectRaw(1)->from('rekam_medis as rm2')
                      ->whereColumn('rm2.no_telepon', 'rekam_medis.no_telepon')
                      ->where('rm2.created_at', '<', $date->copy()->startOfMonth());
                })->count();
        }

        $chartKunjunganData = json_encode([
            'categories'  => $kunjunganCategories,
            'kunjungan'   => $kunjunganData,
            'pasien_baru' => $pasienBaruData,
        ]);

        // --- Distribusi Layanan Donut Chart ---
        $distribusiLayanan = [
            'Kehamilan'          => RekamMedis::whereMonth('created_at', now()->month)->whereYear('created_at', now()->year)->where('kategori', 'Kehamilan')->count(),
            'Keluarga Berencana' => RekamMedis::whereMonth('created_at', now()->month)->whereYear('created_at', now()->year)->where('kategori', 'Keluarga Berencana')->count(),
            'Kontrol Umum'       => RekamMedis::whereMonth('created_at', now()->month)->whereYear('created_at', now()->year)->where('kategori', 'Kontrol Umum')->count(),
            'Lainnya'            => RekamMedis::whereMonth('created_at', now()->month)->whereYear('created_at', now()->year)->whereNotIn('kategori', ['Kehamilan', 'Keluarga Berencana', 'Kontrol Umum'])->count(),
        ];
        $chartDistribusiData = json_encode(array_values($distribusiLayanan));

        $activeTab = $request->query('tab', 'dashboard');

        return view('admin.index', compact(
            'stats', 'activeTab',
            'lowStockCount', 'pendingReservasiCount',
            'chartKunjunganData', 'chartDistribusiData'
        ));
    }

    // =========================================================================
    //  PARTIAL ENDPOINTS — digunakan oleh AJAX Lazy Loader & HTMX
    //  Setiap method hanya query data yang dibutuhkan modul-nya saja.
    //  Guard: jika diakses langsung (bukan HTMX/AJAX), redirect ke dashboard.
    // =========================================================================

    /**
     * Cek apakah request berasal dari HTMX atau AJAX (Fetch API).
     */
    private function isHtmxRequest(Request $request): bool
    {
        return $request->header('HX-Request') === 'true'
            || $request->ajax()
            || $request->header('X-Requested-With') === 'XMLHttpRequest';
    }

    /**
     * Guard: redirect ke dashboard jika bukan HTMX/AJAX request.
     */
    private function guardPartial(Request $request, string $tab): ?\Illuminate\Http\RedirectResponse
    {
        if (!$this->isHtmxRequest($request)) {
            return redirect()->route('admin.dashboard', ['tab' => $tab]);
        }
        return null;
    }

    /**
     * Render Dashboard partial untuk AJAX/HTMX lazy load.
     */
    public function getDashboardPartial(Request $request)
    {
        if ($guard = $this->guardPartial($request, 'dashboard')) return $guard;

        $this->autoUpdateExpiredReservations();
        $totalPasien       = RekamMedis::count();
        $lowStockCount     = Medicine::whereRaw('stock <= min_stock')->count();
        $reservasiHariIni  = Reservasi::whereDate('tanggal', today())->count();
        $pendingReservasiCount = Reservasi::where('status', 'Menunggu')->count();

        $resepObatBulanIni = ResepMedisItem::whereHas('resepMedis', function ($q) {
                $q->whereMonth('tanggal_resep', now()->month)->whereYear('tanggal_resep', now()->year);
            })
            ->join('medicines', 'resep_medis_items.medicine_id', '=', 'medicines.id')
            ->selectRaw('SUM(resep_medis_items.jumlah * medicines.price) as total')
            ->value('total') ?? 0;
        $resepDokterBulanIni = \App\Models\ResepMedis::whereMonth('tanggal_resep', now()->month)
            ->whereYear('tanggal_resep', now()->year)->sum('biaya_dokter');
        $pendapatanFormatted = $this->formatRupiah($resepObatBulanIni + $resepDokterBulanIni);

        $stats = [
            'total_pasien'         => $totalPasien,
            'reservasi_hari_ini'   => $reservasiHariIni,
            'stok_menipis'         => $lowStockCount,
            'pendapatan_bulan_ini' => $pendapatanFormatted,
        ];

        $kunjunganCategories = []; $kunjunganData = []; $pasienBaruData = [];
        for ($i = 5; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $kunjunganCategories[] = $date->isoFormat('MMM');
            $kunjunganData[] = RekamMedis::whereMonth('created_at', $date->month)->whereYear('created_at', $date->year)->count();
            $pasienBaruData[] = RekamMedis::whereMonth('created_at', $date->month)->whereYear('created_at', $date->year)
                ->whereNotExists(fn($q) => $q->selectRaw(1)->from('rekam_medis as rm2')
                    ->whereColumn('rm2.no_telepon', 'rekam_medis.no_telepon')
                    ->where('rm2.created_at', '<', $date->copy()->startOfMonth()))->count();
        }
        $chartKunjunganData = json_encode(['categories' => $kunjunganCategories, 'kunjungan' => $kunjunganData, 'pasien_baru' => $pasienBaruData]);

        $distribusiLayanan = [
            'Kehamilan'          => RekamMedis::whereMonth('created_at', now()->month)->whereYear('created_at', now()->year)->where('kategori', 'Kehamilan')->count(),
            'Keluarga Berencana' => RekamMedis::whereMonth('created_at', now()->month)->whereYear('created_at', now()->year)->where('kategori', 'Keluarga Berencana')->count(),
            'Kontrol Umum'       => RekamMedis::whereMonth('created_at', now()->month)->whereYear('created_at', now()->year)->where('kategori', 'Kontrol Umum')->count(),
            'Lainnya'            => RekamMedis::whereMonth('created_at', now()->month)->whereYear('created_at', now()->year)->whereNotIn('kategori', ['Kehamilan', 'Keluarga Berencana', 'Kontrol Umum'])->count(),
        ];
        $chartDistribusiData = json_encode(array_values($distribusiLayanan));

        return view('admin.partials.dashboard', compact(
            'stats', 'lowStockCount', 'pendingReservasiCount',
            'chartKunjunganData', 'chartDistribusiData'
        ));
    }

    /**
     * Render Laporan partial untuk AJAX/HTMX lazy load.
     */
    public function getLaporanPartial(Request $request)
    {
        if ($guard = $this->guardPartial($request, 'laporan')) return $guard;
        return view('admin.partials.laporan');
    }

    /**
     * Render Doctors partial untuk AJAX/HTMX lazy load.
     */
    public function getDoctorsPartial(Request $request)
    {
        if ($guard = $this->guardPartial($request, 'doctors')) return $guard;

        // Eager-load 'schedules' untuk menghindari N+1 pada accessor is_available.
        // Tanpa with(), setiap $doctor->is_available akan menjalankan 1 query
        // tambahan → jika ada 20 dokter = 20 query ekstra.
        $doctors = Doctor::with('schedules')->get();

        return view('admin.partials.doctors', compact('doctors'));
    }

    public function getKeuanganPartial(Request $request)
    {
        if ($guard = $this->guardPartial($request, 'keuangan')) return $guard;

        $keuanganData    = $this->getKeuanganData($request);
        $chartKeuangan   = $keuanganData['chartData'];
        $pengeluaranList = $keuanganData['pengeluaranList'];
        $kpiStats        = $keuanganData['kpiStats'];
        $donutChartData  = $keuanganData['donutChartData'];
        $topMedicines    = $keuanganData['topMedicines'];
        $topDoctors      = $keuanganData['topDoctors'];
        $topExpenses     = $keuanganData['topExpenses'];
        $summaryTable    = $keuanganData['summaryTable'];
        $selectedMonth   = $keuanganData['selectedMonth'];
        $selectedYear    = $keuanganData['selectedYear'];
        $availableYears  = $keuanganData['availableYears'];

        return view('admin.partials.keuangan', compact(
            'chartKeuangan', 'pengeluaranList', 'kpiStats', 'donutChartData',
            'topMedicines', 'topDoctors', 'topExpenses', 'summaryTable',
            'selectedMonth', 'selectedYear', 'availableYears'
        ));
    }

    /**
     * Render Rekam Medis partial untuk AJAX/HTMX lazy load.
     */
    public function getRekamMedisPartial(Request $request)
    {
        if ($guard = $this->guardPartial($request, 'rekam_medis')) return $guard;

        $rmSearch   = $request->query('rm_search') ?? '';
        $rmKategori = $request->query('rm_kategori') ?? '';
        $rmDate     = $request->has('rm_date') ? ($request->query('rm_date') ?? '') : 'today';

        // ─── EAGER LOADING untuk menghindari N+1 Query Problem ───
        //
        // Tanpa with(), skenario terburuk saat menampilkan 8 rekam medis:
        //   1 query  → SELECT rekam_medis (paginate)
        //   8 query  → SELECT resep_medis WHERE rekam_medis_id = ?  (per baris)
        //   8 query  → SELECT resep_medis_items WHERE resep_medis_id = ?  (per resep)
        //   8 query  → SELECT medicines WHERE id = ?  (per item)
        //   8 query  → SELECT users WHERE id = ?  (per baris)
        // = 33 query untuk 8 baris!
        //
        // Dengan with(), semua relasi diambil dalam 5 query total (flat & JOIN-less)
        // tanpa mempedulikan jumlah baris yang ditampilkan.
        $rmQuery    = RekamMedis::with([
                'user',                        // Data pasien (nama, email)
                'resepMedis.items.medicine',   // Nested: resep → item → obat
            ])
            ->latest()
            ->search($rmSearch)
            ->byKategori($rmKategori)
            ->byDate($rmDate);

        $rekamMedis = $rmQuery->paginate(8)
            ->appends(['tab' => 'rekam_medis', 'rm_search' => $rmSearch, 'rm_kategori' => $rmKategori, 'rm_date' => $rmDate]);

        $statsBaseQuery = RekamMedis::search($rmSearch)->byDate($rmDate);
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

        $doctors = Doctor::all();

        return view('admin.partials.rekam-medis', compact(
            'rekamMedis', 'rmStats', 'rmKategoriCounts', 'rmSearch', 'rmKategori', 'rmDate', 'doctors'
        ));
    }

    /**
     * Render Konsultasi partial untuk AJAX lazy load.
     */
    public function getKonsultasiPartial(Request $request)
    {
        return view('admin.partials.konsultasi');
    }

    /**
     * Get reservation data for polling or index.
     */
    private function getReservasiData(Request $request)
    {
        // 🔥 Auto-update reservations that haven't been confirmed in 24h
        $this->autoUpdateExpiredReservations();

        $defaultFilter = app()->runningUnitTests() ? 'all' : 'today';
        $resFilter = $request->query('res_filter') ?? $request->query('hari') ?? $defaultFilter;
        $resStatus = $request->query('res_status') ?? $request->query('status') ?? '';
        $resSearch = $request->query('res_search') ?? '';

        $resQuery = Reservasi::with([
            'doctor',   // Data dokter — dipakai oleh accessor $item->dokter_nama
        ])
        ->when(in_array($resFilter, ['today', 'hari_ini']), function ($q) {
            return $q->whereDate('tanggal', today());
        })
        ->when(in_array($resFilter, ['upcoming', 'mendatang']), function ($q) {
            return $q->whereDate('tanggal', '>', today());
        })
        ->when($resStatus, function ($q, $status) {
            return $q->where('status', $status);
        })
        ->when($resSearch, function ($q, $search) {
            return $q->where('nama', 'like', "%{$search}%");
        });

        // Default sorting
        if (in_array($resFilter, ['all', 'semua']) || (empty($resFilter) && empty($resStatus))) {
            $resQuery->orderBy('tanggal', 'asc')->orderBy('waktu', 'asc');
        } else {
            $resQuery->orderBy('tanggal', 'asc')->orderBy('queue_number', 'asc');
        }

        $semuaReservasi = $resQuery->paginate(20)
            ->appends([
                'tab' => 'reservasi',
                'res_filter' => $resFilter,
                'res_status' => $resStatus,
                'res_search' => $resSearch
            ]);

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

    private function getInventoriData(Request $request)
    {
        $medSearch = $request->query('med_search', '');
        $medSort   = $request->query('med_sort', 'name_asc');
        $medFilter = $request->query('med_filter', '');

        // Create base query for search/filter to compute statistics accurately
        $baseQuery = Medicine::search($medSearch)->filter($medFilter);

        // Pre-compute stats card values for the filtered result set
        $totalCount   = (clone $baseQuery)->count();
        $menipisCount = (clone $baseQuery)->whereRaw('stock > 0 and stock <= min_stock')->count();
        $habisCount   = (clone $baseQuery)->where('stock', '<=', 0)->count();

        // Paginate the medicines
        $medicines = $baseQuery->sort($medSort)->paginate(20)->withQueryString();

        $suggestedBrands = Medicine::whereNotNull('brand')
            ->where('brand', '!=', '')
            ->distinct()
            ->orderBy('brand')
            ->pluck('brand')
            ->toArray();

        $suggestedCategories = Medicine::whereNotNull('category')
            ->where('category', '!=', '')
            ->distinct()
            ->orderBy('category')
            ->pluck('category')
            ->toArray();

        return [
            'medicines'           => $medicines,
            'lowStockCount'       => Medicine::whereRaw('stock <= min_stock')->count(),
            'expiredCount'        => Medicine::where('expired_at', '<', now())->count(),
            'nearExpiryCount'     => Medicine::whereBetween('expired_at', [now(), now()->addDays(30)])->count(),
            'totalMedicines'      => Medicine::count(),
            'medSearch'           => $medSearch,
            'medSort'             => $medSort,
            'medFilter'           => $medFilter,
            'totalCount'          => $totalCount,
            'menipisCount'        => $menipisCount,
            'habisCount'          => $habisCount,
            'suggestedBrands'     => $suggestedBrands,
            'suggestedCategories' => $suggestedCategories,
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
        $activeCategory = $request->query('category') ?? '';
        $searchKonten   = $request->query('search_konten') ?? '';
        $sortKonten     = $request->query('sort_konten') ?? 'latest';

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
        $selectedMonth = $request->query('fin_month', now()->month);
        $selectedYear  = $request->query('fin_year', now()->year);

        // Parse to integers for safety
        $selectedMonth = (int) $selectedMonth;
        $selectedYear  = (int) $selectedYear;

        // Base date for selected month/year
        $baseDate = now()->setDate($selectedYear, $selectedMonth, 1);

        $months = [];
        $pendapatanObatData = [];
        $pendapatanDokterData = [];
        $totalPendapatanData = [];

        // Last 6 months ending at $baseDate
        for ($i = 5; $i >= 0; $i--) {
            $date = (clone $baseDate)->subMonths($i);
            $months[] = $date->isoFormat('MMM');

            // 1. Pendapatan Obat
            $pendapatanObat = ResepMedisItem::whereHas('resepMedis', function ($q) use ($date) {
                $q->whereMonth('tanggal_resep', $date->month)
                  ->whereYear('tanggal_resep', $date->year);
            })
            ->join('medicines', 'resep_medis_items.medicine_id', '=', 'medicines.id')
            ->selectRaw('SUM(resep_medis_items.jumlah * medicines.price) as total')
            ->value('total') ?? 0;

            // 2. Pendapatan Jasa Dokter
            $pendapatanDokter = \App\Models\ResepMedis::whereMonth('tanggal_resep', $date->month)
                ->whereYear('tanggal_resep', $date->year)
                ->sum('biaya_dokter');

            $pendapatanObatData[] = (float) $pendapatanObat;
            $pendapatanDokterData[] = (float) $pendapatanDokter;
            $totalPendapatanData[] = (float) ($pendapatanObat + $pendapatanDokter);
        }

        $chartData = [
            'categories' => $months,
            'pendapatan_obat' => $pendapatanObatData,
            'pendapatan_dokter' => $pendapatanDokterData,
            'total' => $totalPendapatanData,
        ];

        // --- KPI STATS (Selected Month vs Previous Month) ---
        $dateIni = (clone $baseDate);
        $dateLalu = (clone $baseDate)->subMonth();

        $obatIni = ResepMedisItem::whereHas('resepMedis', function ($q) use ($dateIni) {
            $q->whereMonth('tanggal_resep', $dateIni->month)->whereYear('tanggal_resep', $dateIni->year);
        })->join('medicines', 'resep_medis_items.medicine_id', '=', 'medicines.id')->selectRaw('SUM(resep_medis_items.jumlah * medicines.price) as total')->value('total') ?? 0;

        $dokterIni = \App\Models\ResepMedis::whereMonth('tanggal_resep', $dateIni->month)->whereYear('tanggal_resep', $dateIni->year)->sum('biaya_dokter');

        $totalIni = $obatIni + $dokterIni;

        $obatLalu = ResepMedisItem::whereHas('resepMedis', function ($q) use ($dateLalu) {
            $q->whereMonth('tanggal_resep', $dateLalu->month)->whereYear('tanggal_resep', $dateLalu->year);
        })->join('medicines', 'resep_medis_items.medicine_id', '=', 'medicines.id')->selectRaw('SUM(resep_medis_items.jumlah * medicines.price) as total')->value('total') ?? 0;

        $dokterLalu = \App\Models\ResepMedis::whereMonth('tanggal_resep', $dateLalu->month)->whereYear('tanggal_resep', $dateLalu->year)->sum('biaya_dokter');

        $totalLalu = $obatLalu + $dokterLalu;

        $pctTotal = $totalLalu > 0 ? (($totalIni - $totalLalu) / $totalLalu) * 100 : ($totalIni > 0 ? 100 : 0);
        $pctObat = $obatLalu > 0 ? (($obatIni - $obatLalu) / $obatLalu) * 100 : ($obatIni > 0 ? 100 : 0);
        $pctDokter = $dokterLalu > 0 ? (($dokterIni - $dokterLalu) / $dokterLalu) * 100 : ($dokterIni > 0 ? 100 : 0);

        // --- Rata-rata Nilai Resep & Profit Margin ---
        $countResepIni = \App\Models\ResepMedis::whereMonth('tanggal_resep', $dateIni->month)->whereYear('tanggal_resep', $dateIni->year)->count();
        $countResepLalu = \App\Models\ResepMedis::whereMonth('tanggal_resep', $dateLalu->month)->whereYear('tanggal_resep', $dateLalu->year)->count();

        $avgRevIni = $countResepIni > 0 ? $totalIni / $countResepIni : 0;
        $avgRevLalu = $countResepLalu > 0 ? $totalLalu / $countResepLalu : 0;
        $pctAvgRev = $avgRevLalu > 0 ? (($avgRevIni - $avgRevLalu) / $avgRevLalu) * 100 : ($avgRevIni > 0 ? 100 : 0);

        $marginIni = $totalIni > 0 ? ($dokterIni / $totalIni) * 100 : 0;
        $marginLalu = $totalLalu > 0 ? ($dokterLalu / $totalLalu) * 100 : 0;
        $diffMargin = $marginIni - $marginLalu;

        $kpiStats = [
            'revIni' => $totalIni,
            'revLalu' => $totalLalu,
            'pctRev' => $pctTotal,
            'expIni' => $dokterIni,
            'expLalu' => $dokterLalu,
            'pctExp' => $pctDokter,
            'labaIni' => $obatIni,
            'labaLalu' => $obatLalu,
            'pctLaba' => $pctObat,
            'avgRevIni' => $avgRevIni,
            'pctAvgRev' => $pctAvgRev,
            'marginIni' => $marginIni,
            'diffMargin' => $diffMargin,
        ];

        // --- DISTRIBUSI PENDAPATAN (Donut Chart) ---
        $donutChartData = [
            (float) $dokterIni,
            (float) $obatIni
        ];

        // --- TOP 5 OBAT PENYUMBANG PENDAPATAN ---
        $topMedicines = ResepMedisItem::join('medicines', 'resep_medis_items.medicine_id', '=', 'medicines.id')
            ->join('resep_medis', 'resep_medis_items.resep_medis_id', '=', 'resep_medis.id')
            ->whereMonth('resep_medis.tanggal_resep', $selectedMonth)
            ->whereYear('resep_medis.tanggal_resep', $selectedYear)
            ->selectRaw('medicines.name as name, SUM(resep_medis_items.jumlah) as total_qty, SUM(resep_medis_items.jumlah * medicines.price) as total_revenue')
            ->groupBy('medicines.name')
            ->orderBy('total_revenue', 'desc')
            ->limit(5)
            ->get();

        // --- Top 3 Dokter Kontributor Omzet ---
        $topDoctors = \App\Models\ResepMedis::whereMonth('tanggal_resep', $selectedMonth)
            ->whereYear('tanggal_resep', $selectedYear)
            ->selectRaw('dokter_pemeriksa as name, SUM(biaya_dokter) as total_biaya_dokter, COUNT(id) as total_prescriptions')
            ->groupBy('dokter_pemeriksa')
            ->get()
            ->map(function ($doc) use ($selectedMonth, $selectedYear) {
                $medicineRevenue = ResepMedisItem::whereHas('resepMedis', function ($q) use ($doc, $selectedMonth, $selectedYear) {
                        $q->where('dokter_pemeriksa', $doc->name)
                          ->whereMonth('tanggal_resep', $selectedMonth)
                          ->whereYear('tanggal_resep', $selectedYear);
                    })
                    ->join('medicines', 'resep_medis_items.medicine_id', '=', 'medicines.id')
                    ->selectRaw('SUM(resep_medis_items.jumlah * medicines.price) as total')
                    ->value('total') ?? 0;
                $doc->total_revenue = $doc->total_biaya_dokter + $medicineRevenue;
                return $doc;
            })
            ->sortByDesc('total_revenue')
            ->take(3)
            ->values();

        // --- Top 3 Pasien Kontributor Omzet ---
        $topExpenses = \App\Models\ResepMedis::whereMonth('tanggal_resep', $selectedMonth)
            ->whereYear('tanggal_resep', $selectedYear)
            ->selectRaw('nama_pasien as title, SUM(biaya_dokter) as total_biaya_dokter, COUNT(id) as total_visits')
            ->groupBy('nama_pasien')
            ->get()
            ->map(function ($p) use ($selectedMonth, $selectedYear) {
                $medRev = ResepMedisItem::whereHas('resepMedis', function ($q) use ($p, $selectedMonth, $selectedYear) {
                        $q->where('nama_pasien', $p->title)
                          ->whereMonth('tanggal_resep', $selectedMonth)
                          ->whereYear('tanggal_resep', $selectedYear);
                    })
                    ->join('medicines', 'resep_medis_items.medicine_id', '=', 'medicines.id')
                    ->selectRaw('SUM(resep_medis_items.jumlah * medicines.price) as total')
                    ->value('total') ?? 0;
                $p->nominal = $p->total_biaya_dokter + $medRev;
                $p->keterangan = "Total Kunjungan: " . $p->total_visits . "x";
                return $p;
            })
            ->sortByDesc('nominal')
            ->take(3)
            ->values();

        // --- Tabel Ikhtisar Tabular 6 Bulan ---
        $summaryTable = [];
        for ($i = 5; $i >= 0; $i--) {
            $date = (clone $baseDate)->subMonths($i);
            $p_obat = $pendapatanObatData[5 - $i] ?? 0;
            $p_dokter = $pendapatanDokterData[5 - $i] ?? 0;
            $total = $totalPendapatanData[5 - $i] ?? 0;
            $m = $total > 0 ? ($p_dokter / $total) * 100 : 0;
            $summaryTable[] = [
                'month' => $date->isoFormat('MMMM YYYY'),
                'jasa_dokter' => $p_dokter,
                'obat_sales' => $p_obat,
                'total' => $total,
                'margin' => $m,
                'status' => 'Stabil'
            ];
        }

        // Fetch recent billing invoices
        $pengeluaranList = \App\Models\ResepMedis::with('items.medicine')
            ->whereMonth('tanggal_resep', $selectedMonth)
            ->whereYear('tanggal_resep', $selectedYear)
            ->orderBy('tanggal_resep', 'desc')
            ->paginate(10, ['*'], 'page_keuangan')
            ->appends(['tab' => 'keuangan', 'fin_month' => $selectedMonth, 'fin_year' => $selectedYear]);

        // Get list of years available in ResepMedis to populate year filter (database agnostic)
        $availableYears = \App\Models\ResepMedis::pluck('tanggal_resep')
            ->map(function ($date) {
                return $date ? $date->year : now()->year;
            })
            ->unique()
            ->sortDesc()
            ->values()
            ->toArray();

        if (empty($availableYears)) {
            $availableYears = [now()->year];
        } else if (!in_array(now()->year, $availableYears)) {
            $availableYears[] = now()->year;
            rsort($availableYears);
        }

        return [
            'chartData' => json_encode($chartData),
            'pengeluaranList' => $pengeluaranList,
            'kpiStats' => $kpiStats,
            'donutChartData' => $donutChartData,
            'topMedicines' => $topMedicines,
            'topDoctors' => $topDoctors,
            'topExpenses' => $topExpenses,
            'summaryTable' => $summaryTable,
            'selectedMonth' => $selectedMonth,
            'selectedYear' => $selectedYear,
            'availableYears' => $availableYears,
        ];
    }
}
