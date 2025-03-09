@if ($eventos->count() > 0)
    <table class="table">
        <thead>
            <tr>
                <th>Nombre</th>
                <th>Inicia</th>
                <th>Sede</th>
            </tr>
        </thead>
        @foreach ($eventos as $item)
            <tr>
                <td>{{ $item->nombre }}</td>
                <td>{{ $item->fecha_inicio }}</td>
                <td>{{ $item->area->sedeReal }}</td>
                @if (Auth::check())
                    <td>
                        <form method="POST" action="{{ route('eventos.delete', $item->id) }}" id="formularioEliminar">
                            @csrf
                            @method('delete')
                            <button type="submit" class="btn btn-sm btn-outline-danger">Eliminar</button>
                        </form>
                    </td>
                @endif
            </tr>
        @endforeach
    </table>
@else
    <h3>No hay eventos registrados</h3>
@endif
