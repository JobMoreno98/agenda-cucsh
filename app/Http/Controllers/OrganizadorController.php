<?php

namespace App\Http\Controllers;

use App\Models\Area;
use App\Models\Organizador;
use Illuminate\Http\Request;

class OrganizadorController extends Controller
{
    public function index()
    {
        $organizadores = Organizador::all();
        $areas = Area::where('tipo', 1)->orderBy('nombre')->get();
        return view('organizadores.index', compact('organizadores', 'areas'));
    }
    public function store(Request $request)
    {
        Organizador::create([
            'nombre' => $request->nombre,
            'areas_id' => $request->area_id,
            'contacto' => $request->contacto
        ]);
        return redirect()->route('organizadores.index');
    }
    public function update(Request $request, $id)
    {
        $organizador = Organizador::find($id);
        if (!isset($organizador)) {
            return response()->json([
                'error' => 403,
                'message' => 'No se econtro ese organziador'
            ]);
        }
        $organizador->update([
            'nombre' => $request->nombre,
            'areas_id' => $request->area_id,
            'contacto' => $request->contacto
        ]);
        return redirect()->route('organizadores.index');
    }
    public function listado()
    {
        $organizadores = Organizador::orderBy('nombre')->get();
        if (isset($organizadores)) {
            $selectOrganizadores = view('organizadores.select', compact('organizadores'))->render();
            return response($selectOrganizadores, 200)->header('Content-Type', 'text/html');
        }
    }
}
