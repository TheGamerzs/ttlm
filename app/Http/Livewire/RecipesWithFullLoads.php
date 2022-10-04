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
    protected $listeners = [
        'refresh' => '$refresh'
    ];

    public int $truckCapacity;

    public string $storageName = 'combined';

    public string $capacityUsed = '';

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
        ];

        return $allRecipes->filter(function ($item, $key) use ($otherThanCrafted) {
            $recipeName = Str::of($key);
            return
                $recipeName->startsWith('crafted') ||
                $recipeName->is($otherThanCrafted);
        })->map(function ($item, $key) use ($storage) {
            return RecipeFactory::get(new Item($key))->setInStorageForAllComponents($storage);
        })->filter(function (Recipe $recipe) {
            return $recipe->craftableRecipesFromStorage() >= $recipe->howManyRecipesCanFit($this->truckCapacity - (int)$this->capacityUsed);
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
