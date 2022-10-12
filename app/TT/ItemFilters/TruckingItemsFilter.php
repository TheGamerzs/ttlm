<?php

namespace App\TT\ItemFilters;

use App\TT\Items\Item;
use App\TT\Items\ItemData;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class TruckingItemsFilter
{
    public function handle(Collection $itemCollection, \Closure $next)
    {

        return $next(
            $itemCollection->filter(function (Item $item) {
                return Str::of($item->name)
                    ->startsWith(ItemData::truckingItemsStartWith());
            })
        );
    }
}
