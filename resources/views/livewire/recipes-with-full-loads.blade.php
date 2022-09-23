<?php
/** @var \App\TT\Recipe $recipe */
/** @var \App\TT\Items\CraftingMaterial $craftingMaterial */
?>
<div>
    <x-collapsable-card title="Full Loads Ready">
        <div class="form-floating">
            <x-storage-select />
            <label>Storage</label>
        </div>
        <div class="form-floating mt-1">
            <input type="text" class="form-control" id="compacityUsed" wire:model="compacityUsed"/>
            <label for="compacityUsed">Current Trailer Compacity Used</label>
        </div>
        <hr>
        @if($craftableRecipes->count())
        <ul class="list-group">
            @foreach($craftableRecipes as $recipe)
                <li class="list-group-item d-flex justify-content-center border-bottom-0">
                    <h4>{{ $recipe->name() }}</h4>
                </li>
                <li class="list-group-item d-flex justify-content-around border-top-0">
                    @foreach($recipe->components as $craftingMaterial)
                        <span>{{ $craftingMaterial->name }}: {{ $recipe->howManyCanFit($truckCompacity - (int) $compacityUsed) * $craftingMaterial->recipeCount }}</span>
                    @endforeach
                </li>
            @endforeach
        </ul>
        @endif
    </x-collapsable-card>
</div>
