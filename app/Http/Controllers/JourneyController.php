<?php

namespace App\Http\Controllers;

use App\Models\Journey;
use Illuminate\Http\Request;

class JourneyController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        // Kalau kerabat, lihat journey tunanetra yang terhubung
        if ($user->role === 'kerabat' && $user->kerabat_id) {
            $journeys = Journey::where('user_id', $user->kerabat_id)
                ->orderBy('created_at', 'desc')
                ->get();
        } else {
            $journeys = Journey::where('user_id', $user->id)
                ->orderBy('created_at', 'desc')
                ->get();
        }

        return response()->json($journeys);
    }

    public function store(Request $request)
    {
        $request->validate([
            'alamat_asal'   => 'required|string',
            'alamat_tujuan' => 'required|string',
        ]);

        $journey = Journey::create([
            'user_id'          => $request->user()->id,
            'alamat_asal'      => $request->alamat_asal,
            'alamat_tujuan'    => $request->alamat_tujuan,
            'latitude_asal'    => $request->latitude_asal,
            'longitude_asal'   => $request->longitude_asal,
            'latitude_tujuan'  => $request->latitude_tujuan,
            'longitude_tujuan' => $request->longitude_tujuan,
            'jarak_km'         => $request->jarak_km,
            'durasi_menit'     => $request->durasi_menit,
            'status'           => 'berlangsung',
            'started_at'       => now(),
        ]);

        return response()->json(['message' => 'Journey dimulai', 'journey' => $journey], 201);
    }

    public function finish(Request $request, $id)
    {
        $journey = Journey::where('id', $id)
            ->where('user_id', $request->user()->id)
            ->firstOrFail();

        $journey->update([
            'status'   => 'selesai',
            'ended_at' => now(),
        ]);

        return response()->json(['message' => 'Journey selesai', 'journey' => $journey]);
    }
}