<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('resep_medis', function (Blueprint $table) {
            $table->id();
            $table->string('no_resep')->unique();
            $table->foreignId('rekam_medis_id')->constrained('rekam_medis')->cascadeOnDelete();
            $table->string('nama_pasien');
            $table->string('dokter_pemeriksa')->nullable();
            $table->date('tanggal_resep');
            $table->text('catatan_apoteker')->nullable();
            $table->enum('status', ['Pending', 'Diproses', 'Selesai'])->default('Pending');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('resep_medis');
    }
};
