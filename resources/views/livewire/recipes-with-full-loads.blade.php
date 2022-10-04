<?php
/** @var \App\TT\Recipe $recipe */
/** @var \App\TT\Items\CraftingMaterial $craftingMaterial */
?>
<div>
    <x-collapsable-card title="Full Loads Ready">
        <div class="text-center">
            <x-select-choices wire:model="storageName">
                @foreach(\App\TT\StorageFactory::getRegisteredNames(true) as $internalName => $displayName)
                    <option value="{{ $internalName }}">{{ $displayName }}</option>
                @endforeach
            </x-select-choices>
        </div>
        <div class="form-floating mt-1">
            <input type="text" class="form-control" id="capacityUsed" wire:model="capacityUsed" />
            <label for="capacityUsed">Current Trailer Capacity Used</label>
        </div>
        <hr>
        @if($craftableRecipes->count())
            <ul class="list-group">
                @foreach($craftableRecipes as $recipe)
                    <li class="list-group-item d-flex justify-content-center border-bottom-0">
                        <h4>
                            <x-add-to-game-plan
                                    text="Take a full load of components for {{ $recipe->displayName() }} to {{ $recipe->craftingLocation }}."
                            />
                            {{ $recipe->displayName() }}
                        </h4>
                    </li>
                    <li class="list-group-item text-center border-top-0 border-bottom-0">
                        Makes: {{ $recipe->howManyItemsCanFit($truckCapacity - (int) $capacityUsed) }}
                        <br>
                        In Storage: {{ \App\TT\StorageFactory::getCountFromCombinedForItem($recipe->inventoryItem) }}
                    </li>
                    <li class="list-group-item d-flex justify-content-around border-top-0">
                        @foreach($recipe->components as $craftingMaterial)
                            <span>{{ $craftingMaterial->name() }}: {{ $recipe->howManyRecipesCanFit($truckCapacity - (int) $capacityUsed) * $craftingMaterial->recipeCount }}</span>
                        @endforeach
                    </li>
                @endforeach
            </ul>
        @endif
    </x-collapsable-card>
</div>
