<?php

namespace App\TT;

use App\TT\Factories\ItemFactory;
use App\TT\Items\InventoryItem;
use App\TT\Items\Item;
use Illuminate\Support\Collection;
use Illuminate\Support\Stringable;

class Trunk
{
    public string $name;

    public int    $capacity;

    public int    $capacityUsed = 0;

    /**
     * @var Collection<InventoryItem>
     */
    public Collection $load;

    public function __construct(string $name, int $capacity, array $load = [])
    {
        $this->name = $name;
        $this->capacity = $capacity;
        $this->load = collect($load);
    }

    public function setCapacityUsed(int $int): self
    {
        $this->capacityUsed = $int;
        return $this;
    }

    public function getAvailableCapacity(): int
    {
        return $this->capacity - $this->capacityUsed - $this->loadWeight();
    }

    public function capacityUsedPercent(): float
    {
        return ($this->loadWeight() + $this->capacityUsed) / $this->capacity;
    }

    public function numberOfItemsThatCanFitFromWeight(int $itemWeight): int
    {
        if (empty($itemWeight)) return 0;
        return (int) floor($this->getAvailableCapacity() / $itemWeight);
    }

    public function numberOfItemsThatCanFit(Item $item): int
    {
        return $this->numberOfItemsThatCanFitFromWeight($item->weight);
    }

    public function displayName(): Stringable
    {
        return str($this->name)->headline();
    }

    public function fillLoadWithComponentsForRecipe(Recipe $recipe, bool $limitToStorage = true, bool $diminishComponentInStorage = false): self
    {
        $this->load = $recipe->componentsThatCanFitAsInventoryItems($this->getAvailableCapacity(), $limitToStorage);

        if ($diminishComponentInStorage) {
            foreach ($recipe->components as $item) {
                $item->inStorage -= $this->load->firstWhere('name', $item->name)->count;
            }
        }

        return $this;
    }

    public function fillLoadWithItem(string $itemName): self
    {
        $item = ItemFactory::makeInventoryItem($itemName, 0);

        $item->count = floor($this->getAvailableCapacity() / $item->weight);

        $this->load->push($item);

        return $this;
    }

    public function loadWeight(): int
    {
        return $this->load->sum(function (InventoryItem $item) {
            return $item->getTotalWeight();
        });
    }
}
