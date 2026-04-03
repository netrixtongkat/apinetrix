<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('journeys', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('alamat_asal');
            $table->string('alamat_tujuan');
            $table->decimal('latitude_asal', 10, 8)->nullable();
            $table->decimal('longitude_asal', 11, 8)->nullable();
            $table->decimal('latitude_tujuan', 10, 8)->nullable();
            $table->decimal('longitude_tujuan', 11, 8)->nullable();
            $table->decimal('jarak_km', 8, 2)->nullable();
            $table->integer('durasi_menit')->nullable();
            $table->enum('status', ['berlangsung', 'selesai', 'dibatalkan'])->default('berlangsung');
            $table->timestamp('started_at')->nullable();
            $table->timestamp('ended_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('journeys');
    }
};