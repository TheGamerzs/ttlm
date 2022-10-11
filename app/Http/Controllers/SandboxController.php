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
        $dump = new Item('crafted_ceramictiles');

        dump($dump);
    }

    public function lookup()
    {
        $items = collect(ItemNames::$names)
            ->reject()
            ->reject(function ($value, $itemName) {
                return Str::of($itemName)->startsWith('vehicle_shipment');
            })
            ->mapWithKeys(function ($weight, $itemName) {
            return [$itemName => [
                'myWeight' => $weight,
                ...Http::withOptions(['verify' => false])->get('https://ttapi.elfshot.xyz/items?item=' . $itemName)->json()['data'] ?? []
            ]];
        });

        dd($items);
    }

    public function missingItemsAfterPulledFromAPI()
    {
        $ignoring = collect([
            'gut_knife_tiger|7842',
            'gut_knife_fade|79',
            'vehicle_card|Tmodel|R.T.S. Tesla Model 3',
            'vehicle_card|Zentorno|R.T.S. Zentorno',
            'vehicle_card|Sanctus|R.T.S. Sanctus',
            'vehicle_card|Tropos|R.T.S. Tropos',
            'fish_meat',
        ]);

        $missingItems = Cache::get('missingItems')->reject(function ($string) use ($ignoring) {
            return $ignoring->contains($string);
        });

        dump($missingItems);

        foreach ($missingItems as $itemName) {
            echo '<a href="https://ttapi.elfshot.xyz/items?item=' . $itemName . '">' . $itemName . '</a><br>';
        }
    }
}
