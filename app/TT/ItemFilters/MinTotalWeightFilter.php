<?php

namespace App\TT\ItemFilters;

use App\TT\Items\InventoryItem;
use Illuminate\Support\Collection;

class MinTotalWeightFilter
{
    public function handle(Collection $itemCollection, \Closure $next, int $minTotalWeight)
    {
        return $next(
            $itemCollection->reject(function (InventoryItem $item) use ($minTotalWeight) {
                return $item->getTotalWeight() < $minTotalWeight;
            })
        );
    }
}
