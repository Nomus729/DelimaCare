<?php

namespace Tests\Unit;

use App\Models\Doctor;
use App\Models\DoctorSchedule;
use App\Services\ReservasiService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Suite Pengujian White Box: Basis Path Testing
 * 
 * Target Method : App\Services\ReservasiService::checkDoctorAvailability()
 * Kompleksitas  : V(G) = 6 (McCabe Cyclomatic Complexity)
 * Total Jalur   : 6 Jalur Linier Independen + Analisis Nilai Batas (BVA)
 */
class DoctorAvailabilityBasisPathTest extends TestCase
{
    use RefreshDatabase;

    private ReservasiService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new ReservasiService();
    }

    /**
     * Helper: Mendapatkan tanggal pasti untuk hari tertentu di minggu berjalan.
     */
    private function getTargetDate(string $englishDay): string
    {
        return date('Y-m-d', strtotime("this {$englishDay}"));
    }

    // =========================================================================
    // IMPLEMENTASI BASIS PATH TESTING (V(G) = 6)
    // =========================================================================

    /**
     * TC-WBT-01: Jalur 1 (Path 1: N1 -> N2 -> N13)
     * Menguji kondisi ketika status manual dokter BUKAN 'Tersedia' (misal: 'Libur').
     * Eksekusi langsung berhenti di Node 2 dan mengembalikan pesan error.
     */
    public function test_tc_wbt_01_path_1_doctor_status_not_tersedia_returns_error(): void
    {
        $doctor = Doctor::create([
            'nama'         => 'Dr. Siti Rahma',
            'spesialisasi' => 'Kandungan (Obsgyn)',
            'status'       => 'Libur',
        ]);

        $tanggal = $this->getTargetDate('Monday');
        $jam = '10:00';

        $result = $this->service->checkDoctorAvailability($doctor, $tanggal, $jam);

        $this->assertFalse($result['status'], 'Status harus false jika dokter sedang Libur.');
        $this->assertStringContainsString('Dokter sedang Libur', $result['message']);
    }

    /**
     * TC-WBT-02: Jalur 2 (Path 2: N1 -> N3 -> N4 -> N5 -> N6 -> N7 -> N13)
     * Menguji kondisi dokter 'Tersedia', tabel relasi DB jadwal kosong (N4 True),
     * fallback getParsedSchedule dipanggil (N5), namun string jadwal juga kosong (N6 True).
     * Eksekusi keluar di Node 7 dengan pesan dokter tidak praktek.
     */
    public function test_tc_wbt_02_path_2_no_schedule_in_db_and_fallback_fails_returns_error(): void
    {
        $doctor = Doctor::create([
            'nama'           => 'Dr. Siti Rahma',
            'spesialisasi'   => 'Kandungan (Obsgyn)',
            'status'         => 'Tersedia',
            'jadwal_praktek' => '', // Kosong agar fallback gagal
        ]);

        $tanggal = $this->getTargetDate('Sunday'); // Hari Minggu
        $jam = '10:00';

        $result = $this->service->checkDoctorAvailability($doctor, $tanggal, $jam);

        $this->assertFalse($result['status'], 'Status harus false jika jadwal tidak ditemukan.');
        $this->assertStringContainsString('tidak praktek di hari Minggu', $result['message']);
    }

    /**
     * TC-WBT-03: Jalur 3 (Path 3: N1 -> N3 -> N4 -> N5 -> N6 -> N8 -> N9 -> N11 -> N13)
     * Menguji kondisi dokter 'Tersedia', jadwal relasi DB kosong (N4 True),
     * fallback getParsedSchedule berhasil menemukan jadwal (N5 & N6 False),
     * namun jam pilihan lebih awal dari batas start_time (N9 True).
     * Eksekusi keluar di Node 11 dengan pesan jam di luar operasional.
     */
    public function test_tc_wbt_03_path_3_fallback_schedule_found_but_time_before_start(): void
    {
        $doctor = Doctor::create([
            'nama'           => 'Dr. Siti Rahma',
            'spesialisasi'   => 'Kandungan (Obsgyn)',
            'status'         => 'Tersedia',
            'jadwal_praktek' => 'Senin - Jumat (09:00 - 14:00)', // Valid untuk fallback parsing
        ]);

        $tanggal = $this->getTargetDate('Monday'); // Senin
        $jam = '08:30'; // 30 menit sebelum buka (09:00)

        $result = $this->service->checkDoctorAvailability($doctor, $tanggal, $jam);

        $this->assertFalse($result['status'], 'Status harus false jika jam mendahului jam mulai praktek.');
        $this->assertStringContainsString('hanya praktek dari jam 09:00 sampai 14:00', $result['message']);
    }

    /**
     * TC-WBT-04: Jalur 4 (Path 4: N1 -> N3 -> N4 -> N6 -> N8 -> N9 -> N11 -> N13)
     * Menguji kondisi dokter 'Tersedia', jadwal ditemukan langsung di relasi DB (N4 False),
     * namun jam pilihan lebih awal dari batas start_time (N9 True).
     * Eksekusi keluar di Node 11.
     */
    public function test_tc_wbt_04_path_4_db_schedule_found_but_time_before_start(): void
    {
        $doctor = Doctor::create([
            'nama'         => 'Dr. Siti Rahma',
            'spesialisasi' => 'Kandungan (Obsgyn)',
            'status'       => 'Tersedia',
        ]);

        DoctorSchedule::create([
            'doctor_id'   => $doctor->id,
            'day_of_week' => 'Senin',
            'start_time'  => '09:00:00',
            'end_time'    => '15:00:00',
        ]);

        $tanggal = $this->getTargetDate('Monday');
        $jam = '07:45'; // Lebih awal dari jam buka 09:00

        $result = $this->service->checkDoctorAvailability($doctor, $tanggal, $jam);

        $this->assertFalse($result['status'], 'Status harus false jika jam sebelum start_time.');
        $this->assertStringContainsString('hanya praktek dari jam 09:00 sampai 15:00', $result['message']);
    }

    /**
     * TC-WBT-05: Jalur 5 (Path 5: N1 -> N3 -> N4 -> N6 -> N8 -> N9 -> N10 -> N11 -> N13)
     * Menguji kondisi dokter 'Tersedia', jadwal relasi DB ada (N4 False),
     * jam >= start_time (N9 False), namun jam > end_time (N10 True).
     * Eksekusi keluar di Node 11.
     */
    public function test_tc_wbt_05_path_5_db_schedule_found_but_time_after_end(): void
    {
        $doctor = Doctor::create([
            'nama'         => 'Dr. Siti Rahma',
            'spesialisasi' => 'Kandungan (Obsgyn)',
            'status'       => 'Tersedia',
        ]);

        DoctorSchedule::create([
            'doctor_id'   => $doctor->id,
            'day_of_week' => 'Senin',
            'start_time'  => '09:00:00',
            'end_time'    => '15:00:00',
        ]);

        $tanggal = $this->getTargetDate('Monday');
        $jam = '16:15'; // Melewati batas tutup 15:00

        $result = $this->service->checkDoctorAvailability($doctor, $tanggal, $jam);

        $this->assertFalse($result['status'], 'Status harus false jika jam melewati end_time.');
        $this->assertStringContainsString('hanya praktek dari jam 09:00 sampai 15:00', $result['message']);
    }

    /**
     * TC-WBT-06: Jalur 6 (Path 6: N1 -> N3 -> N4 -> N6 -> N8 -> N9 -> N10 -> N12 -> N13)
     * Menguji kondisi sukses (Happy Path):
     * Dokter 'Tersedia', jadwal relasi DB ada (N4 False), jam >= start (N9 False),
     * dan jam <= end (N10 False). Eksekusi berhasil mencapai Node 12.
     */
    public function test_tc_wbt_06_path_6_db_schedule_found_and_time_valid_returns_success(): void
    {
        $doctor = Doctor::create([
            'nama'         => 'Dr. Siti Rahma',
            'spesialisasi' => 'Kandungan (Obsgyn)',
            'status'       => 'Tersedia',
        ]);

        DoctorSchedule::create([
            'doctor_id'   => $doctor->id,
            'day_of_week' => 'Senin',
            'start_time'  => '09:00:00',
            'end_time'    => '15:00:00',
        ]);

        $tanggal = $this->getTargetDate('Monday');
        $jam = '11:30'; // Berada di dalam rentang 09:00 - 15:00

        $result = $this->service->checkDoctorAvailability($doctor, $tanggal, $jam);

        $this->assertTrue($result['status'], 'Status harus true jika semua kondisi terpenuhi.');
        $this->assertArrayNotHasKey('message', $result, 'Tidak boleh ada pesan error pada hasil sukses.');
    }

    // =========================================================================
    // ANALISIS NILAI BATAS (BOUNDARY VALUE ANALYSIS)
    // =========================================================================

    /**
     * BVA-01: Jam Tepat di Batas Buka (09:00) -> Harus Diterima
     */
    public function test_bva_exact_start_time_is_accepted(): void
    {
        $doctor = Doctor::create([
            'nama' => 'Dr. Siti', 'spesialisasi' => 'Obsgyn', 'status' => 'Tersedia'
        ]);
        DoctorSchedule::create([
            'doctor_id' => $doctor->id, 'day_of_week' => 'Senin',
            'start_time' => '09:00:00', 'end_time' => '15:00:00',
        ]);

        $result = $this->service->checkDoctorAvailability($doctor, $this->getTargetDate('Monday'), '09:00');
        $this->assertTrue($result['status']);
    }

    /**
     * BVA-02: Jam Tepat di Batas Tutup (15:00) -> Harus Diterima
     */
    public function test_bva_exact_end_time_is_accepted(): void
    {
        $doctor = Doctor::create([
            'nama' => 'Dr. Siti', 'spesialisasi' => 'Obsgyn', 'status' => 'Tersedia'
        ]);
        DoctorSchedule::create([
            'doctor_id' => $doctor->id, 'day_of_week' => 'Senin',
            'start_time' => '09:00:00', 'end_time' => '15:00:00',
        ]);

        $result = $this->service->checkDoctorAvailability($doctor, $this->getTargetDate('Monday'), '15:00');
        $this->assertTrue($result['status']);
    }

    /**
     * BVA-03: 1 Menit Sebelum Buka (08:59) -> Harus Ditolak
     */
    public function test_bva_one_minute_before_start_is_rejected(): void
    {
        $doctor = Doctor::create([
            'nama' => 'Dr. Siti', 'spesialisasi' => 'Obsgyn', 'status' => 'Tersedia'
        ]);
        DoctorSchedule::create([
            'doctor_id' => $doctor->id, 'day_of_week' => 'Senin',
            'start_time' => '09:00:00', 'end_time' => '15:00:00',
        ]);

        $result = $this->service->checkDoctorAvailability($doctor, $this->getTargetDate('Monday'), '08:59');
        $this->assertFalse($result['status']);
    }

    /**
     * BVA-04: 1 Menit Setelah Tutup (15:01) -> Harus Ditolak
     */
    public function test_bva_one_minute_after_end_is_rejected(): void
    {
        $doctor = Doctor::create([
            'nama' => 'Dr. Siti', 'spesialisasi' => 'Obsgyn', 'status' => 'Tersedia'
        ]);
        DoctorSchedule::create([
            'doctor_id' => $doctor->id, 'day_of_week' => 'Senin',
            'start_time' => '09:00:00', 'end_time' => '15:00:00',
        ]);

        $result = $this->service->checkDoctorAvailability($doctor, $this->getTargetDate('Monday'), '15:01');
        $this->assertFalse($result['status']);
    }
}
