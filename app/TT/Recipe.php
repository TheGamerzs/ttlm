<?php

namespace App\TT;

use App\TT\Items\CraftingMaterial;
use App\TT\Items\InventoryItem;
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

    public int $cost;

    public function __construct(Item $inventoryItem)
    {
        $this->inventoryItem = $inventoryItem;
        $this->components    = collect();
    }

    public function name(): string
    {
        return $this->inventoryItem->name;
    }

    public function howManyCanFit(int $capacityKG): int
    {
        if ($this->totalWeightOfComponentsToCraft()) {
            return (int)floor($capacityKG / $this->totalWeightOfComponentsToCraft());
        }
        return 0;
    }

    public function totalWeightOfComponentsToCraft(int $count = 1): int
    {
        return $this->components->sum(function ($craftingMaterial) use ($count) {
            /** @var CraftingMaterial $craftingMaterial */
            return $craftingMaterial->getTotalWeightNeeded() * $count;
        });
    }

    public function craftableItemsFromStorage(): int
    {
        return $this->craftableRecipesFromStorage() * $this->makes;
    }

    public function craftableRecipesFromStorage(): int
    {
        if ($this->components->count()) {
            return $this->mostLimitedBy()->recipesCraftableFromStorage();
        }
        return 0;
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

    public function autoSetStorageBasedOnComponentsLocation(): string
    {
        $storageName = $this->findStorageWithMostComponents();
        $this->setInStorageForAllComponents(StorageFactory::get($storageName));
        return $storageName;
    }

    public function findStorageWithMostComponents(): string
    {
        return
            $this->components->map(function (CraftingMaterial $craftingMaterial) {
                return StorageFactory::findStoragesForItem($craftingMaterial);
            })
                ->sortByDesc(function (Collection $storages) {
                    return $storages->sum(function (InventoryItem $item) {
                        return $item->count;
                    });
                })
                ->map(function (Collection $storages) {
                    return $storages
                        ->sortByDesc(function (InventoryItem $item) {
                            return $item->count;
                        })
                        ->keys()
                        ->first();
                })
                ->first() ?? 'combined';
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

    public function costPerItem(): int
    {
        return $this->cost / $this->makes;
    }

}
