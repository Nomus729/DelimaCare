<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reservasi extends Model
{
    use HasFactory;

    // INI PENAWARNYA WOK: Kasih tau Laravel nama tabel aslinya apa
    protected $table = 'reservasi';

    // Biar semua data dari form bisa masuk
    protected $guarded = ['id'];
}
