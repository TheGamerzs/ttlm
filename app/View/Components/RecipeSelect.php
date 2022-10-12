<?php

namespace App\View\Components;

use App\TT\Items\ItemData;
use App\TT\Recipes;
use Illuminate\Support\Collection;

class RecipeSelect extends ItemSelect
{
    public bool $includeBaseItems;

    public function __construct(?string $changeWireModel = null, bool $includeBaseItems = true)
    {
        $this->changeWireModel = $changeWireModel;
        $this->includeBaseItems = $includeBaseItems;
    }

    public function getItemNames(): array|Collection
    {
        if ($this->includeBaseItems) {
            return collect(Recipes::getAllNames())->mapWithKeys(function ($internalName) {
                return [$internalName => ItemData::getName($internalName)];
            })->sort();
        } else {
            return Recipes::getNamesIfComponentsExist(true);
        }
    }
}
