<?php

namespace App\TT;

use App\TT\Items\CraftingMaterial;
use App\TT\Items\Item;

class RecipeFactory
{
    public static function get(Item $item): Recipe
    {
        $recipe = new Recipe($item);
        $recipeDefinition = Recipes::getRecipe($item->name);

        $recipe->craftingLocation = $recipeDefinition['craftingLocation'] ?? null;
        $recipe->pickupRun = $recipeDefinition['pickupRun'] ?? false;
        $recipe->makes = $recipeDefinition['makes'] ?? 1;

        foreach ($recipeDefinition['components'] as $name => $count) {
            $recipe->components->push(new CraftingMaterial($name, $recipe, $count));
        }

        return $recipe;
    }
}
