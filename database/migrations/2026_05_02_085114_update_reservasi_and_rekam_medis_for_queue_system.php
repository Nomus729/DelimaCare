<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('reservasi', function (Blueprint $table) {
            $table->integer('queue_number')->nullable()->after('status');
            $table->time('estimated_time')->nullable()->after('queue_number');
        });

        Schema::table('rekam_medis', function (Blueprint $table) {
            $table->unsignedBigInteger('reservasi_id')->nullable()->after('id');
            $table->foreign('reservasi_id')->references('id')->on('reservasi')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('rekam_medis', function (Blueprint $table) {
            $table->dropForeign(['reservasi_id']);
            $table->dropColumn('reservasi_id');
        });

        Schema::table('reservasi', function (Blueprint $table) {
            $table->dropColumn(['queue_number', 'estimated_time']);
        });
    }
};
