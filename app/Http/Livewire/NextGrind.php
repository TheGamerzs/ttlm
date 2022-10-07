<?php

namespace App\Http\Livewire;

use App\TT\Items\CraftingMaterial;
use App\TT\Items\Item;
use App\TT\PickupRun;
use App\TT\Recipe;
use App\TT\RecipeFactory;
use App\TT\ShoppingListBuilder;
use App\TT\Storage;
use App\TT\StorageFactory;
use Illuminate\Support\Facades\Auth;
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

    public bool $iWantFromGoal = false;

    protected Storage $storage;

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
        $this->goalMount();
    }

    public function booted()
    {
        $this->setStorage();
    }

    protected function setStorage()
    {
        if (empty($this->storage)) {
            $this->storage = StorageFactory::get($this->storageName);
        }
        $this->nextRecipeToGrind->setInStorageForAllComponents($this->storage);
        $this->parentRecipe->autoSetStorageBasedOnComponentsLocation();
    }

    public function updatedStorageName($value)
    {
        // More livewire BS.
        if (is_string($this->storageName)) {
            $this->setStorage();
        }
    }

    protected function setStorageBasedOnLocationOfMostComponents()
    {
        $this->storageName = $this->nextRecipeToGrind->autoSetStorageBasedOnComponentsLocation();
        $this->storage = StorageFactory::get($this->storageName);
    }

    public function hydrateNextRecipeToGrind($value): void
    {
        $this->nextRecipeToGrind = RecipeFactory::get(new Item($value));
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

    public function iWantToolTip(): string
    {
        return $this->iWantFromGoal
            ? 'Default based on your set goal of ' . $this->goalCount . ' ' . RecipeFactory::get(new Item($this->goalRecipe))->displayName()
            : 'Default based on how many are needed to fill a trailer of components for ' . $this->parentRecipe->displayName();
    }

    public function render()
    {
        return view('livewire.next-grind');
    }

    /*
    |--------------------------------------------------------------------------
    | Goal Modal / This is here for now until it needs to be it's own.
    |--------------------------------------------------------------------------
    */

    public string $goalCount = '';

    public array|string $goalRecipe = '';

    public bool $goalWasUpdated = false;

    public function hydrate()
    {
        $this->goalWasUpdated = false;
    }

    public function goalMount()
    {
        if (Session::has('craftingGoal')) {
            $craftingGoal = Session::get('craftingGoal');
            $this->goalRecipe = $craftingGoal['recipe'];
            $this->goalCount = $craftingGoal['count'];
            $this->setIWantFromGoal();
        } else {
            $this->goalRecipe = Auth::user()->default_crafting_recipe;
        }
    }

    public function updateGoal()
    {
        $wasGoalSet = Session::has('craftingGoal');
        if ($this->goalCount) {
            $craftingGoal = [];
            $craftingGoal['recipe'] = $this->goalRecipe;
            $craftingGoal['count'] = $this->goalCount;
            Session::put('craftingGoal', $craftingGoal);
            $this->setIWantFromGoal();
            $goalIsSetNow = true;
        } else {
            Session::remove('craftingGoal');
            $goalIsSetNow = false;
        }

        if ($wasGoalSet != $goalIsSetNow) {
            $this->emit('refreshParentRecipeTable');
        }

        $this->goalWasUpdated = true;
    }

    public function setIWantFromGoal()
    {
        $shoppingList = ShoppingListBuilder::build(
            RecipeFactory::get(new Item($this->goalRecipe)),
            $this->storage,
            (int) $this->goalCount,
            $this->truckCapacity
        )->only(['crafted', 'refined', 'scrap'])->flatten();

        // overwrite if exists
        if ($possibleIWant = $shoppingList->firstWhere('recipeName', $this->nextRecipeToGrind->internalName())?->count) {
            $this->iWant = $possibleIWant;
            $this->iWantFromGoal = true;
        } else {
            // Need to reassign back so that it resets when the count is removed on a live page.
            $this->iWant = $this->countNeededForParentRecipe;
        }
    }

}
