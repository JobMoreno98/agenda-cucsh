@php
    date_default_timezone_set('America/Mexico_City');
    setlocale(LC_TIME, 'es_MX.UTF-8', 'esp');
    $fecha = strftime('%a %e %b', strtotime($evento->fecha_inicio));
@endphp
<div>
    <div class="d-flex align-items-center">
        <div class="text-uppercase  me-3 text-white rounded p-1"
            style="background:{{ $evento->area->color }};width:100px;height:100px;border:{{ $evento->area->color }} 2px solid;">
            <span class="d-block m-auto text-center " style="font-size: 22pt">
                {{ $fecha }}
            </span>
        </div>
        <div>
            <h5>{{ $evento->nombre }}</h5>
            <p class="d-flex m-0 align-items-center">
                <span class="material-symbols-outlined">
                    location_on
                </span> <span>Lugar</span>: {{ $evento->area->sedeReal }} - Edificio {{ $evento->area->edificio }} -
                {{ $evento->area->nombre }}

            </p>
            <p class="d-flex m-0 align-items-center">
                <span class="material-symbols-outlined">
                    schedule
                </span>
                <span>Inicio</span>: {{ $evento->hora_inicio }} / &nbsp; <span>Termina</span>:
                {{ $evento->fecha_fin . ' ' . $evento->hora_fin }}
            </p>
            <p>
                Organiza: {{ $evento->organiza->nombre }} / Contacto {{ $evento->organiza->contacto }}
            </p>
        </div>
    </div>
    <hr>
    <div class="d-flex flex-column justify-content-start fs-5">
        <p class="m-0">
            <span class="material-symbols-outlined">
                description
            </span>
            <span>Descripción</span>: {{ $evento->descripcion }}
        </p>

        <p class="m-0">

        </p>
    </div>
    @if (Auth::check())
        <div class="text-end">
            <button class="btn btn-sm btn-success" onclick="editarEvento('{{ $evento->id }}')">Editar evento</button>
        </div>
    @endif
</div>
