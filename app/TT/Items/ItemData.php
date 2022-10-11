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

    protected static function logMissingItemName(string $itemName): void
    {
        /** @var Collection $missingItems */
        $missingItems = Cache::get('missingItemNames', collect());
        if (! $missingItems->contains($itemName)) {
            $missingItems->push($itemName);
            Cache::put('missingItemNames', $missingItems);
        }
    }

    public static function getFromDataId(string $internalName)
    {
        $return = App::get('itemData')->firstWhere('id', $internalName);

        if (is_null($return)) {
            self::logMissingItemName($internalName);
        }

        return $return;
    }

    public static function getInternalNameDisplayNamePairs()
    {
        return App::get('itemData')
            ->reject(function ($item) {
                return $item->name == 'Invalid Item';
            })
            ->mapWithKeys(function ($itemData) {
                return [$itemData->id => $itemData->name];
            });
    }

    public static function getInternalNameDisplayNamePairsTruckingOnly()
    {
        return App::get('itemData')
            ->filter(function ($item) {
                return str($item->id)->startsWith(self::truckingItemsStartWith());
            })
            ->mapWithKeys(function ($itemData) {
                return [$itemData->id => $itemData->name];
            });
    }

    public static function truckingItemsStartWith(): array
    {
        return [
            'scrap',
            'refined',
            'crafted',
            'recycled',
            'fridge',
            'mechanicals',
            'petrochem',
            'military',
            'tcargo',
            'pucargo',
            'liquid_water',
        ];
    }
}
