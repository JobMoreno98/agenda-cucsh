<?php

namespace App\Http\Controllers;

use App\Models\Area;
use Illuminate\Http\Request;

class AreaController extends Controller
{
    public function index()
    {
        $areas = Area::all();
        if (isset($areas)) {
            $selectAreas = view('areas.select', compact('areas'))->render();
            return response($selectAreas, 200)->header('Content-Type', 'text/html');
        }
    }
}
