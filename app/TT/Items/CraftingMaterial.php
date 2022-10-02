<?php

namespace App\TT\Items;

use App\TT\Recipe;
use App\TT\Storage;

class CraftingMaterial extends Item
{
    public int $recipeCount;

    public int $inStorage;

    public Recipe $recipe;

    public function __construct(string $name, Recipe $recipe, int $recipeCount = 1)
    {
        parent::__construct($name);

        $this->recipeCount = $recipeCount;
        $this->recipe      = $recipe;
    }

    public function getTotalWeightNeeded(): int
    {
        return $this->weight * $this->recipeCount;
    }

    public function setInStorage(Storage $storage): self
    {
        $this->inStorage = $storage->firstWhere('name', $this->name)->count ?? 0;

        return $this;
    }

    public function recipesCraftableFromStorage(): int
    {
        if (!isset($this->inStorage)) return 0;

        return (int) floor(
            $this->inStorage / $this->recipeCount
        );
    }

}
