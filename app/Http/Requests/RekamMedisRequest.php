<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RekamMedisRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $rules = [
            'nama_pasien'               => 'required|string|max:255',
            'usia'                      => 'required|integer|min:1|max:120',
            'no_telepon'                => 'nullable|string|max:20',
            'alamat'                    => 'nullable|string|max:500',
            'golongan_darah'            => 'nullable|string|max:5',
            'kategori'                  => 'required|in:Kehamilan,Keluarga Berencana,Kontrol Umum,Konsultasi,Imunisasi',
            'usia_kehamilan_minggu'     => 'nullable|integer|min:1|max:42',
            'hpht'                      => 'nullable|date',
            'taksiran_persalinan'       => 'nullable|date',
            'status_risiko'             => 'required|in:Rendah,Sedang,Tinggi',
            'status_kunjungan'          => 'required|in:Aktif,Selesai,Dirujuk',
            'tekanan_darah'             => 'nullable|string|max:20',
            'berat_badan'               => 'nullable|numeric|min:1|max:300',
            'tinggi_badan'              => 'nullable|numeric|min:50|max:250',
            'catatan_medis'             => 'nullable|string',
            'catatan_pasien'            => 'nullable|string',
            'diagnosis'                 => 'nullable|string',
            'tindakan'                  => 'nullable|string',
            'tanggal_kunjungan_terakhir'=> 'nullable|date',
            'jadwal_kontrol_berikutnya' => 'nullable|date',
            'dokter_pemeriksa'          => 'nullable|string|max:255',
        ];

        // Hanya store yang butuh reservasi_id
        if ($this->isMethod('POST') && !$this->isMethod('PUT')) {
            $rules['reservasi_id'] = 'nullable|exists:reservasi,id';
        }

        return $rules;
    }

    public function messages(): array
    {
        return [
            'required' => ':attribute wajib diisi.',
            'integer'  => ':attribute harus berupa angka bulat.',
            'numeric'  => ':attribute harus berupa angka.',
            'max'      => ':attribute maksimal :max.',
            'min'      => ':attribute minimal :min.',
            'in'       => 'Pilihan :attribute tidak valid.',
            'date'     => 'Format :attribute tidak sesuai standar.',
            'exists'   => ':attribute tidak ditemukan dalam sistem.',
        ];
    }

    public function attributes(): array
    {
        return [
            'nama_pasien'               => 'Nama Pasien',
            'usia'                      => 'Usia',
            'kategori'                  => 'Kategori Layanan',
            'status_risiko'             => 'Tingkat Risiko',
            'status_kunjungan'          => 'Status Kunjungan',
            'berat_badan'               => 'Berat Badan',
            'tinggi_badan'              => 'Tinggi Badan',
            'tekanan_darah'             => 'Tekanan Darah',
            'hpht'                      => 'Hari Pertama Haid Terakhir (HPHT)',
            'taksiran_persalinan'       => 'Taksiran Persalinan',
            'usia_kehamilan_minggu'     => 'Usia Kehamilan',
            'jadwal_kontrol_berikutnya' => 'Jadwal Kontrol Berikutnya',
            'reservasi_id'              => 'ID Reservasi',
        ];
    }
}
