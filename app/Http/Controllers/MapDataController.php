<?php

namespace App\Http\Controllers;

use App\Models\Marker;
use App\Models\Polygon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class MapDataController extends Controller
{
    public function index()
    {
        return view('interactive');
    }

    public function getMarkers()
    {
        try {
            $markers = Marker::all();
            return response()->json($markers);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error retrieving markers'], 500);
        }
    }

    public function getPolygons()
    {
        try {
            $polygons = Polygon::all();
            return response()->json($polygons);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error retrieving polygons'], 500);
        }
    }

    public function storeMarker(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255',
                'latitude' => 'required|numeric|between:-90,90',
                'longitude' => 'required|numeric|between:-180,180'
            ]);

            if ($validator->fails()) {
                return response()->json(['error' => $validator->errors()], 422);
            }

            $marker = Marker::create($validator->validated());
            return response()->json($marker, 201);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error creating marker'], 500);
        }
    }

    public function storePolygon(Request $request)
    {
        try {
            // Validasi input
            $validator = Validator::make($request->all(), [
                'coordinates' => 'required|array',
                'coordinates.*.lat' => 'required|numeric|between:-90,90',
                'coordinates.*.lng' => 'required|numeric|between:-180,180'
            ]);

            if ($validator->fails()) {
                return response()->json(['error' => $validator->errors()], 422);
            }

            // Simpan polygon baru
            $polygon = Polygon::create([
                'coordinates' => json_encode($request->coordinates) // Simpan sebagai JSON
            ]);

            return response()->json($polygon, 201);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error creating polygon'], 500);
        }
    }

    public function deleteMarker($id)
    {
        try {
            $marker = Marker::findOrFail($id);
            $marker->delete();
            return response()->json(['message' => 'Marker deleted successfully']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error deleting marker'], 500);
        }
    }

    public function deletePolygon($id)
    {
        try {
            $polygon = Polygon::findOrFail($id);
            $polygon->delete();
            return response()->json(['message' => 'Polygon deleted successfully']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error deleting polygon'], 500);
        }
    }
}
