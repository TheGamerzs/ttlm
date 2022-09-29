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
        // When there are 0 items, return an instance of empty collections the same shape as expected.
        if ($this->count() == 0) {
            return new Storage([
                new Storage(),
                new Storage()
            ]);
        }

        // When there is one item, put this collection in the first index and an empty one in the second.
        if ($this->count() == 1) {
            return new Storage([
                $this,
                new Storage()
            ]);
        }

        // Normal behavior with two or more items.
        $split = $this->splitIn(2);
        return $split[0]->zip($split[1]);
    }
}
