<?php

namespace App\TT\Pickup;

use App\TT\Inventories;
use App\TT\Trunk;

class PickupRunYields
{
    public int $capacity;

    public function __construct(Inventories $inventories)
    {
        $this->capacity = $inventories
            ->trunks
            ->sum(function (Trunk $trunk) {
                return $trunk->getAvailableCapacity();
            });
    }

    public function quarry(): array
    {
        $rubbleWeight = 150;
        $pickupCount  = floor($this->capacity / $rubbleWeight);

        // 10 gravel = 4 Flint and 6 Sand
        $gravel                 = $pickupCount * 12;
        $processableGravelCount = floor($gravel / 10);

        return [[
                    'scrap_emerald' => (int)$pickupCount,
                    'scrap_ore'     => (int)(4 * $pickupCount),
                    'refined_flint' => (int)(4 * $processableGravelCount),
                    'refined_sand'  => (int)(6 * $processableGravelCount)
                ]];
    }

    public function logging(string $craftingMaterialName): array
    {
        $logWeight   = 60;
        $pickupCount = floor($this->capacity / $logWeight);

        if ($craftingMaterialName == 'refined_planks') {
            return [[
                'tcargodust'     => (int)(2 * $pickupCount),
                'refined_planks' => (int)($pickupCount)
            ]];
        }
        return [
            [
                'tcargodust' => (int)(10 * $pickupCount),
            ],
            [
                'tcargodust'     => (int)(2 * $pickupCount),
                'refined_planks' => (int)($pickupCount)
            ]
        ];

    }

    public function trash(): array
    {
        $trashWeight = 90;
        $pickupCount = floor($this->capacity / $trashWeight);

        return [
            [
                'scrap_aluminum' => (int)(4 * $pickupCount),
                'scrap_plastic'  => (int)(8 * $pickupCount),
                'scrap_tin'      => (int)(4 * $pickupCount),
            ]
        ];
    }

    public function electronics(): array
    {
        $electronicsWeight = 130;
        $pickupCount       = floor($this->capacity / $electronicsWeight);

        return [
            [
                'scrap_copper'  => (int)(8 * $pickupCount),
                'scrap_gold'    => (int)($pickupCount),
                'scrap_plastic' => (int)(12 * $pickupCount),
            ]
        ];
    }

    public function toxicWaste(): array
    {
        $wasteWeight = 110;
        $pickupCount = floor($this->capacity / $wasteWeight);

        return [
            [
                'scrap_acid' => (int)(4 * $pickupCount),
                'scrap_lead' => (int)(2 * $pickupCount),
                'scrap_mercury' => (int)(2 * $pickupCount),
            ]
        ];
    }

    public function crudeOil(string $craftingMaterialName): array
    {
        if ($craftingMaterialName == 'mechanicals_rubber') {
            $oilWeight = 150 * 4;
            $pickupCount = floor($this->capacity / $oilWeight);
            return [
                [
                    'mechanicals_rubber' => (int)($pickupCount * 4),
                    'petrochem_diesel' => (int)($pickupCount * 2),
                    'petrochem_kerosene' => (int)($pickupCount * 2),
                    'petrochem_petrol' => (int)($pickupCount * 4),
                ]
            ];
        }

        $oilWeight = 150;
        $pickupCount = floor($this->capacity / $oilWeight);

        return [
            [
                'petrochem_diesel' => (int)($pickupCount),
                'petrochem_kerosene' => (int)($pickupCount),
                'petrochem_petrol' => (int)($pickupCount * 2),
            ]
        ];
    }

    public function rawGas(): array
    {
        $gasWeight = 150;
        $pickupCount = floor($this->capacity / $gasWeight);

        return [
            [
                'military_chemicals' => (int)($pickupCount * 2),
                'petrochem_propane' => (int)($pickupCount * 2),
                'petrochem_waste' => (int)($pickupCount),
            ]
        ];
    }

    public function veggies(): array
    {
        $vegWeight = 15;
        $pickupCount = floor($this->capacity / $vegWeight);

        return [
            [
                'fridge_veggies' => (int) $pickupCount
            ]
        ];
    }

    public function dairy(): array
    {
        $dairyWeight = 15;
        $pickupCount = floor($this->capacity / $dairyWeight);

        return [
            [
                'fridge_dairy' => (int) $pickupCount
            ]
        ];
    }
}
