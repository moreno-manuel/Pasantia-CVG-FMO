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
        Schema::create('switches', function (Blueprint $table) { //enlaces
            $table->string('serial')->primary();
            $table->string('model');
            $table->string('number_ports');
            $table->string('user_person'); // espacio fisico donde esta instalado
            $table->string('status');
            $table->string('description')->nullable();
            $table->timestamps();
        });

        Schema::create('links', function (Blueprint $table) { // enlaces
            $table->string('mac')->primary();
            $table->string('mark');
            $table->string('model');
            $table->string('name');
            $table->string('ssid');
            $table->string('location');
            $table->unsignedBigInteger('ip')->unique()->require();
            $table->string('description')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('switches');
        Schema::dropIfExists('links');
    }
};
