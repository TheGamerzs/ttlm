<div>
    <x-collapsable-card title="Where's The Beef?">
        <div class="form-floating">
            <x-item-select-only-in-storage />
            <label>Item</label>
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
