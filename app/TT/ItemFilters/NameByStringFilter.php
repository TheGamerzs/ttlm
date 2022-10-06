<?php

namespace App\TT\ItemFilters;

use App\TT\Items\InventoryItem;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class NameByStringFilter
{

    public function handle(Collection $itemCollection, \Closure $next, string $searchString)
    {
        if (empty($searchString)) return $next($itemCollection);

        $search = Str::of($searchString)->lower();

        $itemCollection = $itemCollection->filter(function (InventoryItem $item) use ($search) {
            return Str::of($item->prettyName)->lower()->contains($search);
        });

        return $next($itemCollection);
    }

}
