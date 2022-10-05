<?php

namespace App\Http\Livewire;

use App\TT\Items\Item;
use App\TT\Recipe;
use App\TT\RecipeFactory;

trait ParentRecipeLivewireCast
{
    public function hydrateParentRecipe($value): void
    {
        $this->parentRecipe = RecipeFactory::get(new Item($value));
    }

    public function dehydrateParentRecipe(Recipe $parentRecipe): void
    {
        $this->parentRecipe = $parentRecipe->internalName();
    }
}
