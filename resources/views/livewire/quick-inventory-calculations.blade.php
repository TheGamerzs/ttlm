<?php
/** @var \App\TT\Items\Item $item */
?>
<div>
    <x-collapsable-card title="Quick Calculations">
        <div class="row">
            <div class="col-6">
                <div class="form-floating">
                    <input type="text" class="form-control" id="compacityUsed" wire:model="compacityUsed"/>
                    <label for="compacityUsed">Current Trailer Compacity Used</label>
                </div>
                <div class="form-floating mt-1">
                    <x-item-select-only-in-storage change-wire-model="itemForFillTrailer"/>
                    <label>Item</label>
                </div>
                <ul class="list-group mt-1">
                    <li class="list-group-item d-flex justify-content-between">
                        <span>Weighs: {{ $trailerLookupItem->weight }}kg</span>
                        <span>{{ $trailerLookupItem->howManyCanFitInSpace($truckCompacity - (int)$compacityUsed) }}</span>
                    </li>
                </ul>
                <hr>

                <h4>Pickups That Can Be Stored In Train Yard ({{ number_format($this->trainYardStorage) }} kg)</h4>
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
                            <x-item-select />
                            <label>Item</label>
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
                <h4 class="text-center">Pickup Counts Required To Make</h4>
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
