<?php

namespace App\Http\Controllers;

use App\Models\Area;
use App\Models\Eventos;
use App\Models\Organizador;
use App\Rules\HorasValida;
use App\Rules\HoraValida;
use DateTime;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use RealRashid\SweetAlert\Facades\Alert;
use Throwable;

class EventosController extends Controller
{
    protected $message = [
        'fecha_fin.0.after' => 'La fecha de finalización debe de ser déspues de la de inicio',
        'fecha_fin.0.required' => 'La fecha de finalización es obligatoria',
        'fecha_fin.1.after' => 'La hora de fin debe de ser déspues de la hora de inicio',
        'hora_inicio.before' => 'La hora de inicio debe de ser antes de la hora de fin',
        'organizador_id.exists' => 'Debe de escoger un organozador que exista',
        'organizador_id.required' => 'El organizador es obligatorio'
    ];

    protected  $horaMinima = '07:00:00';
    protected $horaMaxima = '20:00:00';

    public function eventosDia(String $dia)
    {
        $eventos = Eventos::where('fecha_inicio', '<=', $dia)->where('fecha_fin', '>=', $dia)->get();
        if (isset($eventos)) {
            $tablaEventos = view('eventos.index', compact('eventos'));
            return response($tablaEventos, 200)->header('Content-Type', 'text/html');
        }
    }
    public function store(Request $request, $fecha)
    {

       // return $request->all();

        $validator = Validator::make($request->all(), [
            "titulo"  => ['required'],
            'descripcion'  => 'required|max:255',
            'area_id' => ['required', 'exists:App\Models\Area,id'],
            'fecha_fin.0' => ['required', 'date', Rule::date()->afterOrEqual($fecha)],
            'hora_inicio' => ['required', 'before:fecha_fin.1', new HorasValida($this->horaMinima, $this->horaMaxima,'store')],
            'fecha_fin.1' => ['required', 'after:hora_inicio', new HorasValida($this->horaMinima, $this->horaMaxima,'store')],
            'organizador_id' => ['required', 'exists:App\Models\Organizador,id']
        ], $this->message);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => implode("<br/>", $validator->messages()->all()),
            ]);
        }
        $whereParams = [
            'fecha_inicio' => $fecha,
            'fecha_fin' => $request->fecha_fin[0],
            'hora_inicio' => $request->hora_inicio,
            'hora_fin' => $request->fecha_fin[1]
        ];

        $eventos =  Eventos::where([['areas_id', $request->area]])
            ->where(function (Builder $query) use ($whereParams) {
                $query->where(function ($query) use ($whereParams) {
                    // Caso donde el evento nuevo empieza antes de que el evento actual termine y termina después de que el evento actual empiece
                    $query->where('fecha_inicio', '<=', $whereParams['fecha_fin'])
                        ->where('fecha_fin', '>=', $whereParams['fecha_inicio']);
                })->Where(function ($query) use ($whereParams) {
                    // Verifica que la hora de inicio y fin del evento no se solapen
                    $query->where('hora_inicio', '<=', $whereParams['hora_fin'])
                        ->where('hora_fin', '>=', $whereParams['hora_inicio']);
                });
                //->orWhereBetween('hora_inicio', [$whereParams['hora_inicio'], $whereParams['hora_fin']])
                //->orWhereBetween('hora_fin', [$whereParams['hora_inicio'], $whereParams['hora_fin']]);
            })->count();
        if ($eventos > 0) {
            return response()->json([
                'success' => false,
                'message' => 'Hay ' . $eventos . ' eventos que impiden el registro',
            ], 400);
        }

        $evento = new Eventos();
        $evento->nombre = $request->titulo;
        $evento->descripcion = $request->descripcion;
        $evento->areas_id  = $request->area_id;
        $evento->user_id  = Auth::user()->id;
        $evento->fecha_inicio = $fecha;
        $evento->hora_inicio = date('H:i:s', strtotime($request->hora_inicio));
        $evento->fecha_fin =  $request->fecha_fin[0];
        $evento->hora_fin =  date('H:i:s', strtotime($request->fecha_fin[1]));
        $evento->notas_cta  = $request->notas_cta;
        $evento->notas_generales  = $request->notas_generales;
        $evento->organizador_id = $request->organizador_id;
        $evento->save();
        return response()->json([
            'success' => true,
            'message' => 'Se registro con exito'
        ], 200);
    }

    public function edit($id)
    {
        $evento = Eventos::with('area')->where('id', $id)->first();

        if (!isset($evento)) {
            return response()->json([
                'success' => false,
                'message' => "No existe ese evento",
            ]);
        }
        $areas = Area::orderBy('nombre')->get();
        $organizadores = Organizador::orderBy('nombre')->get();
        $evento = view('eventos.form', compact('evento', 'areas', 'organizadores'));
        return response($evento, 200)->header('Content-Type', 'text/html');
    }

    public function show($id)
    {
        $evento = Eventos::with('area', 'organiza')->where('id', $id)->first();
        if (!isset($evento)) {
            return response()->json([
                'success' => false,
                'message' => "No existe ese evento",
            ]);
        }

        $evento = view('eventos.show', compact('evento'));
        return response($evento, 200)->header('Content-Type', 'text/html');
    }

    public function destroy($id)
    {
        $evento = Eventos::where('id', $id)->first();
        if (!isset($evento)) {
            return response()->json([
                'success' => false,
                'message' => "No existe ese evento",
            ]);
        }
        $evento->delete();
        toast('Se elimino el evento', 'success');
        return redirect()->back();
    }
    public function update(Request $request, $id)
    {
        try {
            $evento = Eventos::where('id', $id)->first();
            if (!isset($evento)) {
                return response()->json([
                    'success' => false,
                    'message' => "No existe ese evento",
                ]);
            }
            $validator = Validator::make($request->all(), [
                "titulo"  => ['required'],
                'descripcion'  => 'required|max:255',
                'area' => ['required', 'exists:App\Models\Area,id'],
                'fecha_inicio' => ['required', 'date'],
                'fecha_fin.0' => ['required', 'date', Rule::date()->afterOrEqual($request->fecha_inicio)],
                'hora_inicio' => ['required', 'before:fecha_fin.1', new HorasValida($this->horaMinima, $this->horaMaxima,'update')],
                'fecha_fin.1' => ['required', 'after:hora_inicio', new HorasValida($this->horaMinima, $this->horaMaxima,'update')],
                'organizador_id' => ['required', 'exists:App\Models\Organizador,id']
            ], $this->message);

            $whereParams = [
                'fecha_inicio' => $request->fecha_inicio,
                'fecha_fin' => $request->fecha_fin[0],
                'hora_inicio' => $request->hora_inicio,
                'hora_fin' => $request->fecha_fin[1]
            ];

            $eventos =  Eventos::where([['areas_id', $request->area], ['id', '!=', $id]])
                ->where(function (Builder $query) use ($whereParams) {
                    $query->where(function ($query) use ($whereParams) {
                        // Caso donde el evento nuevo empieza antes de que el evento actual termine y termina después de que el evento actual empiece
                        $query->where('fecha_inicio', '<=', $whereParams['fecha_fin'])
                            ->where('fecha_fin', '>=', $whereParams['fecha_inicio']);
                    })->Where(function ($query) use ($whereParams) {
                        // Verifica que la hora de inicio y fin del evento no se solapen
                        $query->where('hora_inicio', '<=', $whereParams['hora_fin'])
                            ->where('hora_fin', '>=', $whereParams['hora_inicio']);
                    });
                    //->orWhereBetween('hora_inicio', [$whereParams['hora_inicio'], $whereParams['hora_fin']])
                    //->orWhereBetween('hora_fin', [$whereParams['hora_inicio'], $whereParams['hora_fin']]);
                })->count();
            if ($eventos > 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'Hay ' . $eventos . ' eventos que impiden el registro',
                ], 400);
            }

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => implode("<br/>", $validator->messages()->all()),
                ], 400);
            }

            $evento->nombre = $request->titulo;
            $evento->descripcion = $request->descripcion;
            $evento->areas_id  = $request->area;
            $evento->user_id  = Auth::user()->id;
            $evento->fecha_inicio = $request->fecha_inicio;
            $evento->hora_inicio = $request->hora_inicio;
            $evento->fecha_fin =  $request->fecha_fin[0];
            $evento->hora_fin = $request->fecha_fin[1];
            $evento->notas_cta  = $request->notas_cta;
            $evento->notas_generales  =  $request->notas_generales;
            $evento->organizador_id = $request->organizador_id;
            $evento->update();
        } catch (Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => $e,
            ]);
        }
        return response()->json([
            'success' => true,
            'message' => 'Se registro con exito'
        ], 200);
    }

    public function index()
    {
        $eventos = Eventos::orderBy('fecha_inicio')->paginate(10);
        return view('eventos.admin', compact('eventos'));
    }
    public function listado($sede)
    {
        if (strcmp('todas', $sede) == 0) {
            $areas = Area::select('id', 'sede')->get()->pluck('id');
        } else {
            $areas = Area::select('id', 'sede')->where('sede', $sede)->get()->pluck('id');
        }

        $eventos = Eventos::whereIn('areas_id', $areas)->get();
        $eventos_temp = [];
        foreach ($eventos as $key => $value) {
            $diferencia = (int)date_diff(date_create($value->fecha_fin), date_create($value->fecha_inicio))->format("%a");
            $evento = [];
            $date = new DateTime($value->fecha_inicio);
            for ($i = 0; $i <= $diferencia; $i++) {
                $fecha = $date->format('Y-m-d');
                $evento_tmp = [
                    'id' => $value->id,
                    'nombre' => $value->nombre,
                    'fecha_inicio' => $fecha,
                    'hora_inicio' => date('H:i', strtotime($value->hora_inicio)),
                    'fecha_fin' => $fecha,
                    'hora_fin' => date('H:i', strtotime($value->hora_fin)),
                    'color' => $value->area->color
                ];
                array_push($evento, $evento_tmp);
                $date->modify('+1 day');
            }
            array_push($eventos_temp, $evento);
        }
        $eventos = collect($eventos_temp)->collapse();
        return response()->json([
            'data' => $eventos
        ]);
    }
}
