<?php

namespace App\TT;

use App\TT\Items\CraftingMaterial;
use App\TT\Items\Item;
use Illuminate\Support\Collection;
use Livewire\Wireable;

class Recipe
{
    public Item $inventoryItem;

    public Collection $components;

    public ?string $craftingLocation;

    public bool|string $pickupRun = false;

    public int $makes = 1;

    public function __construct(Item $inventoryItem)
    {
        $this->inventoryItem = $inventoryItem;
        $this->components    = collect();
    }

    public function name()
    {
        return $this->inventoryItem->name;
    }

    public function totalWeightOfComponentsToCraft(int $count = 1): int
    {
        return $this->components->sum(function ($craftingMaterial) use ($count) {
            /** @var CraftingMaterial $craftingMaterial */
            return $craftingMaterial->getTotalWeightNeeded() * $count;
        });
    }

    public function howManyCanFit(int $compacityKG): int
    {
        if ($this->totalWeightOfComponentsToCraft()) {
            return (int) floor($compacityKG / $this->totalWeightOfComponentsToCraft());
        }
        return 0;
    }

    public function setInStorageForAllComponents(Storage $storage): self
    {
        $this->components->each->setInStorage($storage);

        $this->components = $this->components->sortBy(function ($craftingMaterial) {
            /** @var CraftingMaterial $craftingMaterial */
            return $craftingMaterial->recipesCraftableFromStorage();
        });

        return $this;
    }

    public function craftableRecipesFromStorage(): int
    {
        return $this->mostLimitedBy()->recipesCraftableFromStorage();
    }

    public function craftableItemsFromStorage(): int
    {
        return $this->craftableRecipesFromStorage() * $this->makes;
    }

    public function mostLimitedBy(): CraftingMaterial
    {
        // When the storage is set, the components are sorted by how many recipes can be made
        // with what is currently in that storage. This allows us to return the first item.
        return $this->components->first();
    }

    public function getComponent(string $craftingMaterialName)
    {
        return $this->components->firstWhere('name', $craftingMaterialName);
    }
}
