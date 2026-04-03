<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name', 'username', 'email', 'password',
        'role', 'alamat', 'nama_kerabat',
        'alamat_kerabat', 'no_kerabat', 'device_code',
        'google_id', 'fcm_token', 'kerabat_id',
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function device()
    {
        return $this->hasOne(Device::class, 'tunanetra_id');
    }

    public function journeys()
    {
        return $this->hasMany(Journey::class);
    }

    public function savedLocations()
    {
        return $this->hasMany(SavedLocation::class);
    }

    public function emergencyLogs()
    {
        return $this->hasMany(EmergencyLog::class);
    }

    public function kerabat()
    {
        return $this->belongsTo(User::class, 'kerabat_id');
    }

    public function notifikasiTerkirim()
    {
        return $this->hasMany(Notification::class, 'dari_user_id');
    }

    public function notifikasiDiterima()
    {
        return $this->hasMany(Notification::class, 'ke_user_id');
    }
}