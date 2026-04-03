<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->enum('role', ['tunanetra', 'kerabat'])->after('id');
            $table->string('username')->unique()->after('name');
            $table->string('alamat')->after('email');
            $table->string('nama_kerabat')->after('alamat');
            $table->string('alamat_kerabat')->after('nama_kerabat');
            $table->string('no_kerabat')->after('alamat_kerabat');
            $table->string('device_code')->after('no_kerabat');
            $table->string('google_id')->nullable()->after('device_code');
            $table->string('fcm_token')->nullable()->after('google_id');
            $table->foreignId('kerabat_id')->nullable()->constrained('users')->onDelete('set null')->after('fcm_token');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['kerabat_id']);
            $table->dropColumn([
                'role', 'username', 'alamat',
                'nama_kerabat', 'alamat_kerabat',
                'no_kerabat', 'device_code',
                'google_id', 'fcm_token', 'kerabat_id'
            ]);
        });
    }
};