<?php

namespace App\TT;

use App\TT\Items\InventoryItem;
use App\TT\Items\Item;
use Illuminate\Support\Collection;

class TrainYardPickUp
{
    protected static array $yields = [
        'recycled_waste'       => [
            'scrap_acid'    => 4,
            'scrap_lead'    => 2,
            'scrap_mercury' => 2,
        ],
        'recycled_trash'       => [
            'scrap_aluminum' => 4,
            'scrap_plastic'  => 8,
            'scrap_tin'      => 4,
        ],
        'recycled_electronics' => [
            'scrap_copper'  => 8,
            'scrap_gold'    => 1,
            'scrap_plastic' => 12,
        ],
        'petrochem_gas'        => [
            'military_chemicals' => 2,
            'petrochem_propane'  => 2,
            'petrochem_waste'    => 1,
        ],
        'petrochem_oil'        => [
            'petrochem_diesel'   => 1,
            'petrochem_kerosene' => 1,
            'petrochem_petrol'   => 2,
        ]
    ];

    public string $pickupName;

    public Item $pickupItem;

    public Inventories $inventories;

    public function __construct(string $pickupName, Inventories $inventories, bool $leaveRoom)
    {
        $this->pickupName  = $pickupName;
        $this->pickupItem = new Item($pickupName);
        $this->inventories = $inventories;
        $this->fillTrunksWithItem();

        if ($leaveRoom) {
            $this->fillTrainYardWithProcessedItems();
        }
    }

    protected function fillTrainYardWithProcessedItems(): void
    {
        /** @var Trunk $trainYard */
        $trainYard = $this->inventories->trunks->firstWhere('name', 'trainYard');

        foreach (self::$yields[$this->pickupName] as $itemName => $count) {
            $trainYard->load->push(new InventoryItem($itemName, $this->fullLoadCount() * $count) );
        }
    }

    public function getTrunksExceptTrainYard(): Collection
    {
        return $this->inventories->trunks
            ->reject(function (Trunk $trunk) {
                return $trunk->name == 'trainYard';
            });
    }

    protected function fillTrunksWithItem(): void
    {
        /** @var Trunk $trunk */
        $this->getTrunksExceptTrainYard()
            ->each(function (Trunk $trunk) {
                $trunk->fillLoadWithItem($this->pickupName);
            });
    }

    public function fullLoadCount(): int
    {
        return $this->inventories->trunks
            ->sum(function (Trunk $trunk) {
                return $trunk->load->firstWhere('name', $this->pickupName)?->count ?? 0;
            });
    }

    public function runsThatCanFitInTrainYard(): int
    {
        /** @var Trunk $trainYardStorage */
        $trainYardStorage = $this->inventories->trunks->firstWhere('name', 'trainYard');
        $fullLoadWeight   = $this->fullLoadCount() * $this->pickupItem->weight;

        return floor( $trainYardStorage->getAvailableCapacity() / $fullLoadWeight );
    }
}
