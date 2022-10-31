<?php

namespace App\Http\Controllers;

use App\TT\Items\ItemData;
use App\TT\Pickup\PickupRunCounts;
use App\TT\TTApi;
use Illuminate\Support\Facades\Auth;

class SandboxController extends Controller
{
    public function __construct()
    {
        $this->middleware('onlyUserOne');
    }

    public function index()
    {
        $inventories = Auth::user()->makeTruckingInventories();
        $pickupRunCounts = new PickupRunCounts($inventories, 'house', 1000);

        $pickupRunCounts->build();

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

        if (! $response->exists) {
            dd('Not Found');
        }

        $dump = new \stdClass();
        $dump->id = $response->item_id;
        $dump->name = $response->name;
        $dump->weight = (string) $response->weight;

        dump(json_encode($dump));
    }
}
