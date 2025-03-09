<?php

use App\Http\Controllers\AreaController;
use App\Http\Controllers\EventosController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\OrganizadorController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return view('home');
    })->name('dashboard');
});

Route::middleware('auth')->group(function () {
    Route::resource('areas', AreaController::class)->names('areas')->except(['index']);
    Route::resource('organizadores', OrganizadorController::class)->names('organizadores');
    Route::get('/areas', [AreaController::class, 'index'])->name('areas.index');
    Route::get('/listado-areas', [AreaController::class, 'listado'])->name('areas.listado');
    Route::resource('eventos', EventosController::class)->except(['create', 'store', 'destroy', 'show']);
    Route::delete('/elimiar-evento/{id}', [EventosController::class, 'destroy'])->name('eventos.delete')->middleware('auth');
    Route::get('/lista-organziadores', [OrganizadorController::class, 'listado'])->name('organizadores.listado');
});
Route::get('/evento/{id}', [EventosController::class, 'show'])->name('eventos.show');
Route::post('/crear-evento/{fecha}', [EventosController::class, 'store'])->name('eventos.store')->middleware('auth');
Route::resource('areas', AreaController::class)->except(['create']);

Route::get('eventos-dia/{dia}', [EventosController::class, 'eventosDia'])->name('eventos.dia');
Route::put('/actualizar-evento/{id}', [EventosController::class, 'update'])->name('evento.update')->middleware('auth');
