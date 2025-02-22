<table class="table">
    @foreach ($eventos as $item)
        <tr>
            <td>{{ $item->nombre }}</td>
            <td>{{ $item->fecha_inicio }}</td>
            @if (Auth::check())
                <td>
                    <form method="POST" action="{{ route('eventos.delete', $item->id) }}" id="formularioEliminar">
                        @csrf
                        @method('delete')
                        <button type="submit" class="btn btn-sm btn-outline-danger" >Eliminar</button>
                    </form>
                </td>
            @endif
        </tr>
    @endforeach
</table>
