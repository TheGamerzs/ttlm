<?php

namespace App\TT\Items;

use App\TT\Recipe;
use App\TT\RecipeFactory;
use App\TT\Weights;

class Item
{
    public string $name;

    public ?int $weight;

    public function __construct(string $name)
    {
        $this->name   = $name;
        $this->weight = Weights::getWeight($name);
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
