<?php

namespace App\View\Components;

use App\TT\StorageFactory;
use App\TT\Weights;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class ItemSelectOnlyInStorage extends ItemSelect
{
    public function getItemNames(): array
    {
        return StorageFactory::get('combined')->pluck('name')->sort()->toArray();
    }

}
