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
        </div>
    </div>


    <x-storage-select />
    <button wire:click="sync"  class="btn btn-warning">Update Storage Data ({{ Cache::get('api_charges') }})</button>


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
