<?php

namespace App\View\ShoppingList;

use App\TT\Items\ItemData;
use App\TT\RecipeShoppingListDecorator;
use Illuminate\Support\Collection;

class ShoppingListViewModel
{
    protected Collection $totalNeededList;

    protected Collection $stillNeededList;

    protected array $flatStillNeededCounts;

    public function __construct(Collection $totalNeededList, Collection $stillNeededList)
    {
        $this->stillNeededList = $stillNeededList;
        $this->totalNeededList = $totalNeededList;

        $stillNeededCounts = $stillNeededList->only(['crafted', 'refined', 'scrap'])
            ->flatten()
            ->mapWithKeys(function (RecipeShoppingListDecorator $item) {
                return [$item->recipeName => $item->count];
            })
            ->toArray();

        $this->flatStillNeededCounts = $totalNeededList->only(['crafted', 'refined', 'scrap'])
            ->flatten()
            ->mapWithKeys(function (RecipeShoppingListDecorator $item) use ($stillNeededCounts) {
                $count = array_key_exists($item->recipeName, $stillNeededCounts)
                    ? $stillNeededCounts[$item->recipeName]
                    : 0;

                return [$item->recipeName => $count];
            })
            ->merge($this->stillNeededColumnsForPickups())
            ->toArray();
    }

    public function showType(string $type): bool
    {
        if ($type == 'pickup') return true;
        return (bool) $this->totalNeededList[$type]->count();
    }

    public function getStillNeededCount(string $internalName): int
    {
        return $this->flatStillNeededCounts[$internalName];
    }

    public function getTotalNeededCount(string $internalName): int
    {
        return $this->totalNeededList
                ->only(['scrap', 'crafted', 'refined'])
                ->flatten()
                ->firstWhere('recipeName', $internalName)
                ->count ?? 0;
    }

    public function itemNameColumnHeaders(string $type): Collection
    {
        if ($type == 'pickup') return $this->itemNameColumnHeadersForPickups();

        return $this->totalNeededList[$type]->map(function (RecipeShoppingListDecorator $recipeListItem) {
            $columnHeader = new \stdClass();
            $columnHeader->displayName = $recipeListItem->recipe->displayName();
            $columnHeader->isStillNeeded = (bool) $this->getStillNeededCount($recipeListItem->recipeName);
            $columnHeader->internalName = $recipeListItem->recipeName;
            $columnHeader->totalNeeded = $this->getTotalNeededCount($recipeListItem->recipeName);
            return $columnHeader;
        });
    }

    protected function itemNameColumnHeadersForPickups(): Collection
    {
        return collect($this->totalNeededList['pickupCalculator']->baseItemsCounts)
            ->keys()
            ->map(function ($internalName) {
                $columnHeader = new \stdClass();
                $columnHeader->displayName = ItemData::getName($internalName, true);
                $columnHeader->isStillNeeded = (bool) $this->getStillNeededCount($internalName);
                return $columnHeader;
            });
    }

    public function totalNeededColumns(string $type): Collection
    {
        if ($type == 'pickup') return $this->totalNeededColumnsForPickups();

        return $this->totalNeededList[$type]->pluck('count');
    }

    protected function totalNeededColumnsForPickups(): Collection
    {
        return collect($this->totalNeededList['pickupCalculator']->baseItemsCounts)
            ->map(function ($count) {
                return (int) max($count, 0);
            });
    }

    public function stillNeededColumns(string $type): Collection
    {
        if ($type == 'pickup') return $this->stillNeededColumnsForPickups();

        return $this->totalNeededList[$type]->map(function (RecipeShoppingListDecorator $recipeListItem) use ($type) {
            return $this->stillNeededList[$type]
                ->firstWhere('recipeName', $recipeListItem->recipeName)
                ->count
                ?? 0;
        });
    }

    protected function stillNeededColumnsForPickups(): Collection
    {
        return collect($this->totalNeededList['pickupCalculator']->baseItemsCounts)
            ->map(function ($count, $internalName) {
                return (int) max(
                    $this->stillNeededList['pickupCalculator']->baseItemsCounts[$internalName],
                    0);
            });
    }

    public function totalCraftingCost(): string
    {
        return '~$' . number_format($this->totalNeededList['totalCost']);
    }

    public function remainingCraftingCost(): string
    {
        return '~$' . number_format($this->stillNeededList['totalCost']);
    }
}
