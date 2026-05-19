<?php

namespace App\Models;

use App\Traits\HybridSync;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reservasi extends Model
{
    use HasFactory, HybridSync;

    // INI PENAWARNYA WOK: Kasih tau Laravel nama tabel aslinya apa
    protected $table = 'reservasi';

    // Biar semua data dari form bisa masuk
    protected $guarded = ['id'];

    public function rekamMedis()
    {
        return $this->hasOne(RekamMedis::class);
    }

    /**
     * Relasi ke model Doctor (proper FK).
     */
    public function doctor()
    {
        return $this->belongsTo(Doctor::class, 'doctor_id');
    }

    /**
     * Accessor: Ambil nama dokter dari relasi, fallback ke kolom dokter_id lama (string).
     * Ini memastikan backward-compatibility dengan data lama.
     */
    public function getDokterNamaAttribute()
    {
        // Prioritas: relasi doctor -> fallback kolom dokter_id lama (string)
        if ($this->doctor) {
            return $this->doctor->nama;
        }

        return $this->attributes['dokter_id'] ?? null;
    }
}
