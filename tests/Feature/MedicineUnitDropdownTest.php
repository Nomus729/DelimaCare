<?php

namespace Tests\Feature;

use App\Models\Medicine;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MedicineUnitDropdownTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::create([
            'username' => 'admin',
            'email'    => 'admin@test.com',
            'password' => bcrypt('password'),
            'role'     => 'admin',
        ]);
    }

    /** @test */
    public function index_partial_renders_fixed_unit_select_dropdown()
    {
        $response = $this->actingAs($this->admin)
            ->get(route('admin.inventori.partial'));

        $response->assertStatus(200);
        $response->assertViewIs('admin.partials.inventori');

        // Check that <select name="unit"> is present with options
        $response->assertSee('<select name="unit"', false);
        $response->assertSee('<option value="pcs">pcs</option>', false);
        $response->assertSee('<option value="tablet">tablet</option>', false);
        $response->assertSee('<option value="botol">botol</option>', false);

        // Check that the old input structure is gone
        $response->assertDontSee('placeholder="pcs, tablet, botol"', false);
    }

    /** @test */
    public function admin_can_create_medicine_with_unit_from_dropdown()
    {
        $response = $this->actingAs($this->admin)
            ->post(route('admin.medicines.store'), [
                'name'      => 'Asam Folat 400mg',
                'brand'     => 'PT Pharma Indo',
                'category'  => 'Vitamin',
                'stock'     => 10,
                'unit'      => 'tablet',
                'price'     => 500,
                'min_stock' => 5,
            ]);

        $response->assertRedirect();
        
        $this->assertDatabaseHas('medicines', [
            'name' => 'Asam Folat 400mg',
            'unit' => 'tablet',
        ]);
    }

    /** @test */
    public function admin_can_update_medicine_with_unit_from_dropdown()
    {
        $medicine = Medicine::create([
            'name'      => 'Aspirin',
            'brand'     => 'Bayer',
            'category'  => 'Analgesik',
            'stock'     => 20,
            'unit'      => 'tablet',
            'price'     => 1500,
            'min_stock' => 5,
        ]);

        $response = $this->actingAs($this->admin)
            ->put(route('admin.medicines.update', $medicine->id), [
                'name'      => 'Aspirin',
                'brand'     => 'Bayer',
                'category'  => 'Analgesik',
                'stock'     => 20,
                'unit'      => 'botol', // Change to botol
                'price'     => 1500,
                'min_stock' => 5,
            ]);

        $response->assertRedirect();

        $this->assertDatabaseHas('medicines', [
            'id'   => $medicine->id,
            'unit' => 'botol',
        ]);
    }
}
