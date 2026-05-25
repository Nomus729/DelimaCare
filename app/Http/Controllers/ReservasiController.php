<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreReservasiRequest;
use App\Models\Reservasi;
use App\Services\ReservasiService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReservasiController extends Controller
{
    private ReservasiService $reservasiService;

    public function __construct(ReservasiService $reservasiService)
    {
        $this->reservasiService = $reservasiService;
    }

    public function store(StoreReservasiRequest $request)
    {
        $doctor = \App\Models\Doctor::findOrFail($request->dokter_id);

        $queue = $this->reservasiService->calculateQueue($request->tanggal, $doctor);

        $availability = $this->reservasiService->checkDoctorAvailability(
            $doctor, $request->tanggal, $queue['estimated_time']
        );
        if (!$availability['status']) {
            return redirect()->back()->with('error', $availability['message']);
        }

        // Blok error slot 20 menit SUDAH DIHAPUS.
        // Sekarang sistem akan otomatis meloloskan ke antrean berikutnya.

        try {
            $reservasi = $this->reservasiService->createReservasi([
                'user_id'  => Auth::id(),
                'nama'     => Auth::user()->username,
                'phone'    => $request->phone,
                'layanan'  => $request->layanan,
                'tanggal'  => $request->tanggal,
                'keluhan'  => $request->keluhan,
            ], $doctor, 'Menunggu');

        } catch (\RuntimeException $e) {
            return redirect()->back()->with('error', $e->getMessage());
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan sistem. Silakan coba lagi.');
        }

        return redirect()->route('portal')->with(
            'success',
            'Reservasi Berhasil! No. Antrean Anda: ' . $reservasi->queue_number .
            ' (Estimasi Jam ' . $reservasi->estimated_time . ')'
        );
    }

    public function storeAdmin(StoreReservasiRequest $request)
    {
        $doctor = \App\Models\Doctor::findOrFail($request->dokter_id);

        $queue = $this->reservasiService->calculateQueue($request->tanggal, $doctor);

        // Blok error slot 20 menit (Admin) SUDAH DIHAPUS.

        try {
            $reservasi = $this->reservasiService->createReservasi([
                'nama'    => $request->nama,
                'phone'   => $request->phone,
                'layanan' => $request->layanan,
                'tanggal' => $request->tanggal,
                'keluhan' => $request->keluhan,
            ], $doctor, 'Dikonfirmasi');

        } catch (\RuntimeException $e) {
            return redirect()->back()->with('error', $e->getMessage());
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal membuat reservasi. Silakan coba lagi.');
        }

        return redirect()->back()->with(
            'success',
            'Reservasi manual berhasil ditambahkan! No. Antrean: ' . $reservasi->queue_number .
            ' (Estimasi Jam ' . $reservasi->estimated_time . ')'
        );
    }

    // ... (Fungsi indexPasien, destroy, konfirmasiAdmin, batalAdmin, updateStatus biarin utuh kayak aslinya)
}
