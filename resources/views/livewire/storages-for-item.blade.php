<div>
    <x-collapsable-card title="Where's The Beef?">
        <div class="text-center">
            <x-select-choices wire:model="itemName">
                @foreach($this->getItemNames() as $internalName => $displayName)
                    <option value="{{ $internalName }}">{{ $displayName }}</option>
                @endforeach
            </x-select-choices>
        </div>
        <hr>
        <ul class="list-group">
            @foreach($searchResults as $storage => $inventoryItem)
                <li class="list-group-item d-flex justify-content-between">
                    <span>{{ \App\TT\StorageFactory::getPrettyName($storage) }}</span>
                    <span>{{ $inventoryItem->count }}</span>
                </li>
            @endforeach
        </ul>
    </x-collapsable-card>
</div>
