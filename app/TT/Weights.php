<?php

namespace App\TT;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;

class Weights
{
    //Lookup: https://ttapi.elfshot.xyz/items?item=
    public static array $weights = [
        'crafted_batteries' => 30,
        'crafted_cement' => 25,
        'crafted_ceramictiles' => 10,
        'crafted_circuit' => 20,
        'crafted_computer' => 35,
        'crafted_concrete' => 160,
        'crafted_copperwire' => 20,
        'crafted_rebar' => 45,
        'liquid_water' => 100,
        'liquid_water_raw' => 100,
        'military_chemicals' => 25,
        'military_explosives' => 250,
        'military_titanium_ore' => 25,
        'petrochem_diesel' => 25,
        'petrochem_gas' => 150,
        'petrochem_kerosene' => 25,
        'petrochem_oil' => 150,
        'petrochem_petrol' => 25,
        'petrochem_propane' => 25,
        'petrochem_sulfur' => 5,
        'petrochem_waste' => 50, //Waste Water
        'recycled_electronics' => 130,
        'recycled_rubble' => 150,
        'recycled_trash' => 90,
        'recycled_waste' => 110,
        'refined_aluminum' => 10,
        'refined_amalgam' => 15,
        'refined_bronze' => 10,
        'refined_copper' => 10,
        'refined_flint' => 5,
        'refined_gold' => 20,
        'refined_planks' => 15,
        'refined_sand' => 5,
        'refined_solder' => 5,
        'refined_tin' => 10,
        'refined_zinc' => 10,
        'scrap_acid' => 5,
        'scrap_aluminum' => 5,
        'scrap_copper' => 5,
        'scrap_emerald' => 10,
        'scrap_gold' => 10,
        'scrap_gravel' => 5,
        'scrap_lead' => 15,
        'scrap_mercury' => 15,
        'scrap_ore' => 15,
        'scrap_plastic' => 5, //
        'scrap_tin' => 5,
        'tcargodust' => 3, //Sawdust
        'tcargologs' => 60,

        'upgrade_kit_blistata' => 20,
        'upgrade_kit_dragking' => 20,
        'upgrade_kit_hpr1' => 20,
        'upgrade_kit_sultanr' => 20,
        'upgrade_kit_sultanrs' => 20,

        'flotsam' => 10,
        'speed_trap_radar' => 5,
        'rts_professional' => 95,
        'rts_air_license' => 95,


        'house' => 1,
        'testing_fake' => 1
    ];

    protected static function logMissingItem(string $itemName): void
    {
        /** @var Collection $missingItems */
        $missingItems = Cache::get('missingItems', collect());
        if (! $missingItems->contains($itemName) ) {
            $missingItems->push($itemName);
            Cache::put('missingItems', $missingItems);
        }
    }

    public static function getWeight(string $itemName): ?int
    {
        if (array_key_exists($itemName, self::$weights)) {
            return self::$weights[$itemName];
        }

        self::logMissingItem($itemName);
        return null;
    }
}
