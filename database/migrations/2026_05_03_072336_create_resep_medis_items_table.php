<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('resep_medis_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('resep_medis_id')->constrained('resep_medis')->cascadeOnDelete();
            $table->foreignId('medicine_id')->constrained('medicines')->restrictOnDelete();
            $table->string('nama_obat');   // snapshot nama saat resep dibuat
            $table->string('satuan');
            $table->integer('jumlah');
            $table->string('aturan_pakai')->nullable(); // "3x1 sehari sesudah makan"
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('resep_medis_items');
    }
};
