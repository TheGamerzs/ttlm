<?php

namespace App\View\Components;

use App\TT\Items\ItemNames;
use App\TT\StorageFactory;
use Illuminate\Support\Collection;

class ItemSelectOnlyInStorage extends RecipeSelect
{
    public function getItemNames(): array|Collection
    {
        return StorageFactory::get('combined')->pluck('name')->mapWithKeys(function ($idName) {
            return [$idName => ItemNames::getName($idName) ?? $idName];
        })->sort();
    }

}
