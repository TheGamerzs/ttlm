<?php

namespace App\View\Components;

use App\TT\Recipes;
use App\TT\Weights;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class RecipeSelect extends ItemSelect
{
    public function getItemNames(): array
    {
        return Recipes::getAllNames();
    }
}
