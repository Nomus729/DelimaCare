<?php

namespace Tests\Feature;

use App\Models\Medicine;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MedicineSuggestionsTest extends TestCase
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
    public function admin_can_access_inventory_partial_endpoint_and_receive_suggestions()
    {
        // 1. Seed medicines with duplicate and unique brands/categories
        Medicine::create([
            'name'      => 'Aspirin',
            'brand'     => 'Bayer',
            'category'  => 'Analgesik',
            'stock'     => 10,
            'unit'      => 'tablet',
            'price'     => 1500,
            'min_stock' => 5,
        ]);

        Medicine::create([
            'name'      => 'Ibuprofen',
            'brand'     => 'Bayer', // duplicate brand
            'category'  => 'Analgesik', // duplicate category
            'stock'     => 20,
            'unit'      => 'tablet',
            'price'     => 2000,
            'min_stock' => 5,
        ]);

        Medicine::create([
            'name'      => 'Amoxicillin',
            'brand'     => 'Kalbe', // new brand
            'category'  => 'Antibiotik', // new category
            'stock'     => 30,
            'unit'      => 'tablet',
            'price'     => 3000,
            'min_stock' => 5,
        ]);

        // Empty/null values to ensure they are ignored
        Medicine::create([
            'name'      => 'Unknown Medicine',
            'brand'     => '',
            'category'  => null,
            'stock'     => 5,
            'unit'      => 'tablet',
            'price'     => 500,
            'min_stock' => 2,
        ]);

        // 2. Perform request
        $response = $this->actingAs($this->admin)
            ->get(route('admin.inventori.partial'));

        $response->assertStatus(200);
        $response->assertViewIs('admin.partials.inventori');
        
        $response->assertViewHas('suggestedBrands');
        $response->assertViewHas('suggestedCategories');

        $brands = $response->viewData('suggestedBrands');
        $categories = $response->viewData('suggestedCategories');

        // 3. Assertions on data
        // Bayer and Kalbe, sorted alphabetically -> Bayer, Kalbe
        $this->assertEquals(['Bayer', 'Kalbe'], $brands);
        // Analgesik and Antibiotik, sorted alphabetically -> Analgesik, Antibiotik
        $this->assertEquals(['Analgesik', 'Antibiotik'], $categories);

        // 4. Assert HTML content
        $response->assertSee('list="brand-suggestions"', false);
        $response->assertSee('<datalist id="brand-suggestions">', false);
        $response->assertSee('<option value="Bayer">', false);
        $response->assertSee('<option value="Kalbe">', false);
    }
}
