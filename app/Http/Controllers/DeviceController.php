<?php

namespace App\Http\Controllers;

use App\Models\Device;
use Illuminate\Http\Request;

class DeviceController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        $device = Device::where('device_code', $user->device_code)->first();
        return response()->json($device);
    }

    public function update(Request $request)
    {
        $user = $request->user();
        $device = Device::where('device_code', $user->device_code)->first();

        if (!$device) {
            return response()->json(['message' => 'Device tidak ditemukan'], 404);
        }

        $device->update([
            'battery'           => $request->battery,
            'connection_status' => $request->connection_status,
            'gps_status'        => $request->gps_status,
            'latitude'          => $request->latitude,
            'longitude'         => $request->longitude,
            'alamat_sekarang'   => $request->alamat_sekarang,
        ]);

        return response()->json(['message' => 'Device updated', 'device' => $device]);
    }
}