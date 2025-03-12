<div>
    @forelse ($eventos as $item)
        <div class="border border-dark rounded my-1 p-2">

            Nombre: {{ $item->nombre }} <br>
            Inicia: {{ $item->fecha_inicio . ' ' . $item->hora_inicio }} / Termina:
            {{ $item->fecha_fin . ' ' . $item->hora_fin }}
            <br>
            Lugar: {{ $item->area->sedeReal }} - Edificio {{ $item->area->edificio }}
            @if (Auth::check())
                <form method="POST" action="{{ route('eventos.delete', $item->id) }}" id="formularioEliminar">
                    @csrf
                    @method('delete')
                    <button type="submit" class="btn btn-sm btn-outline-danger">Eliminar</button>
                </form>
            @endif
        </div>
    @empty
        <h3>No hay eventos registrados</h3>
    @endforelse
</div>
