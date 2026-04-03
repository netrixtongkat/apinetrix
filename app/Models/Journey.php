<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Journey extends Model
{
    protected $fillable = [
        'user_id', 'alamat_asal', 'alamat_tujuan',
        'latitude_asal', 'longitude_asal',
        'latitude_tujuan', 'longitude_tujuan',
        'jarak_km', 'durasi_menit', 'status',
        'started_at', 'ended_at',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}