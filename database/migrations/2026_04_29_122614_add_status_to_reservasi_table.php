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
            // Nambahin kolom status dengan nilai default 'Menunggu'
            $table->string('status')->default('Menunggu')->after('keluhan');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('reservasi', function (Blueprint $table) {
            // Hapus kolom status kalau misal abang nge-rollback
            $table->dropColumn('status');
        });
    }
};
