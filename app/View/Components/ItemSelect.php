<?php

namespace App\View\Components;

use App\TT\Weights;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class ItemSelect extends Component
{
    public function getItemNames()
    {
        return array_keys(Weights::$weights);
    }

    public function render(): View
    {
        return view('components.item-select');
    }
}
