<?php

namespace App\View\Components;

use App\TT\Items\CraftingMaterial;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class ParentRecipeTableCraftingLink extends Component
{
    public bool $shouldLink;

    public function __construct(public CraftingMaterial $craftingMaterial)
    {
        $this->shouldLink = $this->craftingMaterial->getRecipe()->components->count() > 0;
    }

    public function render(): View
    {
        return view('components.parent-recipe-table-crafting-link');
    }
}
