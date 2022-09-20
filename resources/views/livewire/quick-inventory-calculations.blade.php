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
                <hr>
                <h4 class="text-center">How Many To Fill Trailer</h4>
                <ul class="list-group">
                    @foreach($fillTrailerLookups as $item)
                        <li class="list-group-item d-flex justify-content-between">
                            <span>{{ $item->name }}</span>
                            <span>{{ $item->howManyCanFitInSpace($truckCompacity - (int)$compacityUsed) }}</span>
                        </li>
                    @endforeach
                </ul>
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
