<?php

namespace App\TT\Items;

use App\Models\User;
use App\TT\Factories\ItemFactory;
use App\TT\Storage;

class ExportableItem extends Item
{
    public static array $data = [
        'petrochem_petrol' => [
            'each' => 8000,
            'location' => 'Bristols Fuel Storage'
        ],
        'petrochem_propane' => [
            'each' => 10000,
            'location' => 'Davis Mega Mall'
        ],

        'military_titanium' => [
            'each' => 75000,
            'location' => 'Military Workshop'
        ],

        'scrap_plastic' => [
            'each' => 3600,
            'location' => 'McKenzie Export'
        ],
        'refined_flint'=> [
            'each' => 4250,
            'location' => 'McKenzie Export'
        ],

        'scrap_emerald' => [
            'each' => 25000,
            'location' => 'Jewelry Store'
        ],
        'refined_gold' => [
            'each' => 31000,
            'location' => 'Jewelry Store'
        ],
        'crafted_jewelry' => [
            'each' => 95000,
            'location' => 'Jewelry Store'
        ],

        'tcargodust' => [
            'each' => 1800,
            'location' => 'LS Port'
        ],
        'crafted_ceramictiles' => [
            'each' => 40000,
            'location' => 'LS Port'
        ],
        'crafted_computer' => [
            'each' => 210000,
            'location' => 'LS Port'
        ],
        'scrap_acid' => [
            'each' => 12800,
            'location' => 'LS Port'
        ],
        'refined_aluminum' => [
            'each' => 8400,
            'location' => 'LS Port'
        ],
        'crafted_batteries' => [
            'each' => 150000,
            'location' => 'LS Port'
        ],
        'refined_copper' => [
            'each' => 10000,
            'location' => 'LS Port'
        ],
        'refined_planks' => [
            'each' => 14400,
            'location' => 'LS Port'
        ],
        'refined_bronze' => [
            'each' => 32000,
            'location' => 'LS Port'
        ],
        'refined_glass' => [
            'each' => 10000,
            'location' => 'LS Port'
        ],
        'mechanicals_rubber' => [
            'each' => 10000,
            'location' => 'LS Port'
        ],
        'refined_tin' => [
            'each' => 8400,
            'location' => 'LS Port'
        ],
        'mechanicals_vehicle_framework' => [
            'each' => 350000,
            'location' => 'LS Port'
        ],
        'refined_zinc' => [
            'each' => 11600,
            'location' => 'LS Port'
        ],
    ];

    public static function getAllForStorage(Storage $storage, User $user = null): \Illuminate\Support\Collection|Storage
    {
        $exportable = $storage->whereIn('name', array_keys(self::$data));

        if ($user) {
            $exportable = $exportable->whereNotIn('name', $user->hidden_exportable_items);
        }

        return $exportable->map(function ($item) {
            return ItemFactory::makeExportableItem($item->name, $item->count);
        });
    }

    public int $count;

    public int $valueEach;

    public string $location;

    public function __construct(string $name, int $count, int $weight = 0, ?string $prettyName = null)
    {
        parent::__construct($name, $weight, $prettyName);
        $this->count = $count;
        $this->valueEach = self::$data[$name]['each'];
        $this->location = self::$data[$name]['location'];
    }

    public function getValueFor(int $count): int
    {
        return $this->valueEach * $count;
    }

    public function totalValue(): int
    {
        return $this->getValueFor($this->count);
    }

}
