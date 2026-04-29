<?php

namespace App\Http\Controllers;

use App\Models\Reservasi;
use Illuminate\Http\Request;

class ReservasiController extends Controller
{
    // ==========================================
    // FITUR UNTUK PASIEN (PORTAL)
    // ==========================================

    // 1. Memproses form dari Portal Pasien (SIMPAN KE DATABASE)
    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required',
            'phone' => 'required',
            'layanan' => 'required',
            'tanggal' => 'required|date',
            'waktu' => 'required',
        ]);

        Reservasi::create([
            'nama' => $request->nama,
            'phone' => $request->phone,
            'layanan' => $request->layanan,
            'dokter_id' => $request->dokter_id,
            'tanggal' => $request->tanggal,
            'waktu' => $request->waktu,
            'keluhan' => $request->keluhan,
            'status' => 'Menunggu', // Otomatis set status awal
        ]);

        return redirect()->route('portal')->with('success', 'Permintaan reservasi berhasil dikirim!');
    }

    // 2. Menampilkan data jadwal ke Portal Pasien
    public function indexPasien()
    {
        $jadwalPasien = Reservasi::latest()->get();
        return view('portal.jadwal', compact('jadwalPasien'));
    }

    // 3. Menghapus/Membatalkan Reservasi dari sisi Pasien
    public function destroy($id)
    {
        $reservasi = Reservasi::findOrFail($id);
        $reservasi->delete();

        return redirect()->route('portal')->with('success', 'Jadwal konsultasi berhasil dibatalkan.');
    }


    // ==========================================
    // FITUR UNTUK ADMIN PANEL (YANG TADI KURANG)
    // ==========================================

    // 4. Admin mengonfirmasi reservasi
    public function konfirmasiAdmin($id)
    {
        $reservasi = Reservasi::findOrFail($id);
        $reservasi->status = 'Dikonfirmasi'; // Ubah status jadi ijo
        $reservasi->save();

        return redirect()->back()->with('success', 'Reservasi atas nama ' . $reservasi->nama . ' berhasil dikonfirmasi!');
    }

    // 5. Admin membatalkan/menghapus reservasi
    public function batalAdmin($id)
    {
        $reservasi = Reservasi::findOrFail($id);
        $nama = $reservasi->nama;
        $reservasi->delete(); // Hapus data dari DB

        return redirect()->back()->with('success', 'Reservasi atas nama ' . $nama . ' berhasil dihapus.');
    }
}
