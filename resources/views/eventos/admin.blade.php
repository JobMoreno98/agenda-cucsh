@extends('layouts.app')

@section('content')
    <h2>Eventos</h2>
    <div id="contenido">
        @forelse ($eventos as $item)
            <div class="border d-flex justify-content-between align-items-center border-dark mt-2 p-2 rounded">
                <div>
                    Nombre: {{ $item->nombre }} <br>
                    Fecha de Inicio {{ $item->fecha_inicio . ' ' . $item->hora_inicio }} / Fecha de Fin
                    {{ $item->fecha_fin . ' ' . $item->hora_fin }}
                </div>
                <div>
                    <a href=""class="btn btn-sm btn-success">Editar</a><a
                        href=""class="btn btn-sm btn-danger">Borrar</a>
                </div>
            </div>
        @empty
            <h4>Aun no hay eventos</h4>
        @endforelse
    </div>
@endsection
