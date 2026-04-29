<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function index()
    {
        $articles = \App\Models\Article::latest()->get();
        $medicines = \App\Models\Medicine::orderBy('name')->get();
        
        // Dynamic counts for sidebar and dashboard
        $lowStockCount = \App\Models\Medicine::whereRaw('stock <= min_stock')->count();
        $totalMedicines = $medicines->count();
        
        // Mock data for other parts (can be made real later)
        $stats = [
            'total_pasien' => 342, // Need Patient model for this
            'reservasi_hari_ini' => 28, // Need Reservation model for this
            'stok_menipis' => $lowStockCount,
            'pendapatan_bulan_ini' => '45,2M'
        ];

        return view('admin.index', compact('articles', 'medicines', 'lowStockCount', 'totalMedicines', 'stats'));
    }
}
