<?php

namespace App\Http\Controllers;

use App\Models\Area;
use App\Models\Eventos;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $eventos = Eventos::select('id','nombre','fecha_inicio','hora_inicio','hora_fin','fecha_fin')->get();
        return view('welcome',compact('eventos'));
    }
}
