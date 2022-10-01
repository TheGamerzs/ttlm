<?php

namespace App\TT;

use App\TT\Items\Item;
use JetBrains\PhpStorm\ArrayShape;

class PickupRun
{
    #[ArrayShape(['scrap_emerald' => "float", 'scrap_ore' => "float|int", 'refined_flint' => "float|int", 'refined_sand' => "float|int"])]
    public static function quarry(int $truckCapacity): array
    {
        $rubbleWeight = 150;
        $pickupCount  = floor($truckCapacity / $rubbleWeight);

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

    public static function logging(int $truckCapacity, string $craftingMaterialName): array
    {
        $logWeight   = 60;
        $pickupCount = floor($truckCapacity / $logWeight);

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

    public static function trash(int $truckCapacity): array
    {
        $trashWeight = 90;
        $pickupCount = floor($truckCapacity / $trashWeight);

        return [
            [
                'scrap_aluminum' => (int)(4 * $pickupCount),
                'scrap_plastic'  => (int)(8 * $pickupCount),
                'scrap_tin'      => (int)(4 * $pickupCount),
            ]
        ];
    }

    public static function electronics(int $truckCapacity): array
    {
        $electronicsWeight = 130;
        $pickupCount       = floor($truckCapacity / $electronicsWeight);

        return [
            [
                'scrap_copper'  => (int)(8 * $pickupCount),
                'scrap_gold'    => (int)($pickupCount),
                'scrap_plastic' => (int)(12 * $pickupCount),
            ]
        ];
    }

    public static function toxicWaste(int $truckCapacity): array
    {
        $wasteWeight = 110;
        $pickupCount = floor($truckCapacity / $wasteWeight);

        return [
            [
                'scrap_acid' => (int)(4 * $pickupCount),
                'scrap_lead' => (int)(2 * $pickupCount),
                'scrap_mercury' => (int)(2 * $pickupCount),
            ]
        ];
    }

    public static function crudeOil(int $truckCapacity, string $craftingMaterialName): array
    {
        if ($craftingMaterialName == 'mechanicals_rubber') {
            $oilWeight = 150 * 4;
            $pickupCount = floor($truckCapacity / $oilWeight);
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
        $pickupCount = floor($truckCapacity / $oilWeight);

        return [
            [
                'petrochem_diesel' => (int)($pickupCount),
                'petrochem_kerosene' => (int)($pickupCount),
                'petrochem_petrol' => (int)($pickupCount * 2),
            ]
        ];
    }

    public static function rawGas(int $truckCapacity): array
    {
        $gasWeight = 150;
        $pickupCount = floor($truckCapacity / $gasWeight);

        return [
            [
                'military_chemicals' => (int)($pickupCount * 2),
                'petrochem_propane' => (int)($pickupCount * 2),
                'petrochem_waste' => (int)($pickupCount),
            ]
        ];
    }
}
