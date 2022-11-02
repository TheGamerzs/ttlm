<?php

namespace App\TT;


use Illuminate\Support\Collection;
use Traversable;

class Inventories implements \IteratorAggregate, \Countable
{
    public Collection $trunks;

    public function __construct(array $trunks = [])
    {
        $this->trunks = collect($trunks);
    }

    public function getIterator(): Traversable
    {
        return $this->trunks->getIterator();
    }

    public function count(): int
    {
        return $this->trunks->count();
    }

    public function createTrunk(string $name, ?int $capacity): self
    {
        if (is_null($capacity)) return $this;

        $this->trunks->push(new Trunk($name, $capacity));
        return $this;
    }

    public function addTrunk(Trunk $trunk): self
    {
        $this->trunks->push($trunk);
        return $this;
    }

    public function setCapacityUsed(string $trunkName, int $capacityUsed): self
    {
        /** @var Trunk $trunk */
        $trunk = $this->trunks->firstWhere('name', $trunkName);
        $trunk?->setCapacityUsed($capacityUsed);

        return $this;
    }

    public function createCombined(): self
    {
        $this->createTrunk(
            'combined',
            $this->trunks->sum('capacity')
        );
        return $this;
    }

}
