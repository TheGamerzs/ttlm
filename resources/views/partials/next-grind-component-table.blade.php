<?php
/** @var \App\TT\Trunk $trunk */
/** @var \App\View\NextGrindViewModel $viewModel */
/** @var \App\TT\Items\CraftingMaterial $craftingMaterial */
/** @var \App\TT\Items\InventoryItem $inventoryItem */
?>
<table class="table table-hover text-center">
    <thead>
    <tr>
        <th scope="col" style="width: 15rem;"></th>
        @foreach($viewModel->recipe->components as $craftingMaterial)
            <th>{{ $craftingMaterial->name() }}</th>
        @endforeach
    </tr>
    </thead>
    <tbody>
    <tr>
        <th scope="row">
            Recipe
        </th>
        @foreach($viewModel->recipe->components as $craftingMaterial)
            <td>{{ $craftingMaterial->recipeCount }}</td>
        @endforeach
    </tr>
    <tr>
        <th scope="row">
            In Storage
        </th>
        @foreach($viewModel->recipe->components as $craftingMaterial)
            <td>{{ $craftingMaterial->inStorage }}</td>
        @endforeach
    </tr>

    @foreach($viewModel->inventories as $trunk)
    <tr @class(['table-warning' => $viewModel->recipe->howManyFullLoadsFromStorage($trunk->capacity) < 1])>
        <th scope="row">
            Fill {{ $trunk->displayName() }} Trunk
            <span title="How many full trailers worth you currently have in storage.">
                ({{ $viewModel->recipe->howManyFullLoadsFromStorage($trunk->capacity) }})
            </span>
        </th>
        @foreach($trunk->load as $inventoryItem)
            <td title="{{ $inventoryItem->name() }}">{{ $inventoryItem->count }}</td>
        @endforeach
    </tr>
    @endforeach

    @if($this->usingGoal())
        <tr>
            <th scope="row">
                Needed For Goal ({{ $this->goalCount }})
            </th>
            @foreach($this->getNeededForGoal() as $count)
                <td>{{ $count }}</td>
            @endforeach
        </tr>
    @endif
    <tr>
        <th scope="row">
            One Trailer Run of {{ $this->getParentRecipe()->displayName() }}
            ({{ $this->getParentRecipeCountForFullTrailer() }})
        </th>
        @foreach($this->getNeededForParentTrailer() as $count)
            <td>{{ $count }}</td>
        @endforeach
    </tr>
    <tr>
        <th scope="row">
            <div class="form-floating">
                <input type="text" class="form-control text-end" id="customCount" wire:model="customCount" />
                <label for="customCount">Custom Count</label>
            </div>
        </th>
        @foreach($viewModel->recipe->components as $craftingMaterial)
            <td>{{ (int)$customCount * $craftingMaterial->recipeCount / $craftingMaterial->recipe->makes }}</td>
        @endforeach
    </tr>
    </tbody>
</table>
