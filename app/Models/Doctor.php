<?php

namespace App\Models;

use App\Traits\HybridSync;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Doctor extends Model
{
    use HasFactory, HybridSync;

    protected $guarded = ['id'];

    protected $appends = ['is_available', 'current_status'];

    public function getIsAvailableAttribute()
    {
        // Jika status manual sudah Libur atau Istirahat, jangan ganti otomatis jadi tersedia
        if ($this->status === 'Libur' || $this->status === 'Istirahat') return false;
        
        if (!$this->jadwal_praktek) return false;

        $dayNames = [
            'Sunday' => 'Minggu', 'Monday' => 'Senin', 'Tuesday' => 'Selasa',
            'Wednesday' => 'Rabu', 'Thursday' => 'Kamis', 'Friday' => 'Jumat', 'Saturday' => 'Sabtu'
        ];
        
        // Gunakan timezone Asia/Jakarta (WIB) untuk akurasi
        $now = new \DateTime('now', new \DateTimeZone('Asia/Jakarta'));
        $currentDay = $dayNames[$now->format('l')];
        $currentTime = $now->format('H:i');

        $regex = '/^(.+) - (.+) \((..):(..) - (..):(..)\)$/';
        if (preg_match($regex, $this->jadwal_praktek, $matches)) {
            $dayStart = $matches[1];
            $dayEnd = $matches[2];
            $timeStart = $matches[3] . ':' . $matches[4];
            $timeEnd = $matches[5] . ':' . $matches[6];

            $days = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'];
            $startIndex = array_search($dayStart, $days);
            $endIndex = array_search($dayEnd, $days);
            $currentIndex = array_search($currentDay, $days);

            // Cek Hari (Rentang)
            if ($currentIndex < $startIndex || $currentIndex > $endIndex) return false;

            // Cek Jam
            if ($currentTime < $timeStart || $currentTime > $timeEnd) return false;

            return true;
        }

        return false;
    }

    public function getCurrentStatusAttribute()
    {
        // Prioritas status manual jika Libur
        if ($this->status === 'Libur') return 'Libur';
        
        if ($this->getIsAvailableAttribute()) {
            return 'Tersedia';
        }

        return 'Istirahat';
    }
}
