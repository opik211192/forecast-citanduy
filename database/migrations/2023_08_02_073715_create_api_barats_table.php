<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('api_barats', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('location');
            $table->dateTime('timestamp');
            $table->unsignedBigInteger('temp_min')->nullable();
            $table->unsignedBigInteger('temp_max')->nullable();
            $table->unsignedBigInteger('humidity_min')->nullable();
            $table->unsignedBigInteger('humidity_max')->nullable();
            $table->unsignedBigInteger('humidity')->nullable();
            $table->unsignedBigInteger('temperature')->nullable();
            $table->unsignedBigInteger('weather_code')->nullable();
            $table->string('wind_direction')->nullable();
            $table->unsignedBigInteger('wind_speed')->nullable();
            $table->string('provinsi');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('api_barats');
    }
};
