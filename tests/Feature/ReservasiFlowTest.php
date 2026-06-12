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
            'waktu'     => '08:00',
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
    public function pasien_cannot_create_multiple_active_reservasi()
    {
        // 1. Pasien membuat reservasi pertama
        $this->actingAs($this->pasien)->post(route('reservasi.store'), [
            'dokter_id' => $this->doctor->id,
            'phone'     => '081234567890',
            'layanan'   => 'Konsultasi',
            'tanggal'   => now()->addDay()->format('Y-m-d'),
            'waktu'     => '08:00',
        ]);

        $this->assertDatabaseHas('reservasi', [
            'nama'      => 'pasien1',
            'status'    => 'Menunggu',
        ]);

        // 2. Pasien mencoba membuat reservasi kedua sementara yang pertama masih 'Menunggu'
        $response = $this->actingAs($this->pasien)->post(route('reservasi.store'), [
            'dokter_id' => $this->doctor->id,
            'phone'     => '081234567890',
            'layanan'   => 'Umum',
            'tanggal'   => now()->addDays(2)->format('Y-m-d'),
            'waktu'     => '10:00',
        ]);

        // Harus gagal dan kembali dengan pesan error
        $response->assertRedirect(route('portal'));
        $response->assertSessionHas('error');
        
        $errorMsg = session('error');
        $this->assertStringContainsString('Anda masih memiliki reservasi aktif', $errorMsg);

        // Pastikan di DB hanya ada 1 reservasi untuk pasien ini
        $this->assertEquals(1, Reservasi::where('user_id', $this->pasien->id)->count());
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
            'waktu'     => '08:00',
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

        $pasien2 = User::create([
            'username' => 'pasien2',
            'email'    => 'pasien2@test.com',
            'password' => bcrypt('password'),
            'role'     => 'pasien',
        ]);

        // Buat 2 reservasi berurutan dari 2 pasien yang berbeda
        $this->actingAs($this->pasien)->post(route('reservasi.store'), [
            'dokter_id' => $this->doctor->id,
            'phone'     => '081234567890',
            'layanan'   => 'Konsultasi',
            'tanggal'   => $tomorrow,
            'waktu'     => '08:00',
        ]);

        $this->actingAs($pasien2)->post(route('reservasi.store'), [
            'dokter_id' => $this->doctor->id,
            'phone'     => '081234567890',
            'layanan'   => 'Pemeriksaan Umum',
            'tanggal'   => $tomorrow,
            'waktu'     => '08:30',
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
    public function pasien_cannot_cancel_confirmed_reservasi()
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
            'status'         => 'Dikonfirmasi',
        ]);

        $response = $this->actingAs($this->pasien)
            ->delete(route('reservasi.destroy', $reservasi->id));

        $response->assertRedirect(route('portal'));
        $response->assertSessionHas('error');
        $this->assertStringContainsString('Jadwal konsultasi tidak dapat dibatalkan', session('error'));
        $this->assertDatabaseHas('reservasi', ['id' => $reservasi->id, 'status' => 'Dikonfirmasi']);
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
            'waktu'     => '08:00',
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
            'waktu'     => '08:00',
        ]);
        $response2->assertRedirect();
        $response2->assertSessionHas('error');
        $this->assertStringContainsString('Dokter sedang Istirahat', session('error'));
    }

    /** @test */
    public function patient_can_view_portal_with_reservations()
    {
        $reservasi = Reservasi::create([
            'user_id'        => $this->pasien->id,
            'nama'           => 'pasien1',
            'phone'          => '081234567890',
            'layanan'        => 'Konsultasi',
            'dokter_id'      => $this->doctor->id,
            'tanggal'        => now()->format('Y-m-d'),
            'waktu'          => '08:00',
            'queue_number'   => 1,
            'estimated_time' => '08:00',
            'status'         => 'Menunggu',
        ]);

        $response = $this->actingAs($this->pasien)->get(route('portal'));

        $response->assertStatus(200);
        $response->assertSee('Jadwal Saya');
        $response->assertSee('pasien1');
        $response->assertSee('Dr. Test');
        $response->assertSee('Konsultasi');
    }
}
