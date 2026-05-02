<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RekamMedis extends Model
{
    use HasFactory;

    protected $table = 'rekam_medis';
    protected $fillable = [
        'reservasi_id',
        'no_rekam_medis',
        'nama_pasien',
        'usia',
        'no_telepon',
        'alamat',
        'golongan_darah',
        'kategori',
        'usia_kehamilan_minggu',
        'hpht',
        'taksiran_persalinan',
        'status_risiko',
        'status_kunjungan',
        'tekanan_darah',
        'berat_badan',
        'tinggi_badan',
        'catatan_medis',
        'diagnosis',
        'tindakan',
        'tanggal_kunjungan_terakhir',
        'jadwal_kontrol_berikutnya',
        'dokter_pemeriksa',
    ];

    public function reservasi()
    {
        return $this->belongsTo(Reservasi::class);
    }

    protected $casts = [
        'hpht'                      => 'date',
        'taksiran_persalinan'       => 'date',
        'tanggal_kunjungan_terakhir'=> 'date',
        'jadwal_kontrol_berikutnya' => 'date',
        'berat_badan'               => 'float',
        'tinggi_badan'              => 'float',
    ];

    /**
     * Generate nomor rekam medis otomatis: RM-YYYY-XXXX
     */
    public static function generateNoRekamMedis(): string
    {
        $year    = now()->year;
        $last    = self::whereYear('created_at', $year)->max('id') ?? 0;
        $seq     = $last + 1;
        return 'RM-' . $year . '-' . str_pad($seq, 4, '0', STR_PAD_LEFT);
    }

    /**
     * Scope filter by kategori
     */
    public function scopeByKategori($query, $kategori)
    {
        if ($kategori) {
            return $query->where('kategori', $kategori);
        }
        return $query;
    }

    /**
     * Scope search by nama pasien
     */
    public function scopeSearch($query, $keyword)
    {
        if ($keyword) {
            return $query->where(function ($q) use ($keyword) {
                $q->where('nama_pasien', 'like', "%{$keyword}%")
                  ->orWhere('no_rekam_medis', 'like', "%{$keyword}%")
                  ->orWhere('dokter_pemeriksa', 'like', "%{$keyword}%");
            });
        }
        return $query;
    }
}
