<?php
/** @var \App\TT\Recipe $recipe */
/** @var \App\TT\Items\CraftingMaterial $craftingMaterial */
/** @var \App\TT\Inventories $inventories */
?>
<div>
    <x-collapsable-card title="Full Loads Ready">
        <div class="text-center">
            <x-select-choices wire:model="storageName">
                <x-select-options :items="\App\TT\StorageFactory::getRegisteredNames(true)"/>
            </x-select-choices>
        </div>

        <div class="form-floating mt-1">
            <input type="text" class="form-control" id="capacityUsed" wire:model="trunkOneCapacityUsed" />
            <label for="capacityUsed">Current Capacity Used In {{ $inventories->trunks->first()->displayName() }}</label>
        </div>

        @if($inventories->count() > 1)
        <div class="form-floating mt-1">
            <input type="text" class="form-control" id="capacityUsed" wire:model="trunkTwoCapacityUsed" />
            <label for="capacityUsed">Current Capacity Used In {{ $inventories->trunks->offsetGet(1)->displayName() }}</label>
        </div>
        @endif

        <hr>
        @if($craftableRecipes->count())
            <ul class="list-group border-bottom">
                @foreach($craftableRecipes as $recipe)
                    <x-recipe-with-full-load-details :recipe="$recipe"
                                                     :trunk-one-capacity-used="$trunkOneCapacityUsed"
                                                     :trunk-two-capacity-used="$trunkTwoCapacityUsed"/>
                @endforeach
            </ul>
        @endif
    </x-collapsable-card>
</div>
