<div>
    <label for="">Organizador</label>
    <select name="organizador_id" class="form-control" id="organizador_id">
        <option selected disabled>Elegir ...</option>
        @forelse ($organizadores as $item)
            <option value="{{ $item->id }}">{{ $item->nombre }}</option>
        @empty
            <option selected disabled>No hay organizadores registrados</option>
        @endforelse

    </select>

</div>
