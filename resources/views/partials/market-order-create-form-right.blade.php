<div>

    <div class="mb-2">
        <label>
            {{ $marketOrder->type == 'move' ? 'Move From' : '' }}
            {{ $marketOrder->type == 'buy' ? 'Preferred' : '' }}
            Storage
        </label>
        <x-select-choices wire:model="marketOrder.storage">
            <x-select-options :items="\App\TT\StorageFactory::getRegisteredNames(true, false)" />
        </x-select-choices>
    </div>

    @if($marketOrder->type != 'sell')
        <div class="mb-2">
            <label>
                {{ $marketOrder->type == 'move' ? 'To Storage' : 'Alternate (optional)' }}
            </label>
            <x-select-choices wire:model="marketOrder.storage_additional">
                @if($marketOrder->type == 'buy')
                    <option value="">None</option>
                @endif
                <x-select-options :items="\App\TT\StorageFactory::getRegisteredNames(true, false)" />
            </x-select-choices>
            @error('marketOrder.storage_additional') <div class="text-danger">{{ $message }}</div> @enderror
        </div>
    @endif

    <hr>
    <div class="form-floating">
        <textarea class="form-control" id="additionalDetails" style="height: 100px"></textarea>
        <label for="additionalDetails">Additional Details</label>
    </div>

</div>
