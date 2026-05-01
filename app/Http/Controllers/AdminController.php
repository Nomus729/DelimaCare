<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Article;
use App\Models\Medicine;
use App\Models\RekamMedis;
use App\Models\Reservasi;

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

        $articles = $query->paginate(6)->withQueryString();

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
        $rekamMedis     = $rmQuery->paginate(8)->withQueryString();

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

        // ─── Reservasi (INI YANG DITAMBAHIN WOK) ──────────────────
        $semuaReservasi = Reservasi::latest()->get();

        // ─── Dashboard Stats ──────────────────────────────────────
        $stats = [
            'total_pasien'         => $rmStats['total'] + 342,
            'reservasi_hari_ini'   => 28,
            'stok_menipis'         => $lowStockCount,
            'pendapatan_bulan_ini' => '45,2M',
        ];

        $activeTab = $request->query('tab', 'dashboard');

        // Pastikan 'semuaReservasi' masuk ke dalam compact di bawah ini!
        return view('admin.index', compact(
            'articles', 'categoryCounts', 'activeCategory',
            'medicines', 'lowStockCount', 'totalMedicines', 'medSearch', 'medSort', 'medFilter',
            'rekamMedis', 'rmStats', 'rmKategoriCounts', 'rmSearch', 'rmKategori',
            'semuaReservasi', // <-- INI DIA OLEH-OLEHNYA
            'stats', 'activeTab'
        ));
    }
}
