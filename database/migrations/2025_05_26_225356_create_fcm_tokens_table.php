<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('fcm_tokens', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('token', 191)->unique();
            $table->string('device_name')->nullable();
            $table->string('platform')->nullable(); // e.g., 'android', 'ios', 'web'
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('fcm_tokens');
    }
};
