<?php

namespace App\Http\Livewire;

use App\TT\Items\CraftingMaterial;
use App\TT\Items\Item;
use App\TT\PickupRun;
use App\TT\Recipe;
use App\TT\RecipeFactory;
use App\TT\Storage;
use App\TT\StorageFactory;
use Illuminate\Support\Facades\Session;
use Livewire\Component;

/**
 * Computed Properties
 * @property Storage $storage
 * @property Recipe  $nextRecipeToGrind
 * @property int     $countNeededForParentRecipe
 */
class NextGrind extends Component
{
    use ParentRecipeLivewireCast;

    protected $listeners = [
        'updateNextRecipeToGrind' => 'setNextRecipeToGrindName',
        'refresh'                 => '$refresh'
    ];

    public string|Recipe $parentRecipe;

    public string $nextRecipeToGrindName;

    public int $truckCompacity;

    public string $iWant = '0';

    public string $storageName = 'faq_522';

    public function mount()
    {
        $this->nextRecipeToGrindName = $this->parentRecipe->mostLimitedBy()->name;
        $this->iWant = $this->countNeededForParentRecipe;
    }

    public function updatedStorageName($value)
    {
        $this->forgetComputed('storage');
    }

    public function setNextRecipeToGrindName(string $value)
    {
        $this->nextRecipeToGrindName = $value;
        $this->iWant = $this->countNeededForParentRecipe;
    }

    public function getNextRecipeToGrindProperty(): Recipe
    {
        return RecipeFactory::get(new Item($this->nextRecipeToGrindName))->setInStorageForAllComponents($this->storage);
    }

    public function getCountNeededForParentRecipeProperty(): int
    {
        return $this->parentRecipe->components->firstWhere('name', $this->nextRecipeToGrindName)->recipeCount
            * $this->parentRecipe->howManyCanFit($this->truckCompacity);
    }

    public function getStorageProperty(): Storage
    {
        return StorageFactory::get($this->storageName);
    }

    /*
    |--------------------------------------------------------------------------
    | Business
    |--------------------------------------------------------------------------
    */

    public function recipeAndItemCraftableFromStorage(CraftingMaterial $craftingMaterial): string
    {
        if ($this->nextRecipeToGrind->makes == 1) {
            return $craftingMaterial->recipesCraftableFromStorage();
        }

        return 'Recipes: ' . $craftingMaterial->recipesCraftableFromStorage() .
            '/Items: ' . $craftingMaterial->recipesCraftableFromStorage() * $this->nextRecipeToGrind->makes;
    }

    public function isPickupRun(): bool
    {
        return $this->nextRecipeToGrind->pickupRun;
    }

    public function pickupRunYields(): array
    {
        return match ($this->nextRecipeToGrind->pickupRun) {
            'quarry' => PickupRun::quarry($this->truckCompacity),
            'logging camp' => PickupRun::logging($this->truckCompacity, $this->nextRecipeToGrindName),
            'trash' => PickupRun::trash($this->truckCompacity),
            'electronics' => PickupRun::electronics($this->truckCompacity),
            'toxic waste' => PickupRun::toxicWaste($this->truckCompacity),
            'crude oil' => PickupRun::crudeOil($this->truckCompacity),
            'raw gas' => PickupRun::rawGas($this->truckCompacity),
            default => []
        };
    }

    public function haveEnoughForFullTrailer(): bool
    {
        return $this->nextRecipeToGrind->craftableItemsFromStorage() < $this->nextRecipeToGrind->howManyCanFit($this->truckCompacity);
    }

    public function haveEnoughForIWant(): bool
    {
        return $this->nextRecipeToGrind->craftableItemsFromStorage() < $this->iWant;
    }

    public function render()
    {
        return view('livewire.next-grind');
    }
}
