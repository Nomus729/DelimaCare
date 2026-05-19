<?php

namespace App\Models;

use App\Traits\HybridSync;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RekamMedis extends Model
{
    use HasFactory, HybridSync, SoftDeletes;

    protected $table = 'rekam_medis';
    protected $fillable = [
        'reservasi_id',
        'user_id',
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
        'catatan_pasien',
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

    /**
     * Relasi ke user (pasien) — lebih reliable daripada match by nama_pasien.
     */
    public function user()
    {
        return $this->belongsTo(\App\Models\User::class);
    }

    public function resepMedis()
    {
        return $this->hasOne(\App\Models\ResepMedis::class, 'rekam_medis_id');
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

    /**
     * Scope filter by date
     */
    public function scopeByDate($query, $dateFilter)
    {
        if ($dateFilter) {
            $now = \Carbon\Carbon::now();
            switch ($dateFilter) {
                case 'today':
                    return $query->whereDate('created_at', $now->toDateString());
                case 'week':
                    return $query->whereBetween('created_at', [$now->startOfWeek()->toDateTimeString(), $now->endOfWeek()->toDateTimeString()]);
                case 'month':
                    return $query->whereMonth('created_at', $now->month)->whereYear('created_at', $now->year);
                default:
                    // If it's a specific date (YYYY-MM-DD)
                    if (preg_match('/^\d{4}-\d{2}-\d{2}$/', $dateFilter)) {
                        return $query->whereDate('created_at', $dateFilter);
                    }
                    // If it's a specific month (YYYY-MM)
                    if (preg_match('/^\d{4}-\d{2}$/', $dateFilter)) {
                        $parts = explode('-', $dateFilter);
                        return $query->whereYear('created_at', $parts[0])->whereMonth('created_at', $parts[1]);
                    }
            }
        }
        return $query;
    }
}
