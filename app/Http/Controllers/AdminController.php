<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Article;
use App\Models\Medicine;

class AdminController extends Controller
{
    public function index(Request $request)
    {
        // Category filter (Artikel | Berita | Acara | '' = semua)
        $activeCategory = $request->query('category', '');

        $query = Article::latest();
        if ($activeCategory && in_array($activeCategory, ['Artikel', 'Berita', 'Acara'])) {
            $query->where('category', $activeCategory);
        }

        // 6 articles per page, preserve full query string (tab + category)
        $articles = $query->paginate(6)->withQueryString();

        // Per-category counts for badge display on filter tabs
        $categoryCounts = Article::selectRaw('category, count(*) as total')
            ->groupBy('category')
            ->pluck('total', 'category');

        $medicines     = Medicine::orderBy('name')->get();
        $lowStockCount = Medicine::whereRaw('stock <= min_stock')->count();
        $totalMedicines = $medicines->count();

        $stats = [
            'total_pasien'         => 342,
            'reservasi_hari_ini'   => 28,
            'stok_menipis'         => $lowStockCount,
            'pendapatan_bulan_ini' => '45,2M',
        ];

        $activeTab = $request->query('tab', 'dashboard');

        return view('admin.index', compact(
            'articles', 'categoryCounts', 'activeCategory',
            'medicines', 'lowStockCount', 'totalMedicines',
            'stats', 'activeTab'
        ));
    }
}
