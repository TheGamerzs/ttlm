<?php

namespace App\Http\Controllers;

use App\TT\Items\CraftingMaterial;
use App\TT\Items\Item;
use App\TT\Items\SellableItem;
use App\TT\RecipeFactory;
use App\TT\Recipes;
use App\TT\RecipeShoppingListDecorator;
use App\TT\ShoppingListBuilder;
use App\TT\StorageFactory;
use App\TT\Weights;

class SandboxController extends Controller
{
    public function index()
    {
        $houseRecipe = RecipeFactory::get(new Item('house'));
        $calc = ShoppingListBuilder::build($houseRecipe, StorageFactory::get(), 300, 9775);

        dd($calc->getRunCalculations());
    }

    public function missingRecipes()
    {
        $recipes = collect(Recipes::getRecipe())->keys();
        $weights = collect(Weights::$weights)->keys();

        dd($weights->diff($recipes));
    }
}
