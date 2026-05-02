<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('doctors', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->string('spesialisasi');
            $table->enum('status', ['Tersedia', 'Istirahat', 'Libur'])->default('Tersedia');
            $table->string('phone')->nullable();
            $table->string('image')->nullable();
            $table->text('jadwal_praktek')->nullable(); // Misal: Senin - Jumat (08:00 - 16:00)
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('doctors');
    }
};
