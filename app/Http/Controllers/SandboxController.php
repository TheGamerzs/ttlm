<?php

namespace App\Http\Controllers;

use App\TT\Items\ExcessItem;
use App\TT\Items\Item;
use App\TT\Items\ItemData;
use App\TT\RecipeFactory;
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
        $dump = ExcessItem::makeList(1000, RecipeFactory::get(new Item('house')), StorageFactory::get());
        dump($dump);
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
