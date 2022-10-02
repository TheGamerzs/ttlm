<?php
/** @var \App\TT\Items\CraftingMaterial $craftingMaterial */
?>
<div>
    <h3 class="text-center">{{ $this->countCanBeMade }} {{ $parentRecipe->name() }}s Can Be Made</h3>
    <h5 class="text-center">Trailer can fit {{ $parentRecipe->howManyCanFit($truckCapacity) }}</h5>
    @if($parentRecipe->craftingLocation)
        <h5 class="text-center">Crafted at {{ $parentRecipe->craftingLocation }}</h5>
    @endif

    <x-storage-select />

    <table class="table text-center">
        <thead>
        <tr>
            <td>Takes</td>
            <td>In Storage</td>
            <td>Can Complete</td>
            <td>{{ $this->getFillTruckString() }}</td>
            <td></td>
        </tr>
        </thead>
        <tbody>
        @foreach($parentRecipe->components as $craftingMaterial)
            <tr>
                <td>
                    <x-parent-recipe-table-crafting-link :crafting-material="$craftingMaterial" />
                </td>
                <td>{{ $craftingMaterial->inStorage }}</td>
                <td>{{ $craftingMaterial->recipesCraftableFromStorage() }}</td>
                <td>{{ $this->getFillTruckCount($craftingMaterial) }}</td>
                <td class="cursor-pointer" wire:click="$emit('updateNextRecipeToGrind', '{{ $craftingMaterial->name }}')">
                    <i class="bi bi-arrow-right-square-fill text-success fs-3"></i>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
