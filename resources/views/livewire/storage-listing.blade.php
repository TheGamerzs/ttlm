<?php
/** @var \App\TT\Items\InventoryItem $inventoryItem */
/** @var \App\TT\Items\ExportableItem $exportableItem */
?>
<div>
    <h3 class="text-center">Self Storage Contents</h3>

    <x-storage-select />
    <livewire:sync-storage-button />

    <table class="table">
        <thead>
        <tr>
            <td>Item</td>
            <td>
                <a href="#" wire:click.prevent="$set('sortBy', 'count')">Count</a>
            </td>
            <td>
                <a href="#" wire:click.prevent="$set('sortBy', 'weight')">Total Weight</a>
            </td>
            <td>Item</td>
            <td>
                <a href="#" wire:click.prevent="$set('sortBy', 'count')">Count</a>
            </td>
            <td>
                <a href="#" wire:click.prevent="$set('sortBy', 'weight')">Total Weight</a>
            </td>
        </tr>
        </thead>
        <tbody>
        @foreach($storage as $chunk)
            <tr>
                @foreach($chunk as $inventoryItem)
                    <td>{{ $inventoryItem?->name() }}</td>
                    <td>{{ $inventoryItem?->count }}</td>
                    <td>{{ $inventoryItem?->getTotalWeight() }}</td>
                @endforeach
            </tr>
        @endforeach

        </tbody>
    </table>


    <div class="row">
        <div class="col">
            <livewire:storages-for-item />
        </div>
        <div class="col">
            <x-collapsable-card title="Full Trailer Alerts ({{ $this->fullTrailerAlerts()->count() }})" :open="false">
                <table class="table">
                    <thead>
                    <tr>
                        <td></td>
                        <td class="text-end">Fill Trailer</td>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($this->fullTrailerAlerts() as $inventoryItem)
                        <tr>
                            <td>
                                <x-add-to-game-plan
                                    text="Run a full trailer ({{ $inventoryItem->howManyCanFitInSpace($truckCapacity) }}) of {{ $inventoryItem->name() }}s "
                                />
                                {{ $inventoryItem->name() }}
                            </td>
                            <td class="text-end">
                                {{ $inventoryItem->howManyCanFitInSpace($truckCapacity) }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td class="text-center" colspan="2">Nothing Yet</td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </x-collapsable-card>
            <x-collapsable-card title="Full Trailer Alert Settings" :open="false">
                <div class="input-group mb-3">
                    <x-item-select change-wire-model="itemToAddToFullTrailerAlerts" />
                    <button class="btn btn-outline-secondary" type="button" wire:click.prevent="addItemToFullTrailerAlerts">Add</button>
                </div>

                <ul class="list-group list-group-flush">
                    @foreach(Auth::user()->full_trailer_alerts as $item)
                        <li class="list-group-item d-flex justify-content-between">
                            {{ \App\TT\Items\ItemNames::getName($item) ?? $item }}
                            <i class="bi bi-trash cursor-pointer text-danger" wire:click.prevent="removeItemFromFullTrailerAlerts('{{ $item }}')"></i>
                        </li>
                    @endforeach
                </ul>
            </x-collapsable-card>
        </div>
    </div>


    <h3 class="text-center">
        Exportable Items (${{ number_format($exportableItems->sum(function ($item) { return $item->totalValue(); })) }})
    </h3>

    <x-collapsable-card title="Exportable Item Settings" :open="true">
        <div class="row">
            @foreach($hiddenExportableInputs as $name => $item)
                <div class="col-3">
                    <div class="form-check form-switch">
                        <input wire:model="hiddenExportableInputs.{{ $name }}" class="form-check-input" type="checkbox" role="switch" id="{{ $name }}hide">
                        <label class="form-check-label" for="{{ $name }}hide">{{ \App\TT\Items\ItemNames::getName($name) ?? $name }}</label>
                    </div>
                </div>
            @endforeach
        </div>
    </x-collapsable-card>

    <table class="table mb-5" wire:key="exportables">
        <thead>
            <tr>
                <td></td>
                <td>Count</td>
                <td>Total Value</td>
                <td>Full Trailer</td>
                <td>Location</td>
                <td class="text-center">Value of Custom Count</td>
            </tr>
        </thead>
        <tbody>
            @foreach ($exportableItems as $exportableItem)
            <tr wire:key="export-{{$exportableItem->name}}">
                <td>
                    <x-add-to-game-plan
                        text="Sell a full trailer ({{ $fullTruckCount = $exportableItem->howManyCanFitInSpace($truckCapacity) }}) of {{ $exportableItem->name() }} to {{ $exportableItem->location }} for ${{ number_format($exportableItem->getValueFor($fullTruckCount)) }}."
                    />
                    {{ $exportableItem->name() }}
                </td>
                <td>{{ $exportableItem->count }}</td>
                <td>${{ number_format($exportableItem->totalValue()) }}</td>
                <td>
                    {{ $fullTruckCount }}
                    (${{ number_format($exportableItem->getValueFor($fullTruckCount)) }})
                </td>
                <td>{{ $exportableItem->location }}</td>
                <td class="d-flex justify-content-around border-start" x-data="{value: @js($exportableItem->valueEach), count: @js($fullTruckCount)}">
                    <input x-model="count" type="text" class="form-control form-control-sm w-25">
                    <p x-text="(value * count).toLocaleString('en-US', { style: 'currency', currency: 'USD', maximumFractionDigits: 0 })"></p>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
