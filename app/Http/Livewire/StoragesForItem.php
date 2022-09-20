<?php

namespace App\Http\Livewire;

use App\TT\Items\Item;
use App\TT\StorageFactory;
use Livewire\Component;

class StoragesForItem extends Component
{
    public string $itemName = 'scrap_ore';

    public function render()
    {
        $searchResults = StorageFactory::findStoragesForItem(new Item($this->itemName));
        return view('livewire.storages-for-item')->with([
            'searchResults' => $searchResults
        ]);
    }
}
