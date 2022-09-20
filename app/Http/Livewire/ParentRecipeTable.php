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

    public int $truckCompacity;

    public string $storageName = 'faq_522';

    public string|Recipe $parentRecipe = '';

    protected $listeners = ['refresh' => '$refresh'];

    public function mount()
    {
        $this->storageName = Session::get('ParentRecipeTableStorageName', 'faq_522');
    }

    public function updatedStorageName($value)
    {
        Session::put('ParentRecipeTableStorageName', $value);
        $this->forgetComputed('storage');
        $this->parentRecipe->setInStorageForAllComponents($this->storage);
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
        if ($this->parentRecipe->name() == 'house') {
            return $craftingMaterial->recipeCount * $this->parentRecipe->mostLimitedBy()->recipesCraftableFromStorage();
        }
        return $craftingMaterial->recipeCount * $this->parentRecipe->howManyCanFit($this->truckCompacity);
    }

    public function getFillTruckString(): string
    {
        if ($this->parentRecipe->name() == 'house') {
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
