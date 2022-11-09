<?php
/** @var \App\TT\Trunk $trunk */
/** @var \App\View\NextGrindViewModel $viewModel */
/** @var \App\TT\Items\CraftingMaterial $craftingMaterial */
/** @var \App\TT\Items\InventoryItem $inventoryItem */
?>
<h4 class="text-center mt-4">
    {{ $viewModel->recipe->craftableItemsFromStorage() }} Currently Craftable
    / {{ $viewModel->runsThatCanBeMadeDisplayString() }}
</h4>

<table class="table table-hover text-center">
    <thead>
    <tr>
        <th scope="col" style="width: 33%;"></th>
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
    <tr @class(['table-warning' => $trunk->capacityUsedPercent() < .99])>
        <th scope="row">
            Fill {{ $trunk->displayName() }} Trunk

            @if($viewModel->runsThatCanBeMade() < 1 && $trunk->loadWeight() > 0)
            <span title="How many full trailers worth you currently have in storage.">
                ({{ number_format($trunk->capacityUsedPercent(), 1) }})
            </span>
            @endif

        </th>
        @foreach($trunk->load as $inventoryItem)
            <td title="{{ $inventoryItem->name() }}">
                {{ $inventoryItem->count }}
            </td>
        @endforeach
    </tr>
    @endforeach

    @if($this->usingGoal())
        <tr @class(['table-warning' => $viewModel->recipe->craftableItemsFromStorage() < $this->goalCount ])>
            <th scope="row">
                Still Needed For Goal of {{ $this->goalCount }} {{ $this->getGoalRecipePluralName() }}
            </th>
            @foreach($this->getNeededForGoal() as $count)
                <td>{{ $count }}</td>
            @endforeach
        </tr>
    @endif
    <tr @class(['table-warning' => $this->getParentRecipeCountForFullTrailer() > $viewModel->recipe->craftableItemsFromStorage()])>
        <th scope="row">
            One Trailer Run of {{ $this->getParentRecipe()->displayNamePlural() }}
            ({{ $this->getParentRecipeCountForFullTrailer() }})
        </th>
        @foreach($this->getNeededForParentTrailer() as $count)
            <td>{{ $count }}</td>
        @endforeach
    </tr>
    <tr @class(['table-warning' => $customCount > $viewModel->recipe->craftableItemsFromStorage()])>
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
