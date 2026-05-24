<?php

namespace Tests\Unit;

use App\Models\Doctor;
use App\Models\DoctorSchedule;
use App\Models\Reservasi;
use App\Services\ReservasiService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

/**
 * Unit Test untuk ReservasiService (post-refactor).
 *
 * Database: SQLite in-memory (dikonfigurasi di phpunit.xml).
 * RefreshDatabase: setiap test dijalankan dalam transaksi terpisah yang di-rollback.
 */
class ReservasiServiceTest extends TestCase
{
    use RefreshDatabase;

    private ReservasiService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new ReservasiService();
    }

    // =========================================================================
    // HELPER METHODS
    // =========================================================================

    /**
     * Buat dokter dummy. Jadwal_praktek string lama TIDAK digunakan lagi.
     * Jadwal kini dibuat via relasi schedules().
     */
    private function createDoctor(array $attrs = []): Doctor
    {
        return Doctor::create(array_merge([
            'nama'         => 'Dr. Test',
            'spesialisasi' => 'Umum',
            'status'       => 'Tersedia',
        ], $attrs));
    }

    /**
     * Buat jadwal untuk dokter pada hari tertentu.
     * Default: Senin–Jumat, 08:00–16:00.
     */
    private function createSchedule(Doctor $doctor, array $days = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat'], string $start = '08:00', string $end = '16:00'): void
    {
        foreach ($days as $day) {
            DoctorSchedule::create([
                'doctor_id'  => $doctor->id,
                'day_of_week' => $day,
                'start_time' => $start,
                'end_time'   => $end,
            ]);
        }
    }

    /**
     * Kembalikan string tanggal 'Y-m-d' untuk hari tertentu di minggu ini.
     * Contoh: nextWeekday('Monday') → '2026-05-25' jika sekarang minggu ini ada Senin tsb.
     */
    private function getDateForDay(string $englishDay): string
    {
        return date('Y-m-d', strtotime("this {$englishDay}"));
    }

    // =========================================================================
    // TEST SUITE: calculateQueue()
    // =========================================================================

    /** @test */
    public function it_returns_queue_number_1_when_no_reservations_exist(): void
    {
        $doctor = $this->createDoctor();

        // Buat jadwal untuk hari ini (ambil hari Inggris dari server)
        $todayEnglish = date('l'); // e.g., 'Saturday'
        $dayMap = [
            'Sunday' => 'Minggu', 'Monday' => 'Senin', 'Tuesday' => 'Selasa',
            'Wednesday' => 'Rabu', 'Thursday' => 'Kamis', 'Friday' => 'Jumat', 'Saturday' => 'Sabtu',
        ];
        $this->createSchedule($doctor, [$dayMap[$todayEnglish]], '08:00', '16:00');

        $result = $this->service->calculateQueue(date('Y-m-d'), $doctor);

        $this->assertEquals(1, $result['queue_number']);
        // Antrean pertama harus mulai dari jam buka dokter hari ini
        $this->assertEquals('08:00', $result['estimated_time']);
    }

    /** @test */
    public function it_uses_start_time_from_schedule_for_first_queue(): void
    {
        $doctor = $this->createDoctor();

        // Jadwal mulai jam 09:30, bukan 08:00
        $todayEnglish = date('l');
        $dayMap = [
            'Sunday' => 'Minggu', 'Monday' => 'Senin', 'Tuesday' => 'Selasa',
            'Wednesday' => 'Rabu', 'Thursday' => 'Kamis', 'Friday' => 'Jumat', 'Saturday' => 'Sabtu',
        ];
        $this->createSchedule($doctor, [$dayMap[$todayEnglish]], '09:30', '17:00');

        $result = $this->service->calculateQueue(date('Y-m-d'), $doctor);

        $this->assertEquals(1, $result['queue_number']);
        // Harus membaca start_time dari DB, bukan hardcode '08:00'
        $this->assertEquals('09:30', $result['estimated_time'],
            'calculateQueue() harus membaca start_time dari tabel doctor_schedules, bukan nilai default.');
    }

    /** @test */
    public function it_falls_back_to_default_time_when_no_schedule_exists(): void
    {
        // Dokter tanpa jadwal sama sekali — fallback harus '08:00'
        $doctor = $this->createDoctor();

        $result = $this->service->calculateQueue(date('Y-m-d'), $doctor);

        $this->assertEquals(1, $result['queue_number']);
        $this->assertEquals('08:00', $result['estimated_time'],
            'Harus fallback ke 08:00 jika tidak ada jadwal terdaftar untuk hari ini.');
    }

    /** @test */
    public function it_increments_queue_number_from_last_reservation(): void
    {
        $doctor = $this->createDoctor();
        $today  = date('Y-m-d');

        // Buat 3 reservasi yang sudah ada
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

        // Nomor antrean harus 4 (setelah 3 yang sudah ada)
        $this->assertEquals(4, $result['queue_number']);
    }

    /** @test */
    public function it_calculates_estimated_time_with_30_minute_gap(): void
    {
        $doctor = $this->createDoctor();
        $today  = date('Y-m-d');

        // Reservasi terakhir jam 09:30 → antrean baru harus 10:00
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

    // =========================================================================
    // TEST SUITE: checkDoctorAvailability()
    // =========================================================================

    /** @test */
    public function it_rejects_unavailable_doctor(): void
    {
        $doctor = $this->createDoctor(['status' => 'Libur']);

        $result = $this->service->checkDoctorAvailability($doctor, date('Y-m-d'), '08:00');

        $this->assertFalse($result['status']);
        $this->assertStringContainsString('Libur', $result['message']);
    }

    /** @test */
    public function it_accepts_valid_day_and_time(): void
    {
        $doctor = $this->createDoctor();
        // Pastikan ada jadwal untuk Senin
        $this->createSchedule($doctor, ['Senin'], '08:00', '16:00');

        // Cari tanggal Senin terdekat
        $monday = $this->getDateForDay('Monday');

        $result = $this->service->checkDoctorAvailability($doctor, $monday, '10:00');

        $this->assertTrue($result['status'],
            'Harus berhasil jika hari dan jam sesuai jadwal dokter.');
    }

    /** @test */
    public function it_rejects_wrong_day(): void
    {
        $doctor = $this->createDoctor();
        // Dokter hanya praktek Senin, bukan Sabtu
        $this->createSchedule($doctor, ['Senin'], '08:00', '16:00');

        // Cari tanggal Sabtu terdekat (tidak ada di jadwal)
        $saturday = $this->getDateForDay('Saturday');

        $result = $this->service->checkDoctorAvailability($doctor, $saturday, '10:00');

        $this->assertFalse($result['status'],
            'Harus ditolak jika hari tidak ada di jadwal dokter.');
        $this->assertStringContainsString('tidak praktek', $result['message']);
    }

    /** @test */
    public function it_rejects_time_before_schedule_start(): void
    {
        $doctor = $this->createDoctor();
        $this->createSchedule($doctor, ['Senin'], '08:00', '16:00');

        $monday = $this->getDateForDay('Monday');

        // Jam 07:30 — sebelum jam buka 08:00
        $result = $this->service->checkDoctorAvailability($doctor, $monday, '07:30');

        $this->assertFalse($result['status'],
            'Harus ditolak jika jam lebih awal dari start_time.');
        $this->assertStringContainsString('di luar jam praktek', $result['message']);
    }

    /** @test */
    public function it_rejects_time_after_schedule_end(): void
    {
        $doctor = $this->createDoctor();
        $this->createSchedule($doctor, ['Senin'], '08:00', '16:00');

        $monday = $this->getDateForDay('Monday');

        // Jam 17:00 — setelah jam tutup 16:00
        $result = $this->service->checkDoctorAvailability($doctor, $monday, '17:00');

        $this->assertFalse($result['status'],
            'Harus ditolak jika jam melewati end_time.');
        $this->assertStringContainsString('di luar jam praktek', $result['message']);
    }

    /** @test */
    public function it_accepts_time_exactly_at_schedule_boundaries(): void
    {
        $doctor = $this->createDoctor();
        $this->createSchedule($doctor, ['Senin'], '08:00', '16:00');

        $monday = $this->getDateForDay('Monday');

        // Tepat di jam buka
        $resultStart = $this->service->checkDoctorAvailability($doctor, $monday, '08:00');
        $this->assertTrue($resultStart['status'], 'Harus diterima tepat di jam buka.');

        // Tepat di jam tutup
        $resultEnd = $this->service->checkDoctorAvailability($doctor, $monday, '16:00');
        $this->assertTrue($resultEnd['status'], 'Harus diterima tepat di jam tutup.');
    }

    // =========================================================================
    // TEST SUITE: createReservasi() — Transaksi & Atomisitas
    // =========================================================================

    /** @test */
    public function it_creates_reservasi_with_correct_data(): void
    {
        $doctor = $this->createDoctor();

        $reservasi = $this->service->createReservasi([
            'user_id' => null,
            'nama'    => 'Pasien Baru',
            'phone'   => '081234567890',
            'layanan' => 'Konsultasi',
            'tanggal' => date('Y-m-d'),
        ], $doctor, 'Menunggu');

        // Verifikasi data tersimpan di database (transaksi berhasil commit)
        $this->assertDatabaseHas('reservasi', [
            'id'           => $reservasi->id,
            'nama'         => 'Pasien Baru',
            'doctor_id'    => $doctor->id,
            'queue_number' => 1,
            'status'       => 'Menunggu',
        ]);
    }

    /** @test */
    public function it_generates_sequential_queue_numbers_under_concurrent_like_calls(): void
    {
        // Mensimulasikan dua request yang berurutan (sequential test untuk logika queue).
        // Dalam SQLite in-memory (testing), lockForUpdate() dieksekusi tapi tidak
        // benar-benar memblokir karena SQLite hanya support serializable isolation.
        // Namun di MySQL/PostgreSQL production, lock ini aktif mencegah race condition.
        //
        // Test ini memverifikasi LOGIKA BISNIS: dua pemanggilan createReservasi()
        // pada hari yang sama harus menghasilkan queue_number yang berbeda (1 dan 2).
        $doctor = $this->createDoctor();
        $today  = date('Y-m-d');

        $reservasi1 = $this->service->createReservasi([
            'user_id' => null,
            'nama'    => 'Pasien Pertama',
            'phone'   => '081111111111',
            'layanan' => 'Konsultasi',
            'tanggal' => $today,
        ], $doctor);

        $reservasi2 = $this->service->createReservasi([
            'user_id' => null,
            'nama'    => 'Pasien Kedua',
            'phone'   => '082222222222',
            'layanan' => 'Konsultasi',
            'tanggal' => $today,
        ], $doctor);

        // Membuktikan tidak ada duplikasi nomor antrean
        $this->assertNotEquals(
            $reservasi1->queue_number,
            $reservasi2->queue_number,
            'Dua reservasi pada hari yang sama tidak boleh mendapat queue_number yang sama.'
        );
        $this->assertEquals(1, $reservasi1->queue_number);
        $this->assertEquals(2, $reservasi2->queue_number);

        // Pastikan estimasi waktu reservasi kedua 30 menit setelah yang pertama
        $time1 = new \DateTime($reservasi1->estimated_time);
        $time1->modify('+30 minutes');
        $this->assertEquals(
            $time1->format('H:i'),
            $reservasi2->estimated_time,
            'Estimasi waktu antrean kedua harus 30 menit setelah antrean pertama.'
        );
    }

    /** @test */
    public function it_rolls_back_on_exception_inside_transaction(): void
    {
        // Memverifikasi atomisitas: jika exception terjadi di dalam transaksi,
        // tidak ada data yang tersimpan ke database (rollback otomatis).
        $doctor = $this->createDoctor();

        // Hitung jumlah reservasi sebelum percobaan gagal
        $countBefore = Reservasi::count();

        try {
            DB::transaction(function () use ($doctor) {
                // Buat reservasi valid dulu...
                Reservasi::create([
                    'nama'           => 'Pasien Gagal',
                    'phone'          => '080000000000',
                    'layanan'        => 'Konsultasi',
                    'dokter_id'      => $doctor->nama,
                    'doctor_id'      => $doctor->id,
                    'tanggal'        => date('Y-m-d'),
                    'waktu'          => '08:00',
                    'queue_number'   => 99,
                    'estimated_time' => '08:00',
                    'status'         => 'Menunggu',
                ]);

                // ...lalu paksa exception terjadi di dalam transaksi yang sama
                throw new \RuntimeException('Simulasi error di tengah transaksi');
            });
        } catch (\RuntimeException) {
            // Exception ditangkap agar test tidak crash
        }

        // Jumlah reservasi harus sama persis — rollback berhasil
        $this->assertEquals(
            $countBefore,
            Reservasi::count(),
            'DB::transaction harus rollback semua perubahan jika exception ter-throw di dalam closure.'
        );
    }

    /** @test */
    public function it_assigns_correct_estimated_time_from_doctor_schedule(): void
    {
        $doctor = $this->createDoctor();
        $today  = date('Y-m-d');

        // Buat jadwal dengan jam buka 10:00
        $todayEnglish = date('l');
        $dayMap = [
            'Sunday' => 'Minggu', 'Monday' => 'Senin', 'Tuesday' => 'Selasa',
            'Wednesday' => 'Rabu', 'Thursday' => 'Kamis', 'Friday' => 'Jumat', 'Saturday' => 'Sabtu',
        ];
        $this->createSchedule($doctor, [$dayMap[$todayEnglish]], '10:00', '18:00');

        $reservasi = $this->service->createReservasi([
            'user_id' => null,
            'nama'    => 'Pasien Uji Jadwal',
            'phone'   => '089999999999',
            'layanan' => 'Konsultasi',
            'tanggal' => $today,
        ], $doctor);

        // Estimasi waktu harus dimulai dari jam 10:00 (bukan default 08:00)
        $this->assertEquals(
            '10:00',
            $reservasi->estimated_time,
            'createReservasi() harus menggunakan start_time jadwal dokter sebagai estimasi pertama.'
        );
    }
}
