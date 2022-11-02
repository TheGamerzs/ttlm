<?php
/** @var \App\TT\Items\CraftingMaterial $craftingMaterial */
/** @var \App\View\NextGrindViewModel $viewModel */
?>
<div>
    <div id="next-grind-heading">
        <h3 class="text-center">Next Grind</h3>
        <h5 class="text-center">
            <x-add-to-game-plan text="Make {{ $viewModel->recipe->displayName() }}." />
            <x-parent-recipe-table-crafting-link :show-recipe-count="false"
                                                 :crafting-material="$viewModel->recipe->inventoryItem" />
        </h5>
        <h5 class="text-center">
            @unless($this->getRecipe()->pickupRun)
                A full load can carry components to make {{ $viewModel->itemsThatCanBeCraftedFromAFullLoadOfComponents() }}
            @endunless
            @if($this->getRecipe()->makes > 1)
                ({{ $viewModel->recipesThatCanBeCraftedFromAFullLoadOfComponents() }} Recipes, {{ $viewModel->recipe->makes }} each)
            @endif
        </h5>

        @if($this->getRecipe()->craftingLocation)
            <h5 class="text-center">
                Crafted At {{ $viewModel->recipe->craftingLocation }}
            </h5>
        @endif

        <div class="text-center">
            <x-select-choices wire:model="storageName">
                <x-select-options :items="$viewModel->storageDropdownOptions()"/>
            </x-select-choices>
        </div>
    </div>

    <div>
        @includeWhen($viewModel->showComponentTable(), 'partials.next-grind-component-table')
        @includeWhen($this->getRecipe()->pickupRun, 'partials.next-grind-pickup-table')
    </div>

    @include('livewire.crafting-goal-modal')
</div>
