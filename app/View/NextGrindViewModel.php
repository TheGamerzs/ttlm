<?php

namespace App\View;

use App\TT\Inventories;
use App\TT\Recipe;
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

    public function setRecipe(Recipe $recipe): NextGrindViewModel
    {
        $this->recipe = $recipe;
        $this->inventories->fillTrunksWithRecipeComponents($recipe);

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

    public function runsThatCanBeMade(): int|float
    {
        $this->recipe->mostLimitedBy();
        $temp = new Inventories();
        foreach ($this->inventories as $trunk) {
            $temp->createTrunk($trunk->name, $trunk->capacity);
        }
        foreach ($temp->trunks as $trunk) {
            $trunk->fillLoadWithComponentsForRecipe($this->recipe, false);
        }

        $fullLoadCarries = $temp->trunks->sum(function (Trunk $trunk) {
            return $trunk->load->firstWhere('name', $this->recipe->mostLimitedBy()->name)->count;
        });

        return $this->recipe->mostLimitedBy()->inStorage / $fullLoadCarries;
    }

    public function runsThatCanBeMadeDisplayString(): string
    {
        $runCount = $this->runsThatCanBeMade();

        // Don't show decimal if a whole number.
        $runs = (floor($runCount) == $runCount)
            ? number_format($runCount)
            : number_format($runCount, 1);

        // Plural or not
        return (float) $runs > 1
            ? $runs . ' Runs'
            : $runs . ' Run';
    }

    public function customViewName(): string
    {
        return 'next-grind-custom.' . $this->recipe->kebabName();
    }
}
