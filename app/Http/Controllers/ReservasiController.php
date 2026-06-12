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
        $activeReservation = Reservasi::where('user_id', Auth::id())
            ->whereIn('status', ['Menunggu', 'Dikonfirmasi'])
            ->first();

        if ($activeReservation) {
            return redirect()->route('portal')->with('error', 'Anda masih memiliki reservasi aktif. Selesaikan atau batalkan reservasi sebelumnya untuk membuat yang baru.');
        }

        $doctor = \App\Models\Doctor::findOrFail($request->dokter_id);

        $availability = $this->reservasiService->checkDoctorAvailability(
            $doctor, $request->tanggal, $request->waktu
        );
        if (!$availability['status']) {
            return redirect()->back()->withInput()->with('error', $availability['message']);
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
                'waktu'    => $request->waktu,
                'keluhan'  => $request->keluhan,
            ], $doctor, 'Menunggu');

        } catch (\RuntimeException $e) {
            return redirect()->back()->withInput()->with('error', $e->getMessage());
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

        // Blok error slot 20 menit (Admin) SUDAH DIHAPUS.

        try {
            $reservasi = $this->reservasiService->createReservasi([
                'nama'    => $request->nama,
                'phone'   => $request->phone,
                'layanan' => $request->layanan,
                'tanggal' => $request->tanggal,
                'waktu'   => $request->waktu,
                'keluhan' => $request->keluhan,
            ], $doctor, 'Dikonfirmasi');

        } catch (\RuntimeException $e) {
            return redirect()->back()->withInput()->with('error', $e->getMessage());
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal membuat reservasi. Silakan coba lagi.');
        }

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
        
        if ($reservasi->status !== 'Menunggu') {
            return redirect()->route('portal')->with('error', 'Jadwal konsultasi tidak dapat dibatalkan karena statusnya sudah ' . $reservasi->status . '.');
        }

        $reservasi->status = 'Dibatalkan';
        $reservasi->alasan_batal = 'Dibatalkan oleh Pasien';
        $reservasi->save();

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
    public function batalAdmin(Request $request, $id)
    {
        $request->validate([
            'alasan_batal' => 'required|string|max:255'
        ]);

        $reservasi = Reservasi::findOrFail($id);
        $nama = $reservasi->nama;
        $reservasi->status = 'Dibatalkan';
        $reservasi->alasan_batal = $request->alasan_batal;
        $reservasi->save();

        return redirect()->back()->with('success', 'Reservasi atas nama ' . $nama . ' berhasil dibatalkan.');
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
