<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOfferThingsTable extends Migration
{
    public function up()
    {
        Schema::create('offer_things', function (Blueprint $table) {
            $table->id();
            $table->foreignId('offer_id')->constrained('offers')->onDelete('cascade');
            $table->foreignId('thing_id')->constrained('things')->onDelete('cascade');
            $table->boolean('is_offered');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('offer_things');
    }
}
