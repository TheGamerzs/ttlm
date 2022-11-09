<?php

namespace App\Http\Controllers;

use App\TT\RecipeFactory;
use Illuminate\Support\Facades\Auth;

class CraftingController extends Controller
{
    public function index(string $name = null)
    {
        if (empty($name)) {
            $name = Auth::user()->default_crafting_recipe;
        }

        $truckCapacity = (int) Auth::user()->truckCapacity ?? 0;
        $trainYardStorage = (int) Auth::user()->trainYardCapacity ?? 0;
        $pocketCapacity = (int) Auth::user()->pocketCapacity ?? 0;

        $parentRecipe = RecipeFactory::get($name);

        return view('crafting')->with([
            'recipeName' => $name,
            'parentRecipe' => $parentRecipe,
            'truckCapacity' => $truckCapacity,
            'trainYardStorage' => $trainYardStorage,
            'pocketCapacity' => $pocketCapacity,
        ]);
    }

}
