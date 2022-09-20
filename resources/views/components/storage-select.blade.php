
<select wire:model="storageName" class="form-select form-select-sm">
    @foreach($storageNames() as $name)
        <option value="{{ $name }}">{{ $prettyName($name) }}</option>
    @endforeach
</select>
