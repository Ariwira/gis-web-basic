<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Marker;
use App\Models\Polygon;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class PetaCRUDController extends Controller
{
    public function index()
    {
        return view('crud');
    }

    public function getListMarker(Request $request)
    {
        DB::statement('set @rownum = 0'); // Menggunakan string langsung
        $data = Marker::select([DB::raw('@rownum  := @rownum  + 1 AS no'), 'markers.*']);

        $datatables = Datatables::of($data);
        return $datatables
            ->filter(function($query) use($request) {
                $query->where('name', 'LIKE', '%' . $request->get('search')['value'] . '%');
            })
            ->addcolumn('action', function($data) {
                $btn = '<div class="btn-group">';
                $btn .= '<a target="_blank" href="'.url('/handson4/viewmaps/'.$data->id).'" title="Google API" class="btn btn-info btn-sm"><span data-toggle="tooltip" data-placement="top" title="View Maps">[view maps]</span></a>';
                $btn .= '<span class="btn btn-primary" onClick="edit_data(' . $data->id . ')" data-toggle="tooltip" data-placement="top" title="Edit Data"><i class="fa fa-edit"></i></span>';
                $btn .= '<span class="btn btn-danger" onClick="delete_data(' . $data->id . ')" data-toggle="tooltip" data-placement="top" title="Hapus Data"><i class="fa fa-times"></i></span>';
                $btn .= '</div>';
                return $btn;
            })
            ->rawColumns(['action'])
            ->addIndexColumn()
            ->make(true);
    }


    public function getListPolygon(Request $request)
    {
        DB::statement('set @rownum = 0'); // Gunakan string langsung di sini
        $data = Polygon::select([DB::raw('@rownum  := @rownum  + 1 AS no'), 'polygons.*']);

        $datatables = Datatables::of($data);
        return $datatables
            ->addColumn('action', function($data) {
                $btn = '<div class="btn-group">';
                $btn .= '<a target="_blank" href="'.url('/handson4/viewmaps/'.$data->id).'" title="Google API" class="btn btn-info btn-sm"><span data-toggle="tooltip" data-placement="top" title="View Maps">[view maps]</span></a>';
                $btn .= '<span class="btn btn-primary" onClick="edit_data('.$data->id.')" data-toggle="tooltip" data-placement="top" title="Edit Data"><i class="fa fa-edit"></i></span>';
                $btn .= '<span class="btn btn-danger" onClick="delete_data('.$data->id.')" data-toggle="tooltip" data-placement="top" title="Hapus Data"><i class="fa fa-times"></i></span>';
                $btn .= '</div>';
                return $btn;
            })
            ->rawColumns(['action'])
            ->addIndexColumn()
            ->make(true);
    }


    public function storeMarker(Request $request)
    {
        $marker = Marker::create($request->all());
        return response()->json($marker);
    }

    public function storePolygon(Request $request)
    {
        $polygon = Polygon::create([
            'coordinates' => json_encode($request->coordinates),
        ]);
        return response()->json($polygon);
    }

    public function viewmaps($id) {
        $marker = Marker::find($id);
        return view('_crud_view_maps', compact('marker'));
    }

    public function edit($id) {
        $marker = Marker::find($id);
        return view('edit_maps', compact('marker'));
    }

    public function updateMarker(Request $request, $id)
    {
        // Validasi input dari form
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
        ]);

        // Cari marker berdasarkan ID
        $marker = Marker::find($id);
        if (!$marker) {
            return redirect()->back()->withErrors(['msg' => 'Marker not found.']);
        }

        // Update data marker
        $marker->name = $validated['name'];
        $marker->latitude = $validated['latitude'];
        $marker->longitude = $validated['longitude'];
        $marker->save();

        // Redirect ke halaman sebelumnya dengan pesan sukses
        return redirect()->route('handson4.viewmaps', $id)->with('success', 'Marker updated successfully!');
    }


}
