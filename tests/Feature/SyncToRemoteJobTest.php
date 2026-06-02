<?php

namespace Tests\Feature;

use App\Jobs\SyncToRemoteJob;
use App\Models\Medicine;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Tests\TestCase;

class SyncToRemoteJobTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // 1. Mock the remote database connection to point to a separate in-memory SQLite database
        config([
            'database.connections.mysql_remote' => [
                'driver' => 'sqlite',
                'database' => ':memory:',
                'prefix' => '',
            ],
            'queue.sync_test_mode' => true,
        ]);

        // 2. Build the target table schema on the "remote" connection
        // We will create the remote "medicines" table but WITHOUT the "expired_at" column to test the filtering!
        Schema::connection('mysql_remote')->create('medicines', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('brand')->nullable();
            $table->string('category')->nullable();
            $table->integer('stock');
            $table->string('unit');
            $table->decimal('price', 10, 2);
            $table->integer('min_stock')->nullable();
            $table->timestamps();
        });
    }

    /** @test */
    public function it_filters_out_columns_that_do_not_exist_on_remote_database_during_sync()
    {
        // Local attributes containing "expired_at" (which does not exist in the remote medicines table we created)
        $localAttributes = [
            'id' => 99,
            'name' => 'Amoxicillin 500mg',
            'brand' => 'Kimia Farma',
            'category' => 'Antibiotik',
            'stock' => 100,
            'unit' => 'tablet',
            'price' => 1200,
            'min_stock' => 20,
            'expired_at' => '2026-12-31', // Exists locally but NOT on remote schema
        ];

        // Instantiate and run the job manually
        $job = new SyncToRemoteJob(Medicine::class, $localAttributes, 'create');
        $job->handle();

        // Assert that the record was successfully inserted into the remote database
        // and that it does not crash because of the expired_at column discrepancy.
        $this->assertDatabaseHas('medicines', [
            'id' => 99,
            'name' => 'Amoxicillin 500mg',
        ], 'mysql_remote');
    }
}
