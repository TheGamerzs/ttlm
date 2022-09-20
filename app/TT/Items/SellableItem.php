<?php

namespace App\TT\Items;

use App\TT\Storage;

class SellableItem extends Item
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
        'scrap_plastic' => [
            'each' => 3600,
            'location' => 'McKenzie Export'
        ],
        'scrap_emerald' => [
            'each' => 25000,
            'location' => 'Jewelry Store'
        ],
        'refined_copper' => [
            'each' => 10000,
            'location' => 'LS Port'
        ],
        'refined_zinc' => [
            'each' => 11600,
            'location' => 'LS Port'
        ],
    ];

    public static function getAllForStorage(Storage $storage): \Illuminate\Support\Collection|Storage
    {
        $sellable = $storage->whereIn('name', array_keys(self::$data));

        return $sellable->map(function ($item) {
            return new self($item->name, $item->count);
        });
    }

    public int $count;

    public int $valueEach;

    public string $location;

    public function __construct(string $name, int $count)
    {
        parent::__construct($name);
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
