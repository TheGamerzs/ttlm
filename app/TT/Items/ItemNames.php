<?php

namespace App\TT\Items;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class ItemNames
{
    //Lookup: https://ttapi.elfshot.xyz/items?item=
    public static array $names = [
        'crafted_batteries' => 'Truck Cargo: Batteries',
        'crafted_cement' => 'Truck Cargo: Cement Mix',
        'crafted_ceramictiles' => 'Truck Cargo: Ceramic Tiles',
        'crafted_circuit' => 'Truck Cargo: Circuit Boards',
        'crafted_computer' => 'Truck Cargo: Computers',
        'crafted_concrete' => 'Truck Cargo: Concrete',
        'crafted_copperwire' => 'Truck Cargo: Copper Wire Spool',
        'crafted_fiberglass' => 'Truck Cargo: Fiberglass Spool',
        'crafted_jewelry' => 'Truck Cargo: Jewelry',
        'crafted_rebar' => 'Truck Cargo: Rebar',

        'fridge_airline_meal' => 'Airline Meal',
        'fridge_veggies' => 'Fridge: Vegetables',
        'fridge_dairy' => 'Fridge: Dairy Products',
        'fridge_meat' => 'Fridge: Frozen Raw Meat',

        'liquid_water' => 'Truck Cargo: Treated Water (100L)',
        'liquid_water_raw' => 'Truck Cargo: Unfiltered Water (100L)',

        'mechanicals_battery' => 'Automotive: Car Battery',
        'mechanicals_battery_evb' => 'Automotive: Traction Battery',
        'mechanicals_chassis' => 'Automotive: Chassis',
        'mechanicals_vehicle_framework' => 'Automotive: Vehicle Framework',
        'mechanicals_wheels' => 'Automotive: Wheels',
        'mechanicals_rubber' => 'Automotive: Rubber',

        'military_chemicals' => 'Military: Chemicals',
        'military_explosives' => 'Military: Explosives',
        'military_titanium' => 'Military: Titanium',
        'military_titanium_ore' => 'Military: Titanium Ore',

        'petrochem_diesel' => 'Petrochem: Diesel',
        'petrochem_kerosene' => 'Petrochem: Kerosene',
        'petrochem_petrol' => 'Petrochem: Petrol',
        'petrochem_propane' => 'Petrochem: Propane',
        'petrochem_sulfur' => 'Petrochem: Sulfur',
        'petrochem_waste' => 'Petrochem: Waste Water',

        'petrochem_gas' => 'Petrochem: Raw Gas',
        'petrochem_oil' => 'Petrochem: Crude Oil',
        'recycled_electronics' => 'Truck Cargo: Recycled Electronics',
        'recycled_rubble' => 'Truck Cargo: Quarry Rubble',
        'recycled_trash' => 'Truck Cargo: Recycled Trash',
        'recycled_waste' => 'Truck Cargo: Toxic Waste',

        'refined_aluminum' => 'Truck Cargo: Refined Aluminum',
        'refined_amalgam' => 'Truck Cargo: Refined Amalgam',
        'refined_bronze' => 'Truck Cargo: Refined Bronze Alloy',
        'refined_copper' => 'Truck Cargo: Refined Copper',
        'refined_flint' => 'Truck Cargo: Flint',
        'refined_glass' => 'Truck Cargo: Glass',
        'refined_gold' => 'Truck Cargo: Refined Gold',
        'refined_planks' => 'Truck Cargo: Planks',
        'refined_sand' => 'Truck Cargo: Sand',
        'refined_solder' => 'Truck Cargo: Refined Solder',
        'refined_tin' => 'Truck Cargo: Refined Tin',
        'refined_zinc' => 'Truck Cargo: Refined Zinc',

        'scrap_acid' => 'Truck Cargo: Acid',
        'scrap_aluminum' => 'Truck Cargo: Scrap Aluminum',
        'scrap_copper' => 'Truck Cargo: Scrap Copper',
        'scrap_emerald' => 'Truck Cargo: Raw Emeralds',
        'scrap_gold' => 'Truck Cargo: Scrap Gold',
        'scrap_gravel' => 'Truck Cargo: Gravel',
        'scrap_lead' => 'Truck Cargo: Scrap Lead',
        'scrap_mercury' => 'Truck Cargo: Scrap Mercury',
        'scrap_ore' => 'Truck Cargo: Raw Ore Mix',
        'scrap_plastic' => 'Truck Cargo: Scrap Plastic',
        'scrap_tin' => 'Truck Cargo: Scrap Tin',
        'tcargodust' => 'Truck Cargo: Sawdust',
        'tcargologs' => 'Truck Cargo: Logs',
        'pucargosmall' => 'Truck Cargo: Tools',

        'vehicle_shipment|zr350|Annis ZR-350|car' => 'Vehicle Shipment: Annis ZR-350',
        'vehicle_shipment|voltic2|Coil Rocket Voltic|car' => 'Vehicle Shipment: Coil Rocket Voltic',
        'vehicle_shipment|savanna|Coil Savanna|car' => 'Vehicle Shipment: Coil Savanna',
        'vehicle_shipment|vertice|Hijak Vertice|car' => 'Vehicle Shipment: Hijak Vertice',
        'vehicle_shipment|futo|Karin Futo|car' => 'Vehicle Shipment: Karin Futo',
        'vehicle_shipment|landstalker2|Landstalker XL|car' => 'Vehicle Shipment: Karin Futo',
        'repair_shop' => 'Temporary Repair Shop',

        'upgrade_kit_blistata' => 'Upgrade Kit (Go Go Monkey Blista)',
        'upgrade_kit_dragking' => 'Upgrade Kit (Rampant Rocket)',
        'upgrade_kit_hpr1' => 'Upgrade Kit (RE-7B)',
        'upgrade_kit_sultanr' => 'Upgrade Kit (Sultan)',
        'upgrade_kit_sultanrs' => 'Upgrade Kit (Sultan R)',

        'defibkit' => 'Defibkit',
        'flotsam' => 'Flotsam',
        'speed_trap_radar' => 'Speed Trap Radar',
        'rts_professional' => 'R.T.S.: Professional License',
        'rts_air_license' => 'R.T.S.: Aviator License',

        'house' => 'Completed House',
    ];

    protected static function logMissingItemName(string $itemName): void
    {
        /** @var Collection $missingItems */
        $missingItems = Cache::get('missingItemNames', collect());
        if (! $missingItems->contains($itemName) ) {
            $missingItems->push($itemName);
            Cache::put('missingItemsNames', $missingItems);
        }
    }

    public static function getName(string $itemName): ?string
    {
        if (array_key_exists($itemName, self::$names)) {
            return Str::of(self::$names[$itemName])->after(': ');
        }

        self::logMissingItemname($itemName);
        return null;
    }
}
