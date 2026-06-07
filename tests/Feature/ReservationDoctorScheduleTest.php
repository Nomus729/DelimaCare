<?php

namespace Tests\Feature;

use App\Models\Doctor;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ReservationDoctorScheduleTest extends TestCase
{
    use RefreshDatabase;

    protected $doctor;
    protected $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->doctor = Doctor::create([
            'nama' => 'Dr. B',
            'spesialisasi' => 'Anak',
            'status' => 'Tersedia',
            'jadwal_praktek' => 'Senin - Jumat (08:00 - 14:00)',
        ]);
        
        $this->user = User::create([
            'username' => 'PasienTester',
            'email' => 'pasien@test.com',
            'password' => bcrypt('password'),
            'role' => 'pasien',
        ]);
    }

    public function test_cannot_book_outside_doctor_schedule()
    {
        // Dokter praktek 08:00 - 14:00. Kita coba booking 15:00.
        $response = $this->actingAs($this->user)->post(route('reservasi.store'), [
            'dokter_id' => $this->doctor->id,
            'phone'     => '081234567890',
            'layanan'   => 'Pemeriksaan Umum',
            'tanggal'   => now()->next('Monday')->format('Y-m-d'),
            'waktu'     => '15:00',
            'keluhan'   => '',
        ]);

        $response->assertSessionHas('error', 'Maaf, Dr. B hanya praktek dari jam 08:00 sampai 14:00 di hari Senin.');
        $this->assertDatabaseMissing('reservasi', [
            'doctor_id' => $this->doctor->id,
            'waktu'     => '15:00',
        ]);
    }

    public function test_can_book_within_doctor_schedule()
    {
        // Dokter praktek 08:00 - 14:00. Kita coba booking 10:00.
        $response = $this->actingAs($this->user)->post(route('reservasi.store'), [
            'dokter_id' => $this->doctor->id,
            'phone'     => '081234567890',
            'layanan'   => 'Pemeriksaan Umum',
            'tanggal'   => now()->next('Monday')->format('Y-m-d'),
            'waktu'     => '10:00',
            'keluhan'   => '',
        ]);

        $response->assertSessionHas('success');
        $this->assertDatabaseHas('reservasi', [
            'doctor_id' => $this->doctor->id,
            'waktu'     => '10:00',
        ]);
    }
}
