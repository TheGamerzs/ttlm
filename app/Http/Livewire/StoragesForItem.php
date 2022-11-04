<?php

namespace App\Http\Livewire;

use App\TT\Items\Item;
use App\TT\StorageFactory;
use Illuminate\Support\Collection;
use Livewire\Component;

class StoragesForItem extends Component
{
    protected $listeners = [
        'refresh' => '$refresh'
    ];

    public array|string $itemName = 'scrap_ore';

    public function getItemNames(): Collection
    {
        return StorageFactory::getAllItemNamesInCombinedStorage(true);
    }


    public function render()
    {
        $searchResults = StorageFactory::findStoragesForItem(new Item($this->itemName));
        return view('livewire.storages-for-item')->with([
            'searchResults' => $searchResults
        ]);
    }
}
