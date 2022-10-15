<?php

namespace App\TT;

use App\Providers\StorageUpdatedFromTT;
use App\TT\Items\InventoryItem;
use App\TT\Items\Item;
use App\TT\Items\ItemData;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class StorageFactory
{
    public static bool      $freshData = false;

    public static array $storages = [];

    public static function get($name = 'combined'): Storage
    {
        if (!count(self::$storages)) {
            self::fillStoragesArray();
        }

        if (array_key_exists($name, self::$storages)) {
            return self::$storages[$name];
        }

        throw new \Exception('Invalid Storage: ' . $name);
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

        // Inject personal inventory as a storage named 'pocket'
        $pocket = new \stdClass();
        $pocket->name = 'pocket';
        $pocket->inventory = (new TTApi())->getUserInventory(false);
        $data->storages[] = $pocket;

        foreach ($data->storages as $storageData) {
            self::make($storageData->name);
        }

        self::registerNonTTStorages();

        if (self::$freshData) {
            StorageUpdatedFromTT::dispatch(Auth::user());
        }

        self::injectFakes();
    }

    protected static function registerNonTTStorages(): void
    {
        // Default Combined
        self::registerCombined('combined', array_keys(self::$storages));
        self::registerCombined('Custom Combined Storage', Auth::user()->custom_combined_storage->toArray());

    }

    protected static function registerCombined(string $name, array $ttStorages): void
    {
        $storagesToCombine = collect($ttStorages)->mapWithKeys(function (string $storageName) {
            return [$storageName => self::$storages[$storageName]];
        });

        $combinedStorage = new Storage();
        foreach ($storagesToCombine as $storageData) {
            foreach ($storageData as $inventoryItem) {
                $existing = $combinedStorage->firstWhere('name', $inventoryItem->name);

                if ($existing) {
                    $existing->count += $inventoryItem->count;
                } else {
                    $combinedStorage->push(clone $inventoryItem);
                }
            }
        }
        self::registerStorage(Str::of($name)->snake(), $combinedStorage);
    }

    public static function findStoragesForItem(Item $item): Collection
    {
        if (!count(self::$storages)) {
            self::fillStoragesArray();
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
        $data          = (new TTApi())->getStorages();
        return json_decode($data);
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

    public static function getAllItemNames(bool $mapPrettyNames = false): Collection|Storage
    {
        $names = self::get('combined')->pluck('name')->sort();

        if ($mapPrettyNames) {
            return $names->mapWithKeys(function ($internalName) {
                return [$internalName => ItemData::getName($internalName)];
            })->sort();
        }

        return $names;
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
                'faq_100' => 'LS Foundry',
                'faq_186' => 'LS Factory',
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

//        \Log::debug('Missing storage name: ' . $storageName);

        return $name->headline();
    }

}
