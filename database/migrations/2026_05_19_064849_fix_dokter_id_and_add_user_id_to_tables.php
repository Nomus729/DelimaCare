<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Fix #1: Tambah doctor_id (integer FK) ke tabel reservasi
     * Fix #2: Tambah user_id (integer FK) ke tabel rekam_medis
     *
     * Strategi backward-compatible:
     * - Kolom `dokter_id` (string) lama TETAP ADA sebagai fallback untuk data existing
     * - Kolom `doctor_id` baru (integer) digunakan oleh code baru
     * - Kolom `user_id` baru pada rekam_medis untuk relasi yang lebih reliable
     */
    public function up(): void
    {
        // Fix #1: Tambah proper foreign key doctor_id ke reservasi
        Schema::table('reservasi', function (Blueprint $table) {
            $table->unsignedBigInteger('doctor_id')->nullable()->after('dokter_id');
        });

        // Migrasi data existing: lookup doctor by nama dari kolom dokter_id lama
        // Menggunakan DB::table (bukan Eloquent) agar tidak terkena SoftDeletes scope
        $reservasis = \DB::table('reservasi')->whereNotNull('dokter_id')->whereNull('doctor_id')->get();
        foreach ($reservasis as $reservasi) {
            $doctor = \DB::table('doctors')->where('nama', $reservasi->dokter_id)->first();
            if ($doctor) {
                \DB::table('reservasi')->where('id', $reservasi->id)->update(['doctor_id' => $doctor->id]);
            }
        }

        // Fix #2: Tambah user_id ke rekam_medis
        Schema::table('rekam_medis', function (Blueprint $table) {
            $table->unsignedBigInteger('user_id')->nullable()->after('reservasi_id');
        });

        // Migrasi data existing: lookup user by username dari kolom nama_pasien
        $rekamMedisList = \DB::table('rekam_medis')->whereNull('user_id')->get();
        foreach ($rekamMedisList as $rm) {
            $user = \DB::table('users')->where('username', $rm->nama_pasien)->first();
            if ($user) {
                \DB::table('rekam_medis')->where('id', $rm->id)->update(['user_id' => $user->id]);
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('reservasi', function (Blueprint $table) {
            $table->dropColumn('doctor_id');
        });

        Schema::table('rekam_medis', function (Blueprint $table) {
            $table->dropColumn('user_id');
        });
    }
};
