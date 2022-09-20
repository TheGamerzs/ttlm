<select wire:model="itemName" class="form-select">
    @foreach($getItemNames() as $name)
        <option value="{{ $name }}">{{ $name }}</option>
    @endforeach
</select>
