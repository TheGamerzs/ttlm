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

    // alias for readability
    public static function getFromInternalName(string $internalName)
    {
        return self::getFromDataId($internalName);
    }

    public static function getName(string $internalName): string
    {
        return self::getFromDataId($internalName)?->name ?? $internalName;
    }

    public static function getWeight(string $internalName): int
    {
        return (int) self::getFromDataId($internalName)?->weight ?? 0;
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

    public static function getAllInternalNames(): Collection
    {
        return App::get('itemData')->pluck('id');
    }

    public static function getAllInternalTruckingNames(): Collection
    {
        return self::getAllInternalNames()
            ->filter(function ($name) {
                return str($name)->startsWith(self::truckingItemsStartWith());
            })
            ->values(); // Rekey indexes.
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
