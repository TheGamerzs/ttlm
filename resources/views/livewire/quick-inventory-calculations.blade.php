<?php
/** @var \App\TT\Items\Item $item */
?>
<div>
    <x-collapsable-card title="Quick Calculations">
        <div class="row">
            <div class="col-6">
                <div class="form-floating">
                    <input type="text" class="form-control" id="capacityUsed" wire:model="capacityUsed"/>
                    <label for="capacityUsed">Current Trailer Capacity Used</label>
                </div>
                @if(Auth::user()->hasTrainYard())
                <div class="form-floating mt-1">
                    <input type="text" class="form-control" id="capacityUsedTY" wire:model="capacityUsedTY"/>
                    <label for="capacityUsedTY">Current Train Yard Capacity Used</label>
                </div>
                @endif
                <div class="form-floating mt-1">
                    <x-item-select-only-in-storage change-wire-model="itemForFillTrailer"/>
                    <label>Item</label>
                </div>
                <ul class="list-group mt-1">
                    <li class="list-group-item d-flex justify-content-between">
                        <span>Weighs: {{ $trailerLookupItem->weight }}kg</span>
                        @if(Auth::user()->hasTrainYard())
                            <span>
                            Train Yard: {{ $trailerLookupItem->howManyCanFitInSpace($trainYardStorage - (int)$capacityUsedTY) }}
                        </span>
                        @endif
                        <span>
                            Trailer: {{ $trailerLookupItem->howManyCanFitInSpace($truckCapacity - (int)$capacityUsed) }}
                        </span>
                        <span>
                            Pocket: {{ $trailerLookupItem->howManyCanFitInSpace($pocketCapacity) }}
                        </span>
                    </li>
                </ul>
                <hr>

                @if($trainYardStorage)
                <h4>Pickups That Can Be Stored In Train Yard ({{ number_format($this->trainYardStorage) }} kg)</h4>

                <div>
                    <div class="form-check form-switch">
                        <input wire:model="leaveRoomForProcessed" class="form-check-input" type="checkbox" role="switch" id="leave-room">
                        <label class="form-check-label" for="leave-room">
                            Ensure there is enough room for one load of processed items.
                        </label>
                    </div>
                </div>

                <table class="table table-sm border">
                    @foreach($trainYardPickups as $trainYardPickup)
                    <tr>
                        <td class="fw-bold">{{ $trainYardPickup->pickupItemName }}</td>
                        <td>Trailer: {{ $trainYardPickup->pickupItemsCountTrailer() }}</td>
                        <td>Pocket: {{ $trainYardPickup->pickupItemsCountPocket() }}</td>
                        <td>Runs Stored: {{ $trainYardPickup->howManyTimesTrainYardCanBeUsed() }}</td>
                    </tr>
                    @endforeach
                </table>
                @endif

            </div>
            <div class="col-6">
                <div class="row">
                    <div class="col-4">
                        <div class="form-floating">
                            <input type="text" class="form-control" id="howMany" wire:model="pickupCountsYield"/>
                            <label for="howMany">How Many</label>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="form-floating">
                            <x-recipe-select />
                            <label>Recipe</label>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="form-floating">
                            <x-storage-select />
                            <label>Storage</label>
                        </div>
                    </div>
                </div>
                <hr>
                <h4 class="text-center">
                    Pickup Runs Still Required
                    <a target="_blank" href="{{ route('shoppingList', ['recipeName' => $itemName, 'count' => $pickupCountsYield]) }}">
                        <i class="bi bi-cart4 ms-2 fs-5 text-info" title="Full Shopping List"></i>
                    </a>
                </h4>
                <ul class="list-group">
                    @foreach($pickupCounts as $name => $count)
                        <li class="list-group-item d-flex justify-content-between">
                            <span>{{ \Illuminate\Support\Str::title($name) }}</span>
                            <span>{{ $count }}</span>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </x-collapsable-card>
</div>
