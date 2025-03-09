@extends('layouts.app')

@section('content')
    <h2>Eventos</h2>
    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#crearEvento">
        Crear Evento
    </button>
    <div id="contenido">
        @forelse ($eventos as $item)
            <div class="border d-flex justify-content-between align-items-center border-dark mt-2 p-2 rounded">
                <div>
                    Nombre: {{ $item->nombre }} <br>
                    Fecha de Inicio {{ $item->fecha_inicio . ' ' . $item->hora_inicio }} / Fecha de Fin
                    {{ $item->fecha_fin . ' ' . $item->hora_fin }}
                </div>
                <div class="d-flex">
                    <button href=""class="btn btn-sm btn-success mx-1"><span class="material-symbols-outlined">
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
                    ...
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary">Save changes</button>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('js')
    <script>
        function crearOrganizador() {
            if (document.getElementsByTagName('actualizar')) {
                let actualizar = document.getElementsByTagName('actualizar');
                actualizar.parentNode.removeChild(actualizar);
            }
            document.getElementById("formualrio").reset();
            let url = "{{ route('organizadores.store') }}";
            document.getElementById('formualrio').action = url;
        }

        function editOrganizador(item) {
            item = JSON.parse(item);
            document.getElementById('crearOrganizador').innerHtml = "editar Organizador";
            let url = "{{ route('organizadores.update', ':id') }}";
            url = url.replace(':id', item['id']);
            let formulario = document.getElementById('formualrio');
            formulario.action = url;
            var x = document.createElement("input");
            x.setAttribute("type", "hidden");
            x.setAttribute("value", "PUT");
            x.setAttribute('name', '_method');
            x.setAttribute('id', 'actualizar');
            $('#nombre').val(item['nombre']);
            $('#area_id').val(item['areas_id']);
            $('#contacto').val(item['contacto']);
            formulario.appendChild(x);
            $("#crearOrganizador").modal("show");
        }
    </script>
@endsection
