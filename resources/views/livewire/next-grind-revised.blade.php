<?php
/** @var \App\TT\Items\CraftingMaterial $craftingMaterial */
$cra
?>
<div>
    <div id="next-grind-heading">
        <h3 class="text-center">Next Grind</h3>
        <h5 class="text-center">
            <x-add-to-game-plan text="Make {{ $this->getRecipe()->displayName() }}." />
            <x-parent-recipe-table-crafting-link :show-recipe-count="false"
                                                 :crafting-material="$this->getRecipe()->inventoryItem" />
        </h5>
        <h5 class="text-center">
            @unless($this->getRecipe()->pickupRun)
                Trailer can fit components to make {{ $this->getRecipe()->howManyItemsCanFit($truckCapacity) }}
            @endunless
            @if($this->getRecipe()->makes > 1)
                ({{ $this->getRecipe()->howManyRecipesCanFit($truckCapacity) }} Recipes, {{ $this->getRecipe()->makes }} each)
            @endif
        </h5>

        @if($this->getRecipe()->craftingLocation)
            <h5 class="text-center">
                Crafted At {{ $this->getRecipe()->craftingLocation }}
            </h5>
        @endif

        <div class="text-center">
            <x-select-choices wire:model="storageName">
                <x-select-options :items="\App\TT\StorageFactory::getRegisteredNames(true)"/>
            </x-select-choices>
        </div>
    </div>

    <div>
        @includeWhen($this->getRecipe()->components->count(), 'partials.next-grind-component-table')
        @includeWhen($this->getRecipe()->pickupRun, 'partials.next-grind-pickup-table')
    </div>

    @include('livewire.crafting-goal-modal')
</div>
