<?php

namespace App\TT\ItemFilters;

use App\TT\Items\InventoryItem;
use Illuminate\Support\Collection;

class MaxCountFilter
{
    public function handle(Collection $itemCollection, \Closure $next, int $maxCount)
    {
        return $next(
            $itemCollection->reject(function (InventoryItem $item) use ($maxCount) {
                return $item->count > $maxCount;
            })
        );
    }
}
