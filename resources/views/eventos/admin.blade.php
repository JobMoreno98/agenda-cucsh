@php
    date_default_timezone_set('America/Mexico_City');
    setlocale(LC_TIME, 'es_MX.UTF-8', 'esp');
@endphp
@extends('layouts.app')

@section('content')
    <h2>Eventos</h2>
    <button type="button" class="btn btn-success btn-sm" onclick="crearEvento()">
        Crear Evento
    </button>
    <div id="contenido">
        @forelse ($eventos as $item)
            @php
                $fecha = strftime('%a %e %b', strtotime($item->fecha_inicio));
            @endphp
            <div class="border d-flex justify-content-between align-items-center border-dark mt-2 p-2 rounded">
                <div class="d-flex align-items-center">
                    <div class="text-uppercase text-white me-2 rounded p-1 text-break d-flex align-items-center"
                        style="background:{{ $item->area->color }};width:100px;height:100px;border:{{ $item->area->color }} 2px solid;">
                        <span class="d-block m-auto text-center " style="font-size: 16pt">
                            {{ $fecha }}
                        </span>
                    </div>
                    <div>
                        <b>Nombre:</b> {{ $item->nombre }} <br>
                        <b>Fecha de Fin:</b> {{ $item->fecha_fin . ' ' . $item->hora_fin }}
                    </div>
                </div>

                <div class="d-flex">
                    <button class="btn btn-sm btn-success mx-1" onclick="editarEvento('{{ $item->id }}')"><span
                            class="material-symbols-outlined">
                            edit
                        </span></button>

                    <form method="POST" action="{{ route('eventos.delete', $item->id) }}" id="formularioEliminar">
                        @csrf
                        @method('delete')
                        <button type="submit" class="btn btn-sm btn-danger mx-1">
                            <span class="material-symbols-outlined">
                                delete
                            </span>
                        </button>
                    </form>
                </div>
            </div>
            <div>
                {{ $eventos->links() }}
            </div>
        @empty
            <h4>Aun no hay eventos</h4>
        @endforelse
    </div>

    <!-- Modal -->
    <div class="modal fade" id="crearEvento" tabindex="-1" aria-labelledby="crearEventoLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="crearEventoLabel">Modal title</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div id="contenidoModal"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary">Save changes</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="exampleModalToggle2" aria-hidden="true" aria-labelledby="exampleModalToggleLabel2"
        tabindex="-1">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="eventoModal"></h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="modalBody">
                    <form method="post" action="" id="createEvent">
                        @csrf
                        @method('POST')
                        <div>
                            <label for="">Título</label>
                            <input class="form-control" type="text" name="titulo">
                        </div>
                        <div>
                            <label for="">Descripción</label>
                            <textarea name="descripcion" id="descripcion" class="form-control"></textarea>
                        </div>
                        <div>
                            <h6>Inicio</h6>
                            <div>
                                <label for="">Fecha Inicio</label>
                                <input class="form-control" type="date" name="fecha_inicio" id="fecha_inicio">
                            </div>
                            <div>
                                <label for="">Hora Inicio</label>
                                <input class="form-control" type="time" name="hora_inicio" id="hora_inicio">
                            </div>
                            <h6>Fin</h6>
                            <div>
                                <label for="">Fecha fin</label>
                                <input class="form-control" type="date" name="fecha_fin[]" id="fecha_fin">
                            </div>

                            <div>
                                <label for="">Hora Fin</label>
                                <input class="form-control" type="time" name="fecha_fin[]" id="hora_fin">
                            </div>
                            <hr>
                        </div>
                        <div id="areas">
                        </div>
                        <div id="organziadores">

                        </div>
                        <div>
                            <label for="">Notas CTA</label>
                            <textarea name="notas_cta" id="notas_cta" class="form-control"></textarea>
                        </div>
                        <div>
                            <label for="">Notas Servicio Generales</label>
                            <textarea name="notas_generales" id="notas_generales" class="form-control"></textarea>
                        </div>
                        <div class="text-end">
                            <span class="btn btn-success mt-1 btn-sm " onclick="guardarEvento()">
                                Guardar
                            </span>
                    </form>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-primary" data-bs-target="#modalEvento" data-bs-toggle="modal">Regresar</button>
            </div>
        </div>
    </div>
@endsection
@section('js')
    <script>
        async function editarEvento(id) {
            eventoID = id;
            let url = "{{ route('eventos.edit', ':id') }}";
            url = url.replace(':id', id);
            try {
                const response = await fetch(url);
                if (!response.ok) {
                    throw new Error(`Response status: ${response.status}`);
                }
                const evento = await response.text();
                document.getElementById('contenidoModal').innerHTML = evento;
                $('#crearEvento').modal('show');
            } catch (error) {
                console.error(error.message);
            }
        }

        function actualizarEvento() {
            let url = "{{ route('evento.update', ':id') }}";
            url = url.replace(':id', eventoID);
            $.ajax({
                url: url,
                method: 'PUT',
                data: $('#editarEvento').serialize(),
                error: function(data) {
                    let datos = JSON.parse(data.responseText);
                    Toast.fire({
                        title: datos.message,
                        icon: "error"
                    });
                }
            }).done(function(data) {
                if (data.success === true) {
                    Toast.fire({
                        title: data.message,
                        icon: "success"
                    });
                    setTimeout(() => {
                        window.location.reload();
                    }, 2000);
                }
            })
        }
        let eventoID = 0;
        async function areas() {
            let url = "{{ route('areas.listado') }}";
            try {
                const response = await fetch(url);
                if (!response.ok) {
                    throw new Error(`Response status: ${response.status}`);
                }
                const html = await response.text();
                document.getElementById('areas').innerHTML = html;
            } catch (error) {
                console.error(error.message);
            }
        }
        async function organizadores() {
            let url = "{{ route('organizadores.listado') }}";
            try {
                const response = await fetch(url);
                if (!response.ok) {
                    throw new Error("Reponse status: ${response.status}");
                }
                const html = await response.text();
                document.getElementById('organziadores').innerHTML = html;
            } catch (error) {
                console.error(error.message);
            }
        }

        function crearEvento() {
            organizadores();
            areas();
            $("#exampleModalToggle2").modal('show')
        }

        function guardarEvento() {
            let diaSeleccionado = $('#fecha_inicio').val();
            let url = "{{ route('eventos.store', ':id') }}";
            url = url.replace(':id', diaSeleccionado);
            $.ajax({
                url: url,
                method: 'POST',
                data: $('#createEvent').serialize(),
                error: function(data) {
                    let datos = JSON.parse(data.responseText);
                    Toast.fire({
                        title: datos.message,
                        icon: "error"
                    });
                }
            }).done(function(data) {
                if (data.success === true) {
                    Toast.fire({
                        title: data.message,
                        icon: "success"
                    });
                    setTimeout(() => {
                        window.location.reload();
                    }, 2000);
                } else {
                    Toast.fire({
                        title: data.message,
                        icon: "error"
                    });
                }
            });
        }
    </script>
@endsection
