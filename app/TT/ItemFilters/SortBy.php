<?php

namespace App\TT\ItemFilters;

use App\TT\Storage;
use App\TT\StorageFactory;
use Illuminate\Support\Collection;

class SortBy
{
    public function handle(Storage $itemCollection, \Closure $next, string $sortByString)
    {
        if ($sortByString == 'weight') {
            return $next(
                $itemCollection->sortByWeight()
            );
        }

        return $next(
            $itemCollection->sortByCount()
        );
    }
}
