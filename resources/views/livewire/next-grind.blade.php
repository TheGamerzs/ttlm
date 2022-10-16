<div>

    <h3 class="text-center">Next Grind</h3>
    <h5 class="text-center">
        <x-add-to-game-plan text="Make {{ $nextRecipeToGrind->displayName() }}." />
        <x-parent-recipe-table-crafting-link :show-recipe-count="false"
                                             :crafting-material="$nextRecipeToGrind->inventoryItem" />
    </h5>

    @unless($this->isPickupRun())
        <h5 class="text-center">Trailer can fit components
            for {{ $this->nextRecipeToGrind->howManyRecipesCanFit($truckCapacity) }} recipes</h5>
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
        <h5 class="text-center">Each Recipe Yields {{ $this->nextRecipeToGrind->makes }}</h5>
    @endif

    <div class="text-center">
        <x-select-choices wire:model="storageName">
            <x-select-options :items="\App\TT\StorageFactory::getRegisteredNames(true)"/>
        </x-select-choices>
    </div>

    @if(!$this->isPickupRun())
        <table class="table text-center">
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
                        {{ $craftingMaterial->name() }}
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
            Needed To Make {{ $parentRecipe->howManyRecipesCanFit($truckCapacity) }}:
            {{ $this->countNeededForParentRecipe }}
        </div>

        <hr>
        <h4 class="text-center">Load Up</h4>
        <table class="table text-center">
            <thead>
            <tr>
                <td></td>
                <td>
                    From Storage ({{ $this->nextRecipeToGrind->craftableItemsFromStorage() }})
                    <i class="bi bi-info-circle text-info"
                       title="Full Loads: {{ $this->nextRecipeToGrind->howManyFullLoadsFromStorage($truckCapacity) }}"></i>
                </td>
                <td @class(['table-danger' => $this->haveEnoughForIWant()])>
                    I Want ({{ $iWant }})
                    <i class="bi bi-info-circle text-info"
                       title="Full Loads: {{ $this->nextRecipeToGrind->howManyFullLoadsFromCount($truckCapacity, $iWant) }}"></i>
                </td>
                <td @class(['table-danger' => $this->haveEnoughForFullTrailer()])>
                    Full Trailer
                    ({{ $this->nextRecipeToGrind->howManyItemsCanFit($truckCapacity) }})
                </td>
            </tr>
            </thead>
            <tbody>
            @foreach($this->nextRecipeToGrind->components as $craftingMaterial)
                <tr>
                    <td>{{ $craftingMaterial->name() }}</td>
                    <td>{{ $this->nextRecipeToGrind->craftableRecipesFromStorage() * $craftingMaterial->recipeCount }}</td>
                    <td @class(['table-danger' => $this->haveEnoughForIWant()])>
                        {{ (int)$this->iWant * $craftingMaterial->recipeCount / $craftingMaterial->recipe->makes }}
                    </td>
                    <td @class(['table-danger' => $this->haveEnoughForFullTrailer()])>
                        {{ $craftingMaterial->recipeCount * $this->nextRecipeToGrind->howManyRecipesCanFit($truckCapacity) }}
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
        <div class="row">
            <div class="col-1 offset-3 d-flex justify-content-end pe-0">
                <i class="bi bi-info-circle text-info" title="{{ $this->iWantToolTip() }}"></i>
            </div>
            <div class="col-4 form-floating">
                <input type="text" class="form-control" id="iWant" wire:model="iWant" />
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
                {{ max( (int)ceil(($this->countNeededForParentRecipe - $parentRecipe->getComponent($nextRecipeToGrind->internalName())->inStorage) / $runPossibility[$nextRecipeToGrind->internalName()]), 0) }}
                Runs Required
                for {{ $parentRecipe->howManyRecipesCanFit($truckCapacity) }} {{ $parentRecipe->displayNamePlural() }}
            </p>
        @endforeach
    @endif

    {{-- Crafting Goal Modal --}}
    <div class="modal fade" id="craftingGoal" aria-modal="true" role="dialog" wire:ignore.self>
        <div class="modal-dialog modal-dialog-centered modal-sm">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-4" id="exampleModalXlLabel">Goal</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <div class="form-floating">
                        <input type="text" class="form-control" id="goalCount" wire:model="goalCount"/>
                        <label for="howMany">How Many</label>
                    </div>

                    <div class="text-center mt-2">
                        <x-select-choices wire:model="goalRecipe">
                            <x-select-options :items="\App\TT\Recipes::getNamesIfComponentsExist(true)" />
                        </x-select-choices>
                    </div>

                    <div class="d-grid mt-2">
                        <button class="btn btn-success" wire:click="updateGoal">
                            Save
                            @if($goalWasUpdated)
                                <i class="bi bi-check-all"></i>
                            @endif
                        </button>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
