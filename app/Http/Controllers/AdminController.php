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
        $activeCategory = $request->query('category', '');

        $query = Article::latest();
        if ($activeCategory && in_array($activeCategory, ['Artikel', 'Berita', 'Acara'])) {
            $query->where('category', $activeCategory);
        }

        $articles = $query->paginate(6)
            ->appends(['tab' => 'konten', 'category' => $activeCategory]);

        $categoryCounts = Article::selectRaw('category, count(*) as total')
            ->groupBy('category')
            ->pluck('total', 'category');

        // ─── Inventori ────────────────────────────────────────────
        $medSearch = $request->query('med_search', '');
        $medSort   = $request->query('med_sort', 'name_asc');
        $medFilter = $request->query('med_filter', '');

        $medicines = Medicine::search($medSearch)
            ->filter($medFilter)
            ->sort($medSort)
            ->get();

        $lowStockCount  = Medicine::whereRaw('stock <= min_stock')->count();
        $totalMedicines = Medicine::count();

        // ─── Rekam Medis ──────────────────────────────────────────
        $rmSearch    = $request->query('rm_search', '');
        $rmKategori  = $request->query('rm_kategori', '');

        $rmQuery = RekamMedis::latest()
            ->search($rmSearch)
            ->byKategori($rmKategori);

        $rekamMedisAll  = RekamMedis::get();
        $rekamMedis = $rmQuery->paginate(8)
            ->appends(['tab' => 'rekam_medis', 'rm_search' => $rmSearch, 'rm_kategori' => $rmKategori]);

        $rmStats = [
            'total'       => $rekamMedisAll->count(),
            'kehamilan'   => $rekamMedisAll->where('kategori', 'Kehamilan')->count(),
            'kb'          => $rekamMedisAll->where('kategori', 'Keluarga Berencana')->count(),
            'risiko_tinggi' => $rekamMedisAll->where('status_risiko', 'Tinggi')->count(),
        ];

        $rmKategoriCounts = [
            ''                   => $rekamMedisAll->count(),
            'Kehamilan'          => $rekamMedisAll->where('kategori', 'Kehamilan')->count(),
            'Keluarga Berencana' => $rekamMedisAll->where('kategori', 'Keluarga Berencana')->count(),
            'Kontrol Umum'       => $rekamMedisAll->where('kategori', 'Kontrol Umum')->count(),
            'Konsultasi'         => $rekamMedisAll->where('kategori', 'Konsultasi')->count(),
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
            'articles', 'categoryCounts', 'activeCategory',
            'medicines', 'lowStockCount', 'totalMedicines', 'medSearch', 'medSort', 'medFilter',
            'rekamMedis', 'rmStats', 'rmKategoriCounts', 'rmSearch', 'rmKategori',
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
