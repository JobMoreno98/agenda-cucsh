@extends('layouts.app')

@section('content')
    <h2>Áreas</h2>
    <div id="contenido">
        <div>
            <button type="button" class="btn btn-sm btn-success" data-bs-toggle="modal" data-bs-target="#crearArea">
                Crear Área
            </button>

        </div>
        @forelse ($areas as $item)
            <div class="border d-flex justify-content-between align-items-center border-dark mt-2 p-2 rounded">
                <div>
                    Nombre: {{ $item->nombre }} <br>
                </div>
                <div>
                    <a href=""class="btn btn-sm btn-success">Editar</a><a
                        href=""class="btn btn-sm btn-danger">Borrar</a>
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
                    <form action="{{ route('areas.store') }}" method="post">
                        @csrf
                        <div>
                            <label for="">Nombre</label>
                            <input class="form-control" type="text" name="nombre">
                        </div>
                        <div>
                            <label for="">Sede</label>
                            <input class="form-control" type="text" name="sede">
                        </div>
                        <div>
                            <label for="">Edificio</label>
                            <input class="form-control" type="text" name="edificio">
                        </div>
                        <div>
                            <label for="exampleColorInput" class="form-label">Color</label>
                            <input type="color" class="form-control form-control-color" id="exampleColorInput"
                                name="color" value="#563d7c" title="Choose your color">
                        </div>
                        <div><button type="submit">Enviar</button></div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
