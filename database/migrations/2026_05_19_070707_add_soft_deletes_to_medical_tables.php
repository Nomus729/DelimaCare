<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Tambah soft deletes ke tabel data medis.
     * Data rekam medis dan resep medis tidak boleh hilang permanen.
     */
    public function up(): void
    {
        Schema::table('rekam_medis', function (Blueprint $table) {
            $table->softDeletes();
        });

        Schema::table('resep_medis', function (Blueprint $table) {
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::table('rekam_medis', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });

        Schema::table('resep_medis', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
    }
};
