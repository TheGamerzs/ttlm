<?php

namespace App\TT\ItemFilters;

use App\TT\Items\Item;
use App\TT\Items\ItemNames;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class NonTruckingItemsFilter
{
    public function handle(Collection $itemCollection, \Closure $next)
    {
        return $next(
            $itemCollection->reject(function (Item $item) {
                return Str::of($item->name)
                    ->startsWith(ItemNames::truckingItemsStartWith());
            })
        );
    }
}
