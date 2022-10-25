<?php

namespace App\TT\Items;

use App\TT\Recipe;
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

    public static function makeList(int $neededRecipeCount, Recipe $recipe)
    {
        $needed = ShoppingListBuilder::build(
                    $recipe,
                    new Storage(),
                    $neededRecipeCount,
                    1000
                )
                ->only(['crafted', 'refined', 'scrap'])
                ->flatten();

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
                return $excessItem->hasExcessFactorOf(2);
            })
            ->values()
            ->sortByDesc->inExcessFactor();
    }

    public static function makeFromInventoryItem(InventoryItem $inventoryItem, int $neededCount): ExcessItem
    {
        return new self($inventoryItem->name, $inventoryItem->count, $neededCount);
    }

    public function hasExcessFactorOf(float $factor): bool
    {
        return $this->count > $this->neededCount * $factor;
    }

    public function inExcessCountOf(): int
    {
        return $this->count - $this->neededCount;
    }

    public function inExcessWeightOf(): int|float
    {
        return $this->inExcessCountOf() * $this->weight;
    }

    public function inExcessFactor(): float
    {
        return $this->count / $this->neededCount;
    }
}
