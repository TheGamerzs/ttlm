<?php

namespace App\Http\Controllers;

use App\TT\Items\CraftingMaterial;
use App\TT\Items\InventoryItem;
use App\TT\Items\Item;
use App\TT\Items\SellableItem;
use App\TT\RecipeFactory;
use App\TT\Recipes;
use App\TT\RecipeShoppingListDecorator;
use App\TT\ShoppingListBuilder;
use App\TT\StorageFactory;
use App\TT\Weights;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class SandboxController extends Controller
{
    public function index()
    {
        $pickupItemWeight = Weights::getWeight('recycled_waste');
        $truckCompacity = 9775;
        $pocketCompacity = 600;
        $storageCompacity = 30107;

        $pickupItemsCountTrailer = (int) floor($truckCompacity / $pickupItemWeight);
        $pickupItemsCountPocket= (int) floor($pocketCompacity / $pickupItemWeight);

        $pickupItemRefinedWeight = collect([
            new InventoryItem('scrap_acid', 4),
            new InventoryItem('scrap_lead', 2),
            new InventoryItem('scrap_mercury', 2)
        ])->sum(function (InventoryItem $item) {
            return $item->getTotalWeight();
        });

        $leftoverWeightNeededForFirstRefine = ($pickupItemsCountTrailer + $pickupItemsCountPocket) * $pickupItemRefinedWeight;
        $usableStorageCompacity = $storageCompacity - $leftoverWeightNeededForFirstRefine;

        $oneRunTruckWeight = ($pickupItemsCountTrailer + $pickupItemsCountPocket) * $pickupItemWeight;
        $final = (int) floor($usableStorageCompacity / $oneRunTruckWeight);

        dd($pickupItemsCountTrailer, $pickupItemsCountPocket, $final);
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
