<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Reservasi;
use App\Models\RekamMedis;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function getStats(Request $request)
    {
        $bulan = $request->bulan ?? date('m');
        $tahun = $request->tahun ?? date('Y');

        // 1. Total Pasien (Role Pasien)
        $totalPasien = User::where('role', 'pasien')->count();

        // Pasien Baru Bulan Ini
        $pasienBaru = User::where('role', 'pasien')
            ->whereMonth('created_at', $bulan)
            ->whereYear('created_at', $tahun)
            ->count();

        // 2. Data Kehamilan (Misal dari Rekam Medis dengan diagnosa tertentu)
        $totalHamil = RekamMedis::where('kategori', 'Kehamilan')->count();

        // 3. Data KB
        $totalKB = RekamMedis::where('kategori', 'KB')->count();

        // 4. Total Kunjungan (Reservasi yang sudah selesai)
        $totalKunjungan = Reservasi::whereMonth('tanggal_kunjungan', $bulan)
            ->whereYear('tanggal_kunjungan', $tahun)
            ->count();

        // 5. Data Tabel Bulanan (Grouping per bulan)
        $tableData = Reservasi::select(
                DB::raw('strftime("%m", tanggal_kunjungan) as bulan'),
                DB::raw('count(*) as total'),
                DB::raw('SUM(CASE WHEN kategori = "Kehamilan" THEN 1 ELSE 0 END) as hamil'),
                DB::raw('SUM(CASE WHEN kategori = "KB" THEN 1 ELSE 0 END) as kb')
            )
            ->whereYear('tanggal_kunjungan', $tahun)
            ->groupBy('bulan')
            ->get();

        return response()->json([
            'totalPasien' => $totalPasien,
            'pasienBaru' => $pasienBaru,
            'totalHamil' => $totalHamil,
            'totalKB' => $totalKB,
            'totalKunjungan' => $totalKunjungan,
            'table' => $tableData
        ]);
    }
}
