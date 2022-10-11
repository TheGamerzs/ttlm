<?php

namespace App\TT\Items;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Cache;

class ItemData
{
    public static function resetCachedData(): void
    {
        Cache::forget('itemData');
    }

    public static function getFromDataId(string $internalName)
    {
        return App::get('itemData')->firstWhere('id', $internalName);
    }

    public static function getInternalNameDisplayNamePairs()
    {
        return App::get('itemData')->mapWithKeys(function ($itemData) {
            return [$itemData->id => $itemData->name];
        });
    }
}
