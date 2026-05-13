<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
       Schema::create('consultation_messages', function (Blueprint $table) {
        $table->id();
        $table->string('username'); // Wajib string karena isinya nama (fares, dsb)
        $table->string('sender')->default('user'); // user, admin, bot
        $table->string('type')->default('text');
        $table->text('message');
        $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('consultation_messages');
    }
};
