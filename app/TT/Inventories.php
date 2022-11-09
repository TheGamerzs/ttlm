<?php

namespace App\TT;


use App\TT\Items\CraftingMaterial;
use App\TT\Items\InventoryItem;
use Illuminate\Support\Collection;
use Traversable;

class Inventories implements \IteratorAggregate, \Countable
{
    /**
     * @var Collection|Trunk[]
     *
     */
    public Collection|array $trunks;

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

    public function totalAvailableCapacity(): int
    {
        return $this->trunks->sum(function (Trunk $trunk) {
            return $trunk->getAvailableCapacity();
        });
    }

    public function fillTrunksWithRecipeComponents(Recipe $recipe)
    {
        // Clone recipe to mutate storage numbers
        $inStorage = $recipe->components->map(function (CraftingMaterial $item) {
            return InventoryItem::fromCraftingMaterial($item, $item->inStorage);
        });
        $recipe = RecipeFactory::get($recipe->internalName())->setInStorageForAllComponents(new Storage($inStorage));

        foreach($this->trunks as $trunk) {
            $trunk->fillLoadWithComponentsForRecipe($recipe, true, true);
        }
    }

}
