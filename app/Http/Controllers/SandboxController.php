<?php

namespace App\Http\Controllers;

use App\TT\Items\InventoryItem;
use App\TT\Items\Item;
use App\TT\Items\ItemData;
use App\TT\RecipeFactory;
use App\TT\ShoppingListBuilder;
use App\TT\Storage;
use App\TT\StorageFactory;
use App\TT\TTApi;

class SandboxController extends Controller
{
    public function __construct()
    {
        $this->middleware('onlyUserOne');
    }

    public function index()
    {
        $needed = ShoppingListBuilder::build(
            RecipeFactory::get(new Item('house')),
            new Storage(),
            (int) 1000,
            1000
        )
            ->only(['crafted', 'refined', 'scrap'])
            ->flatten();

        $itemsInStorage = StorageFactory::get()
            ->filter(function (InventoryItem $item) use ($needed) {
                return $needed->contains('recipeName', $item->name);
            })
            ->map(function (InventoryItem $item) use ($needed) {
                return [
                    'inventoryItem' => $item,
                    'fromNeeded' => $needed->firstWhere('recipeName', $item->name)
                ];
            })
            ->filter(function (array $combo) {
                return $combo['inventoryItem']->count > $combo['fromNeeded']->count * 2;
            })
            ->values()
            ->dd();

        dump($needed->contains('recipeName', 'refined_sand'), $itemsInStorage);
    }

    public function userInventory()
    {
        (new TTApi())->getUserInventory()
            ->mapWithKeys(function ($data, $internalName) {
                return [
                    $internalName => [
                        'prettyName' => ItemData::getName($internalName),
                        'count' => $data->amount
                    ]
                ];
            })->sort()->dd();
    }

    public function apiItemLookup($name)
    {
        $response = TTApi::ttItemDataFromInternalName($name);

        $dump = new \stdClass();
        $dump->id = $response->item_id;
        $dump->name = $response->name;
        $dump->weight = (string) $response->weight;

        dump(json_encode($dump));
    }
}
