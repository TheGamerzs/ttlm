<?php

namespace App\TT\Pickup;

use App\TT\Inventories;
use App\TT\RecipeFactory;
use App\TT\ShoppingListBuilder;
use App\TT\StorageFactory;

class PickupRunCounts
{
    public Inventories $inventories;

    public string $forRecipe;

    public string $storageName = 'combined';

    public int   $recipeCount;

    public function __construct(Inventories $inventories, string $forRecipe, int $recipeCount)
    {
        $this->inventories = $inventories;
        $this->forRecipe   = $forRecipe;
        $this->recipeCount = $recipeCount;
    }

    public function setStorageName(string $storageName): self
    {
        $this->storageName = $storageName;
        return $this;
    }

    public function getCalculator(): PickupRunCalculator
    {
        $combinedCapacity = $this->inventories->trunks->sum('capacity');

        return ShoppingListBuilder::build(
            RecipeFactory::get($this->forRecipe),
            StorageFactory::get($this->storageName),
            $this->recipeCount,
            $combinedCapacity
        )['pickupCalculator'];
    }

    public function getFormattedCountsArray(): array
    {
        return $this->getCalculator()
            ->getRunCalculations()
            ->filter(function ($count, $name) {
                return $count > 0;
            })
            ->toArray();
    }
}
