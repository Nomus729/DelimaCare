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
}
