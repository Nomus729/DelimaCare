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
        // 1. Ambil Parameter dari request (JS mengirimkan month, year, type)
        $bulan = $request->query('month', date('m'));
        $tahun = $request->query('year', date('Y'));
        $type = $request->query('type', 'bulanan');

        // 2. Data Statistik (Card Atas)
        $totalPasien = User::where('role', 'pasien')->count();
        $pasienBaru = User::where('role', 'pasien')
            ->whereMonth('created_at', $bulan)
            ->whereYear('created_at', $tahun)
            ->count();

        // Data Kehamilan & KB dari tabel rekam_medis (Kategori pasien aktif)
        $totalHamil = RekamMedis::where('kategori', 'Kehamilan')->count();
        $totalKB = RekamMedis::where('kategori', 'Keluarga Berencana')->count();

        // Total Kunjungan (Berdasarkan reservasi yang selesai/datang di bulan terpilih)
        $totalKunjungan = Reservasi::where('status', 'Datang')
            ->whereMonth('tanggal', $bulan)
            ->whereYear('tanggal', $tahun)
            ->count();

        // 3. Data Tabel & Grafik (Grouping per bulan untuk tahun terpilih)
        // Karena data mungkin sedikit, kita bisa ambil semua di tahun tersebut lalu group by di PHP menggunakan Collection.
        $reservasiTahunan = Reservasi::where('status', 'Datang')
            ->whereYear('tanggal', $tahun)
            ->get();

        $bulanIndo = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
        ];

        $tableData = [];
        
        // Asumsi pendapatan rata-rata per kunjungan untuk demonstrasi
        $rataRataBiayaHamil = 150000;
        $rataRataBiayaKB = 100000;
        $rataRataBiayaUmum = 75000;

        for ($i = 1; $i <= 12; $i++) {
            $dataBulanIni = $reservasiTahunan->filter(function ($item) use ($i) {
                return Carbon::parse($item->tanggal)->month == $i;
            });

            $hamilCount = $dataBulanIni->where('layanan', 'Kehamilan')->count();
            $kbCount = $dataBulanIni->where('layanan', 'Keluarga Berencana')->count();
            $umumCount = $dataBulanIni->whereNotIn('layanan', ['Kehamilan', 'Keluarga Berencana'])->count();

            $total = $dataBulanIni->count();
            
            // Perhitungan estimasi income (karena belum ada tabel transaksi khusus)
            $income = ($hamilCount * $rataRataBiayaHamil) + ($kbCount * $rataRataBiayaKB) + ($umumCount * $rataRataBiayaUmum);

            // Hanya tambahkan bulan sampai bulan saat ini (jika tahun ini) atau semua (jika tahun lalu)
            // Atau tampilkan semua bulan untuk laporan tahunan
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

        // Jika bulanan, hanya kembalikan 1 row di tabel. Jika tahunan, kembalikan 12 row.

        return response()->json([
            'totalPasien' => $totalPasien,
            'pasienBaru' => $pasienBaru,
            'totalHamil' => $totalHamil,
            'totalKB' => $totalKB,
            'totalKunjungan' => $totalKunjungan,
            'table' => $tableData,
            'chartLabels' => collect($tableData)->pluck('month'),
            'chartData' => collect($tableData)->pluck('total')
        ]);
    }
}
