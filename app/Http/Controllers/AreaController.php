<?php

namespace App\Http\Controllers;

use App\Models\Area;
use Illuminate\Http\Request;

class AreaController extends Controller
{
    public function listado()
    {
        $areas = Area::orderBy('nombre')->get();
        if (isset($areas)) {
            $selectAreas = view('areas.select', compact('areas'))->render();
            return response($selectAreas, 200)->header('Content-Type', 'text/html');
        }
    }
    public function index()
    {
        $areas = Area::all();
        return view('areas.admin', compact('areas'));
    }


    public function store(Request $request)
    {
        Area::create([
            'sede' => $request->sede,
            'nombre' => $request->nombre,
            'edificio' => $request->edificio,
            'color' => $request->color
        ]);
        return redirect()->route('areas.index')->with([
            'success' => true,
            'message' => 'Se registro de forma exitosa el área'
        ]);
    }

    public function update(Request $request, $id)
    {
        $area = Area::find($id);
        if (!isset($area)) {
            return response()->json([
                'error' => 'Not Found',
                'message' => 'No se encontro el área que ingresaste'
            ], 403);
        }

        $area->update([
            'nombre' => $request->nombre,
            'sede' => $request->sede,
            'edificio' => $request->edificio,
            'color' => $request->color
        ]);
        return redirect()->route('areas.index')->with([
            'success' => true,
            'message' => 'Se actualizo de forma exitosa el área'
        ]);
    }
}
