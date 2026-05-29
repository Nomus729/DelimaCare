<?php

namespace App\Models;

use App\Traits\HybridSync;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Doctor extends Model
{
    use HasFactory, HybridSync;

    protected $guarded = ['id'];

    protected $appends = ['is_available', 'current_status'];

    public function getIsAvailableAttribute(): bool
    {
        // Jika status manual Libur atau Istirahat, tidak perlu cek jadwal
        if ($this->status === 'Libur' || $this->status === 'Istirahat') {
            return false;
        }

        // Gunakan timezone Asia/Jakarta (WIB) untuk akurasi
        $now        = new \DateTime('now', new \DateTimeZone('Asia/Jakarta'));
        $dayNames   = [
            'Sunday'    => 'Minggu', 'Monday' => 'Senin',   'Tuesday'  => 'Selasa',
            'Wednesday' => 'Rabu',   'Thursday' => 'Kamis', 'Friday'   => 'Jumat',
            'Saturday'  => 'Sabtu',
        ];
        $currentDay  = $dayNames[$now->format('l')];
        $currentTime = $now->format('H:i:s'); // Format time agar kompatibel dengan kolom TIME di DB

        // Cari jadwal hari ini dari relasi (satu query, tanpa Regex)
        // Jika relasi sudah di-eager-load (with('schedules')), tidak ada query tambahan.
        $todaySchedule = $this->schedules
            ->firstWhere('day_of_week', $currentDay);

        if (! $todaySchedule) {
            $todaySchedule = $this->getParsedSchedule($currentDay);
        }

        // Jika tidak ada jadwal untuk hari ini, dokter tidak praktek
        if (! $todaySchedule) {
            return false;
        }

        // Bandingkan string waktu langsung — aman karena format HH:MM konsisten
        return $currentTime >= $todaySchedule->start_time
            && $currentTime <= $todaySchedule->end_time;
    }

    /**
     * Relasi ke jadwal praktek dokter (one-to-many).
     * Setiap row di doctor_schedules merepresentasikan satu hari praktek.
     */
    public function schedules(): HasMany
    {
        return $this->hasMany(DoctorSchedule::class);
    }

    /**
     * Relasi ke reservasi yang menggunakan dokter ini.
     */
    public function reservasis(): HasMany
    {
        return $this->hasMany(Reservasi::class, 'doctor_id');
    }

    public function getCurrentStatusAttribute(): string
    {
        // Status manual admin selalu ditampilkan apa adanya.
        //
        // Penjelasan desain:
        //   - Kolom `status` adalah OVERRIDE MANUAL oleh admin (Tersedia / Libur / Istirahat).
        //   - Accessor `is_available` digunakan khusus oleh ReservasiService untuk memvalidasi
        //     apakah dokter bisa menerima booking baru (cek hari + jam praktek).
        //   - Keduanya memiliki tanggung jawab berbeda dan tidak boleh saling menimpa.
        //
        // Bug sebelumnya: hanya 'Libur' yang dihormati, 'Tersedia' selalu ditimpa oleh
        // hasil is_available → jika dokter belum ada jadwal di doctor_schedules,
        // is_available = false → status tampil 'Istirahat' meskipun admin menyimpan 'Tersedia'.
        return $this->status ?? 'Istirahat';
    }

    /**
     * Fallback method untuk memparsing string jadwal_praktek
     * apabila tabel doctor_schedules kosong atau belum diset.
     * Mengembalikan object dengan start_time dan end_time atau null.
     */
    public function getParsedSchedule(string $dayName): ?object
    {
        $days = [
            'Senin' => 1, 'Selasa' => 2, 'Rabu' => 3, 'Kamis' => 4,
            'Jumat' => 5, 'Sabtu' => 6, 'Minggu' => 7
        ];
        
        $jadwal = $this->jadwal_praktek ?? '';
        if (empty($jadwal)) {
            return null;
        }

        if (str_contains(strtolower($jadwal), 'setiap hari') || str_contains(strtolower($jadwal), '24 jam')) {
            return (object)[
                'start_time' => '00:00:00',
                'end_time' => '23:59:59'
            ];
        }

        // Contoh: Senin - Jumat (08:00 - 14:00)
        if (preg_match('/([A-Za-z]+)\s*-\s*([A-Za-z]+)\s*\(([\d:]+)\s*-\s*([\d:]+)\)/i', $jadwal, $matches)) {
            $startDayStr = ucfirst(strtolower($matches[1]));
            $endDayStr = ucfirst(strtolower($matches[2]));
            $startTime = $matches[3];
            $endTime = $matches[4];

            $startDayIdx = $days[$startDayStr] ?? 1;
            $endDayIdx = $days[$endDayStr] ?? 7;
            $currentDayIdx = $days[$dayName] ?? 0;

            if ($currentDayIdx >= $startDayIdx && $currentDayIdx <= $endDayIdx) {
                // Pastikan format waktu HH:MM:SS
                if (strlen($startTime) == 5) $startTime .= ':00';
                if (strlen($endTime) == 5) $endTime .= ':00';

                return (object)[
                    'start_time' => $startTime,
                    'end_time' => $endTime
                ];
            }
        }

        return null;
    }
}
