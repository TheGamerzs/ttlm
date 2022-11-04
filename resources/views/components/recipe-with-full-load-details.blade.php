<?php
/** @var \App\TT\Trunk $trunk */
?>
<div x-data="{open: false}">
    <li class="list-group-item d-flex justify-content-center border-bottom-0" @click="open = ! open">
        <h4>
            <x-add-to-game-plan
                text="Take a full load of components for {{ $recipe->displayName() }} to {{ $recipe->craftingLocation }}."
            />
            <label class="cursor-pointer" title="Crafted at {{ $recipe->craftingLocation }}">
                {{ $recipe->displayName() }}
                @if($showMultipleRunCounts())
                    ({{ floor($recipe->howManyFullLoadsFromStorage($trunkCapacitySum())) }}x)
                @endif
            </label>
        </h4>
    </li>

    <div x-show="open" x-transition>
        <li class="list-group-item text-center border-top-0 border-bottom-0">
            In Storage: {{ \App\TT\StorageFactory::getCountFromCombinedForItem($recipe->inventoryItem) }}
        </li>

        @foreach($inventories as $trunk)
            <li @class(['list-group-item border-bottom-0 text-center border-top-0 pb-0'])>
                @if($inventories->trunks->count() > 1)
                    <h5 class="pb-0">
                        {{ $trunk->displayName() }}
                    </h5>
                @endif

                Makes: {{ $recipe->howManyItemsCanFit($trunk->getAvailableCapacity()) }}
            </li>

            <li @class(['list-group-item d-flex justify-content-around border-top-0 pt-0', 'border-bottom-0' => ! $loop->last])>

                @foreach($recipe->components as $craftingMaterial)
                    <span>{{ $craftingMaterial->name() }}: {{ $recipe->howManyRecipesCanFit($trunk->getAvailableCapacity()) * $craftingMaterial->recipeCount }}</span>
                @endforeach

            </li>
        @endforeach
    </div>

</div>
