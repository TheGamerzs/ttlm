<?php

namespace App\Http\Livewire;

use App\TT\Items\ExcessItem;
use App\TT\Items\Item;
use App\TT\RecipeFactory;
use App\TT\Storage;
use App\TT\StorageFactory;
use Illuminate\Support\Facades\Auth;

class ExcessItemsInStorageModal extends BaseComponent
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
        return "Excess Items for {$this->count} {$this->hydratedRecipe()->displayNamePlural()} across all storages";
    }

    protected function hydratedRecipe(): \App\TT\Recipe
    {
        return RecipeFactory::get(new Item($this->recipe));
    }

    public function getItems(): \Illuminate\Support\Collection|Storage
    {
        return ExcessItem::makeList(
            $this->count,
            $this->hydratedRecipe(),
            StorageFactory::get()
        );
    }

    public function render()
    {
        return view('livewire.excess-items-in-storage-modal')
            ->with([
                'excessItems' => $this->getItems()
            ]);
    }
}
