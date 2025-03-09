<?php

namespace App\Http\Controllers;

use App\Models\Area;
use App\Models\Eventos;
use DateTime;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $eventos = Eventos::with('area')->select('id', 'nombre', 'areas_id', 'fecha_inicio', 'hora_inicio', 'hora_fin', 'fecha_fin')->get();
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
        return view('welcome', compact('eventos'));
    }
}
