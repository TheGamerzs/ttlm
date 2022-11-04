<?php

namespace App\View\Components;

use App\TT\Inventories;
use App\TT\Recipe;
use App\TT\Trunk;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\Component;

class RecipeWithFullLoadDetails extends Component
{
    public Recipe $recipe;

    public Inventories $inventories;

    public function __construct(Recipe $recipe, $trunkOneCapacityUsed, $trunkTwoCapacityUsed)
    {
        $this->recipe = $recipe;
        $this->inventories = Auth::user()->makeTruckingInventories();

        $this->inventories->trunks->first()->setCapacityUsed((int) $trunkOneCapacityUsed);
        if ($this->inventories->trunks->count() > 1) {
            $this->inventories->trunks->offsetGet(1)->setCapacityUsed((int) $trunkTwoCapacityUsed);
        }
    }

    public function trunkCapacitySum(): int
    {
        return $this->inventories->trunks->sum(function (Trunk $trunk) {
            return $trunk->getAvailableCapacity();
        });
    }

    public function showMultipleRunCounts(): bool
    {
        return $this->inventories->count() == 1 &&
            $this->recipe->howManyFullLoadsFromStorage($this->trunkCapacitySum()) > 2;
    }

    public function render(): View
    {
        return view('components.recipe-with-full-load-details');
    }
}
