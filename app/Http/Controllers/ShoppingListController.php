<?php

namespace App\Http\Controllers;

use App\TT\Items\Item;
use App\TT\RecipeFactory;
use App\TT\ShoppingListBuilder;
use App\TT\Storage;
use App\TT\StorageFactory;
use Illuminate\Support\Facades\Auth;

class ShoppingListController
{
    public function index(string $name = 'house')
    {

        return view('shopping-list')->with([
            'name' => $name,
            'truckCompacity' => Auth::user()->truckCompacity
        ]);
    }
}
