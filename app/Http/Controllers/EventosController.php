<?php

namespace App\Http\Controllers;

use App\Models\Area;
use App\Models\Eventos;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use RealRashid\SweetAlert\Facades\Alert;

class EventosController extends Controller
{
    public function eventosDia(String $dia)
    {
        $eventos = Eventos::where('fecha_inicio', $dia)->get();
        if (isset($eventos)) {
            $title = 'Delete User!';
            $text = "Are you sure you want to delete?";
            confirmDelete($title, $text);
            $tablaEventos = view('eventos.index', compact('eventos'));
            return response($tablaEventos, 200)->header('Content-Type', 'text/html');
        }
    }
    public function store(Request $request, $fecha)
    {
        $message = [
            'fecha_fin.0.after' => 'La fecha de finalización debe de ser déspues de la de inicio',
            'fecha_fin.0.required' => 'La fecha de finalización es obligatoria'
        ];
        $validator = Validator::make($request->all(), [
            "titulo"  => ['required'],
            'descripcion'  => 'required|max:255',
            'area' => ['required', 'exists:App\Models\Area,id'],
            'fecha_fin.0' => ['required', 'date', Rule::date()->afterOrEqual($fecha)],
        ], $message);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => implode("<br/>", $validator->messages()->all()),
            ]);
        } else {
            $evento = new Eventos();
            $evento->nombre = $request->titulo;
            $evento->descripcion = $request->descripcion;
            $evento->areas_id  = $request->area;
            $evento->user_id  = Auth::user()->id;
            $evento->fecha_inicio = $fecha;
            $evento->hora_inicio = $request->hora_inicio;
            $evento->fecha_fin =  $request->fecha_fin[0];
            $evento->hora_fin = $request->fecha_fin[1];
            $evento->notas_cta  = $request->notas_cta;
            $evento->notas_generales  = $request->notas_generales;
            $evento->save();
            return response()->json([
                'success' => true,
                'message' => 'Se registro con exito'
            ], 200);
        }
    }

    public function show($id)
    {
        $evento = Eventos::with('area')->where('id', $id)->first();
        if (!isset($evento)) {
            return response()->json([
                'success' => false,
                'message' => "No existe ese evento",
            ]);
        }
        $areas = Area::all();
        $evento = view('eventos.form', compact('evento', 'areas'));
        return response($evento, 200)->header('Content-Type', 'text/html');
    }

    public function destroy($id){
        $evento = Eventos::with('area')->where('id', $id)->first();
        if (!isset($evento)) {
            return response()->json([
                'success' => false,
                'message' => "No existe ese evento",
            ]);
        }
        $evento->delete();
        toast('Se elimino el evento','success');
        return redirect()->route('home');
    }
}
