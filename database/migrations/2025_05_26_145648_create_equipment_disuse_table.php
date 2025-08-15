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
            $table->string('location');
            $table->string('equipment');
            $table->string('description');
            $table->timestamps();
        });

        Schema::create('switch_disuses', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreign('id')->references('id')->on('equipment_disuses')->onDelete('cascade');
            $table->string('number_ports');
            $table->timestamps();
        });

        Schema::create('link_disuses', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreign('id')->references('id')->on('equipment_disuses')->onDelete('cascade');
            $table->string('name');
            $table->string('ssid');
            $table->unsignedBigInteger('ip');
            $table->timestamps();
        });

        Schema::create('camera_disuses', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreign('id')->references('id')->on('equipment_disuses')->onDelete('cascade');
            $table->string('name');
            $table->string('nvr');
            $table->string('ip');
            $table->timestamps();
        });

        Schema::create('stock_equ_disuses', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreign('id')->references('id')->on('equipment_disuses')->onDelete('cascade');
            $table->string('delivery_note');
            $table->timestamps();
        });

        Schema::create('nvr_disuses', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreign('id')->references('id')->on('equipment_disuses')->onDelete('cascade');
            $table->string('name');
            $table->unsignedBigInteger('ip');
            $table->integer('ports_number');
            $table->integer('slot_number');
            $table->timestamps();
        });

        Schema::create('slot_nvr_disuses', function (Blueprint $table) {
            $table->id();
            $table->string('nvr_id');
            $table->foreign('nvr_id')->references('id')->on('nvr_disuses')->onDelete('cascade');
            $table->bigInteger('capacity_max');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stock_equ_disuses');
        Schema::dropIfExists('camera_disuses');
        Schema::dropIfExists('link_disuses');
        Schema::dropIfExists('nvr_disuses');
        Schema::dropIfExists('slot_nvr_disuses');
    }
};
