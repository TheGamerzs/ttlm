<?php
/** @var \App\TT\Items\InventoryItem $inventoryItem */
/** @var \App\TT\Items\ExportableItem $exportableItem */
?>
<div>
    <h3 class="text-center">Self Storage Contents</h3>

    <div class="row">
        <div class="col-3">
            <div class="card">
                <h5 class="card-header d-flex justify-content-around">
                    Sync With TT
                </h5>
                <div class="card-body">
                    <div class="row">
                        <div class="col d-flex justify-content-evenly">
                            <livewire:sync-storage-button />
                        </div>
                    </div>
                    <div class="row mt-2">
                        <div class="col d-flex justify-content-evenly">
                            <livewire:sync-pocket-button />
                        </div>
                    </div>
                </div>
            </div>

            <div class="card mt-3">
                <h5 class="card-header d-flex justify-content-around">
                    Filters
                </h5>
                <div class="card-body" >
                    <input type="text" class="form-control" wire:model="searchStringInput" placeholder="By Name..." />

                    <span class="mt-2 text-center">Type</span>
                    <div class="text-center">
                        <x-select-choices wire:model="typeFilter">
                            <x-select-options :items="$this->getTypeFilterOptions()" />
                        </x-select-choices>
                    </div>

                    <span class="mt-2 text-center">Count</span>
                    <input wire:model="minCountFilter" type="text" class="form-control" placeholder="Minimum">
                    <input wire:model="maxCountFilter" type="text" class="form-control" placeholder="Maximum">

                    <span class="mt-2 text-center">Total Weight</span>
                    <input wire:model="minTotalWeightFilter" type="text" class="form-control" placeholder="Minimum">
                    <input wire:model="maxTotalWeightFilter" type="text" class="form-control" placeholder="Maximum">
                </div>
            </div>

            <div class="card mt-3">
                <h5 class="card-header d-flex justify-content-around">
                    Custom Combined Storage
                </h5>
                <div class="card-body" >
                    @foreach($this->customStorageInput as $storageName => $null)
                        <div class="form-check form-switch">
                            <input wire:model="customStorageInput.{{ $storageName }}" class="form-check-input" type="checkbox" role="switch" id="{{ $storageName }}hide">
                            <label class="form-check-label" for="{{ $storageName }}hide">{{ \App\TT\StorageFactory::getPrettyName($storageName) ?? $storageName }}</label>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <div class="col-9">
            <div class="text-center">
                <x-select-choices wire:model="storageName">
                    <x-select-options :items="\App\TT\StorageFactory::getRegisteredNames(true)"/>
                </x-select-choices>
            </div>

            <div class="row">
                @foreach($newStorage as $half)
                <div class="col-md-6 col-sm-12">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <td></td>
                                <td class="text-center">
                                    <a href="#" wire:click.prevent="$set('sortBy', 'count')">Count</a>
                                </td>
                                <td class="text-center">
                                    <a href="#" wire:click.prevent="$set('sortBy', 'weight')">Total Weight</a>
                                </td>
                                <td></td>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($half as $inventoryItem)
                            <tr>
                                    <td>{{ $inventoryItem?->name() }}</td>
                                    <td class="text-center">{{ $inventoryItem?->count }}</td>
                                    <td class="text-center">{{ $inventoryItem?->getTotalWeight() }}</td>
                                    <td>
                                        <x-storage-listing-item-menu :inventory-item="$inventoryItem" />
                                    </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @endforeach
            </div>


        </div>
    </div>

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
                                    text="Run a full trailer ({{ $inventoryItem->howManyCanFitInSpace($truckCapacity) }}) of {{ str($inventoryItem->name())->plural() }} "
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
                <div class="text-center">
                    <x-select-choices wire:model="itemToAddToFullTrailerAlerts">
                        <x-select-options :items="\App\TT\Items\ItemData::getInternalNameDisplayNamePairsTruckingOnly()" />
                    </x-select-choices>
                </div>
                    <button class="btn btn-outline-secondary" type="button" wire:click.prevent="addItemToFullTrailerAlerts">Add</button>

                <ul class="list-group list-group-flush">
                    @foreach(Auth::user()->full_trailer_alerts as $item)
                        <li class="list-group-item d-flex justify-content-between">
                            {{ \App\TT\Items\ItemData::getName($item) }}
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
                        <label class="form-check-label" for="{{ $name }}hide">{{ \App\TT\Items\ItemData::getName($name) }}</label>
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

    <livewire:market-order-create-edit />
    <button class="btn btn-success" wire:click="$emit('newMarketOrder', 'crafted_concrete')">Open</button>
</div>
