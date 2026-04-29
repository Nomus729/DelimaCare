<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MedicineSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\Medicine::create([
            'name' => 'Asam Folat 400mg',
            'brand' => 'PT Pharma Indo',
            'category' => 'Vitamin',
            'stock' => 45,
            'unit' => 'tablet',
            'price' => 500,
            'min_stock' => 10,
        ]);

        \App\Models\Medicine::create([
            'name' => 'IUD Copper T',
            'brand' => 'PT Alkes Medika',
            'category' => 'Alat KB',
            'stock' => 5,
            'unit' => 'pcs',
            'price' => 150000,
            'min_stock' => 10,
        ]);

        \App\Models\Medicine::create([
            'name' => 'Amoxicillin 500mg',
            'brand' => 'Kimia Farma',
            'category' => 'Antibiotik',
            'stock' => 100,
            'unit' => 'tablet',
            'price' => 1200,
            'min_stock' => 20,
        ]);
    }
}
