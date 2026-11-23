<?php

namespace Tests\Feature;

use App\Models\Medicine;
use App\Models\RekamMedis;
use App\Models\ResepMedis;
use App\Models\ResepMedisItem;
use App\Models\Reservasi;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Suite Pengujian Feature: Modul Laporan & Analitik (ReportController)
 * 
 * Target Endpoint: GET /admin/report/stats
 * Target View    : resources/views/admin/partials/laporan.blade.php
 */
class AdminReportAnalyticsTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;
    private User $patient;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::create([
            'username' => 'admin_laporan',
            'email'    => 'admin_laporan@delimacare.id',
            'password' => bcrypt('password123'),
            'role'     => 'admin',
        ]);

        $this->patient = User::create([
            'username' => 'pasien_laporan',
            'email'    => 'pasien_laporan@gmail.com',
            'password' => bcrypt('password123'),
            'role'     => 'pasien',
        ]);
    }

    /**
     * Test Hak Akses: Tamu (Guest) tidak bisa mengakses data laporan analitik.
     */
    public function test_guest_cannot_access_report_stats(): void
    {
        $response = $this->getJson(route('admin.report.stats'));
        // Guest dialihkan ke login
        $response->assertStatus(401);
    }

    /**
     * Test Hak Akses: Pasien ditolak oleh middleware EnsureIsAdmin.
     */
    public function test_patient_cannot_access_report_stats(): void
    {
        $response = $this->actingAs($this->patient)->get(route('admin.report.stats'));
        // Pasien di-redirect ke /portal dengan flash error
        $response->assertRedirect(route('portal'));
    }

    /**
     * Test Struktur Data JSON: Admin menerima seluruh properti yang dibutuhkan statCards & chart.
     */
    public function test_admin_receives_complete_analytics_json_structure(): void
    {
        $response = $this->actingAs($this->admin)->getJson(route('admin.report.stats'));

        $response->assertStatus(200)
                 ->assertJsonStructure([
                     'totalPasien',
                     'pasienBaru',
                     'totalHamil',
                     'totalKB',
                     'totalKunjungan',
                     'table',
                     'chartLabels',
                     'chartData',
                 ]);
    }

    /**
     * Test Filter Bulanan: Hanya menampilkan 1 baris rincian di tabel untuk bulan terpilih.
     */
    public function test_monthly_filter_returns_single_table_row(): void
    {
        $response = $this->actingAs($this->admin)->getJson(route('admin.report.stats', [
            'type'  => 'bulanan',
            'month' => 5, // Mei
            'year'  => 2026,
        ]));

        $response->assertStatus(200);
        $data = $response->json();

        $this->assertCount(1, $data['table'], 'Filter bulanan harus mengembalikan tepat 1 baris tabel.');
        $this->assertEquals('Mei', $data['table'][0]['month']);
        $this->assertCount(12, $data['chartLabels'], 'Chart tren selalu menampilkan 12 bulan.');
    }

    /**
     * Test Filter Tahunan: Menampilkan ringkasan 12 bulan penuh di tabel.
     */
    public function test_yearly_filter_returns_twelve_table_rows(): void
    {
        $response = $this->actingAs($this->admin)->getJson(route('admin.report.stats', [
            'type' => 'tahunan',
            'year' => 2026,
        ]));

        $response->assertStatus(200);
        $data = $response->json();

        $this->assertCount(12, $data['table'], 'Filter tahunan harus mengembalikan 12 baris tabel.');
    }

    /**
     * Test Sinkronisasi Pendapatan: Memverifikasi kalkulasi total pendapatan (Jasa Dokter + Obat).
     */
    public function test_income_calculation_accurately_sums_doctor_fees_and_medicine_prices(): void
    {
        $tahun = 2026;
        $bulan = 5; // Mei

        // 1. Setup Rekam Medis (diperlukan karena resep_medis.rekam_medis_id NOT NULL)
        $rekamMedis = RekamMedis::create([
            'no_rekam_medis' => 'RM-2026-0001',
            'nama_pasien'    => 'Ibu Ani',
            'usia'           => 28,
            'kategori'       => 'Kehamilan',
        ]);

        // 2. Setup Obat di Inventori
        $obat = Medicine::create([
            'name'      => 'Asam Folat 400mcg',
            'brand'     => 'Folavit',
            'category'  => 'Vitamin',
            'stock'     => 100,
            'unit'      => 'Tablet',
            'price'     => 5000,
            'min_stock' => 10,
        ]);

        // 3. Setup Resep Medis pada bulan Mei 2026
        $resep = ResepMedis::create([
            'no_resep'         => 'RX-2026-9999',
            'rekam_medis_id'   => $rekamMedis->id,
            'nama_pasien'      => 'Ibu Ani',
            'dokter_pemeriksa' => 'Dr. Siti',
            'tanggal_resep'    => "{$tahun}-05-15",
            'biaya_dokter'     => 50000, // Biaya jasa dokter
            'status'           => 'Selesai',
        ]);

        // 3. Tambahkan 10 butir obat (10 * 5000 = 50.000)
        ResepMedisItem::create([
            'resep_medis_id' => $resep->id,
            'medicine_id'    => $obat->id,
            'nama_obat'      => $obat->name,
            'satuan'         => $obat->unit,
            'jumlah'         => 10,
        ]);

        // Total pendapatan yang diharapkan: 50.000 (jasa) + 50.000 (obat) = 100.000
        $response = $this->actingAs($this->admin)->getJson(route('admin.report.stats', [
            'type'  => 'bulanan',
            'month' => $bulan,
            'year'  => $tahun,
        ]));

        $response->assertStatus(200);
        $data = $response->json();

        // Di ReportController: number_format($income, 0, ',', '.') -> "100.000"
        $this->assertEquals('100.000', $data['table'][0]['income']);
    }
}
