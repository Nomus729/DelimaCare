<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('rekam_medis', function (Blueprint $table) {
            $table->id();
            $table->string('no_rekam_medis')->unique();

            // Data pasien
            $table->string('nama_pasien');
            $table->integer('usia');
            $table->string('no_telepon')->nullable();
            $table->string('alamat')->nullable();
            $table->string('golongan_darah')->nullable();

            // Kategori layanan
            $table->enum('kategori', ['Kehamilan', 'Keluarga Berencana', 'Kontrol Umum', 'Konsultasi'])->default('Kontrol Umum');

            // Data khusus kehamilan
            $table->integer('usia_kehamilan_minggu')->nullable();
            $table->date('hpht')->nullable();
            $table->date('taksiran_persalinan')->nullable();

            // Status & kondisi
            $table->enum('status_risiko', ['Rendah', 'Sedang', 'Tinggi'])->default('Rendah');
            $table->enum('status_kunjungan', ['Aktif', 'Selesai', 'Dirujuk'])->default('Aktif');

            // Tanda vital
            $table->string('tekanan_darah')->nullable();
            $table->decimal('berat_badan', 5, 1)->nullable();
            $table->decimal('tinggi_badan', 5, 1)->nullable();

            // Catatan medis
            $table->text('catatan_medis')->nullable();
            $table->text('diagnosis')->nullable();
            $table->text('tindakan')->nullable();

            // Kunjungan
            $table->date('tanggal_kunjungan_terakhir')->nullable();
            $table->date('jadwal_kontrol_berikutnya')->nullable();
            $table->string('dokter_pemeriksa')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rekam_medis');
    }
};
