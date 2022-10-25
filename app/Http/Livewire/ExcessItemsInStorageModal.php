<?php

namespace App\Http\Livewire;

use App\TT\Items\InventoryItem;
use App\TT\Items\Item;
use App\TT\RecipeFactory;
use App\TT\ShoppingListBuilder;
use App\TT\Storage;
use App\TT\StorageFactory;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class ExcessItemsInStorageModal extends Component
{
    public string $count = '0';

    public string $recipe = '';

    public function mount()
    {
        if (Auth::user()->hasCraftingGoal()) {
            $goal = Auth::user()->getCraftingGoal();
            $this->count = $goal['count'];
            $this->recipe = $goal['recipe'];
        } else {
            $this->recipe = Auth::user()->default_crafting_recipe;
        }
    }

    public function showModal(): void
    {
        $this->emit('showStorageListingExcessModal');
    }

    public function modalTitle(): string
    {
        return "Excess Items for {$this->count} {$this->hydratedRecipe()->displayNamePlural()}";
    }

    protected function hydratedRecipe(): \App\TT\Recipe
    {
        return RecipeFactory::get(new Item($this->recipe));
    }

    protected function getItems(): \Illuminate\Support\Collection|Storage
    {
        $needed = ShoppingListBuilder::build(
            $this->hydratedRecipe(),
            new Storage(),
            (int) $this->count,
            1000
        )
            ->only(['crafted', 'refined', 'scrap'])
            ->flatten();

        return StorageFactory::get()
            ->filter(function (InventoryItem $item) use ($needed) {
                return $needed->contains('recipeName', $item->name);
            })
            ->map(function (InventoryItem $item) use ($needed) {
                return [
                    'inventoryItem' => $item,
                    'fromNeeded' => $needed->firstWhere('recipeName', $item->name)
                ];
            })
            ->filter(function (array $combo) {
                return $combo['inventoryItem']->count > $combo['fromNeeded']->count * 2;
            })
            ->values();
    }

    public function render()
    {
        return view('livewire.excess-items-in-storage-modal')
            ->with([
                'excessItems' => $this->getItems()
            ]);
    }
}
