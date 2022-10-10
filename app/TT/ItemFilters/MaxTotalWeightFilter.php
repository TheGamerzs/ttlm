<?php

namespace App\TT\ItemFilters;

use App\TT\Items\InventoryItem;
use Illuminate\Support\Collection;

class MaxTotalWeightFilter
{
    public function handle(Collection $itemCollection, \Closure $next, int $maxTotalWeight)
    {
        return $next(
            $itemCollection->reject(function (InventoryItem $item) use ($maxTotalWeight) {
                return $item->getTotalWeight() > $maxTotalWeight;
            })
        );
    }
}
