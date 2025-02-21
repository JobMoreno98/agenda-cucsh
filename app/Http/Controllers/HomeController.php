<?php

namespace App\Http\Controllers;

use App\Models\Area;
use App\Models\Eventos;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $eventos = Eventos::all();
        return view('welcome',compact('eventos'));
    }
}
