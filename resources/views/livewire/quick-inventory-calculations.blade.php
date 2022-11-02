<?php
/** @var \App\TT\Items\Item $item */
/** @var \App\TT\Trunk $trunk */
/** @var \App\TT\TrainYardPickUp $trainYardPickup */
?>
<div>
    <x-collapsable-card title="Quick Calculations">
        <div class="row">
            <div class="col-6">
                <div class="form-floating">
                    <input type="text" class="form-control" id="capacityUsed" wire:model="capacityUsed"/>
                    <label for="capacityUsed">Current Trailer Capacity Used</label>
                </div>

                @if(Auth::user()->hasSecondTrailer())
                <div class="form-floating mt-1">
                    <input type="text" class="form-control" id="capacityUsed" wire:model="capacityUsedTwo"/>
                    <label for="capacityUsed">Current Second Trailer Capacity Used</label>
                </div>
                @endif

                @if(Auth::user()->hasTrainYard())
                <div class="form-floating mt-1">
                    <input type="text" class="form-control" id="capacityUsedTY" wire:model="capacityUsedTY"/>
                    <label for="capacityUsedTY">Current Train Yard Capacity Used</label>
                </div>
                @endif

                <div class="text-center mt-1">
                    <x-select-choices wire:model="itemForFillTrailer">
                        <x-select-options :items="$this->getItemNamesThatExistInStorage()" />
                    </x-select-choices>
                </div>

                <ul class="list-group mt-1">
                    <li class="list-group-item d-flex justify-content-between">
                        <span>Weighs: {{ $trailerLookupItem->weight }}kg</span>

                        @foreach($userInventories->trunks as $trunk)
                            <span>
                                {{ $trunk->displayName() }}: {{ $trunk->numberOfItemsThatCanFit($trailerLookupItem) }}
                            </span>
                        @endforeach
                    </li>
                </ul>
                <hr>

                @if($trainYardStorage)
                <h4>Pickups That Can Be Stored In Train Yard ({{ number_format($this->trainYardStorage - (int) $capacityUsedTY) }} kg)</h4>

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
                        <td class="fw-bold">{{ \App\TT\Items\ItemData::getName($trainYardPickup->pickupName) }}</td>

                        @foreach($trainYardPickup->getTrunksExceptTrainYard() as $trunk)
                            <td>{{ $trunk->displayName() }}: {{ $trunk->load->first()->count }}</td>
                        @endforeach

                        <td class="border-start text-center">Runs: {{ $trainYardPickup->runsThatCanFitInTrainYard() }}</td>
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
                        <div class="text-center">
                            <x-select-choices wire:model="itemName">
                                <x-select-options :items="\App\TT\Recipes::getNamesIfComponentsExist(true)" />
                            </x-select-choices>
                        </div>
                    </div>

                    <div class="col-4">
                        <div class="text-center">
                            <x-select-choices wire:model="storageName">
                                <x-select-options :items="\App\TT\StorageFactory::getRegisteredNames(true)" />
                            </x-select-choices>
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

                <p class="text-center">
                    Using
                    @foreach($pickupCounts->inventories->trunks as $trunk)
                        {{ $trunk->displayName() }}({{ $trunk->capacity }}kg)
                        @if($loop->count == 2 && $loop->first)
                            and
                        @endif
                    @endforeach
                </p>

                <ul class="list-group">
                    @foreach($pickupCounts->getFormattedCountsArray() as $name => $count)
                        <li class="list-group-item d-flex justify-content-between">
                            <span>
                                <x-add-to-game-plan text="Make {{ $count }} {{ \App\TT\Items\ItemData::getName($name) }} runs." />
                                {{ \App\TT\Items\ItemData::getName($name) }}
                            </span>
                            <span>{{ $count }}</span>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </x-collapsable-card>
</div>
