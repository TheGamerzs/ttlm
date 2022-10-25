<?php

namespace App\TT\Items;

use App\TT\Recipe;
use App\TT\RecipeShoppingListDecorator;
use App\TT\ShoppingListBuilder;
use App\TT\Storage;
use App\TT\StorageFactory;

class ExcessItem extends InventoryItem
{
    public int $neededCount;

    public function __construct(string $name, int $count, int $neededCount)
    {
        parent::__construct($name, $count);
        $this->neededCount = $neededCount;
    }

    public static function makeList(int $neededRecipeCount, Recipe $recipe, Storage $storage)
    {
        $needed = ShoppingListBuilder::build(
            $recipe,
            new Storage(),
            $neededRecipeCount,
            1000
        )
            ->only(['crafted', 'refined', 'scrap'])
            ->flatten();

        $neededWithStorage = ShoppingListBuilder::build(
                    $recipe,
                    $storage,
                    $neededRecipeCount,
                    1000
                )
                ->only(['crafted', 'refined', 'scrap'])
                ->flatten();

        $needed->each(function (RecipeShoppingListDecorator $item) use ($neededWithStorage) {
            $item->count = $neededWithStorage->firstWhere('recipeName', $item->recipeName)?->count ?? 0;
        });

        // When the recipe is house, remove the top level components.
        if ($recipe->inventoryItem->name == 'house') {
            $toReject = $recipe->components->pluck('name');
            $needed = $needed->reject(function (RecipeShoppingListDecorator $item) use ($toReject) {
                return $toReject->contains($item->recipeName);
            });
        }

        return StorageFactory::get()
            ->filter(function (InventoryItem $item) use ($needed) {
                return $needed->contains('recipeName', $item->name);
            })
            ->map(function (InventoryItem $item) use ($needed) {
                return self::makeFromInventoryItem(
                    $item,
                    $needed->firstWhere('recipeName', $item->name)->count
                );
            })
            ->filter(function (ExcessItem $excessItem) {
                return $excessItem->hasExcessFactorOfAtLeast(2);
            })
            ->values()
            ->sortByDesc->inExcessWeight();
    }

    public static function makeFromInventoryItem(InventoryItem $inventoryItem, int $neededCount): ExcessItem
    {
        return new self($inventoryItem->name, $inventoryItem->count, $neededCount);
    }

    public function hasExcessFactorOfAtLeast(float $factor): bool
    {
        return $this->count > $this->neededCount * $factor;
    }

    public function inExcessCount(): int
    {
        return $this->count - $this->neededCount;
    }

    public function inExcessWeight(): int|float
    {
        return $this->inExcessCount() * $this->weight;
    }

    public function inExcessFactor(): float
    {
        if ($this->neededCount == 0) return 0;

        return $this->count / $this->neededCount;
    }
}
