<div>

    <h3 class="text-center">Next Grind</h3>
    <h5 class="text-center">
        <x-parent-recipe-table-crafting-link :show-recipe-count="false"
                                             :crafting-material="$nextRecipeToGrind->inventoryItem"/>
    </h5>

    @unless($this->isPickupRun())
        <h5 class="text-center">Trailer can fit {{ $this->nextRecipeToGrind->howManyCanFit($truckCapacity) }}</h5>
    @endunless

    @if($this->nextRecipeToGrind->craftingLocation)
        <h5 class="text-center">
            Crafted At {{ $this->nextRecipeToGrind->craftingLocation }}
            @if($this->isPickupRun())
                with a {{ $this->nextRecipeToGrind->pickupRun }} run
            @endif
        </h5>
    @endif

    @if($this->nextRecipeToGrind->makes > 1)
        <h5 class="text-center">Recipe Yields {{ $this->nextRecipeToGrind->makes }}</h5>
    @endif

    <x-storage-select />

    @if(!$this->isPickupRun())
        <table class="table">
            <thead>
            <tr>
                <td></td>
                <td>Recipe</td>
                <td>In Storage</td>
                <td>Can Make</td>
            </tr>
            </thead>
            <tbody>
            @foreach($this->nextRecipeToGrind->components as $craftingMaterial)
                <tr>
                    <td>
                            {{ $craftingMaterial->name }}
                    </td>
                    <td>{{ $craftingMaterial->recipeCount }}</td>
                    <td>{{ $craftingMaterial->inStorage }}</td>
                    <td>{{ $this->recipeAndItemCraftableFromStorage($craftingMaterial) }}</td>
                </tr>
            @endforeach
            </tbody>
        </table>

        <div class="text-center">
            Can Currently Craft: {{ $this->nextRecipeToGrind->craftableItemsFromStorage() }}<br>
            Needed To Make {{ $parentRecipe->howManyCanFit($truckCapacity) }}:
            {{ $this->countNeededForParentRecipe }}
        </div>

        <hr>
        <h4 class="text-center">Load Up</h4>
        <table class="table">
            <thead>
            <tr>
                <td></td>
                <td>From Storage ({{ $this->nextRecipeToGrind->craftableItemsFromStorage() }})</td>
                <td @class(['table-danger' => $this->haveEnoughForIWant()])>
                    I Want ({{ $iWant }})
                </td>
                <td @class(['table-danger' => $this->haveEnoughForFullTrailer()])>
                    Full Trailer ({{ $this->nextRecipeToGrind->howManyCanFit($truckCapacity) * $this->nextRecipeToGrind->makes }})
                </td>
            </tr>
            </thead>
            <tbody>
            @foreach($this->nextRecipeToGrind->components as $craftingMaterial)
                <tr>
                    <td>{{ $craftingMaterial->name }}</td>
                    <td>{{ $this->nextRecipeToGrind->craftableRecipesFromStorage() * $craftingMaterial->recipeCount }}</td>
                    <td @class(['table-danger' => $this->haveEnoughForIWant()])>
                        {{ (int)$this->iWant * $craftingMaterial->recipeCount / $craftingMaterial->recipe->makes }}
                    </td>
                    <td @class(['table-danger' => $this->haveEnoughForFullTrailer()])>
                        {{ $craftingMaterial->recipeCount * $this->nextRecipeToGrind->howManyCanFit($truckCapacity) }}
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
        <div class="row">
            <div class="col-4 offset-4 form-floating">
                <input type="text" class="form-control" id="iWant" wire:model="iWant"/>
                <label for="iWant">I Want</label>
            </div>
        </div>
    @else
        <hr>
        <h5 class="text-center">{{ $this->nextRecipeToGrind->pickupRun }} Run Yields</h5>
        @foreach($this->pickupRunYields() as $runPossibility)
            <table class="table">
                <thead>
                <tr>
                    <td></td>
                    <td>One</td>
                    <td>Two</td>
                    <td>Three</td>
                </tr>
                </thead>
                <tbody>
                    @foreach($runPossibility as $materialName => $count)
                        <tr>
                            <td>{{ $materialName }}</td>
                            <td>{{ $count }}</td>
                            <td>{{ $count * 2 }}</td>
                            <td>{{ $count * 3 }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        <p class="text-center">
            {{ (int)ceil(($this->countNeededForParentRecipe - $parentRecipe->getComponent($nextRecipeToGrind->name())->inStorage) / $runPossibility[$nextRecipeToGrind->name()]) }}
            Runs Required for {{ $parentRecipe->howManyCanFit($truckCapacity) }} {{ $parentRecipe->name() }}s
        </p>
        @endforeach
    @endif
</div>
