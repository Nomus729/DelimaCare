<?php

namespace Tests\Feature;

use App\Models\RekamMedis;
use App\Models\Reservasi;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RekamMedisHistoryTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::create([
            'username' => 'admin',
            'email'    => 'admin@test.com',
            'password' => bcrypt('password'),
            'role'     => 'admin',
        ]);
    }

    /** @test */
    public function ajax_create_returns_empty_history_if_no_patient_details_provided()
    {
        $response = $this->actingAs($this->admin)
            ->getJson(route('admin.rekam-medis.create'), [
                'HTTP_X-Requested-With' => 'XMLHttpRequest'
            ]);

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'history' => []
        ]);
    }

    /** @test */
    public function ajax_create_returns_history_for_user_id()
    {
        $patient = User::create([
            'username' => 'siti_aminah',
            'email'    => 'siti@test.com',
            'password' => bcrypt('password'),
            'role'     => 'pasien',
        ]);

        // Create older records (should be in history)
        $rm1 = new RekamMedis([
            'no_rekam_medis' => 'RM-2026-0001',
            'user_id' => $patient->id,
            'nama_pasien' => 'Siti Aminah',
            'usia' => 30,
            'kategori' => 'Kontrol Umum',
            'status_risiko' => 'Rendah',
            'status_kunjungan' => 'Selesai',
        ]);
        $rm1->created_at = now()->subDays(5);
        $rm1->save();

        $rm2 = new RekamMedis([
            'no_rekam_medis' => 'RM-2026-0002',
            'user_id' => $patient->id,
            'nama_pasien' => 'Siti Aminah',
            'usia' => 30,
            'kategori' => 'Kontrol Umum',
            'status_risiko' => 'Rendah',
            'status_kunjungan' => 'Aktif',
        ]);
        $rm2->created_at = now()->subDays(10);
        $rm2->save();

        // Hit ajax endpoint with user_id
        $response = $this->actingAs($this->admin)
            ->getJson(route('admin.rekam-medis.create', ['user_id' => $patient->id]), [
                'HTTP_X-Requested-With' => 'XMLHttpRequest'
            ]);

        $response->assertStatus(200);
        $response->assertJsonCount(2, 'history');
        
        // Assert sorting (descending by created_at)
        $this->assertEquals($rm1->id, $response->json('history.0.id'));
        $this->assertEquals($rm2->id, $response->json('history.1.id'));
    }

    /** @test */
    public function ajax_create_returns_history_for_reservasi_id()
    {
        $patient = User::create([
            'username' => 'siti_aminah',
            'email'    => 'siti@test.com',
            'password' => bcrypt('password'),
            'role'     => 'pasien',
        ]);

        $reservasi = Reservasi::create([
            'user_id' => $patient->id,
            'nama' => 'Siti Aminah',
            'phone' => '081234567890',
            'layanan' => 'Pemeriksaan Umum',
            'tanggal' => now(),
            'waktu' => '09:00',
            'status' => 'Selesai',
            'status_konfirmasi' => 'Diterima'
        ]);

        $rm = new RekamMedis([
            'no_rekam_medis' => 'RM-2026-0003',
            'user_id' => $patient->id,
            'nama_pasien' => 'Siti Aminah',
            'usia' => 30,
            'kategori' => 'Kontrol Umum',
            'status_risiko' => 'Rendah',
            'status_kunjungan' => 'Selesai',
        ]);
        $rm->created_at = now()->subDays(5);
        $rm->save();

        $response = $this->actingAs($this->admin)
            ->getJson(route('admin.rekam-medis.create', ['reservasi_id' => $reservasi->id]), [
                'HTTP_X-Requested-With' => 'XMLHttpRequest'
            ]);

        $response->assertStatus(200);
        $response->assertJsonCount(1, 'history');
        $this->assertEquals($rm->id, $response->json('history.0.id'));
    }

    /** @test */
    public function ajax_create_returns_history_for_nama_pasien_fallback()
    {
        // Patient has no user account (walk-in)
        $rm = new RekamMedis([
            'no_rekam_medis' => 'RM-2026-0004',
            'nama_pasien' => 'WalkIn Patient',
            'usia' => 45,
            'kategori' => 'Kontrol Umum',
            'status_risiko' => 'Rendah',
            'status_kunjungan' => 'Selesai',
        ]);
        $rm->created_at = now()->subDays(2);
        $rm->save();

        $response = $this->actingAs($this->admin)
            ->getJson(route('admin.rekam-medis.create', ['nama_pasien' => 'WalkIn Patient']), [
                'HTTP_X-Requested-With' => 'XMLHttpRequest'
            ]);

        $response->assertStatus(200);
        $response->assertJsonCount(1, 'history');
        $this->assertEquals($rm->id, $response->json('history.0.id'));
    }

    /** @test */
    public function ajax_edit_returns_history_excluding_current_record()
    {
        $patient = User::create([
            'username' => 'siti_aminah',
            'email'    => 'siti@test.com',
            'password' => bcrypt('password'),
            'role'     => 'pasien',
        ]);

        $rm_current = new RekamMedis([
            'no_rekam_medis' => 'RM-2026-0005',
            'user_id' => $patient->id,
            'nama_pasien' => 'Siti Aminah',
            'usia' => 30,
            'kategori' => 'Kontrol Umum',
            'status_risiko' => 'Rendah',
            'status_kunjungan' => 'Aktif',
        ]);
        $rm_current->created_at = now();
        $rm_current->save();

        $rm_older = new RekamMedis([
            'no_rekam_medis' => 'RM-2026-0006',
            'user_id' => $patient->id,
            'nama_pasien' => 'Siti Aminah',
            'usia' => 30,
            'kategori' => 'Kontrol Umum',
            'status_risiko' => 'Rendah',
            'status_kunjungan' => 'Selesai',
        ]);
        $rm_older->created_at = now()->subDays(3);
        $rm_older->save();

        // Edit endpoint should retrieve history for patient but exclude current record being edited
        $response = $this->actingAs($this->admin)
            ->getJson(route('admin.rekam-medis.edit', $rm_current), [
                'HTTP_X-Requested-With' => 'XMLHttpRequest'
            ]);

        $response->assertStatus(200);
        $response->assertJsonCount(1, 'history');
        $this->assertEquals($rm_older->id, $response->json('history.0.id'));
    }

    /** @test */
    public function history_filters_out_active_records_from_today()
    {
        $patient = User::create([
            'username' => 'siti_aminah',
            'email'    => 'siti@test.com',
            'password' => bcrypt('password'),
            'role'     => 'pasien',
        ]);

        // 1. Created today, status "Aktif" -> should be EXCLUDED
        $rm_today_aktif = new RekamMedis([
            'no_rekam_medis' => 'RM-2026-0007',
            'user_id' => $patient->id,
            'nama_pasien' => 'Siti Aminah',
            'usia' => 30,
            'kategori' => 'Kontrol Umum',
            'status_risiko' => 'Rendah',
            'status_kunjungan' => 'Aktif',
        ]);
        $rm_today_aktif->created_at = now();
        $rm_today_aktif->save();

        // 2. Created today, status "Selesai" -> should be INCLUDED
        $rm_today_selesai = new RekamMedis([
            'no_rekam_medis' => 'RM-2026-0008',
            'user_id' => $patient->id,
            'nama_pasien' => 'Siti Aminah',
            'usia' => 30,
            'kategori' => 'Kontrol Umum',
            'status_risiko' => 'Rendah',
            'status_kunjungan' => 'Selesai',
        ]);
        $rm_today_selesai->created_at = now();
        $rm_today_selesai->save();

        // 3. Created yesterday, status "Aktif" -> should be INCLUDED (since it's before today)
        $rm_yesterday_aktif = new RekamMedis([
            'no_rekam_medis' => 'RM-2026-0009',
            'user_id' => $patient->id,
            'nama_pasien' => 'Siti Aminah',
            'usia' => 30,
            'kategori' => 'Kontrol Umum',
            'status_risiko' => 'Rendah',
            'status_kunjungan' => 'Aktif',
        ]);
        $rm_yesterday_aktif->created_at = now()->subDay();
        $rm_yesterday_aktif->save();

        $response = $this->actingAs($this->admin)
            ->getJson(route('admin.rekam-medis.create', ['user_id' => $patient->id]), [
                'HTTP_X-Requested-With' => 'XMLHttpRequest'
            ]);

        $response->assertStatus(200);
        $response->assertJsonCount(2, 'history');
        
        $historyIds = collect($response->json('history'))->pluck('id')->toArray();
        
        $this->assertContains($rm_today_selesai->id, $historyIds);
        $this->assertContains($rm_yesterday_aktif->id, $historyIds);
        $this->assertNotContains($rm_today_aktif->id, $historyIds);
    }
}
