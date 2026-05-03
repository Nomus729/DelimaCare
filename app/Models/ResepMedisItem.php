<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ResepMedisItem extends Model
{
    use HasFactory;

    protected $table = 'resep_medis_items';
    protected $fillable = [
        'resep_medis_id', 'medicine_id', 'nama_obat',
        'satuan', 'jumlah', 'aturan_pakai',
    ];

    public function resepMedis()
    {
        return $this->belongsTo(ResepMedis::class, 'resep_medis_id');
    }

    public function medicine()
    {
        return $this->belongsTo(Medicine::class, 'medicine_id');
    }
}
