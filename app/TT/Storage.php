<?php

namespace App\TT;

use Illuminate\Support\Collection;

class Storage extends Collection
{
    public function totalWeight(): int
    {
        return $this->sum(function ($inventoryItem) {
            return $inventoryItem->weight;
        });
    }

    public function sortByWeight(): self
    {
        return $this->sortByDesc(function ($inventoryItem) {
            return $inventoryItem->getTotalWeight();
        });
    }

    public function sortByCount(): self
    {
        return $this->sortByDesc(function ($inventoryItem) {
            return $inventoryItem->count;
        });
    }

    public function splitAndZip(): self
    {
        $split = $this->splitIn(2);
        return $split[0]->zip($split[1]);
    }
}
