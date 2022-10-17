<?php

namespace App\Http\Controllers;

use App\TT\Items\ItemData;
use App\TT\TTApi;

class SandboxController extends Controller
{
    public function __construct()
    {
        $this->middleware('onlyUserOne');
    }

    public function index()
    {
        $this->itemApiLookupWithCopyPasteJson();
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

    public function itemApiLookupWithCopyPasteJson()
    {
        $response = TTApi::ttItemDataFromInternalName('liberty_fish_export');

        $dump = new \stdClass();
        $dump->id = $response->item_id;
        $dump->name = $response->name;
        $dump->weight = (string) $response->weight;

        dump(json_encode($dump));
    }
}
