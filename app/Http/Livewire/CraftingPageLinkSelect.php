<?php

namespace App\Http\Livewire;

use App\TT\Recipes;
use Livewire\Component;

class CraftingPageLinkSelect extends Component
{
    public array|string $itemName = '';

    public function mount()
    {
        $this->itemName = Recipes::getNamesIfComponentsExist()->first();
    }

    public function goToLink()
    {
        return redirect()->route('craftingPage', ['name' => $this->itemName]);
    }

    public function render()
    {
        return view('livewire.crafting-page-link-select');
    }
}
