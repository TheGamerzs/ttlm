<?php

namespace App\TT;

use App\TT\Items\InventoryItem;
use App\TT\Items\Item;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class StorageFactory
{
    protected static object $apiData;

    public static array $storages = [];

    public static function get($name = 'combined'): Storage
    {
        if (!count(self::$storages)) {
            self::registerCombined();
        }

        if (array_key_exists($name, self::$storages)) {
            return self::$storages[$name];
        }

        throw new \Exception('Invalid Storage');
    }

    protected static function make(string $name): Storage
    {
        $storage = new Storage();

        $items = collect(self::getData()->storages)->firstWhere('name', $name)?->inventory;
        foreach ($items as $inventoryItemName => $item) {
            $storage->push(new InventoryItem($inventoryItemName, $item->amount));
        }

        self::registerStorage($name, $storage);

        return $storage;
    }

    public static function registerStorage(string $name, Storage $storage): void
    {
        self::$storages[$name] = $storage;
    }

    protected static function fillStoragesArray(): void
    {
        $data = self::getData();
        foreach ($data->storages as $storageData) {
            self::make($storageData->name);
        }
        self::injectFakes();
    }

    public static function registerCombined(): void
    {
        self::fillStoragesArray();

        $combinedStorage = new Storage();
        foreach (collect(self::$storages) as $storageData) {
            foreach ($storageData as $inventoryItem) {
                $existing = $combinedStorage->firstWhere('name', $inventoryItem->name);

                if ($existing) {
                    $existing->count += $inventoryItem->count;
                } else {
                    $combinedStorage->push(clone $inventoryItem);
                }
            }
        }
        self::registerStorage('combined', $combinedStorage);
    }

    public static function findStoragesForItem(Item $item): Collection
    {
        if (!count(self::$storages)) {
            self::registerCombined();
        }

        return collect(self::$storages)
            ->except('combined')
            ->filter(function (Storage $storage) use ($item) {
                return $storage->contains('name', $item->name);
            })->map(function (Storage $storage) use ($item) {
                return $storage->firstWhere('name', $item->name);
            });
    }

    public static function getRegisteredNames(bool $mapPrettyNames = false): array|Collection
    {
        if (! $mapPrettyNames) return array_keys(self::$storages);

        return collect(self::$storages)->mapWithKeys(function ($storage, $internalName) {
            return [$internalName => self::getPrettyName($internalName)];
        });
    }

    public static function getCountFromCombinedForItem(Item $item): int
    {
        /** @var Storage $combined */
        $combined = self::$storages['combined'];
        return $combined->firstWhere('name', $item->name)?->count ?? 0;
    }

    protected static function getData()
    {
        if (! isset(self::$apiData)) {
            $api           = new TTApi();
            $data          = $api->getStorages();
            self::$apiData = json_decode($data);
        }
        return self::$apiData;
    }

    protected static function injectFakes(): void
    {
        $fakes = [
            [
                'storage'  => 'faq_522',
                'itemName' => 'crafted_concrete',
                'count'    => 300
            ],
            [
                'storage'  => 'faq_522',
                'itemName' => 'liquid_water_raw',
                'count'    => 49305
            ]
        ];
//        if (Auth::id() == 1) {
//            foreach ($fakes as $fake) {
//                $storage = self::$storages[$fake['storage']];
//                $existing = $storage->firstWhere('name', $fake['itemName']);
//
//                if ($existing) {
//                    $existing->count += $fake['count'];
//                } else {
//                    $storage->push(new InventoryItem($fake['itemName'], $fake['count']));
//                }
//            }
//        }
    }

    public static function getPrettyName(string $storageName): string
    {
        if ($storageName == 'combined') return 'All Combined Storages';

        $name = Str::of($storageName);

        if ($name->startsWith('faq')) {
            $factionLookup = [
                'faq_522' => 'House Of E',
                'faq_54'=> 'TSA',
                'faq_56'=> 'I Don\'t Know',
                'faq_225'=> 'HOUSES',
                'faq_287'=> 'OVERLORD',
                'faq_310'=> 'HouseCo',
                'faq_330' => 'The Foundry Group',
            ];
            return array_key_exists($storageName, $factionLookup)
                ? 'Faction - ' . $factionLookup[$storageName]
                : 'Faction - ' . $name->afterLast('_');
        }

        $lookup = App::get('storageData')->mapWithKeys(function ($item, $key) {
            return [$item->id => $item->name];
        });

        if ($lookup->keys()->contains($storageName)) {
            return $lookup[$storageName];
        }

        \Log::debug('Missing storage name: ' . $storageName);

        return $storageName;
    }

}
