<div class="text-center">

    <div class="mb-2">
        <label>Type</label>
        @if($marketOrder->exists)
            <br>{{ str($marketOrder->type)->title() }}
        @else
            <x-select-choices wire:model="marketOrder.type">
                <x-select-options :items="['buy' => 'Buy', 'sell' => 'Sell', 'move' => 'Move']" />
            </x-select-choices>
        @endif
    </div>

    <div class="mb-2">
        <label>Item</label>
        @if($marketOrder->exists)
            <br>{{ $marketOrder->item->name() }}
        @else
            <x-select-choices wire:model="marketOrder.item_name">
                <x-select-options :items="$this->getItemOptionsList()" />
            </x-select-choices>
        @endif
    </div>

    <div class="mb-2">
        <label for="countInput">Count</label>
        <input wire:model="marketOrder.count"
               class="form-control text-center @error('marketOrder.count') is-invalid @enderror"
               id="countInput"
               type="text">
        @error('marketOrder.count')<div class="text-danger">{{ $message }}</div>@enderror
    </div>

    <div class="mb-2">
        <label for="priceEach">
            {{ $marketOrder->type == 'move' ? 'Price Per KG' : 'Price Each' }}
        </label>
        <div class="input-group mb-3">
            <span class="input-group-text" id="basic-addon1">$</span>
            <input wire:model="marketOrder.price_each"
                   class="form-control text-center @error('marketOrder.price_each') is-invalid @enderror"
                   id="priceEach"
                   type="text">
        </div>
        @error('marketOrder.price_each')<div class="text-danger">{{ $message }}</div>@enderror
    </div>

    <div class="mb-2">
        <label>Storage</label>
        <x-select-choices wire:model="marketOrder.storage">
            <x-select-options :items="\App\TT\StorageFactory::getRegisteredNames(true, false)" />
        </x-select-choices>
    </div>

    <hr>

    <div class="d-grid">
        <button class="btn btn-success" wire:click="save">
            {{ $marketOrder->exists ? 'Update Listing' : 'List' }}
        </button>
    </div>

</div>
