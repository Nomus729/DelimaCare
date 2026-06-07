<?php

namespace Tests\Feature;

use App\Models\Doctor;
use App\Models\Reservasi;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminQueueFilterTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;
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

        $this->doctor = Doctor::create([
            'nama'           => 'Dr. Test',
            'spesialisasi'   => 'Umum',
            'jadwal_praktek' => 'Senin - Minggu (08:00 - 20:00)',
            'status'         => 'Tersedia',
        ]);
    }

    /** @test */
    public function admin_can_filter_queue_by_status_and_date()
    {
        $match = Reservasi::create([
            'nama'           => 'Pasien Match',
            'phone'          => '081234567890',
            'layanan'        => 'Konsultasi',
            'doctor_id'      => $this->doctor->id,
            'dokter_id'      => $this->doctor->nama,
            'tanggal'        => today()->format('Y-m-d'),
            'waktu'          => '08:00',
            'queue_number'   => 1,
            'estimated_time' => '08:00',
            'status'         => 'selesai',
        ]);

        $wrongDate = Reservasi::create([
            'nama'           => 'Pasien Wrong Date',
            'phone'          => '081234567890',
            'layanan'        => 'Konsultasi',
            'doctor_id'      => $this->doctor->id,
            'dokter_id'      => $this->doctor->nama,
            'tanggal'        => today()->addDay()->format('Y-m-d'),
            'waktu'          => '08:20',
            'queue_number'   => 2,
            'estimated_time' => '08:20',
            'status'         => 'selesai',
        ]);

        $wrongStatus = Reservasi::create([
            'nama'           => 'Pasien Wrong Status',
            'phone'          => '081234567890',
            'layanan'        => 'Konsultasi',
            'doctor_id'      => $this->doctor->id,
            'dokter_id'      => $this->doctor->nama,
            'tanggal'        => today()->format('Y-m-d'),
            'waktu'          => '08:40',
            'queue_number'   => 3,
            'estimated_time' => '08:40',
            'status'         => 'Menunggu',
        ]);

        $response = $this->actingAs($this->admin)
            ->withHeaders(['HX-Request' => 'true'])
            ->get(route('admin.reservasi.partial', [
                'status' => 'selesai',
                'hari'   => 'hari_ini',
            ]));

        $response->assertStatus(200);
        $response->assertSee('Pasien Match');
        $response->assertDontSee('Pasien Wrong Date');
        $response->assertDontSee('Pasien Wrong Status');
    }

    /** @test */
    public function admin_gets_queue_ordered_chronologically_by_default()
    {
        $r1 = Reservasi::create([
            'nama'           => 'Urutan Kedua',
            'phone'          => '081234567890',
            'layanan'        => 'Konsultasi',
            'doctor_id'      => $this->doctor->id,
            'dokter_id'      => $this->doctor->nama,
            'tanggal'        => today()->addDay()->format('Y-m-d'),
            'waktu'          => '10:00',
            'queue_number'   => 2,
            'estimated_time' => '10:00',
            'status'         => 'Menunggu',
        ]);

        $r2 = Reservasi::create([
            'nama'           => 'Urutan Kesatu',
            'phone'          => '081234567890',
            'layanan'        => 'Konsultasi',
            'doctor_id'      => $this->doctor->id,
            'dokter_id'      => $this->doctor->nama,
            'tanggal'        => today()->addDay()->format('Y-m-d'),
            'waktu'          => '09:00',
            'queue_number'   => 1,
            'estimated_time' => '09:00',
            'status'         => 'Menunggu',
        ]);

        $r3 = Reservasi::create([
            'nama'           => 'Urutan Ketiga',
            'phone'          => '081234567890',
            'layanan'        => 'Konsultasi',
            'doctor_id'      => $this->doctor->id,
            'dokter_id'      => $this->doctor->nama,
            'tanggal'        => today()->addDays(2)->format('Y-m-d'),
            'waktu'          => '08:00',
            'queue_number'   => 1,
            'estimated_time' => '08:00',
            'status'         => 'Menunggu',
        ]);

        $response = $this->actingAs($this->admin)
            ->withHeaders(['HX-Request' => 'true'])
            ->get(route('admin.reservasi.partial'));

        $response->assertStatus(200);
        $response->assertSeeInOrder([
            'Urutan Kesatu',
            'Urutan Kedua',
            'Urutan Ketiga',
        ]);
    }
}
