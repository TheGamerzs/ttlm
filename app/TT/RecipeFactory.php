<?php

namespace App\TT;

use App\TT\Factories\ItemFactory;
use App\TT\Items\Item;

class RecipeFactory
{
    public static function get(Item|string $item): Recipe
    {
        //Make Item if string is passed in.
        $item = $item instanceof Item
            ? $item
            : new Item($item);

        $recipe           = new Recipe($item);
        $recipeDefinition = Recipes::getRecipe($item->name);

        $recipe->craftingLocation = $recipeDefinition['craftingLocation'] ?? null;
        $recipe->pickupRun        = $recipeDefinition['pickupRun'] ?? false;
        $recipe->makes            = $recipeDefinition['makes'] ?? 1;
        $recipe->cost             = $recipeDefinition['cost'];

        foreach ($recipeDefinition['components'] as $internalName => $count) {
            $recipe->components->push(ItemFactory::makeCraftingMaterial($internalName, $recipe, $count));
        }

        return $recipe;
    }
}
