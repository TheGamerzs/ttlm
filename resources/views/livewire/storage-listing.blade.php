<?php
/** @var \App\TT\Items\InventoryItem $inventoryItem */
/** @var \App\TT\Items\SellableItem $sellableItem */
?>
<div>
    <h3 class="text-center">Self Storage Contents</h3>

    <div class="row">
        <div class="col">
            <livewire:storages-for-item />
        </div>
        <div class="col">
            <x-collapsable-card title="Full Trailer Alerts">
                <table class="table">
                    <thead>
                    <tr>
                        <td></td>
                        <td>Fill Trailer</td>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($this->fullTrailerAlerts() as $inventoryItem)
                        <tr>
                            <td>{{ $inventoryItem->name }}</td>
                            <td>{{ $inventoryItem->howManyCanFitInSpace($truckCapacity) }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="2">Nothing Yet</td>
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
                            {{ $item }}
                            <i class="bi bi-trash cursor-pointer text-danger" wire:click.prevent="removeItemFromFullTrailerAlerts('{{ $item }}')"></i>
                        </li>
                    @endforeach
                </ul>
            </x-collapsable-card>
        </div>
    </div>


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
                    <td>{{ $inventoryItem?->name }}</td>
                    <td>{{ $inventoryItem?->count }}</td>
                    <td>{{ $inventoryItem?->getTotalWeight() }}</td>
                @endforeach
            </tr>
        @endforeach

        </tbody>
    </table>
    <h3 class="text-center">
        Sellables (${{ number_format($sellableItems->sum(function ($item) { return $item->totalValue(); })) }})
    </h3>
    <table class="table mb-5">
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
            @foreach ($sellableItems as $sellableItem)
            <tr>
                <td>{{ $sellableItem->name }}</td>
                <td>{{ $sellableItem->count }}</td>
                <td>${{ number_format($sellableItem->totalValue()) }}</td>
                <td>
                    {{ $fullTruckCount = $sellableItem->howManyCanFitInSpace($truckCapacity) }}
                    (${{ number_format($sellableItem->getValueFor($fullTruckCount)) }})
                </td>
                <td>{{ $sellableItem->location }}</td>
                <td class="d-flex justify-content-around border-start" x-data="{value: @js($sellableItem->valueEach), count: @js($fullTruckCount)}">
                    <input x-model="count" type="text" class="form-control form-control-sm w-25">
                    <p x-text="(value * count).toLocaleString('en-US', { style: 'currency', currency: 'USD', maximumFractionDigits: 0 })"></p>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
