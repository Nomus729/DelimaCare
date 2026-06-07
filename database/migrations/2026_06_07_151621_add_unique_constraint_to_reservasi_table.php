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
        Schema::table('reservasi', function (Blueprint $table) {
            // Kita pakai tipe doctor_id yang FK integer. Namun saat ini dokter_id masih ada (string).
            // Berdasarkan ReservasiService, data baru menyimpan ke doctor_id.
            $table->unique(['doctor_id', 'tanggal', 'waktu'], 'unique_reservasi_slot');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('reservasi', function (Blueprint $table) {
            $table->dropUnique('unique_reservasi_slot');
        });
    }
};
