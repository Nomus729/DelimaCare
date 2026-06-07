<?php

namespace App\Services;

use App\Models\Doctor;
use App\Models\Reservasi;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class ReservasiService
{
    /**
     * Hitung nomor antrean dan estimasi waktu berdasarkan tanggal dan jadwal dokter.
     *
     * CATATAN PENTING: Method ini TIDAK boleh dipanggil dari luar transaksi
     * aktif jika digunakan bersama lockForUpdate(), karena lock hanya berlaku
     * selama transaksi berjalan. Pemanggilan dari createReservasi() sudah aman.
     *
     * @return array{queue_number: int, estimated_time: string}
     */
    public function calculateQueue(string $tanggal, Doctor $doctor): array
    {
        // Konversi tanggal ke nama hari Bahasa Indonesia untuk query ke doctor_schedules
        $dayNames = [
            'Sunday'    => 'Minggu', 'Monday' => 'Senin',   'Tuesday'  => 'Selasa',
            'Wednesday' => 'Rabu',   'Thursday' => 'Kamis', 'Friday'   => 'Jumat',
            'Saturday'  => 'Sabtu',
        ];
        $dayOfWeek = $dayNames[date('l', strtotime($tanggal))];

        // Ambil start_time dari jadwal dokter hari tersebut.
        // Null coalescing memberikan fallback '08:00' jika tidak ada jadwal terdaftar.
        $startTime = $doctor->schedules()
            ->where('day_of_week', $dayOfWeek)
            ->value('start_time');
            
        // Fallback ke method getParsedSchedule jika jadwal database kosong
        if (!$startTime) {
            $parsedSchedule = $doctor->getParsedSchedule($dayOfWeek);
            $startTime = $parsedSchedule ? $parsedSchedule->start_time : '08:00';
        }

        // Normalkan: kolom TIME bisa mengembalikan 'HH:MM:SS', ambil 'HH:MM' saja
        $startTime = substr($startTime, 0, 5);

        // ─── PESSIMISTIC LOCK — Inti dari penyelesaian Race Condition ───
        // Antrean dihitung secara global per tanggal kunjungan untuk menghindari nomor antrean ganda
        $lastReservasi = Reservasi::whereDate('tanggal', $tanggal)
            ->orderBy('queue_number', 'desc')
            ->lockForUpdate()   // ← kunci baris teratas hingga transaksi commit
            ->first();

        $queueNumber   = 1;
        $estimatedTime = $startTime;

        if ($lastReservasi) {
            $queueNumber = $lastReservasi->queue_number + 1;

            $lastTime   = $lastReservasi->estimated_time ?? $lastReservasi->waktu;
            $newTimeObj = new \DateTime($lastTime);

            // 🔥 UBAH KE 20 MENIT 🔥
            $newTimeObj->modify('+20 minutes');

            $estimatedTime = $newTimeObj->format('H:i');
        }

        return [
            'queue_number'   => $queueNumber,
            'estimated_time' => $estimatedTime,
        ];
    }

    /**
     * Validasi apakah dokter tersedia pada tanggal dan jam tertentu.
     *
     * @return array{status: bool, message?: string}
     */
    public function checkDoctorAvailability(Doctor $doctor, string $tanggal, string $jam): array
    {
        // Cek status manual dokter terlebih dahulu
        if ($doctor->status !== 'Tersedia') {
            return [
                'status'  => false,
                'message' => 'Dokter sedang ' . $doctor->status . '. Silakan pilih dokter lain.',
            ];
        }

        $dayNames = [
            'Sunday'    => 'Minggu', 'Monday' => 'Senin',   'Tuesday'  => 'Selasa',
            'Wednesday' => 'Rabu',   'Thursday' => 'Kamis', 'Friday'   => 'Jumat',
            'Saturday'  => 'Sabtu',
        ];
        $selectedDay = $dayNames[date('l', strtotime($tanggal))];

        // Cari jadwal untuk hari yang dipilih — null jika tidak ada
        $schedule = $doctor->schedules()
            ->where('day_of_week', $selectedDay)
            ->first();
            
        // Fallback ke method getParsedSchedule jika jadwal database kosong
        if (!$schedule) {
            $schedule = $doctor->getParsedSchedule($selectedDay);
        }

        if (! $schedule) {
            return [
                'status'  => false,
                'message' => "Maaf, {$doctor->nama} tidak praktek di hari {$selectedDay}.",
            ];
        }

        // Validasi waktu praktek (Jam Mulai - Jam Selesai)
        $waktuPilihan = substr($jam, 0, 5);
        $startPraktek = substr($schedule->start_time, 0, 5);
        $endPraktek   = substr($schedule->end_time, 0, 5);

        if ($waktuPilihan < $startPraktek || $waktuPilihan > $endPraktek) {
            return [
                'status'  => false,
                'message' => "Maaf, {$doctor->nama} hanya praktek dari jam {$startPraktek} sampai {$endPraktek} di hari {$selectedDay}.",
            ];
        }

        return ['status' => true];
    }

    /**
     * Buat reservasi baru, terlindungi dari race condition via DB::transaction + lockForUpdate.
     *
     * Alur di dalam transaksi:
     * 1. calculateQueue() dipanggil → di dalamnya, lockForUpdate() mengunci baris reservasi
     * terakhir pada tanggal tersebut hingga transaksi ini selesai.
     * 2. Reservasi baru di-insert dengan nomor antrean yang sudah aman dan unik.
     * 3. Transaksi di-commit → lock dilepas → request lain baru bisa baca.
     *
     * @throws \Throwable jika terjadi deadlock atau error database yang tidak bisa di-recover
     */
    public function createReservasi(array $data, Doctor $doctor, string $status = 'Menunggu'): Reservasi
    {
        try {
            // DB::transaction() akan otomatis:
            //   - Melakukan COMMIT jika closure selesai tanpa exception
            //   - Melakukan ROLLBACK jika ada exception yang ter-throw
            return DB::transaction(function () use ($data, $doctor, $status) {

                // Karena pasien sekarang memilih waktu spesifik, kita tidak menggunakan calculateQueue()
                // untuk waktu, tapi kita tetap butuh mengunci baris untuk mendapatkan nomor antrean berurutan
                $lastReservasi = Reservasi::whereDate('tanggal', $data['tanggal'])
                    ->orderBy('queue_number', 'desc')
                    ->lockForUpdate()
                    ->first();
                
                $queueNumber = $lastReservasi ? $lastReservasi->queue_number + 1 : 1;

                return Reservasi::create([
                    'user_id'        => $data['user_id'] ?? null,
                    'nama'           => $data['nama'],
                    'phone'          => $data['phone'],
                    'layanan'        => $data['layanan'],
                    'dokter_id'      => $doctor->nama,  // Backward compat: kolom string lama
                    'doctor_id'      => $doctor->id,    // FK integer baru
                    'tanggal'        => $data['tanggal'],
                    'waktu'          => $data['waktu'], // Waktu spesifik dari input pasien
                    'queue_number'   => $queueNumber,
                    'estimated_time' => $data['waktu'], // Waktu estimasi = waktu spesifik
                    'keluhan'        => $data['keluhan'] ?? null,
                    'status'         => $status,
                ]);
            });

        } catch (\Illuminate\Database\QueryException $e) {
            // Error 23000 atau 23505 adalah kode untuk Unique Constraint Violation (Duplicate Entry)
            if ($e->getCode() === '23000' || $e->getCode() === '23505') {
                Log::warning('Double booking (Race condition) berhasil dicegah.', [
                    'doctor_id' => $doctor->id,
                    'tanggal'   => $data['tanggal'],
                    'waktu'     => $data['waktu'],
                ]);
                throw new \RuntimeException(
                    'Maaf, slot waktu ini baru saja dipesan oleh pasien lain. Silakan pilih waktu lain.'
                );
            }

            // Error 40001 = deadlock terdeteksi oleh MySQL/PostgreSQL.
            if ($e->getCode() === '40001') {
                Log::warning('Deadlock terdeteksi saat membuat reservasi. Silakan coba kembali.', [
                    'doctor_id' => $doctor->id,
                    'tanggal'   => $data['tanggal'],
                    'error'     => $e->getMessage(),
                ]);

                // Re-throw agar controller bisa menampilkan pesan yang ramah ke user
                throw new \RuntimeException(
                    'Sistem sedang sibuk memproses reservasi lain. Silakan coba beberapa saat lagi.',
                    0,
                    $e
                );
            }

            // Error database lain (koneksi putus, constraint violation, dll.)
            Log::error('Gagal membuat reservasi karena error database.', [
                'doctor_id' => $doctor->id,
                'tanggal'   => $data['tanggal'],
                'error'     => $e->getMessage(),
            ]);

            throw $e; // Re-throw error asli untuk debugging
        }
    }
}
