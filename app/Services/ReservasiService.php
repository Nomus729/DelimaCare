<?php

namespace App\Services;

use App\Models\Doctor;
use App\Models\Reservasi;

class ReservasiService
{
    /**
     * Hitung nomor antrean dan estimasi waktu berdasarkan tanggal dan jadwal dokter.
     *
     * @return array{queue_number: int, estimated_time: string}
     */
    public function calculateQueue(string $tanggal, Doctor $doctor): array
    {
        // Parse jam mulai dari jadwal praktek dokter
        $startTime = '08:00';
        if (preg_match('/\((\d{2}):(\d{2}) - (\d{2}):(\d{2})\)/', $doctor->jadwal_praktek, $matches)) {
            $startTime = $matches[1] . ':' . $matches[2];
        }

        // Cari reservasi terakhir untuk tanggal tersebut (global clinic queue)
        $lastReservasi = Reservasi::whereDate('tanggal', $tanggal)
            ->orderBy('queue_number', 'desc')
            ->first();

        $queueNumber = 1;
        $estimatedTime = $startTime;

        if ($lastReservasi) {
            $queueNumber = $lastReservasi->queue_number + 1;

            $lastTime = $lastReservasi->estimated_time ?? $lastReservasi->waktu;
            $newTimeObj = new \DateTime($lastTime);
            $newTimeObj->modify('+30 minutes');
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
        // Cek status dokter
        if ($doctor->status !== 'Tersedia') {
            return [
                'status'  => false,
                'message' => 'Dokter sedang ' . $doctor->status . '. Silakan pilih dokter lain.',
            ];
        }

        $dayNames = [
            'Sunday' => 'Minggu', 'Monday' => 'Senin', 'Tuesday' => 'Selasa',
            'Wednesday' => 'Rabu', 'Thursday' => 'Kamis', 'Friday' => 'Jumat', 'Saturday' => 'Sabtu',
        ];
        $selectedDay = $dayNames[date('l', strtotime($tanggal))];

        // Parse format: 'Senin - Jumat (08:00 - 16:00)'
        $regex = '/^(.+) - (.+) \((\d{2}):(\d{2}) - (\d{2}):(\d{2})\)$/';
        if (preg_match($regex, $doctor->jadwal_praktek, $matches)) {
            $dayStart  = $matches[1];
            $dayEnd    = $matches[2];
            $timeStart = $matches[3] . ':' . $matches[4];
            $timeEnd   = $matches[5] . ':' . $matches[6];

            $days = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'];
            $startIndex   = array_search($dayStart, $days);
            $endIndex     = array_search($dayEnd, $days);
            $currentIndex = array_search($selectedDay, $days);

            // Cek hari
            if ($currentIndex < $startIndex || $currentIndex > $endIndex) {
                return [
                    'status'  => false,
                    'message' => "Maaf, {$doctor->nama} tidak praktek di hari {$selectedDay}. Jadwal: {$dayStart} - {$dayEnd}",
                ];
            }

            // Cek jam
            if ($jam < $timeStart || $jam > $timeEnd) {
                return [
                    'status'  => false,
                    'message' => "Maaf, antrean untuk {$doctor->nama} sudah penuh atau di luar jam praktek ({$timeStart} - {$timeEnd}).",
                ];
            }
        }

        return ['status' => true];
    }

    /**
     * Buat reservasi baru dengan data standar.
     */
    public function createReservasi(array $data, Doctor $doctor, string $status = 'Menunggu'): Reservasi
    {
        $queue = $this->calculateQueue($data['tanggal'], $doctor);

        return Reservasi::create([
            'user_id'        => $data['user_id'] ?? null,
            'nama'           => $data['nama'],
            'phone'          => $data['phone'],
            'layanan'        => $data['layanan'],
            'dokter_id'      => $doctor->nama,  // Backward compat: kolom string lama
            'doctor_id'      => $doctor->id,    // FK integer baru
            'tanggal'        => $data['tanggal'],
            'waktu'          => $queue['estimated_time'],
            'queue_number'   => $queue['queue_number'],
            'estimated_time' => $queue['estimated_time'],
            'keluhan'        => $data['keluhan'] ?? null,
            'status'         => $status,
        ]);
    }
}
