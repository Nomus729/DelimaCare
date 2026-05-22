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
        Schema::table('resep_medis', function (Blueprint $table) {
            $table->integer('biaya_dokter')->default(50000)->after('tanggal_resep');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('resep_medis', function (Blueprint $table) {
            $table->dropColumn('biaya_dokter');
        });
    }
};
