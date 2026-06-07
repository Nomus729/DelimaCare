<?php

namespace Tests\Feature;

use App\Models\Doctor;
use App\Models\Reservasi;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ReservasiFlowTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;
    private User $pasien;
    private Doctor $doctor;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::create([
            'username' => 'admin',
            'email'    => 'admin@test.com',
            'password' => bcrypt('password'),
            'role'     => 'admin',
        ]);

        $this->pasien = User::create([
            'username' => 'pasien1',
            'email'    => 'pasien@test.com',
            'password' => bcrypt('password'),
            'role'     => 'pasien',
        ]);

        $this->doctor = Doctor::create([
            'nama'           => 'Dr. Test',
            'spesialisasi'   => 'Umum',
            'jadwal_praktek' => 'Senin - Minggu (08:00 - 20:00)',
            'status'         => 'Tersedia',
        ]);

        $dayNames = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'];
        foreach ($dayNames as $day) {
            $this->doctor->schedules()->create([
                'day_of_week' => $day,
                'start_time'  => '08:00:00',
                'end_time'    => '20:00:00',
            ]);
        }
    }

    /** @test */
    public function pasien_can_create_reservasi()
    {
        $response = $this->actingAs($this->pasien)->post(route('reservasi.store'), [
            'dokter_id' => $this->doctor->id,
            'phone'     => '081234567890',
            'layanan'   => 'Konsultasi',
            'tanggal'   => now()->addDay()->format('Y-m-d'),
        ]);

        $response->assertRedirect(route('portal'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('reservasi', [
            'nama'      => 'pasien1',
            'doctor_id' => $this->doctor->id,
            'status'    => 'Menunggu',
        ]);
    }

    /** @test */
    public function reservasi_fails_with_invalid_doctor()
    {
        $response = $this->actingAs($this->pasien)->post(route('reservasi.store'), [
            'dokter_id' => 9999, // Doctor doesn't exist
            'phone'     => '081234567890',
            'layanan'   => 'Konsultasi',
            'tanggal'   => now()->addDay()->format('Y-m-d'),
        ]);

        $response->assertSessionHasErrors('dokter_id');
    }

    /** @test */
    public function admin_can_create_manual_reservasi()
    {
        $response = $this->actingAs($this->admin)->post(route('admin.reservasi.store_admin'), [
            'nama'      => 'Siti Rahayu',
            'dokter_id' => $this->doctor->id,
            'phone'     => '081234567890',
            'layanan'   => 'Pemeriksaan Kehamilan',
            'tanggal'   => now()->addDay()->format('Y-m-d'),
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('reservasi', [
            'nama'      => 'Siti Rahayu',
            'doctor_id' => $this->doctor->id,
            'status'    => 'Dikonfirmasi',
        ]);
    }

    /** @test */
    public function admin_can_confirm_reservasi()
    {
        $reservasi = Reservasi::create([
            'nama'           => 'Test Pasien',
            'phone'          => '08123',
            'layanan'        => 'Umum',
            'dokter_id'      => $this->doctor->nama,
            'doctor_id'      => $this->doctor->id,
            'tanggal'        => now()->format('Y-m-d'),
            'waktu'          => '08:00',
            'queue_number'   => 1,
            'estimated_time' => '08:00',
            'status'         => 'Menunggu',
        ]);

        $response = $this->actingAs($this->admin)
            ->patch(route('admin.reservasi.konfirmasi', $reservasi->id));

        $response->assertRedirect();
        $this->assertEquals('Dikonfirmasi', $reservasi->fresh()->status);
    }

    /** @test */
    public function queue_numbers_increment_correctly_across_reservations()
    {
        $tomorrow = now()->addDay()->format('Y-m-d');

        // Buat 2 reservasi berurutan
        $this->actingAs($this->pasien)->post(route('reservasi.store'), [
            'dokter_id' => $this->doctor->id,
            'phone'     => '081234567890',
            'layanan'   => 'Konsultasi',
            'tanggal'   => $tomorrow,
        ]);

        $this->actingAs($this->pasien)->post(route('reservasi.store'), [
            'dokter_id' => $this->doctor->id,
            'phone'     => '081234567890',
            'layanan'   => 'Pemeriksaan Umum',
            'tanggal'   => $tomorrow,
        ]);

        $reservations = Reservasi::whereDate('tanggal', $tomorrow)->orderBy('queue_number')->get();
        $this->assertEquals(1, $reservations[0]->queue_number);
        $this->assertEquals(2, $reservations[1]->queue_number);
        $this->assertNotEquals($reservations[0]->estimated_time, $reservations[1]->estimated_time);
    }

    /** @test */
    public function pasien_can_cancel_own_reservasi()
    {
        $reservasi = Reservasi::create([
            'user_id'        => $this->pasien->id,
            'nama'           => 'pasien1',
            'phone'          => '08123',
            'layanan'        => 'Umum',
            'dokter_id'      => $this->doctor->nama,
            'doctor_id'      => $this->doctor->id,
            'tanggal'        => now()->format('Y-m-d'),
            'waktu'          => '08:00',
            'queue_number'   => 1,
            'estimated_time' => '08:00',
            'status'         => 'Menunggu',
        ]);

        $response = $this->actingAs($this->pasien)
            ->delete(route('reservasi.destroy', $reservasi->id));

        $response->assertRedirect(route('portal'));
        $this->assertDatabaseMissing('reservasi', ['id' => $reservasi->id]);
    }

    /** @test */
    public function reservasi_fails_if_doctor_is_on_leave_or_resting()
    {
        $onLeaveDoctor = Doctor::create([
            'nama'           => 'Dr. Dokter Libur',
            'spesialisasi'   => 'Gigi',
            'jadwal_praktek' => 'Senin - Minggu (08:00 - 20:00)',
            'status'         => 'Libur',
        ]);

        $restingDoctor = Doctor::create([
            'nama'           => 'Dr. Dokter Istirahat',
            'spesialisasi'   => 'Anak',
            'jadwal_praktek' => 'Senin - Minggu (08:00 - 20:00)',
            'status'         => 'Istirahat',
        ]);

        // Try booking on-leave doctor
        $response1 = $this->actingAs($this->pasien)->post(route('reservasi.store'), [
            'dokter_id' => $onLeaveDoctor->id,
            'phone'     => '081234567890',
            'layanan'   => 'Konsultasi',
            'tanggal'   => now()->addDay()->format('Y-m-d'),
        ]);
        $response1->assertRedirect();
        $response1->assertSessionHas('error');
        $this->assertStringContainsString('Dokter sedang Libur', session('error'));

        // Try booking resting doctor
        $response2 = $this->actingAs($this->pasien)->post(route('reservasi.store'), [
            'dokter_id' => $restingDoctor->id,
            'phone'     => '081234567890',
            'layanan'   => 'Konsultasi',
            'tanggal'   => now()->addDay()->format('Y-m-d'),
        ]);
        $response2->assertRedirect();
        $response2->assertSessionHas('error');
        $this->assertStringContainsString('Dokter sedang Istirahat', session('error'));
    }
}
