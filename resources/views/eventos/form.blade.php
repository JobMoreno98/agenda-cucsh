<form method="post" action="{{ route('evento.update', $evento->id) }}" id="editarEvento">
    @method('PUT')
    @csrf
    <div>
        <label for="">Título</label>
        <input class="form-control" type="text" value="{{ $evento->nombre }}" name="titulo">
    </div>
    <div>
        <label for="">Descripción</label>
        <textarea name="descripcion" id="descripcion" class="form-control">{{ $evento->descripcion }}</textarea>
    </div>
    <div>
        <h6>Inicio</h6>
        <div>
            <label for="">Fecha Inicio</label>
            <input class="form-control" type="date" name="fecha_inicio" value="{{ $evento->fecha_inicio }}"
                id="fecha_fin">
        </div>
        <div>
            <label for="">Hora Inicio</label>
            <input class="form-control" type="time" name="hora_inicio" value="{{ $evento->hora_inicio }}"
                id="hora_inicio">
        </div>
        <h6>Fin</h6>
        <div>
            <label for="">Fecha fin</label>
            <input class="form-control" type="date" name="fecha_fin[]" value="{{ $evento->fecha_fin }}"
                id="fecha_fin">
        </div>

        <div>
            <label for="">Hora Fin</label>
            <input class="form-control" type="time" name="fecha_fin[]" value="{{ $evento->hora_fin }}"
                id="hora_fin">
        </div>
        <hr>
    </div>
    <div id="areas">
        <label for="">Áreas</label>
        <select class="form-control" name="area" id="">
            @forelse ($areas as $item)
                <option value="{{ $item->id }}" {{ $evento->areas_id == $item->id ? 'selected' : '' }}> {{ $item->nombre }}</option>
            @empty
                <option selected disabled>No hay áreas</option>
            @endforelse
        </select>
    </div>
    <div>
        <label for="">Organizador</label>
        <select name="organizador_id" class="form-control" id="organizador_id">
            @forelse ($organizadores as $item)
            <option value="{{ $item->id }}" {{ $evento->organizador_id == $item->id ? 'selected' : '' }}> {{ $item->nombre }}</option>
            @empty
                <option selected disabled>No hay organizadores registrados</option>
            @endforelse

        </select>
    </div>
    <div>
        <label for="">Notas CTA</label>
        <textarea name="notas_cta" id="notas_cta" class="form-control">{{ $evento->notas_cta }}</textarea>
    </div>
    <div>
        <label for="">Notas Servicio Generales</label>
        <textarea name="notas_generales" id="notas_generales" class="form-control">{{ $evento->notas_generales }}</textarea>
    </div>
    <input type="submit" value="enviar">
    <div class="text-end">
        <span class="btn btn-success mt-1 btn-sm " onclick="actualizarEvento()">
            Guardar
        </span>
</form>
