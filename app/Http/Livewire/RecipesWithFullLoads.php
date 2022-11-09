<?php

namespace App\Http\Livewire;

use App\TT\Inventories;
use App\TT\Recipe;
use App\TT\RecipeFactory;
use App\TT\Recipes;
use App\TT\StorageFactory;
use App\TT\Trunk;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class RecipesWithFullLoads extends BaseComponent
{
    protected $listeners = [
        'refresh' => '$refresh'
    ];

    public string $trunkOneCapacityUsed = '';

    public string $trunkTwoCapacityUsed = '';

    public string $storageName = 'combined';

    public function get()
    {
        $storage = StorageFactory::get($this->storageName);
        $allRecipes = collect(Recipes::getAllRecipes());
        $otherThanCrafted = [
            'refined_solder',
            'refined_aluminum',
            'refined_amalgam',
            'refined_bronze',
            'refined_tin',
            'sd_zinc_alloy'
        ];

        /** @var Inventories $inventories */
        $inventories = Auth::user()->makeTruckingInventories();
        $inventories->trunks->first()->setCapacityUsed((int) $this->trunkOneCapacityUsed);
        if ($inventories->trunks->count() > 1) {
            $inventories->trunks->offsetGet(1)->setCapacityUsed((int) $this->trunkTwoCapacityUsed);
        }

        $availableCapacity = $inventories->trunks->sum(function (Trunk $trunk) {
                return $trunk->getAvailableCapacity();
            });

        return $allRecipes->filter(function ($item, $key) use ($otherThanCrafted) {
            $recipeName = Str::of($key);
            return
                $recipeName->startsWith('crafted') ||
                $recipeName->is($otherThanCrafted);
        })->map(function ($item, $key) use ($storage) {
            return RecipeFactory::get($key)->setInStorageForAllComponents($storage);
        })->filter(function (Recipe $recipe) use ($availableCapacity) {
            return $recipe->craftableRecipesFromStorage() >= $recipe->howManyRecipesCanFit($availableCapacity);
        })->sortBy(function (Recipe $recipe) {
            return StorageFactory::getCountFromCombinedForItem($recipe->inventoryItem);
        });
    }

    public function render()
    {
        $this->get();
        return view('livewire.recipes-with-full-loads')->with([
            'craftableRecipes' => $this->get(),
            'inventories' => Auth::user()->makeTruckingInventories()
        ]);
    }
}
