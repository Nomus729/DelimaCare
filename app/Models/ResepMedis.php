<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ResepMedis extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'resep_medis';
    protected $fillable = [
        'no_resep', 'rekam_medis_id', 'nama_pasien',
        'dokter_pemeriksa', 'tanggal_resep',
        'catatan_apoteker', 'status',
    ];

    protected $casts = [
        'tanggal_resep' => 'date',
    ];

    public function rekamMedis()
    {
        return $this->belongsTo(RekamMedis::class, 'rekam_medis_id');
    }

    public function items()
    {
        return $this->hasMany(ResepMedisItem::class, 'resep_medis_id');
    }

    public static function generateNoResep(): string
    {
        $year = now()->year;
        $last = self::whereYear('created_at', $year)->count() + 1;
        return 'RX-' . $year . '-' . str_pad($last, 4, '0', STR_PAD_LEFT);
    }
}
