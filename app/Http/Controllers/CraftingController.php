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

        $parentRecipe = RecipeFactory::get(new Item($name))
            ->setInStorageForAllComponents(
                StorageFactory::get(
                    Session::get('ParentRecipeTableStorageName', 'faq_522')
                )
            );

        return view('crafting')->with([
            'recipeName' => $name,
            'parentRecipe' => $parentRecipe,
            'truckCompacity' => $truckCompacity,
            'trainYardStorage' => $trainYardStorage,
        ]);
    }

}
