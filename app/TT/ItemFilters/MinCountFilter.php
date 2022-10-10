<?php

namespace App\TT\ItemFilters;

use App\TT\Items\InventoryItem;
use Illuminate\Support\Collection;

class MinCountFilter
{
    public function handle(Collection $itemCollection, \Closure $next, int $minCount)
    {
        return $next(
            $itemCollection->reject(function (InventoryItem $item) use ($minCount) {
                return $item->count < $minCount;
            })
        );
    }
}
