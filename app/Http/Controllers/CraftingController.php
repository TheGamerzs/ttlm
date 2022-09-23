<?php

namespace App\Http\Controllers;

use App\TT\Items\CraftingMaterial;
use App\TT\Items\Item;
use App\TT\RecipeFactory;
use App\TT\StorageFactory;
use Illuminate\Support\Facades\Session;

class CraftingController extends Controller
{
    public function index(string $name = 'house')
    {
        $truckCompacity = 9775;
        $trainYardStorage = 30107;
        $pocketCompacity = 645;

        $parentRecipe = RecipeFactory::get(new Item($name));

        return view('crafting')->with([
            'recipeName' => $name,
            'parentRecipe' => $parentRecipe,
            'truckCompacity' => $truckCompacity,
            'trainYardStorage' => $trainYardStorage,
            'pocketCompacity' => $pocketCompacity,
        ]);
    }

}
