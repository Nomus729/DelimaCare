<?php

namespace Tests\Feature;

use App\Models\Medicine;
use App\Models\User;
use App\Models\ResepMedis;
use App\Models\ResepMedisItem;
use App\Models\RekamMedis;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Carbon\Carbon;

class FinanceDashboardTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;
    private RekamMedis $rekamMedis;

    protected function setUp(): void
    {
        parent::setUp();

        // Create an admin user
        $this->admin = User::create([
            'username' => 'admin',
            'email'    => 'admin@test.com',
            'password' => bcrypt('password'),
            'role'     => 'admin',
        ]);

        // Create a rekam medis record needed for foreign keys
        $this->rekamMedis = RekamMedis::create([
            'no_rekam_medis' => 'RM-2026-0001',
            'nama_pasien'    => 'John Doe',
            'usia'           => 30,
            'kategori'       => 'Kontrol Umum',
            'status_risiko'  => 'Rendah',
            'status_kunjungan' => 'Aktif',
        ]);
    }

    /** @test */
    public function admin_can_access_finance_partial_endpoint()
    {
        $response = $this->actingAs($this->admin)
            ->withHeaders(['HX-Request' => 'true'])
            ->get(route('admin.keuangan.partial'));

        $response->assertStatus(200);
        $response->assertViewIs('admin.partials.keuangan');
        $response->assertViewHas([
            'chartKeuangan', 'pengeluaranList', 'kpiStats', 'donutChartData',
            'topMedicines', 'topDoctors', 'topExpenses', 'summaryTable',
            'selectedMonth', 'selectedYear', 'availableYears'
        ]);
    }

    /** @test */
    public function non_admin_cannot_access_finance_partial_endpoint()
    {
        $pasien = User::create([
            'username' => 'pasien',
            'email'    => 'pasien@test.com',
            'password' => bcrypt('password'),
            'role'     => 'pasien',
        ]);

        $response = $this->actingAs($pasien)
            ->withHeaders(['HX-Request' => 'true'])
            ->get(route('admin.keuangan.partial'));

        $response->assertStatus(302);
    }

    /** @test */
    public function finance_dashboard_filters_data_by_month_and_year()
    {
        // 1. Create a medicine
        $medicine = Medicine::create([
            'name' => 'Paracetamol',
            'stock' => 100,
            'price' => 5000,
            'category' => 'Generik',
            'is_critical' => false,
        ]);

        // 2. Create some transactions in different months
        // Target: May 2026
        $targetResep = ResepMedis::create([
            'no_resep' => 'RX-2026-0001',
            'rekam_medis_id' => $this->rekamMedis->id,
            'nama_pasien' => 'John Doe',
            'dokter_pemeriksa' => 'Dr. Jane Smith',
            'tanggal_resep' => Carbon::create(2026, 5, 10),
            'biaya_dokter' => 50000,
            'status' => 'Selesai',
        ]);
        ResepMedisItem::create([
            'resep_medis_id' => $targetResep->id,
            'medicine_id' => $medicine->id,
            'nama_obat' => 'Paracetamol',
            'satuan' => 'tablet',
            'jumlah' => 2,
        ]);

        // Other: June 2026
        $otherResep = ResepMedis::create([
            'no_resep' => 'RX-2026-0002',
            'rekam_medis_id' => $this->rekamMedis->id,
            'nama_pasien' => 'Alice',
            'dokter_pemeriksa' => 'Dr. Bob',
            'tanggal_resep' => Carbon::create(2026, 6, 15),
            'biaya_dokter' => 75000,
            'status' => 'Selesai',
        ]);
        ResepMedisItem::create([
            'resep_medis_id' => $otherResep->id,
            'medicine_id' => $medicine->id,
            'nama_obat' => 'Paracetamol',
            'satuan' => 'tablet',
            'jumlah' => 4,
        ]);

        // Request with filter for May 2026 (fin_month=5, fin_year=2026)
        $response = $this->actingAs($this->admin)
            ->withHeaders(['HX-Request' => 'true'])
            ->get(route('admin.keuangan.partial', [
                'fin_month' => 5,
                'fin_year' => 2026,
            ]));

        $response->assertStatus(200);

        // Verify the filtered variables
        $kpiStats = $response->viewData('kpiStats');
        // Jasa dokter in May 2026 should be 50000
        $this->assertEquals(50000, $kpiStats['revIni'] - $kpiStats['labaIni']);
        // Medicine sales in May 2026 should be 2 * 5000 = 10000
        $this->assertEquals(10000, $kpiStats['labaIni']);

        // Check if the paginated pengeluaranList only has the target resep
        $list = $response->viewData('pengeluaranList');
        $this->assertCount(1, $list);
        $this->assertEquals('RX-2026-0001', $list[0]->no_resep);
    }

    /** @test */
    public function finance_dashboard_provides_accurate_available_years()
    {
        // Create transactions in 2024 and 2025
        ResepMedis::create([
            'no_resep' => 'RX-2024-0001',
            'rekam_medis_id' => $this->rekamMedis->id,
            'nama_pasien' => 'Patient A',
            'dokter_pemeriksa' => 'Dr. A',
            'tanggal_resep' => Carbon::create(2024, 1, 1),
            'biaya_dokter' => 10000,
            'status' => 'Selesai',
        ]);

        ResepMedis::create([
            'no_resep' => 'RX-2025-0001',
            'rekam_medis_id' => $this->rekamMedis->id,
            'nama_pasien' => 'Patient B',
            'dokter_pemeriksa' => 'Dr. B',
            'tanggal_resep' => Carbon::create(2025, 1, 1),
            'biaya_dokter' => 10000,
            'status' => 'Selesai',
        ]);

        $response = $this->actingAs($this->admin)
            ->withHeaders(['HX-Request' => 'true'])
            ->get(route('admin.keuangan.partial'));

        $availableYears = $response->viewData('availableYears');
        
        // Should contain 2024, 2025, and the current year (which is 2026 in the current timestamp context)
        $this->assertContains(2024, $availableYears);
        $this->assertContains(2025, $availableYears);
        $this->assertContains(now()->year, $availableYears);
    }
}
