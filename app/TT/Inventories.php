<?php

namespace App\TT;


use Illuminate\Support\Collection;

class Inventories
{
    public Collection $items;

    public function __construct()
    {
        $this->items = collect();
    }

    public function createTrunk(string $name, int $capacity): self
    {
        $this->items->push(new Trunk($name, $capacity));
        return $this;
    }

    public function addTrunk(Trunk $trunk): self
    {
        $this->items->push($trunk);
        return $this;
    }
}
