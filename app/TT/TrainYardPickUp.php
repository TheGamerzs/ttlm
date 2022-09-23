<?php

namespace App\TT;

use App\TT\Items\InventoryItem;

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
        ]
    ];

    public string $pickupItemName;

    public int $pickupItemWeight;

    public int $truckCompacity;

    public int $pocketCompacity;

    public int $storageCompacity;

    public function __construct($pickupItemName, int $truckCompacity, $pocketCompacity = 600, $trainYardStorageCompacity = 30107)
    {
        $this->pickupItemName = $pickupItemName;
        $this->pickupItemWeight = Weights::getWeight($pickupItemName);
        $this->truckCompacity = $truckCompacity;
        $this->pocketCompacity = $pocketCompacity;
        $this->storageCompacity = $trainYardStorageCompacity;
    }

    public function pickupItemsCountTrailer(): int
    {
        return (int) floor($this->truckCompacity / $this->pickupItemWeight);
    }

    public function pickupItemsCountPocket(): int
    {
        return (int) floor($this->pocketCompacity / $this->pickupItemWeight);
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

    public function usableStorageCompacity(): int
    {
        return $this->storageCompacity - $this->leftoverWeightNeededForFirstRefine();
    }

    public function oneRunTotalWeight(): int
    {
        return ( $this->pickupItemsCountTrailer() + $this->pickupItemsCountPocket() )
            * $this->pickupItemWeight;
    }

    public function howManyTimesTrainYardCanBeUsed(): int
    {
        return (int) floor($this->usableStorageCompacity() / $this->oneRunTotalWeight());
    }
}
