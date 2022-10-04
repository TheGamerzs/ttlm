<select wire:model="{{ $changeWireModel ?? 'itemName'}}" class="form-select">
    @foreach($getItemNames() as $idName => $prettyName)
        <option value="{{ $idName }}">{{ $prettyName }}</option>
    @endforeach
</select>
