<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\RekamMedis;
use Illuminate\Http\Request;

class RekamMedisController extends Controller
{
    /**
     * Store a newly created record.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_pasien'               => 'required|string|max:255',
            'usia'                      => 'required|integer|min:1|max:120',
            'no_telepon'                => 'nullable|string|max:20',
            'alamat'                    => 'nullable|string|max:500',
            'golongan_darah'            => 'nullable|string|max:5',
            'kategori'                  => 'required|in:Kehamilan,Keluarga Berencana,Kontrol Umum,Konsultasi',
            'usia_kehamilan_minggu'     => 'nullable|integer|min:1|max:42',
            'hpht'                      => 'nullable|date',
            'taksiran_persalinan'       => 'nullable|date',
            'status_risiko'             => 'required|in:Rendah,Sedang,Tinggi',
            'status_kunjungan'          => 'required|in:Aktif,Selesai,Dirujuk',
            'tekanan_darah'             => 'nullable|string|max:20',
            'berat_badan'               => 'nullable|numeric|min:1|max:300',
            'tinggi_badan'              => 'nullable|numeric|min:50|max:250',
            'catatan_medis'             => 'nullable|string',
            'diagnosis'                 => 'nullable|string',
            'tindakan'                  => 'nullable|string',
            'tanggal_kunjungan_terakhir'=> 'nullable|date',
            'jadwal_kontrol_berikutnya' => 'nullable|date',
            'dokter_pemeriksa'          => 'nullable|string|max:255',
        ]);

        $validated['no_rekam_medis'] = RekamMedis::generateNoRekamMedis();

        RekamMedis::create($validated);

        return redirect()
            ->route('admin.dashboard', ['tab' => 'rekam_medis'])
            ->with('success', 'Rekam medis pasien berhasil ditambahkan.');
    }

    /**
     * Update the specified record.
     */
    public function update(Request $request, RekamMedis $rekamMedis)
    {
        $validated = $request->validate([
            'nama_pasien'               => 'required|string|max:255',
            'usia'                      => 'required|integer|min:1|max:120',
            'no_telepon'                => 'nullable|string|max:20',
            'alamat'                    => 'nullable|string|max:500',
            'golongan_darah'            => 'nullable|string|max:5',
            'kategori'                  => 'required|in:Kehamilan,Keluarga Berencana,Kontrol Umum,Konsultasi',
            'usia_kehamilan_minggu'     => 'nullable|integer|min:1|max:42',
            'hpht'                      => 'nullable|date',
            'taksiran_persalinan'       => 'nullable|date',
            'status_risiko'             => 'required|in:Rendah,Sedang,Tinggi',
            'status_kunjungan'          => 'required|in:Aktif,Selesai,Dirujuk',
            'tekanan_darah'             => 'nullable|string|max:20',
            'berat_badan'               => 'nullable|numeric|min:1|max:300',
            'tinggi_badan'              => 'nullable|numeric|min:50|max:250',
            'catatan_medis'             => 'nullable|string',
            'diagnosis'                 => 'nullable|string',
            'tindakan'                  => 'nullable|string',
            'tanggal_kunjungan_terakhir'=> 'nullable|date',
            'jadwal_kontrol_berikutnya' => 'nullable|date',
            'dokter_pemeriksa'          => 'nullable|string|max:255',
        ]);

        $rekamMedis->update($validated);

        return redirect()
            ->route('admin.dashboard', ['tab' => 'rekam_medis'])
            ->with('success', 'Rekam medis berhasil diperbarui.');
    }

    /**
     * Remove the specified record.
     */
    public function destroy(RekamMedis $rekamMedis)
    {
        $rekamMedis->delete();

        return redirect()
            ->route('admin.dashboard', ['tab' => 'rekam_medis'])
            ->with('success', 'Rekam medis berhasil dihapus.');
    }
}
