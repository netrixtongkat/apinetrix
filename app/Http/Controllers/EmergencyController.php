<?php

namespace App\Http\Controllers;

use App\Models\EmergencyLog;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Http\Request;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\Notification as FcmNotification;
use Kreait\Laravel\Firebase\Facades\Firebase;

class EmergencyController extends Controller
{
    public function trigger(Request $request)
    {
        $user = $request->user();

        $log = EmergencyLog::create([
            'user_id'         => $user->id,
            'latitude'        => $request->latitude,
            'longitude'       => $request->longitude,
            'alamat_kejadian' => $request->alamat_kejadian,
            'status'          => 'terkirim',
        ]);

        if ($user->kerabat_id) {
            Notification::create([
                'dari_user_id'    => $user->id,
                'ke_user_id'      => $user->kerabat_id,
                'judul'           => 'SOS Alert!',
                'pesan'           => $user->name . ' membutuhkan bantuan segera!',
                'latitude'        => $request->latitude,
                'longitude'       => $request->longitude,
                'alamat_kejadian' => $request->alamat_kejadian,
                'tipe'            => 'emergency',
            ]);

            // Kirim FCM push notification ke HP kerabat
            $kerabat = User::find($user->kerabat_id);
            if ($kerabat && $kerabat->fcm_token) {
                try {
                    $messaging = Firebase::messaging();
                    $message = CloudMessage::withTarget('token', $kerabat->fcm_token)
                        ->withNotification(FcmNotification::create(
                            'SOS Alert! 🚨',
                            $user->name . ' membutuhkan bantuan segera!'
                        ))
                        ->withData([
                            'type'            => 'emergency',
                            'user_id'         => (string) $user->id,
                            'latitude'        => (string) $request->latitude,
                            'longitude'       => (string) $request->longitude,
                            'alamat_kejadian' => $request->alamat_kejadian ?? '',
                        ]);

                    $messaging->send($message);
                } catch (\Exception $e) {
                    // FCM gagal tidak perlu return error
                    // Notifikasi database tetap tersimpan
                }
            }
        }

        return response()->json(['message' => 'Emergency alert terkirim', 'log' => $log], 201);
    }

    public function logs(Request $request)
    {
        $logs = EmergencyLog::where('user_id', $request->user()->id)
            ->orderBy('created_at', 'desc')
            ->get();
        return response()->json($logs);
    }
}