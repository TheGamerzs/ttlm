<?php

namespace App\TT;

use App\TT\Items\InventoryItem;
use App\TT\Items\ItemData;

class TrainYardPickUp
{
    protected static array $yields = [
        'recycled_waste' => [
            'scrap_acid' => 4,
            'scrap_lead' => 2,
            'scrap_mercury' => 2,
        ],
        'recycled_trash' => [
            'scrap_aluminum' => 4,
            'scrap_plastic'  => 8,
            'scrap_tin'      => 4,
        ],
        'recycled_electronics' => [
            'scrap_copper'  => 8,
            'scrap_gold'    => 1,
            'scrap_plastic' => 12,
        ],
        'petrochem_gas' => [
            'military_chemicals' => 2,
            'petrochem_propane' => 2,
            'petrochem_waste' => 1,
        ],
        'petrochem_oil' => [
            'petrochem_diesel' => 1,
            'petrochem_kerosene' => 1,
            'petrochem_petrol' => 2,
        ]
    ];

    public string $pickupItemName;

    public int $pickupItemWeight;

    public int $truckCapacity;

    public int $pocketCapacity;

    public int $storageCapacity;

    public bool $leaveRoom;

    public function __construct(string $pickupItemName,
                                int $truckCapacity,
                                bool $leaveRoom,
                                int $pocketCapacity = 600,
                                int $trainYardStorageCapacity = 30107)
    {
        $this->pickupItemName = $pickupItemName;
        $this->pickupItemWeight = ItemData::getWeight($pickupItemName);
        $this->truckCapacity = $truckCapacity;
        $this->pocketCapacity = $pocketCapacity;
        $this->storageCapacity = $trainYardStorageCapacity;
        $this->leaveRoom = $leaveRoom;
    }

    public function pickupItemsCountTrailer(): int
    {
        return (int) floor($this->truckCapacity / $this->pickupItemWeight);
    }

    public function pickupItemsCountPocket(): int
    {
        return (int) floor($this->pocketCapacity / $this->pickupItemWeight);
    }

    public function pickupItemRefinedWeight(): int
    {
        return collect(self::$yields[$this->pickupItemName])
            ->map(function (int $itemCount, string $itemName) {
                return new InventoryItem($itemName, $itemCount);
            })
            ->sum(function (InventoryItem $item) {
                return $item->getTotalWeight();
            });
    }

    public function leftoverWeightNeededForFirstRefine(): int
    {
        return ( $this->pickupItemsCountTrailer() + $this->pickupItemsCountPocket() )
            * $this->pickupItemRefinedWeight();
    }

    public function usableStorageCapacity(): int
    {
        if ($this->leaveRoom) {
            return $this->storageCapacity - $this->leftoverWeightNeededForFirstRefine();
        }
        return $this->storageCapacity;
    }

    public function oneRunTotalWeight(): int
    {
        return ( $this->pickupItemsCountTrailer() + $this->pickupItemsCountPocket() )
            * $this->pickupItemWeight;
    }

    public function howManyTimesTrainYardCanBeUsed(): int
    {
        if ($this->oneRunTotalWeight() < 1) return 0;

        return (int) floor($this->usableStorageCapacity() / $this->oneRunTotalWeight());
    }
}
