<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('devices', function (Blueprint $table) {
            $table->id();
            $table->string('device_code')->unique();
            $table->foreignId('tunanetra_id')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('kerabat_id')->nullable()->constrained('users')->onDelete('set null');
            $table->integer('battery')->default(0);
            $table->enum('connection_status', ['terhubung', 'tidak_terhubung'])->default('tidak_terhubung');
            $table->enum('gps_status', ['aktif', 'tidak_aktif'])->default('tidak_aktif');
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();
            $table->string('alamat_sekarang')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('devices');
    }
};