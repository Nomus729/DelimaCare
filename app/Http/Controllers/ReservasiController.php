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
            'dokter_id' => 'required',
            'phone' => 'required',
            'layanan' => 'required',
            'tanggal' => 'required|date',
        ]);

        // --- VALIDASI JADWAL DOKTER WOK ---
        $doctor = \App\Models\Doctor::where('nama', $request->dokter_id)->first();
        if (!$doctor) {
            return redirect()->back()->with('error', 'Dokter tidak ditemukan.');
        }

        if ($doctor->status !== 'Tersedia') {
            return redirect()->back()->with('error', 'Dokter sedang ' . $doctor->status . '. Silakan pilih dokter lain.');
        }

        // Ambil jam mulai dari jadwal praktek dokter
        $startTime = '08:00'; // Default jika gagal parse
        $regex = '/\((..):(..) - (..):(..)\)/';
        if (preg_match($regex, $doctor->jadwal_praktek, $matches)) {
            $startTime = $matches[1] . ':' . $matches[2];
        }

        // --- LOGIKA ANTREAN OTOMATIS (GLOBAL CLINIC QUEUE) ---
        // Cari reservasi terakhir untuk TANGGAL tersebut (siapapun dokternya)
        $lastReservasi = Reservasi::whereDate('tanggal', $request->tanggal)
            ->orderBy('queue_number', 'desc')
            ->first();

        $newQueue = 1;
        $estimatedTime = $startTime;

        if ($lastReservasi) {
            $newQueue = $lastReservasi->queue_number + 1;
            
            // Tambahkan gap 30 menit dari pasien terakhir di klinik
            $lastTime = $lastReservasi->estimated_time ?? $lastReservasi->waktu;
            $newTimeObj = new \DateTime($lastTime);
            $newTimeObj->modify('+30 minutes');
            $estimatedTime = $newTimeObj->format('H:i');
        }

        // --- CEK APAKAH TANGGAL & JAM MASUK JADWAL DOKTER ---
        $availability = $this->checkDoctorAvailability($doctor, $request->tanggal, $estimatedTime);
        if (!$availability['status']) {
            return redirect()->back()->with('error', $availability['message']);
        }

        Reservasi::create([
            'user_id' => Auth::id(),
            'nama' => Auth::user()->username,
            'phone' => $request->phone,
            'layanan' => $request->layanan,
            'dokter_id' => $request->dokter_id,
            'tanggal' => $request->tanggal,
            'waktu' => $estimatedTime,
            'queue_number' => $newQueue,
            'estimated_time' => $estimatedTime,
            'keluhan' => $request->keluhan,
            'status' => 'Menunggu',
        ]);

        return redirect()->route('portal')->with('success', 'Reservasi Berhasil! No. Antrean Anda: ' . $newQueue . ' (Estimasi Jam ' . $estimatedTime . ')');
    }

    private function checkDoctorAvailability($doctor, $tanggal, $jam)
    {
        $dayNames = [
            'Sunday' => 'Minggu', 'Monday' => 'Senin', 'Tuesday' => 'Selasa',
            'Wednesday' => 'Rabu', 'Thursday' => 'Kamis', 'Friday' => 'Jumat', 'Saturday' => 'Sabtu'
        ];
        $selectedDay = $dayNames[date('l', strtotime($tanggal))];
        
        // Parse Format: 'Senin - Jumat (08:00 - 16:00)'
        $regex = '/^(.+) - (.+) \((..):(..) - (..):(..)\)$/';
        if (preg_match($regex, $doctor->jadwal_praktek, $matches)) {
            $dayStart = $matches[1];
            $dayEnd = $matches[2];
            $timeStart = $matches[3] . ':' . $matches[4];
            $timeEnd = $matches[5] . ':' . $matches[6];

            $days = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'];
            $startIndex = array_search($dayStart, $days);
            $endIndex = array_search($dayEnd, $days);
            $currentIndex = array_search($selectedDay, $days);

            // Cek Hari
            if ($currentIndex < $startIndex || $currentIndex > $endIndex) {
                return ['status' => false, 'message' => "Maaf, $doctor->nama tidak praktek di hari $selectedDay. Jadwal: $dayStart - $dayEnd"];
            }

            // Cek Jam
            if ($jam < $timeStart || $jam > $timeEnd) {
                return ['status' => false, 'message' => "Maaf, antrean untuk $doctor->nama sudah penuh atau di luar jam praktek ($timeStart - $timeEnd)."];
            }
        }

        return ['status' => true];
    }

    // --- FITUR ADMIN: TAMBAH RESERVASI MANUAL ---
    public function storeAdmin(Request $request)
    {
        $request->validate([
            'nama' => 'required',
            'phone' => 'required',
            'layanan' => 'required',
            'dokter_id' => 'required', // Admin harus pilih dokter
            'tanggal' => 'required|date',
        ]);

        $doctor = \App\Models\Doctor::where('nama', $request->dokter_id)->first();
        if (!$doctor) {
            return redirect()->back()->with('error', 'Dokter tidak ditemukan.');
        }

        // Ambil jam mulai dari jadwal praktek dokter
        $startTime = '08:00';
        $regex = '/\((..):(..) - (..):(..)\)/';
        if (preg_match($regex, $doctor->jadwal_praktek, $matches)) {
            $startTime = $matches[1] . ':' . $matches[2];
        }

        // --- LOGIKA ANTREAN OTOMATIS (GLOBAL CLINIC QUEUE) ---
        $lastReservasi = Reservasi::whereDate('tanggal', $request->tanggal)
            ->orderBy('queue_number', 'desc')
            ->first();

        $newQueue = 1;
        $estimatedTime = $startTime;

        if ($lastReservasi) {
            $newQueue = $lastReservasi->queue_number + 1;
            $lastTime = $lastReservasi->estimated_time ?? $lastReservasi->waktu;
            $newTimeObj = new \DateTime($lastTime);
            $newTimeObj->modify('+30 minutes');
            $estimatedTime = $newTimeObj->format('H:i');
        }

        Reservasi::create([
            'nama' => $request->nama,
            'phone' => $request->phone,
            'layanan' => $request->layanan,
            'dokter_id' => $request->dokter_id,
            'tanggal' => $request->tanggal,
            'waktu' => $estimatedTime,
            'queue_number' => $newQueue,
            'estimated_time' => $estimatedTime,
            'keluhan' => $request->keluhan,
            'status' => 'Dikonfirmasi',
        ]);

        return redirect()->back()->with('success', 'Reservasi manual berhasil ditambahkan! No. Antrean: ' . $newQueue . ' (Estimasi Jam ' . $estimatedTime . ')');
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
