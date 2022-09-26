<?php

namespace App\Http\Livewire;

use App\TT\Items\Item;
use App\TT\RecipeFactory;
use App\TT\ShoppingListBuilder;
use App\TT\Storage;
use App\TT\StorageFactory;
use Livewire\Component;

class ShoppingList extends Component
{
    public string $recipeName = 'house';

    public int $truckCompacity;

    public string $count = '300';

    protected $queryString = [
        'recipeName' => ['except' => ''],
        'count' => ['except' => 1],
    ];

    public function mount(string $name)
    {
        $this->recipeName = $name;
    }

    public function render()
    {
        $fullList = ShoppingListBuilder::build(
            RecipeFactory::get(new Item($this->recipeName)),
            new Storage(),
            $this->count,
            $this->truckCompacity
        );

        $afterStorageList = ShoppingListBuilder::build(
            RecipeFactory::get(new Item($this->recipeName)),
            StorageFactory::get('combined'),
            $this->count,
            $this->truckCompacity
        );

        return view('livewire.shopping-list')->with([
            'fullList' => $fullList,
            'afterStorageList' => $afterStorageList,
        ]);
    }
}
