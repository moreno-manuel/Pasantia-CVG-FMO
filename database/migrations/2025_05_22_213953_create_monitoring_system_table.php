<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('nvrs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('mac');
            $table->string('mark');
            $table->string('model');
            $table->string('name')->unique()->required();
            $table->unsignedBigInteger('ip')->unique()->required();
            $table->integer('ports_number');
            $table->integer('slot_number');
            $table->string('location');
            $table->string('status');
            $table->string('description')->nullable();
            $table->timestamps();
        });

        Schema::create('slot_nvrs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignId('nvr_id')
                ->constrained('nvrs')
                ->onDelete('cascade');
            $table->bigInteger('capacity_max');
            $table->string('hdd_serial')->nullable();
            $table->bigInteger('hdd_capacity')->nullable();
            $table->string('status');
            $table->timestamps();
        });

        Schema::create('cameras', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('mac');
            $table->foreignId('nvr_id')
                ->constrained('nvrs')
                ->onDelete('cascade');
            $table->string('mark');
            $table->string('model');
            $table->string('name')->unique()->required();
            $table->string('location');
            $table->unsignedBigInteger('ip')->unique()->required();
            $table->string('status');
            $table->string('description')->nullable();
            $table->timestamps();
        });

        Schema::create('condition_attentions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('camera_id')
                ->constrained('cameras')
                ->onDelete('cascade');
            $table->string('name');
            $table->date('date_ini');
            $table->date('date_end')->nullable();
            $table->string('description');
            $table->string('status');
            $table->timestamps();
        });

        Schema::create('control_conditions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('condition_attention_id')
                ->constrained('condition_attentions')
                ->onDelete('cascade');
            $table->string('text');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('nvrs');
        Schema::dropIfExists('slot_nvrs');
        Schema::dropIfExists('cameras');
        Schema::dropIfExists('condition_attentions');
        Schema::dropIfExists('control_conditions');
    }
};
