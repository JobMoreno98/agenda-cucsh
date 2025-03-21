@extends('layouts.app')

@section('content')
    <h2>Usuarios</h2>
    <button type="button" class="btn btn-success btn-sm " data-bs-toggle="modal" data-bs-target="#crearUsuario">
        Crear usuario
    </button>
    <div>
        @forelse ($usuarios as $item)
            <div class="border border-dark p-1 my-1 rounded">
                <b>Nombre:</b> {{ $item->name }} / <b>Rol</b> {{ $item->getRoleNames() }}
            </div>
        @empty
            <h3>No hay usuarios registardos</h3>
        @endforelse
    </div>
    <!-- Button trigger modal -->


    <!-- Modal -->
    <div class="modal fade" id="crearUsuario" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Modal title</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('usuarios.store') }}" method="post">
                        @csrf
                        <div>
                            <label class="form-label" for="name">Nombre</label>
                            <input type="text" class="form-control" name="name">
                        </div>
                        <div>
                            <label class="form-label" for="email">Email</label>
                            <input type="email" class="form-control" name="email">
                        </div>
                        <div>
                            <label class="form-label" for="password">Contraseña</label>
                            <input type="password" class="form-control" id="password" name="password">
                        </div>
                        <div>
                            <label class="form-label" for="password_confirmation">Confirmar contraseña</label>
                            <input type="password" class="form-control"id="password_confirmation"
                                name="password_confirmation ">
                        </div>
                        <div>
                            <label for="rol">Rol</label>
                            <select name="rol" id="rol" class="form-control">
                                <option selected disabled>Elegir ...</option>
                                <option value="admin">Administrador</option>
                                <option value="editor">Editor</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-success btn-sm">Enviar</button>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
@endsection
