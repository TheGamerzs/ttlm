<?php

namespace App\Http\Livewire;

use App\TT\Items\Item;
use App\TT\RecipeFactory;
use App\TT\ShoppingListBuilder;
use App\TT\Storage;
use App\TT\StorageFactory;
use App\View\ShoppingList\ShoppingListViewModel;
use Illuminate\Support\Facades\Auth;

class ShoppingListIndex extends BaseComponent
{
    protected $listeners = [
        'refresh' => '$refresh'
    ];

    public string $recipeName = 'house';

    public string $count = '300';

    protected $queryString = [
        'recipeName' => ['except' => 'house'],
        'count' => ['except' => 1],
    ];

    public function setRecipe(string $internalName, int $count)
    {
        return redirect()->route('shoppingList', ['recipeName' => $internalName, 'count' => $count]);
    }

    public function render()
    {
        $trunkCapacity = Auth::user()->makeTruckingInventories()->totalAvailableCapacity();

        $totalNeededList = ShoppingListBuilder::build(
            RecipeFactory::get(new Item($this->recipeName)),
            new Storage(),
            (int) $this->count,
            $trunkCapacity
        );

        $stillNeededList = ShoppingListBuilder::build(
            RecipeFactory::get(new Item($this->recipeName)),
            StorageFactory::get('combined'),
            (int) $this->count,
            $trunkCapacity
        );

        return view('livewire.shopping-list-index')->with([
            'viewModel' => new ShoppingListViewModel($totalNeededList, $stillNeededList)
        ]);
    }
}
