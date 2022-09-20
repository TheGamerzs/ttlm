<?php

namespace App\TT\Items;

use App\TT\Weights;

class Item
{
    public string $name;

    public int    $weight;

    public function __construct(string $name)
    {
        $this->name   = $name;
        $this->weight = Weights::getWeight($name);
    }

    public function howManyCanFitInSpace(int $compacityKG): int
    {
        return floor($compacityKG / $this->weight);
    }
}
