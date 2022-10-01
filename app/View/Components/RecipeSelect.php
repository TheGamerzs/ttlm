<?php

namespace App\View\Components;

use App\TT\Recipes;
use App\TT\Weights;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class RecipeSelect extends ItemSelect
{
    public bool $includeBaseItems;

    public function __construct(?string $changeWireModel = null, bool $includeBaseItems = true)
    {
        $this->changeWireModel = $changeWireModel;
        $this->includeBaseItems = $includeBaseItems;
    }

    public function getItemNames(): array
    {
        if ($this->includeBaseItems) {
            return Recipes::getAllNames();
        } else {
            return Recipes::getNamesIfComponentsExist()->toArray();
        }
    }
}
