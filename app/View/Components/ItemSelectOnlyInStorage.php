<?php

namespace App\View\Components;

use App\TT\StorageFactory;

class ItemSelectOnlyInStorage extends RecipeSelect
{
    public function getItemNames(): array
    {
        return StorageFactory::get('combined')->pluck('name')->sort()->toArray();
    }

}
