<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Buat tabel doctor_schedules untuk menggantikan kolom jadwal_praktek (string).
     *
     * Setiap row merepresentasikan SATU hari praktek dokter.
     * Contoh: dokter praktek Senin & Rabu → 2 row terpisah.
     * Ini memungkinkan jadwal non-kontigu dan query langsung via database.
     */
    public function up(): void
    {
        Schema::create('doctor_schedules', function (Blueprint $table) {
            $table->id();

            // Foreign key ke tabel doctors dengan cascade delete:
            // jika dokter dihapus, semua jadwalnya ikut terhapus otomatis.
            $table->foreignId('doctor_id')
                  ->constrained('doctors')
                  ->onDelete('cascade');

            // Enum hari — menggunakan nama Bahasa Indonesia agar konsisten
            // dengan data yang sudah ada di sistem.
            $table->enum('day_of_week', [
                'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'
            ]);

            // Tipe TIME di database — memungkinkan perbandingan langsung:
            // WHERE '09:30' BETWEEN start_time AND end_time
            $table->time('start_time');
            $table->time('end_time');

            $table->timestamps();

            // Index komposit untuk mempercepat query paling umum:
            // "ambil jadwal dokter X di hari Y"
            $table->index(['doctor_id', 'day_of_week']);
        });
    }

    /**
     * Hapus tabel doctor_schedules saat rollback.
     */
    public function down(): void
    {
        Schema::dropIfExists('doctor_schedules');
    }
};
