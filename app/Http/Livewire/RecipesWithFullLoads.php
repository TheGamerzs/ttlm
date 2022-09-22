<?php

namespace App\Http\Livewire;

use App\TT\Items\Item;
use App\TT\Recipe;
use App\TT\RecipeFactory;
use App\TT\Recipes;
use App\TT\StorageFactory;
use Illuminate\Support\Str;
use Livewire\Component;

class RecipesWithFullLoads extends Component
{
    public int $truckCompacity;

    public string $storageName = 'faq_522';

    public function get()
    {
        $storage = StorageFactory::get($this->storageName);
        $allRecipes = collect(Recipes::getAllRecipes());

        return $allRecipes->filter(function ($item, $key) {
            return Str::of($key)->startsWith('crafted');
        })->map(function ($item, $key) use ($storage) {
            return RecipeFactory::get(new Item($key))->setInStorageForAllComponents($storage);
        })->filter(function (Recipe $recipe) {
            return $recipe->craftableRecipesFromStorage() >= $recipe->howManyCanFit($this->truckCompacity);
        });
    }

    public function render()
    {
        $this->get();
        return view('livewire.recipes-with-full-loads')->with([
            'craftableRecipes' => $this->get()
        ]);
    }
}
