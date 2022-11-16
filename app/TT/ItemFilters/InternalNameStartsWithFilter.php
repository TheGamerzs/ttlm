<?php

namespace App\TT\ItemFilters;

use App\TT\Items\Item;
use Closure;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class InternalNameStartsWithFilter
{
    public function handle(Collection $itemCollection, Closure $next, string $startsWithString)
    {
        return $next(
            $itemCollection->filter(function (Item $item) use ($startsWithString) {
                return Str::of($item->name)->contains($startsWithString);
            })
        );
    }
}
