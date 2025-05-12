@extends('layouts.app')

@section('content')
    <h2>Organizadores</h2>
    <div>
        <button type="button" onclick="crearOrganizador()" class="btn btn-sm btn-success" data-bs-toggle="modal"
            data-bs-target="#crearOrganizador">
            Crear Organizador
        </button>

    </div>
    @forelse ($organizadores as $item)
        <div class="border d-flex justify-content-between align-items-center border-dark mt-2 p-2 rounded">
            <div>
                Nombre: {{ $item->nombre }} <br>
                Área: {{ $item->area->edificio ." - ". $item->area->nombre }} <br>
                Contacto: {{ $item->contacto }}
            </div>
            <div>
                <button class="btn btn-sm btn-success mx-1" onclick="editOrganizador('{{ json_encode($item) }}')">
                    <span class="material-symbols-outlined">
                        edit
                    </span></button>
                <button href=""class="btn btn-sm btn-danger mx-1"><span class="material-symbols-outlined">
                        delete
                    </span></button>
            </div>
        </div>


    @empty
        <h4>Aun no hay organizadores registrados</h4>
    @endforelse
    <div class="modal fade" id="crearOrganizador" tabindex="-1" aria-labelledby="crearOrganizador" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="crearOrganizadorTitle"></h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('organizadores.store') }}" method="post" id="formualrio">
                        @csrf
                        <div>
                            <label for="">Nombre</label>
                            <input class="form-control" type="text" name="nombre" id="nombre">
                        </div>
                        <div>
                            <label for="">Área</label>
                            <select class="form-control" name="area_id" id="area_id">
                                <option disabled selected>Elegir ...</option>
                                @forelse ($areas as $item)
                                    <option value="{{ $item->id }}"> {{ $item->edificio . " - ". $item->nombre }}  </option>
                                @empty
                                    <option disabled>No hay áreas registradas</option>
                                @endforelse
                            </select>
                        </div>
                        <div>
                            <label for="">Contacto</label>
                            <input class="form-control" type="text" id="contacto" name="contacto">
                        </div>
                        <div class="text-cecnter"><button type="submit" class="btn btn-success btn-sm">Enviar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script>
        function crearOrganizador() {
            if (document.getElementsByTagName('actualizar') > 0) {
                let actualizar = document.getElementsByTagName('actualizar');
                actualizar.parentNode.removeChild(actualizar);
            }
            document.getElementById("formualrio").reset();
            let url = "{{ route('organizadores.store') }}";
            document.getElementById('formualrio').action = url;
        }

        function editOrganizador(item) {
            item = JSON.parse(item);
            document.getElementById('crearOrganizadorTitle').innerHtml = "Editar Organizador";
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
