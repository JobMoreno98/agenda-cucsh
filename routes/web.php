<?php

use App\Http\Controllers\AreaController;
use App\Http\Controllers\EventosController;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
});

Route::resource('eventos', EventosController::class)->except(['create', 'store', 'destroy']);
Route::post('/crear-evento/{fecha}', [EventosController::class, 'store'])->name('eventos.store')->middleware('auth');
Route::resource('areas', AreaController::class)->except(['create']);

Route::get('eventos-dia/{dia}', [EventosController::class, 'eventosDia'])->name('eventos.dia');
Route::delete('/elimiar-evento/{id}', [EventosController::class, 'destroy'])->name('eventos.delete')->middleware('auth');
