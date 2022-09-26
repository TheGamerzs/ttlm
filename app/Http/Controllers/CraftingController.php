<?php

namespace App\Http\Controllers;

use App\TT\Items\CraftingMaterial;
use App\TT\Items\Item;
use App\TT\RecipeFactory;
use App\TT\StorageFactory;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class CraftingController extends Controller
{
    public function index(string $name = 'house')
    {
        $truckCapacity = (int) Auth::user()->truckCapacity ?? 0;
        $trainYardStorage = (int) Auth::user()->trainYardCapacity ?? 0;
        $pocketCapacity = (int) Auth::user()->pocketCapacity ?? 0;

        $parentRecipe = RecipeFactory::get(new Item($name));

        return view('crafting')->with([
            'recipeName' => $name,
            'parentRecipe' => $parentRecipe,
            'truckCapacity' => $truckCapacity,
            'trainYardStorage' => $trainYardStorage,
            'pocketCapacity' => $pocketCapacity,
        ]);
    }

}
