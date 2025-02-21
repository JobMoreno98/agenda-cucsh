<div>
    <label for="">Áreas</label>
    <select class="form-control" name="area" id="">
        <option selected disabled>Elegir ...</option>
        @forelse ($areas as $item)
            <option value="{{ $item->id }}">{{ $item->nombre }}</option>
        @empty
            <option selected disabled>No hay áreas</option>
        @endforelse
    </select>
</div>
