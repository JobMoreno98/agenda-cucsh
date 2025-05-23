<?php

namespace App\Http\Controllers;

use App\Enums\Tipo;
use App\Models\Area;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Enum;

class AreaController extends Controller
{
    public function listado()
    {
        $areas = Area::orderBy('nombre')->where('tipo','!=','1')->get();
        if (isset($areas)) {
            $selectAreas = view('areas.select', compact('areas'))->render();
            return response($selectAreas, 200)->header('Content-Type', 'text/html');
        }
    }
    public function index()
    {
        $areas = Area::paginate(10);
        return view('areas.admin', compact('areas'));
    }


    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nombre' => ['required', 'string'],
            'sede' => ['required'],
            'edificio' => ['required'],
            'color' => ['required'],
            'tipo' => ['required', new Enum(Tipo::class)]
        ]);

        if ($validator->fails()) {
            return redirect()->route('areas.index')->with([
                'error' => true,
                'message' => implode("<br/>", $validator->messages()->all()),
            ]);
        }

        Area::create([
            'sede' => $request->sede,
            'nombre' => $request->nombre,
            'edificio' => $request->edificio,
            'color' => $request->color,
            'tipo' => $request->tipo
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

        $validator = Validator::make($request->all(), [
            'nombre' => ['required', 'string',  Rule::unique('areas')->ignore($area->id)],
            'sede' => ['required'],
            'edificio' => ['required'],
            'color' => ['required'],
            'tipo' => ['required', new Enum(Tipo::class)]
        ]);

        if ($validator->fails()) {
            return redirect()->route('areas.index')->with([
                'error' => true,
                'message' => implode("<br/>", $validator->messages()->all()),
            ]);
        }
        $area->update([
            'nombre' => $request->nombre,
            'sede' => $request->sede,
            'edificio' => $request->edificio,
            'color' => $request->color,
            'tipo' => $request->tipo
        ]);
        return redirect()->route('areas.index')->with([
            'success' => true,
            'message' => 'Se actualizo de forma exitosa el área'
        ]);
    }
}
