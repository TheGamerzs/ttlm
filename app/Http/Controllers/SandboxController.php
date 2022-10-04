<?php

namespace App\Http\Controllers;

use App\TT\Items\Item;
use App\TT\RecipeFactory;
use App\TT\ShoppingListBuilder;
use App\TT\Storage;
use Illuminate\Support\Facades\Cache;

class SandboxController extends Controller
{
    public function __construct()
    {
        $this->middleware('onlyUserOne');
    }

    public function index()
    {
        dd(Cache::getStore());

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
