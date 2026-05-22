<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Reservasi;
use App\Models\RekamMedis;
use App\Models\ResepMedis;
use App\Models\ResepMedisItem;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function getStats(Request $request)
    {
        // 1. Ambil Parameter dari request
        $bulan = (int) $request->query('month', date('m'));
        $tahun = (int) $request->query('year', date('Y'));
        $type = $request->query('type', 'bulanan');

        // 2. Data Statistik (Card Atas)
        $totalPasien = User::where('role', 'pasien')->count();
        
        // Pasien Baru terdaftar di bulan terpilih
        $pasienBaru = User::where('role', 'pasien')
            ->whereMonth('created_at', $bulan)
            ->whereYear('created_at', $tahun)
            ->count();

        // Data Kehamilan & KB aktif dari rekam_medis
        $totalHamil = RekamMedis::where('kategori', 'Kehamilan')->count();
        $totalKB = RekamMedis::where('kategori', 'Keluarga Berencana')->count();

        // Total Kunjungan Selesai/Datang di bulan terpilih
        $totalKunjungan = Reservasi::whereIn('status', ['Selesai', 'Datang', 'Hadir'])
            ->whereMonth('tanggal', $bulan)
            ->whereYear('tanggal', $tahun)
            ->count();

        // 3. Ambil data Reservasi tahun berjalan
        $reservasiTahunan = Reservasi::whereIn('status', ['Selesai', 'Datang', 'Hadir'])
            ->whereYear('tanggal', $tahun)
            ->get();

        $bulanIndo = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
        ];

        $tableData = [];
        $chartLabels = [];
        $chartData = [];

        // Loop 12 bulan untuk menyusun tren tahunan & data tabel
        for ($i = 1; $i <= 12; $i++) {
            $dataBulanIni = $reservasiTahunan->filter(function ($item) use ($i) {
                return Carbon::parse($item->tanggal)->month == $i;
            });

            $hamilCount = $dataBulanIni->filter(function($item) {
                return $item->layanan === 'Kehamilan';
            })->count();

            $kbCount = $dataBulanIni->filter(function($item) {
                return in_array($item->layanan, ['Layanan KB', 'Keluarga Berencana']);
            })->count();

            $total = $dataBulanIni->count();

            // SINKRONISASI PENDAPATAN REAL dari Resep Medis & Obat
            $realDokter = ResepMedis::whereMonth('tanggal_resep', $i)
                ->whereYear('tanggal_resep', $tahun)
                ->sum('biaya_dokter');

            $realObat = ResepMedisItem::whereHas('resepMedis', function ($q) use ($i, $tahun) {
                $q->whereMonth('tanggal_resep', $i)
                  ->whereYear('tanggal_resep', $tahun);
            })
            ->join('medicines', 'resep_medis_items.medicine_id', '=', 'medicines.id')
            ->selectRaw('SUM(resep_medis_items.jumlah * medicines.price) as total')
            ->value('total') ?? 0;

            $income = $realDokter + $realObat;

            // Isi data chart bulanan secara terus menerus (tren 12 bulan)
            $chartLabels[] = $bulanIndo[$i];
            $chartData[] = $total;

            // Kondisi tampilan baris tabel
            if ($type === 'tahunan' || ($type === 'bulanan' && $i == $bulan)) {
                $tableData[] = [
                    'month_index' => $i,
                    'month' => $bulanIndo[$i],
                    'total' => $total,
                    'hamil' => $hamilCount,
                    'kb' => $kbCount,
                    'income' => number_format($income, 0, ',', '.')
                ];
            }
        }

        return response()->json([
            'totalPasien' => $totalPasien,
            'pasienBaru' => $pasienBaru,
            'totalHamil' => $totalHamil,
            'totalKB' => $totalKB,
            'totalKunjungan' => $totalKunjungan,
            'table' => $tableData,
            'chartLabels' => $chartLabels,
            'chartData' => $chartData
        ]);
    }
}
