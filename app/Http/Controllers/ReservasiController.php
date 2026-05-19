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

    // ==========================================
    // FITUR UNTUK PASIEN (PORTAL)
    // ==========================================

    /**
     * Memproses form reservasi dari Portal Pasien.
     */
    public function store(StoreReservasiRequest $request)
    {
        $doctor = \App\Models\Doctor::findOrFail($request->dokter_id);

        // Hitung antrean via service
        $queue = $this->reservasiService->calculateQueue($request->tanggal, $doctor);

        // Cek ketersediaan dokter
        $availability = $this->reservasiService->checkDoctorAvailability(
            $doctor, $request->tanggal, $queue['estimated_time']
        );
        if (!$availability['status']) {
            return redirect()->back()->with('error', $availability['message']);
        }

        // Buat reservasi via service
        $reservasi = $this->reservasiService->createReservasi([
            'user_id'  => Auth::id(),
            'nama'     => Auth::user()->username,
            'phone'    => $request->phone,
            'layanan'  => $request->layanan,
            'tanggal'  => $request->tanggal,
            'keluhan'  => $request->keluhan,
        ], $doctor, 'Menunggu');

        return redirect()->route('portal')->with(
            'success',
            'Reservasi Berhasil! No. Antrean Anda: ' . $reservasi->queue_number .
            ' (Estimasi Jam ' . $reservasi->estimated_time . ')'
        );
    }

    /**
     * Admin: Tambah reservasi manual.
     */
    public function storeAdmin(StoreReservasiRequest $request)
    {
        $doctor = \App\Models\Doctor::findOrFail($request->dokter_id);

        $reservasi = $this->reservasiService->createReservasi([
            'nama'    => $request->nama,
            'phone'   => $request->phone,
            'layanan' => $request->layanan,
            'tanggal' => $request->tanggal,
            'keluhan' => $request->keluhan,
        ], $doctor, 'Dikonfirmasi');

        return redirect()->back()->with(
            'success',
            'Reservasi manual berhasil ditambahkan! No. Antrean: ' . $reservasi->queue_number .
            ' (Estimasi Jam ' . $reservasi->estimated_time . ')'
        );
    }

    // 2. Menampilkan data jadwal ke Portal Pasien
    public function indexPasien()
    {
        $jadwalPasien = Reservasi::where('user_id', Auth::id())->latest()->get();
        return view('portal.jadwal', compact('jadwalPasien'));
    }

    // 3. Menghapus/Membatalkan Reservasi dari sisi Pasien
    public function destroy($id)
    {
        $reservasi = Reservasi::where('id', $id)->where('user_id', Auth::id())->firstOrFail();
        $reservasi->delete();

        return redirect()->route('portal')->with('success', 'Jadwal konsultasi berhasil dibatalkan.');
    }


    // ==========================================
    // FITUR UNTUK ADMIN PANEL (ADMIN BISA LIHAT SEMUA)
    // ==========================================

    // 4. Admin mengonfirmasi reservasi
    public function konfirmasiAdmin($id)
    {
        $reservasi = Reservasi::findOrFail($id);
        $reservasi->status = 'Dikonfirmasi';
        $reservasi->save();

        return redirect()->back()->with('success', 'Reservasi atas nama ' . $reservasi->nama . ' berhasil dikonfirmasi!');
    }

    // 5. Admin membatalkan/menghapus reservasi
    public function batalAdmin($id)
    {
        $reservasi = Reservasi::findOrFail($id);
        $nama = $reservasi->nama;
        $reservasi->delete();

        return redirect()->back()->with('success', 'Reservasi atas nama ' . $nama . ' berhasil dihapus.');
    }

    // 6. Admin update status reservasi (Datang / Tidak Datang)
    public function updateStatus(Request $request, $id)
    {
        $request->validate(['status' => 'required|in:Menunggu,Dikonfirmasi,Datang,Tidak Datang']);
        $reservasi = Reservasi::findOrFail($id);
        $reservasi->status = $request->status;
        $reservasi->save();

        return redirect()->back()->with('success', 'Status reservasi ' . $reservasi->nama . ' diperbarui menjadi ' . $request->status . '.');
    }
}
