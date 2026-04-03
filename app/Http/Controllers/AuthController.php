<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Device;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Kreait\Laravel\Firebase\Facades\Firebase;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $request->validate([
            'name'           => 'required|string|max:255',
            'username'       => 'required|string|unique:users|max:255',
            'email'          => 'required|email|unique:users',
            'password'       => 'required|confirmed|min:6',
            'role'           => 'required|in:tunanetra,kerabat',
            'alamat'         => 'required|string',
            'nama_kerabat'   => 'required|string',
            'alamat_kerabat' => 'required|string',
            'no_kerabat'     => 'required|string',
            'device_code'    => 'required|string|exists:devices,device_code',
        ]);

        $device = Device::where('device_code', $request->device_code)->first();

        if ($request->role === 'tunanetra' && $device->tunanetra_id) {
            return response()->json(['message' => 'Tongkat ini sudah memiliki akun tunanetra'], 400);
        }
        if ($request->role === 'kerabat' && $device->kerabat_id) {
            return response()->json(['message' => 'Tongkat ini sudah memiliki akun kerabat'], 400);
        }

        $user = User::create([
            'name'           => $request->name,
            'username'       => $request->username,
            'email'          => $request->email,
            'password'       => Hash::make($request->password),
            'role'           => $request->role,
            'alamat'         => $request->alamat,
            'nama_kerabat'   => $request->nama_kerabat,
            'alamat_kerabat' => $request->alamat_kerabat,
            'no_kerabat'     => $request->no_kerabat,
            'device_code'    => $request->device_code,
        ]);

        if ($request->role === 'tunanetra') {
            $device->update(['tunanetra_id' => $user->id]);
        } else {
            $device->update(['kerabat_id' => $user->id]);
        }

        if ($device->tunanetra_id && $device->kerabat_id) {
            User::where('id', $device->tunanetra_id)->update(['kerabat_id' => $device->kerabat_id]);
            User::where('id', $device->kerabat_id)->update(['kerabat_id' => $device->tunanetra_id]);
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message' => 'Registrasi berhasil',
            'token'   => $token,
            'user'    => $user
        ], 201);
    }

    public function login(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        if (!Auth::attempt($request->only('email', 'password'))) {
            return response()->json(['message' => 'Email atau password salah'], 401);
        }

        $user  = Auth::user();
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message' => 'Login berhasil',
            'token'   => $token,
            'user'    => $user
        ]);
    }

    public function googleLogin(Request $request)
    {
        $request->validate([
            'id_token'       => 'required|string',
            'device_code'    => 'required|string|exists:devices,device_code',
            'role'           => 'required|in:tunanetra,kerabat',
            'nama_kerabat'   => 'required|string',
            'alamat_kerabat' => 'required|string',
            'no_kerabat'     => 'required|string',
            'alamat'         => 'required|string',
        ]);

        try {
            $auth = Firebase::auth();
            $verifiedToken = $auth->verifyIdToken($request->id_token);
            $uid   = $verifiedToken->claims()->get('sub');
            $email = $verifiedToken->claims()->get('email');
            $name  = $verifiedToken->claims()->get('name');

            $device = Device::where('device_code', $request->device_code)->first();

            $user = User::where('email', $email)->first();

            if (!$user) {
                if ($request->role === 'tunanetra' && $device->tunanetra_id) {
                    return response()->json(['message' => 'Tongkat ini sudah memiliki akun tunanetra'], 400);
                }
                if ($request->role === 'kerabat' && $device->kerabat_id) {
                    return response()->json(['message' => 'Tongkat ini sudah memiliki akun kerabat'], 400);
                }

                $user = User::create([
                    'name'           => $name,
                    'username'       => strtolower(str_replace(' ', '', $name)) . rand(100, 999),
                    'email'          => $email,
                    'password'       => Hash::make(uniqid()),
                    'google_id'      => $uid,
                    'role'           => $request->role,
                    'alamat'         => $request->alamat,
                    'nama_kerabat'   => $request->nama_kerabat,
                    'alamat_kerabat' => $request->alamat_kerabat,
                    'no_kerabat'     => $request->no_kerabat,
                    'device_code'    => $request->device_code,
                ]);

                if ($request->role === 'tunanetra') {
                    $device->update(['tunanetra_id' => $user->id]);
                } else {
                    $device->update(['kerabat_id' => $user->id]);
                }

                if ($device->tunanetra_id && $device->kerabat_id) {
                    User::where('id', $device->tunanetra_id)->update(['kerabat_id' => $device->kerabat_id]);
                    User::where('id', $device->kerabat_id)->update(['kerabat_id' => $device->tunanetra_id]);
                }
            }

            $token = $user->createToken('auth_token')->plainTextToken;

            return response()->json([
                'message' => 'Login Google berhasil',
                'token'   => $token,
                'user'    => $user,
            ]);

        } catch (\Exception $e) {
            return response()->json(['message' => 'Token Google tidak valid', 'error' => $e->getMessage()], 401);
        }
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return response()->json(['message' => 'Logout berhasil']);
    }

    public function profile(Request $request)
    {
        return response()->json($request->user());
    }

    public function updateFcmToken(Request $request)
    {
        $request->validate([
            'fcm_token' => 'required|string',
        ]);

        $request->user()->update([
            'fcm_token' => $request->fcm_token,
        ]);

        return response()->json(['message' => 'FCM token updated']);
    }
}