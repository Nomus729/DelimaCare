<?php

namespace Tests\Unit;

use App\Models\RekamMedis;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RekamMedisModelTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_generates_valid_no_rekam_medis_format()
    {
        $no = RekamMedis::generateNoRekamMedis();
        $year = now()->year;

        // Format: RM-YYYY-XXXX
        $this->assertMatchesRegularExpression("/^RM-{$year}-\d{4}$/", $no);
    }

    /** @test */
    public function it_increments_no_rekam_medis_correctly()
    {
        // Buat 1 rekam medis
        RekamMedis::create([
            'no_rekam_medis' => 'RM-' . now()->year . '-0001',
            'nama_pasien'    => 'Pasien Satu',
            'usia'           => 30,
            'kategori'       => 'Kontrol Umum',
            'status_risiko'  => 'Rendah',
            'status_kunjungan' => 'Aktif',
        ]);

        $no2 = RekamMedis::generateNoRekamMedis();
        $this->assertStringEndsWith('0002', $no2);
    }

    /** @test */
    public function it_soft_deletes_instead_of_permanently_removing()
    {
        $rm = RekamMedis::create([
            'no_rekam_medis' => 'RM-2026-TEST',
            'nama_pasien'    => 'Soft Delete Test',
            'usia'           => 25,
            'kategori'       => 'Konsultasi',
            'status_risiko'  => 'Rendah',
            'status_kunjungan' => 'Aktif',
        ]);

        $rm->delete();

        // Tidak ditemukan via query normal
        $this->assertNull(RekamMedis::find($rm->id));

        // Masih ada via withTrashed
        $this->assertNotNull(RekamMedis::withTrashed()->find($rm->id));
        $this->assertNotNull(RekamMedis::withTrashed()->find($rm->id)->deleted_at);
    }

    /** @test */
    public function it_can_restore_soft_deleted_record()
    {
        $rm = RekamMedis::create([
            'no_rekam_medis' => 'RM-2026-REST',
            'nama_pasien'    => 'Restore Test',
            'usia'           => 28,
            'kategori'       => 'Kehamilan',
            'status_risiko'  => 'Sedang',
            'status_kunjungan' => 'Aktif',
        ]);

        $rm->delete();
        $this->assertNull(RekamMedis::find($rm->id));

        // Restore
        RekamMedis::withTrashed()->find($rm->id)->restore();
        $this->assertNotNull(RekamMedis::find($rm->id));
    }

    /** @test */
    public function it_has_user_relationship()
    {
        $user = User::create([
            'username' => 'testuser',
            'email'    => 'testuser@example.com',
            'password' => bcrypt('password'),
            'role'     => 'pasien',
        ]);

        $rm = RekamMedis::create([
            'no_rekam_medis' => 'RM-2026-REL',
            'nama_pasien'    => 'testuser',
            'user_id'        => $user->id,
            'usia'           => 30,
            'kategori'       => 'Kontrol Umum',
            'status_risiko'  => 'Rendah',
            'status_kunjungan' => 'Aktif',
        ]);

        $this->assertEquals($user->id, $rm->user->id);
        $this->assertTrue($user->rekamMedis->contains($rm));
    }
}
