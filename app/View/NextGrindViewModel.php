<?php

namespace App\View;

use App\TT\Inventories;
use App\TT\Items\Item;
use App\TT\Recipe;
use App\TT\RecipeFactory;
use App\TT\Trunk;
use Illuminate\Support\Collection;

class NextGrindViewModel
{
    public Recipe       $recipe;

    public Inventories $inventories;

    public function __construct(Inventories $inventories)
    {
        $this->inventories = $inventories;
    }

    public function setRecipeFromString(string $internalName): self
    {
        $this->setRecipe(
            RecipeFactory::get(new Item($internalName))
        );

        return $this;
    }

    public function setRecipe(Recipe $recipe): NextGrindViewModel
    {
        $this->recipe = $recipe;

        foreach($this->inventories as $trunk) {
            /** @var Trunk $trunk */
            $trunk->fillLoadWithComponentsForRecipe($recipe);
        }

        return $this;
    }

    public function showComponentTable(): bool
    {
        return (bool) $this->recipe->components->count();
    }

    public function itemsThatCanBeCraftedFromAFullLoadOfComponents(): int
    {
        return $this->inventories->trunks
            ->sum(function (Trunk $trunk) {
                return $this->recipe->howManyItemsCanFit($trunk->capacity);
            });
    }

    public function recipesThatCanBeCraftedFromAFullLoadOfComponents(): int
    {
        return $this->inventories->trunks
            ->sum(function (Trunk $trunk) {
                return $this->recipe->howManyRecipesCanFit($trunk->capacity);
            });
    }

    public function storageDropdownOptions(): Collection
    {
        return \App\TT\StorageFactory::getRegisteredNames(true);
    }
}
