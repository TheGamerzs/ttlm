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
 * @property int     $countNeededForParentRecipe
 */
class NextGrind extends Component
{
    use ParentRecipeLivewireCast;

    protected $listeners = [
        'updateNextRecipeToGrind' => 'setNextRecipeToGrind',
        'refresh'                 => '$refresh'
    ];

    public string|Recipe $parentRecipe;

    public string|Recipe $nextRecipeToGrind = '';

    public int $truckCapacity;

    public string $iWant = '0';

    public array|string $storageName = 'combined';

    /*
    |--------------------------------------------------------------------------
    | Lifecycle Hooks
    |--------------------------------------------------------------------------
    */

    public function mount()
    {
        $this->nextRecipeToGrind = RecipeFactory::get($this->parentRecipe->mostLimitedBy());
        $this->setStorageBasedOnLocationOfMostComponents();
        $this->iWant = $this->countNeededForParentRecipe;
    }

    public function updatedStorageName($value)
    {
        // More livewire BS.
        if (is_string($this->storageName)) {
            $this->forgetComputed('storage');
            $this->nextRecipeToGrind->setInStorageForAllComponents($this->storage);
        }
    }

    protected function setStorageBasedOnLocationOfMostComponents()
    {
        $this->forgetComputed('storage');
        $this->storageName = $this->nextRecipeToGrind->autoSetStorageBasedOnComponentsLocation();
    }

    public function hydrateNextRecipeToGrind($value): void
    {
        $this->nextRecipeToGrind = RecipeFactory::get(new Item($value))->setInStorageForAllComponents($this->storage);
    }

    public function dehydrateNextRecipeToGrind(Recipe $nextRecipeToGrind): void
    {
        $this->nextRecipeToGrind = $nextRecipeToGrind->internalName();
    }

    public function setNextRecipeToGrind(string $value)
    {
        $this->nextRecipeToGrind = RecipeFactory::get(new Item($value));
        $this->setStorageBasedOnLocationOfMostComponents();
        $this->iWant = $this->countNeededForParentRecipe;
    }

    /*
    |--------------------------------------------------------------------------
    | Computed Properties
    |--------------------------------------------------------------------------
    */

    public function getCountNeededForParentRecipeProperty(): int
    {
        return $this->parentRecipe->components->firstWhere('name', $this->nextRecipeToGrind->internalName())->recipeCount
            * $this->parentRecipe->howManyRecipesCanFit($this->truckCapacity);
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
            'quarry' => PickupRun::quarry($this->truckCapacity),
            'logging camp' => PickupRun::logging($this->truckCapacity, $this->nextRecipeToGrind->internalName()),
            'trash' => PickupRun::trash($this->truckCapacity),
            'electronics' => PickupRun::electronics($this->truckCapacity),
            'toxic waste' => PickupRun::toxicWaste($this->truckCapacity),
            'crude oil' => PickupRun::crudeOil($this->truckCapacity, $this->nextRecipeToGrind->internalName()),
            'raw gas' => PickupRun::rawGas($this->truckCapacity),
            default => []
        };
    }

    public function haveEnoughForFullTrailer(): bool
    {
        return $this->nextRecipeToGrind->craftableItemsFromStorage() < $this->nextRecipeToGrind->howManyItemsCanFit($this->truckCapacity);
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
