<?php

namespace App\TT\Items;

use App\TT\Recipe;
use App\TT\RecipeFactory;

class Item
{
    public string $name;

    public ?string $prettyName;

    public int $weight = 0;

    public function __construct(string $internalName)
    {
        $data = ItemData::getFromInternalName($internalName);
        $this->name   = $internalName;
        if ($data) {
            $this->weight = (int) $data->weight;
            $this->prettyName = $data->name;
        }
    }

    public function name(): string
    {
        return isset($this->prettyName)
            ? str($this->prettyName)->after(': ')
            : str($this->name)->title();
    }

    public function getRecipe(): Recipe
    {
        return RecipeFactory::get($this);
    }

    public function howManyCanFitInSpace(int $capacityKG): int
    {
        if ($this->weight) {
            return floor($capacityKG / $this->weight);
        }
        return 0;
    }
}
