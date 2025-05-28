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
        Schema::create('equipment_disuses', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->string('mark');
            $table->string('model');
            $table->date('date');
            $table->date('description');
            $table->timestamps();
        });

        Schema::create('switch_disuses', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->string('number_ports');
            $table->string('person_user');
            $table->timestamps();
        });

        Schema::create('link_disuses', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->string('name');
            $table->string('ssid');
            $table->string('location');
            $table->unsignedBigInteger('ip');
            $table->timestamps();
        });

        Schema::create('camera_disuses', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->string('name');
            $table->string('location');
            $table->string('ip');
            $table->timestamps();
        });

        Schema::create('nvr_disuses', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->string('name');
            $table->unsignedBigInteger('ip');
            $table->integer('number_port');
            $table->integer('number_hdd');
            $table->timestamps();
        });

        Schema::create('nvr_hdd_disuses', function (Blueprint $table) {
            $table->id();
            //clave foranea
            $table->string('nvr_id');
            $table->foreign('nvr_id')->references('id')->on('nvr_disuses');
            //
            $table->bigInteger('capacity_max');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('disused_equipments');
        Schema::dropIfExists('camera_disuses');
        Schema::dropIfExists('link_disuses');
        Schema::dropIfExists('nvr_disuses');
        Schema::dropIfExists('nvr_hdd_disuses');
    }
};
