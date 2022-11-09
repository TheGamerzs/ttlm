<?php

namespace App\Http\Livewire;

use App\TT\Items\CraftingMaterial;
use App\TT\Pickup\PickupRunYields;
use App\TT\Recipe;
use App\TT\RecipeFactory;
use App\TT\ShoppingListBuilder;
use App\TT\Storage;
use App\TT\StorageFactory;
use App\TT\Trunk;
use App\View\NextGrindViewModel;
use Illuminate\Support\Facades\Auth;

class NextGrindRevised extends BaseComponent
{
    protected $listeners = [
        'refresh'                 => '$refresh',
        'updateNextRecipeToGrind' => 'changeRecipe',
    ];

    protected Recipe $recipe;

    protected Recipe $parentRecipe;

    protected Storage $storage;

    public int $truckCapacity;

    public string $customCount = '100';

    public array $toHydrate = [];

    public string $storageName = '';

    // Properties for Goal Modal
    public string $goalCount;

    public string $goalRecipe;

    /*
    |--------------------------------------------------------------------------
    | Life Cycle Hooks and Listener Calls
    |--------------------------------------------------------------------------
    */

    public function mount(Recipe $parentRecipe)
    {
        $this->parentRecipe = $parentRecipe;
        $this->recipe       = $parentRecipe->mostLimitedByAsRecipe();

        $this->toHydrate['parentRecipe'] = $parentRecipe->internalName();
        $this->toHydrate['recipe']       = $this->recipe->internalName();

        $this->setStorageOnRecipeAndThis();

        $this->mountGoal();
    }

    public function hydrate()
    {
        foreach ($this->toHydrate as $property => $recipeName) {
            $this->{$property} = RecipeFactory::get($recipeName);
        }

        $this->parentRecipe->autoSetStorageBasedOnLocationOfMostComponents();
        $this->setStorageOnRecipeAndThis();
    }

    protected function setStorageOnRecipeAndThis()
    {
        $this->storageName = $this->recipe->autoSetStorageBasedOnLocationOfMostComponents();
        $this->storage     = StorageFactory::get($this->storageName);
    }

    public function updatedStorageName($storageName)
    {
        if (is_string($storageName)) {
            $this->storage = StorageFactory::get($storageName);
            $this->recipe->setInStorageForAllComponents($this->storage);
        }
    }

    public function changeRecipe(string $recipeName)
    {
        $this->toHydrate['recipe'] = $recipeName;
        $this->recipe              = RecipeFactory::get($recipeName);
        $this->setStorageOnRecipeAndThis();
    }

    public function makeViewModel(): NextGrindViewModel
    {
        return (new NextGrindViewModel(Auth::user()->makeTruckingInventories()))
            ->setRecipe($this->recipe);
    }

    public function render()
    {
        return view('livewire.next-grind-revised')
            ->with([
                'viewModel' => $this->makeViewModel()
            ]);
    }

    /*
    |--------------------------------------------------------------------------
    | Protected Getters
    |--------------------------------------------------------------------------
    */

    public function getParentRecipe(): Recipe
    {
        return $this->parentRecipe;
    }

    public function getRecipe(): Recipe
    {
        return $this->recipe;
    }

    public function getStorage(): Storage
    {
        return $this->storage;
    }

    /*
    |--------------------------------------------------------------------------
    | Goal Modal Methods
    |--------------------------------------------------------------------------
    */

    public function mountGoal()
    {
        $goal             = Auth::user()->getCraftingGoal();
        $this->goalCount  = $goal['count'];
        $this->goalRecipe = $goal['recipe'];
    }

    public function updateGoal()
    {
        Auth::user()->setCraftingGoal((int)$this->goalCount, $this->goalRecipe);
        $this->emit('refreshParentRecipeTable');
        $this->emit('closeCraftingGoal');
    }

    public function usingGoal(): bool
    {
        // Int cast required for 00 to evaluate false.
        return (bool)(int)$this->goalCount;
    }

    public function getGoalRecipePluralName(): string
    {
        return RecipeFactory::get($this->goalRecipe)?->displayNamePlural() ?? '';
    }

    /*
    |--------------------------------------------------------------------------
    | Business
    |--------------------------------------------------------------------------
    */

    public function getNeededForGoal()
    {
        $shoppingList = ShoppingListBuilder::build(
            RecipeFactory::get($this->goalRecipe),
            $this->storage,
            (int)$this->goalCount,
            $this->truckCapacity
        )
            ->only(['crafted', 'refined', 'scrap'])
            ->flatten();

        return $this->recipe->components->map(function (CraftingMaterial $craftingMaterial) use ($shoppingList) {
            return $shoppingList->firstWhere('recipeName', $craftingMaterial->name)?->count ?? 0;
        });
    }

    public function getParentRecipeCountForFullTrailer()
    {
        return Auth::user()->makeTruckingInventories()->trunks
            ->sum(function (Trunk $trunk) {
                return $this->parentRecipe->components->firstWhere('name', $this->recipe->internalName())->recipeCount
                    * $this->parentRecipe->howManyRecipesCanFit($trunk->capacity);
            });
    }

    public function getNeededForParentTrailer()
    {
        return $this->recipe->components->map(function (CraftingMaterial $craftingMaterial) {
            return $this->getParentRecipeCountForFullTrailer() * $craftingMaterial->recipeCount / $craftingMaterial->recipe->makes;
        });
    }

    public function pickupRunYields(): array
    {
        $runYields = new PickupRunYields(Auth::user()->makeTruckingInventories());

        return match ($this->getRecipe()->pickupRun) {
            'quarry' => $runYields->quarry(),
            'logging camp' => $runYields->logging($this->getRecipe()->internalName()),
            'trash' => $runYields->trash(),
            'electronics' => $runYields->electronics(),
            'toxic waste' => $runYields->toxicWaste(),
            'crude oil' => $runYields->crudeOil($this->getRecipe()->internalName()),
            'raw gas' => $runYields->rawGas(),
            'veggies' => $runYields->veggies(),
            'dairy' => $runYields->dairy(),
            default => []
        };
    }


}
