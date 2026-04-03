<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    protected $fillable = [
        'dari_user_id', 'ke_user_id', 'judul', 'pesan',
        'latitude', 'longitude', 'alamat_kejadian',
        'tipe', 'is_read', 'read_at',
    ];

    public function dariUser()
    {
        return $this->belongsTo(User::class, 'dari_user_id');
    }

    public function keUser()
    {
        return $this->belongsTo(User::class, 'ke_user_id');
    }
}