<div>
    <h1 class="text-center">
        <div class="row">
            <div class="col-3 offset-3">
                Shopping List for
            </div>
            <div class="col-1">
                <input class="form-control d-inline" type="text" wire:model.debounce="count" style="width: 8rem">
            </div>
            <div class="col-3">
                <x-select-choices wire:model="recipeName" class="form-control">
                    <x-select-options :items="App\TT\Recipes::getNamesIfComponentsExist(true)" />
                </x-select-choices>
            </div>
        </div>
    </h1>
    <div class="text-center mt-5 mb-5">
        <h3>Total Crafting Costs: {{ $viewModel->totalCraftingCost() }} </h3>
        <h3>Remaining Crafting Costs: {{ $viewModel->remainingCraftingCost() }}</h3>
    </div>

    <div class="d-flex justify-content-center mb-2">
        <livewire:sync-storage-button />
    </div>

    <?php
    /** @var \App\TT\RecipeShoppingListDecorator $shoppingListItem */
    ?>

    @foreach(['pickup', 'scrap', 'refined', 'crafted'] as $type)
        @includeWhen($viewModel->showType($type), 'partials.shopping-list-section-table', ['type' => $type])
    @endforeach

</div>
