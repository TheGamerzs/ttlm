<?php

namespace App\TT;

use App\TT\Items\Item;
use Illuminate\Support\Stringable;

class Trunk
{
    public string $name;

    public int    $capacity;

    public int    $capacityUsed = 0;

    public function __construct(string $name, int $capacity)
    {
        $this->name = $name;
        $this->capacity = $capacity;
    }

    public function setCapacityUsed(int $int): self
    {
        $this->capacityUsed = $int;
        return $this;
    }

    public function getAvailableCapacity(): int
    {
        return $this->capacity - $this->capacityUsed;
    }

    public function numberOfItemsThatCanFitFromWeight(int $itemWeight): int
    {
        return $this->getAvailableCapacity() / $itemWeight;
    }

    public function numberOfItemsThatCanFit(Item $item): int
    {
        return $this->numberOfItemsThatCanFitFromWeight($item->weight);
    }

    public function displayName(): Stringable
    {
        return str($this->name)->title();
    }
}
