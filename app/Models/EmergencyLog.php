<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmergencyLog extends Model
{
    protected $fillable = [
        'user_id', 'latitude', 'longitude',
        'alamat_kejadian', 'status', 'responded_at',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}