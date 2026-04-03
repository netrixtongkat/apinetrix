<?php

namespace App\Http\Controllers;

use App\Models\SavedLocation;
use Illuminate\Http\Request;

class SavedLocationController extends Controller
{
    public function index(Request $request)
    {
        $locations = SavedLocation::where('user_id', $request->user()->id)->get();
        return response()->json($locations);
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_lokasi' => 'required|string',
            'alamat'      => 'required|string',
        ]);

        $location = SavedLocation::create([
            'user_id'     => $request->user()->id,
            'nama_lokasi' => $request->nama_lokasi,
            'alamat'      => $request->alamat,
            'latitude'    => $request->latitude,
            'longitude'   => $request->longitude,
        ]);

        return response()->json(['message' => 'Lokasi disimpan', 'location' => $location], 201);
    }

    public function destroy(Request $request, $id)
    {
        $location = SavedLocation::where('id', $id)
            ->where('user_id', $request->user()->id)
            ->firstOrFail();

        $location->delete();
        return response()->json(['message' => 'Lokasi dihapus']);
    }
}