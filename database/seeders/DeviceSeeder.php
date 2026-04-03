<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Device;

class DeviceSeeder extends Seeder
{
    public function run(): void
    {
        $devices = [
            ['device_code' => 'TN-001'],
            ['device_code' => 'TN-002'],
            ['device_code' => 'TN-003'],
            ['device_code' => 'TN-004'],
            ['device_code' => 'TN-005'],
        ];

        foreach ($devices as $device) {
            Device::create($device);
        }
    }
}