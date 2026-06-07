<?php

namespace Tests\Feature;

use App\Models\Doctor;
use App\Models\Reservasi;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ReservationRaceConditionTest extends TestCase
{
    use RefreshDatabase;

    protected $doctor;
    protected $user;

    protected function setUp(): void
    {
        parent::setUp();
        // Buat data master
        $this->doctor = Doctor::create([
            'nama' => 'Dr. A',
            'spesialisasi' => 'Umum',
            'status' => 'Tersedia',
            'jadwal_praktek' => 'Senin - Jumat (08:00 - 14:00)',
        ]);
        
        $this->user = User::create([
            'username' => 'TestUser1',
            'email' => 'test1@example.com',
            'password' => bcrypt('password'),
            'role' => 'pasien',
        ]);
    }

    public function test_can_create_reservation_successfully()
    {
        $response = $this->actingAs($this->user)->post(route('reservasi.store'), [
            'dokter_id' => $this->doctor->id,
            'phone'     => '081234567890',
            'layanan'   => 'Pemeriksaan Umum',
            'tanggal'   => now()->next('Monday')->format('Y-m-d'),
            'waktu'     => '09:00',
            'keluhan'   => 'Sakit kepala',
        ]);

        $response->assertSessionHas('success');
        $this->assertDatabaseHas('reservasi', [
            'doctor_id' => $this->doctor->id,
            'tanggal'   => now()->next('Monday')->format('Y-m-d'),
            'waktu'     => '09:00',
        ]);
    }

    public function test_prevents_race_condition_double_booking()
    {
        $date = now()->next('Monday')->format('Y-m-d');
        
        // 1. Buat reservasi pertama (seakan-akan sudah masuk ke database lebih dulu)
        Reservasi::create([
            'user_id' => $this->user->id,
            'nama'    => $this->user->username ?? 'Test User',
            'phone'   => '081234567890',
            'layanan' => 'Pemeriksaan Umum',
            'doctor_id' => $this->doctor->id,
            'dokter_id' => $this->doctor->nama,
            'tanggal' => $date,
            'waktu'   => '10:00',
            'queue_number' => 1,
            'status'  => 'Menunggu',
        ]);

        // 2. User kedua mencoba booking di waktu yang sama persis
        $user2 = User::create([
            'username' => 'TestUser2',
            'email' => 'test2@example.com',
            'password' => bcrypt('password'),
            'role' => 'pasien',
        ]);
        
        $response = $this->actingAs($user2)->post(route('reservasi.store'), [
            'dokter_id' => $this->doctor->id,
            'phone'     => '081234567899',
            'layanan'   => 'Konsultasi',
            'tanggal'   => $date,
            'waktu'     => '10:00', // WAKTU SAMA PERSIS!
            'keluhan'   => '',
        ]);

        // 3. Pastikan gagal dan mendapatkan session error dari exception catch kita
        $response->assertSessionHas('error', 'Maaf, slot waktu ini baru saja dipesan oleh pasien lain. Silakan pilih waktu lain.');
        
        // 4. Pastikan di database tetap hanya ada 1 reservasi di waktu tersebut untuk dokter tersebut
        $count = Reservasi::where('doctor_id', $this->doctor->id)
            ->where('tanggal', $date)
            ->where('waktu', '10:00')
            ->count();
            
        $this->assertEquals(1, $count);
    }
}
