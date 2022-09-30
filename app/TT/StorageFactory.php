<?php

namespace App\TT;

use App\TT\Items\InventoryItem;
use App\TT\Items\Item;
use Illuminate\Support\Collection;

class StorageFactory
{
    protected static object $apiData;

    public static array $storages = [];

    public static function get($name = 'combined'): Storage
    {
        if (! count(self::$storages)) {
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
        if (! count(self::$storages)) {
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

    public static function getRegisteredNames(): array
    {
        return array_keys(self::$storages);
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
            $data          = TTApi::storages();
            self::$apiData = json_decode($data);
        }
        return self::$apiData;
    }

    protected static function injectFakes(): void
    {
        $fakes = [
            [
                'storage' => 'faq_522',
                'itemName' => 'crafted_concrete',
                'count' => 300
            ]
        ];
//        foreach ($fakes as $fake) {
//            $storage = self::$storages[$fake['storage']];
//            $existing = $storage->firstWhere('name', $fake['itemName']);
//
//            if ($existing) {
//                $existing->count += $fake['count'];
//            } else {
//                $storage->push(new InventoryItem($fake['itemName'], $fake['count']));
//            }
//        }
    }

    public static function getPrettyName(string $storageName): string
    {
        $lookup = [
            'biz_granny' => 'Grandmas House',
            'biz_yellowjack' => 'Yellowjack',
            'biz_hookies' => 'Hookies',
            'faq_522' => 'Faction',
            'gohq' => 'Oil Refinery',
            'combined' => 'Combined',
            'biz_train' => 'Train Yard'
        ];

        if (array_key_exists($storageName, $lookup)) {
            return $lookup[$storageName];
        }
        return $storageName;
    }

}
