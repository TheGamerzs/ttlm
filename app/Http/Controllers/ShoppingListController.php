<?php

namespace App\Http\Controllers;

use App\TT\Items\Item;
use App\TT\RecipeFactory;
use App\TT\ShoppingListBuilder;
use App\TT\Storage;
use App\TT\StorageFactory;

class ShoppingListController
{
    public function index(string $name = 'house')
    {
        $fullList = ShoppingListBuilder::build(
            RecipeFactory::get(new Item('house')),
            new Storage(),
            300,
            9775
        );

        $afterStorageList = ShoppingListBuilder::build(
            RecipeFactory::get(new Item('house')),
            StorageFactory::get('combined'),
            300,
            9775
        );

        return view('shopping-list')->with([
            'fullList' => $fullList,
            'afterStorageList' => $afterStorageList,
        ]);
    }
}
