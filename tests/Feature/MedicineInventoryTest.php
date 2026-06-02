<?php

namespace Tests\Feature;

use App\Models\Medicine;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MedicineInventoryTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;

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
    }

    /** @test */
    public function admin_can_access_inventory_partial_endpoint()
    {
        $response = $this->actingAs($this->admin)
            ->get(route('admin.inventori.partial'));

        $response->assertStatus(200);
        $response->assertViewIs('admin.partials.inventori');
        $response->assertViewHas('medicines');
    }

    /** @test */
    public function non_admin_cannot_access_inventory_partial_endpoint()
    {
        $pasien = User::create([
            'username' => 'pasien',
            'email'    => 'pasien@test.com',
            'password' => bcrypt('password'),
            'role'     => 'pasien',
        ]);

        $response = $this->actingAs($pasien)
            ->get(route('admin.inventori.partial'));

        $response->assertStatus(302); // Should redirect (e.g. to home or dashboard)
    }

    /** @test */
    public function inventory_paginates_exactly_20_items_per_page()
    {
        // Seed 25 medicines
        for ($i = 1; $i <= 25; $i++) {
            Medicine::create([
                'name'      => 'Obat ' . sprintf('%02d', $i),
                'brand'     => 'Brand A',
                'stock'     => 50,
                'unit'      => 'tablet',
                'price'     => 5000,
                'min_stock' => 10,
            ]);
        }

        // Fetch page 1
        $response = $this->actingAs($this->admin)
            ->get(route('admin.inventori.partial', ['page' => 1]));

        $response->assertStatus(200);
        $medicinesPage1 = $response->viewData('medicines');
        $this->assertCount(20, $medicinesPage1);
        $this->assertEquals(25, $response->viewData('totalCount'));

        // Fetch page 2
        $response2 = $this->actingAs($this->admin)
            ->get(route('admin.inventori.partial', ['page' => 2]));

        $response2->assertStatus(200);
        $medicinesPage2 = $response2->viewData('medicines');
        $this->assertCount(5, $medicinesPage2);
    }

    /** @test */
    public function inventory_statistics_are_calculated_correctly_with_search_filter()
    {
        // Seed some data
        // 5 low stock (stock <= min_stock), 3 out of stock (stock = 0), and some other data
        Medicine::create(['name' => 'Obat A', 'stock' => 0, 'min_stock' => 5, 'unit' => 'pcs', 'price' => 1000]); // habis
        Medicine::create(['name' => 'Obat B', 'stock' => 3, 'min_stock' => 5, 'unit' => 'pcs', 'price' => 1000]); // menipis
        Medicine::create(['name' => 'Obat C', 'stock' => 10, 'min_stock' => 5, 'unit' => 'pcs', 'price' => 1000]); // cukup
        Medicine::create(['name' => 'Paracetamol', 'stock' => 0, 'min_stock' => 10, 'unit' => 'pcs', 'price' => 1000]); // habis, matches 'Para'

        // Search for 'Obat'
        $response = $this->actingAs($this->admin)
            ->get(route('admin.inventori.partial', ['med_search' => 'Obat']));

        $response->assertStatus(200);
        $this->assertEquals(3, $response->viewData('totalCount')); // Obat A, B, C
        $this->assertEquals(1, $response->viewData('menipisCount')); // Obat B (stock > 0 and stock <= min_stock)
        $this->assertEquals(1, $response->viewData('habisCount')); // Obat A (stock <= 0)

        // Search for 'Para'
        $responsePara = $this->actingAs($this->admin)
            ->get(route('admin.inventori.partial', ['med_search' => 'Para']));

        $responsePara->assertStatus(200);
        $this->assertEquals(1, $responsePara->viewData('totalCount'));
        $this->assertEquals(1, $responsePara->viewData('habisCount'));
        $this->assertEquals(0, $responsePara->viewData('menipisCount'));
    }

    /** @test */
    public function html_response_contains_pagination_links_when_items_exceed_20()
    {
        // Seed 21 medicines
        for ($i = 1; $i <= 21; $i++) {
            Medicine::create([
                'name'      => 'Obat ' . $i,
                'brand'     => 'Brand',
                'stock'     => 10,
                'unit'      => 'pcs',
                'price'     => 1000,
                'min_stock' => 5,
            ]);
        }

        $response = $this->actingAs($this->admin)
            ->get(route('admin.inventori.partial'));

        $response->assertStatus(200);
        
        // Assert the presence of pagination navigation elements
        $response->assertSee('nav role="navigation"', false);
        $response->assertSee('page=2', false);
    }
}
