<?php

namespace App\Http\Livewire;

use App\TT\Items\Item;
use App\TT\RecipeFactory;
use App\TT\ShoppingListBuilder;
use App\TT\Storage;
use App\TT\StorageFactory;
use App\View\ShoppingList\ShoppingListViewModel;

class ShoppingListIndex extends BaseComponent
{
    public string $recipeName = 'house';

    public int $truckCapacity;

    public string $count = '300';

    protected $queryString = [
        'recipeName' => ['except' => 'house'],
        'count' => ['except' => 1],
    ];

    public function render()
    {
        $totalNeededList = ShoppingListBuilder::build(
            RecipeFactory::get(new Item($this->recipeName)),
            new Storage(),
            (int) $this->count,
            $this->truckCapacity
        );

        $stillNeededList = ShoppingListBuilder::build(
            RecipeFactory::get(new Item($this->recipeName)),
            StorageFactory::get('combined'),
            (int) $this->count,
            $this->truckCapacity
        );

        return view('livewire.shopping-list-index')->with([
            'viewModel' => new ShoppingListViewModel($totalNeededList, $stillNeededList)
        ]);
    }
}
