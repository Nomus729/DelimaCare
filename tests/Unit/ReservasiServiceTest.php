<?php

namespace Tests\Unit;

use App\Models\Doctor;
use App\Models\Reservasi;
use App\Services\ReservasiService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ReservasiServiceTest extends TestCase
{
    use RefreshDatabase;

    private ReservasiService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new ReservasiService();
    }

    /**
     * Helper: buat dokter dummy untuk testing.
     */
    private function createDoctor(array $attrs = []): Doctor
    {
        return Doctor::create(array_merge([
            'nama'           => 'Dr. Test',
            'spesialisasi'   => 'Umum',
            'jadwal_praktek' => 'Senin - Jumat (08:00 - 16:00)',
            'status'         => 'Tersedia',
        ], $attrs));
    }

    /** @test */
    public function it_returns_queue_number_1_when_no_reservations_exist()
    {
        $doctor = $this->createDoctor();
        $result = $this->service->calculateQueue(date('Y-m-d'), $doctor);

        $this->assertEquals(1, $result['queue_number']);
        $this->assertEquals('08:00', $result['estimated_time']);
    }

    /** @test */
    public function it_increments_queue_number_from_last_reservation()
    {
        $doctor = $this->createDoctor();
        $today = date('Y-m-d');

        // Buat 3 reservasi existing
        for ($i = 1; $i <= 3; $i++) {
            Reservasi::create([
                'nama'           => "Pasien {$i}",
                'phone'          => '08123456789',
                'layanan'        => 'Konsultasi',
                'dokter_id'      => $doctor->nama,
                'doctor_id'      => $doctor->id,
                'tanggal'        => $today,
                'waktu'          => '08:00',
                'queue_number'   => $i,
                'estimated_time' => ($i === 3) ? '09:00' : sprintf('08:%02d', ($i - 1) * 30),
                'status'         => 'Menunggu',
            ]);
        }

        $result = $this->service->calculateQueue($today, $doctor);

        $this->assertEquals(4, $result['queue_number']);
    }

    /** @test */
    public function it_calculates_estimated_time_with_30_minute_gap()
    {
        $doctor = $this->createDoctor();
        $today = date('Y-m-d');

        // Reservasi terakhir jam 09:30
        Reservasi::create([
            'nama'           => 'Pasien 1',
            'phone'          => '08123456789',
            'layanan'        => 'Konsultasi',
            'dokter_id'      => $doctor->nama,
            'doctor_id'      => $doctor->id,
            'tanggal'        => $today,
            'waktu'          => '09:30',
            'queue_number'   => 1,
            'estimated_time' => '09:30',
            'status'         => 'Menunggu',
        ]);

        $result = $this->service->calculateQueue($today, $doctor);

        $this->assertEquals('10:00', $result['estimated_time']);
    }

    /** @test */
    public function it_rejects_unavailable_doctor()
    {
        $doctor = $this->createDoctor(['status' => 'Libur']);

        $result = $this->service->checkDoctorAvailability($doctor, date('Y-m-d'), '08:00');

        $this->assertFalse($result['status']);
        $this->assertStringContainsString('Libur', $result['message']);
    }

    /** @test */
    public function it_creates_reservasi_with_correct_data()
    {
        $doctor = $this->createDoctor();

        $reservasi = $this->service->createReservasi([
            'user_id' => null,
            'nama'    => 'Pasien Baru',
            'phone'   => '081234567890',
            'layanan' => 'Konsultasi',
            'tanggal' => date('Y-m-d'),
        ], $doctor, 'Menunggu');

        $this->assertDatabaseHas('reservasi', [
            'id'           => $reservasi->id,
            'nama'         => 'Pasien Baru',
            'doctor_id'    => $doctor->id,
            'queue_number' => 1,
            'status'       => 'Menunggu',
        ]);
    }
}
