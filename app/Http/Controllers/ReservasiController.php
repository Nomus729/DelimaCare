<?php

namespace App\Http\Controllers;

use App\Models\Reservasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // <-- KUNCI UTAMANYA DI SINI WOK

class ReservasiController extends Controller
{
    // ==========================================
    // FITUR UNTUK PASIEN (PORTAL)
    // ==========================================

    // 1. Memproses form dari Portal Pasien (SIMPAN KE DATABASE)
    public function store(Request $request)
    {
        $request->validate([
            // Validasi nama dihapus karena kita ngambil otomatis dari akun yang login
            'phone' => 'required',
            'layanan' => 'required',
            'tanggal' => 'required|date',
            'waktu' => 'required',
        ]);

        Reservasi::create([
            'user_id' => Auth::id(), // <-- SIMPAN ID PASIEN BIAR GAK KETUKER
            'nama' => Auth::user()->username, // <-- Ambil nama langsung dari sistem (Anti Inspect Element)
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
        // <-- CUMA NAMPILIN DATA YANG PUNYA DIA SENDIRI AJA
        $jadwalPasien = Reservasi::where('user_id', Auth::id())->latest()->get();
        return view('portal.jadwal', compact('jadwalPasien'));
    }

    // 3. Menghapus/Membatalkan Reservasi dari sisi Pasien
    public function destroy($id)
    {
        // Ekstra aman: Cari data berdasarkan ID Reservasi DAN ID User biar gak bisa ngehapus punya orang
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
