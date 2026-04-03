<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return response()->json([
        'name'    => 'Tongkat Netrix API',
        'version' => '1.0.0',
        'status'  => 'running',
        'endpoints' => [
            'POST /api/register',
            'POST /api/login',
            'POST /api/auth/google',
            'GET  /api/profile',
            'GET  /api/device',
            'POST /api/device',
            'GET  /api/journeys',
            'POST /api/journeys',
            'POST /api/emergency',
            'GET  /api/notifications',
        ]
    ]);
});