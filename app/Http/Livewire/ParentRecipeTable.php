<?php

namespace App\Http\Livewire;

use App\TT\Items\CraftingMaterial;
use App\TT\Items\InventoryItem;
use App\TT\Items\Item;
use App\TT\Recipe;
use App\TT\RecipeFactory;
use App\TT\Storage;
use App\TT\StorageFactory;
use Illuminate\Support\Facades\Session;
use Livewire\Component;

/**
 * @property Storage $storage
 * @property int     $countCanBeMade
 */
class ParentRecipeTable extends Component
{
    use ParentRecipeLivewireCast;

    protected $listeners = [
        'refresh' => '$refresh'
    ];

    public int $truckCapacity;

    public array|string $storageName = 'combined';

    public string|Recipe $parentRecipe = '';

    public function mount()
    {
        $this->storageName = $this->parentRecipe->autoSetStorageBasedOnComponentsLocation();
    }

    public function updatedStorageName($value)
    {
        // Livewire BS
        if (is_string($this->storageName)) {
            $this->forgetComputed('storage');
            $this->parentRecipe->setInStorageForAllComponents($this->storage);
        }
    }

    /*
    |--------------------------------------------------------------------------
    | Business
    |--------------------------------------------------------------------------
    */

    public function getStorageProperty(): Storage
    {
        return StorageFactory::get($this->storageName);
    }

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

    public function render()
    {
        $this->emit('updateNextRecipeToGrind', $this->parentRecipe->mostLimitedBy()->name);
        return view('livewire.parent-recipe-table');
    }
}
