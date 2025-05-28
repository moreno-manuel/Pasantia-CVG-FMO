<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('nvrs', function (Blueprint $table) {
            $table->string('mac')->primary();
            $table->string('mark');
            $table->string('model');
            $table->string('name')->unique()->required();
            $table->unsignedBigInteger('ip')->unique()->required();
            $table->integer('number_ports');
            $table->integer('number_hdd');
            $table->string('status');
            $table->string('description')->nullable();
            $table->timestamps();
        });

        Schema::create('hdd_nvrs', function (Blueprint $table) {
            $table->id();
            $table->string('nvr_id');
            $table->foreign('nvr_id')->references('mac')->on('nvrs')->onDelete('cascade');
            $table->string('serial_hdd')->nullable();
            $table->bigInteger('capacity_hdd')->nullable();
            $table->bigInteger('capacity_max');
            $table->string('status');
            $table->timestamps();
        });

        Schema::create('cameras', function (Blueprint $table) {
            $table->string('mac')->primary();
            $table->string('nvr_id');
            $table->foreign('nvr_id')->references('mac')->on('nvrs')->onDelete('restrict');
            $table->string('mark');
            $table->string('model');
            $table->string('name')->unique()->required();
            $table->unsignedBigInteger('ip')->unique()->required();
            $table->string('location');
            $table->string('status');
            $table->string('description')->nullable();
            $table->timestamps();
        });

        Schema::create('condition_attentions', function (Blueprint $table) {
            $table->id();
            $table->string('camera_id');
            $table->foreign('camera_id')->references('mac')->on('cameras')->onDelete('cascade');
            $table->string('name');
            $table->date('date_ini');
            $table->date('date_end')->nullable();
            $table->string('status');
            $table->string('description')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('nvrs');
        Schema::dropIfExists('hdd_nvrs');
        Schema::dropIfExists('cameras');
        Schema::dropIfExists('condition_attentions');
    }
};
