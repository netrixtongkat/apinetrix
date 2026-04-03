<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Device extends Model
{
    protected $fillable = [
        'device_code', 'tunanetra_id', 'kerabat_id',
        'battery', 'connection_status', 'gps_status',
        'latitude', 'longitude', 'alamat_sekarang',
    ];

    public function tunanetra()
    {
        return $this->belongsTo(User::class, 'tunanetra_id');
    }

    public function kerabat()
    {
        return $this->belongsTo(User::class, 'kerabat_id');
    }
}