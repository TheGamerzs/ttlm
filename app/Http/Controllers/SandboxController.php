<?php

namespace App\Http\Controllers;

use App\Http\Livewire\ParentRecipeTable;
use App\TT\Items\Item;
use App\TT\Items\ItemData;
use App\TT\Items\ItemNames;
use App\TT\Items\Weights;
use App\TT\RecipeFactory;
use App\TT\Recipes;
use App\TT\ShoppingListBuilder;
use App\TT\Storage;
use App\TT\StorageFactory;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Livewire\Livewire;

class SandboxController extends Controller
{
    public function __construct()
    {
        $this->middleware('onlyUserOne');
    }

    public function index()
    {
        $dump = ItemData::getInternalNameDisplayNamePairsTruckingOnly();
        dump($dump);
    }
}
