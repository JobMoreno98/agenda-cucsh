@extends('layouts.app')

@section('content')
    <h2>Áreas</h2>
    <div id="contenido">
        <div>
            <button type="button" onclick="crearArea()" class="btn btn-sm btn-success" data-bs-toggle="modal"
                data-bs-target="#crearArea">
                Crear Área
            </button>

        </div>
        @forelse ($areas as $item)
            <div class="border d-flex justify-content-between align-items-center border-dark mt-2 p-2 rounded">
                <div>
                    Nombre: {{ $item->nombre }} <br>
                </div>
                <div>
                    <button class="btn btn-sm btn-success mx-1" onclick="editArea('{{ json_encode($item) }}')"> <span
                            class="material-symbols-outlined">
                            edit
                        </span></button>
                </div>
            </div>
        @empty
            <h4>Aun no hay áreas registradas</h4>
        @endforelse
    </div>

    <div class="modal fade" id="crearArea" tabindex="-1" aria-labelledby="crearArea" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="crearArea">Crear Área</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('areas.store') }}" method="post" id="formulario">
                        @csrf
                        <div>
                            <label for="">Nombre</label>
                            <input class="form-control" type="text" name="nombre" id="nombre">
                        </div>
                        <div>
                            <label for="">Sede</label>
                            <select class="form-control" name="sede" id="sede">
                                <option>Elegir ...</option>
                                <option value="normal">La Normal</option>
                                <option value="belenes">Belenes</option>
                                <option value="aulas">Belenes Aulas</option>
                            </select>
                        </div>
                        <div>
                            <label for="">Edificio</label>
                            <input class="form-control" type="text" name="edificio" id="edificio">
                        </div>
                        <div>
                            <label for="color" class="form-label">Color</label>
                            <input type="color" class="form-control form-control-color" id="color" name="color"
                                value="#563d7c" title="Choose your color">
                        </div>
                        <div class="text-end"><button type="submit" class="btn btn-sm btn-success">Guardar</button></div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('js')
    <script>
        function crearArea() {
            if (document.getElementById('actualizar')) {
                let actualizar = document.getElementById('actualizar');
                actualizar.parentNode.removeChild(actualizar);
            }
            document.getElementById("formulario").reset();
            let url = "{{ route('areas.store') }}";
            document.getElementById('formulario').action = url;
        }

        function editArea(item) {
            item = JSON.parse(item);
            document.getElementById('crearArea').innerHtml = "Editar Organizador";
            let url = "{{ route('areas.update', ':id') }}";
            url = url.replace(':id', item['id']);
            let formulario = document.getElementById('formulario');
            formulario.action = url;
            var x = document.createElement("input");
            x.setAttribute("type", "hidden");
            x.setAttribute("value", "PUT");
            x.setAttribute('name', '_method');
            x.setAttribute('id', 'actualizar');
            $('#nombre').val(item['nombre']);
            $('#sede').val(item['sede']);
            $('#edificio').val(item['edificio']);
            $('#color').val(item['color']);
            formulario.appendChild(x);
            $("#crearArea").modal("show");
        }
    </script>
@endsection
