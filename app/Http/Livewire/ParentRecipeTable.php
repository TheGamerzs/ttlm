<?php

namespace App\Http\Livewire;

use App\TT\Items\CraftingMaterial;
use App\TT\Recipe;
use App\TT\Storage;
use App\TT\StorageFactory;
use App\TT\Trunk;
use Illuminate\Support\Facades\Auth;

/**
 * @property Storage $storage
 * @property int     $countCanBeMade
 */
class ParentRecipeTable extends BaseComponent
{
    use ParentRecipeLivewireCast;

    protected $listeners = [
        'refresh' => '$refresh',
        'refreshParentRecipeTable' => '$refresh'
    ];

    public int $truckCapacity;

    public string $storageName = 'combined';

    public string|Recipe $parentRecipe = '';

    protected Storage $storage;

    public function mount()
    {
        $this->storageName = $this->parentRecipe->autoSetStorageBasedOnLocationOfMostComponents();
    }

    public function booted()
    {
        $this->setStorage();
    }

    protected function setStorage()
    {
        $this->storage = StorageFactory::get($this->storageName);
        $this->parentRecipe->setInStorageForAllComponents($this->storage);
    }

    public function updatedStorageName($value)
    {
        // Livewire BS
        if (is_string($this->storageName)) {
            $this->setStorage();
        }
    }

    /*
    |--------------------------------------------------------------------------
    | Business
    |--------------------------------------------------------------------------
    */

    public function getCountCanBeMadeProperty(): int
    {
        return $this->parentRecipe->mostLimitedBy()->recipesCraftableFromStorage();
    }

    public function getFillTruckCount(CraftingMaterial $craftingMaterial): int
    {
        if ($this->parentRecipe->internalName() == 'house') {
            return $craftingMaterial->recipeCount * $this->parentRecipe->mostLimitedBy()->recipesCraftableFromStorage();
        }
        return $craftingMaterial->recipeCount * $this->parentRecipe->howManyRecipesCanFit($this->truckCapacity);
    }

    public function getFillTruckString(): string
    {
        if ($this->parentRecipe->internalName() == 'house') {
            return 'Transfer';
        }
        return 'Fill Trailer';
    }

    public function recipesThatCanFitInFullLoad()
    {
        return Auth::user()->makeTruckingInventories()->trunks
            ->sum(function (Trunk $trunk) {
                return $this->parentRecipe->howManyItemsCanFit($trunk->capacity);
            });
    }

    public function render()
    {
        $this->emit('updateNextRecipeToGrind', $this->parentRecipe->mostLimitedBy()->name);
        return view('livewire.parent-recipe-table');
    }
}
