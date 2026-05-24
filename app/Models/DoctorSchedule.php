<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Model DoctorSchedule — Merepresentasikan satu hari jadwal praktek dokter.
 *
 * @property int    $id
 * @property int    $doctor_id
 * @property string $day_of_week  Salah satu dari: Senin, Selasa, Rabu, Kamis, Jumat, Sabtu, Minggu
 * @property string $start_time   Format HH:MM (dari kolom TIME di database)
 * @property string $end_time     Format HH:MM (dari kolom TIME di database)
 */
class DoctorSchedule extends Model
{
    use HasFactory;

    /**
     * Kolom yang boleh diisi secara mass-assignment.
     */
    protected $fillable = [
        'doctor_id',
        'day_of_week',
        'start_time',
        'end_time',
    ];

    /**
     * Relasi ke dokter pemilik jadwal ini.
     */
    public function doctor(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Doctor::class);
    }
}
