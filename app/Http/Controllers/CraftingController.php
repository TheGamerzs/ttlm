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
        $truckCompacity = (int) Auth::user()->truckCompacity ?? 0;
        $trainYardStorage = (int) Auth::user()->trainYardCompacity ?? 0;
        $pocketCompacity = (int) Auth::user()->pocketCompacity ?? 0;

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
