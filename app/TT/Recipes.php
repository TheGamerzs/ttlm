<?php

namespace App\TT;

class Recipes
{
    public static function getRecipe($recipe = null): array
    {
        $recipes = [];

        $recipes['house'] = [
            'craftingLocation' => 'House Construction Site',
            'components'       => [
                'crafted_computer'     => 1,
                'crafted_concrete'     => 1,
                'crafted_copperwire'   => 1,
                'crafted_rebar'        => 1,
                'refined_planks'       => 2,
                'crafted_ceramictiles' => 4,
            ],
        ];

        /*
        |--------------------------------------------------------------------------
        | Crafted
        |--------------------------------------------------------------------------
        */
        $recipes['crafted_batteries'] = [
            'craftingLocation' => 'LS Factory',
            'makes'            => 2,
            'components'       => [
                'refined_solder' => 4,
                'refined_zinc'   => 2,
                'scrap_acid'     => 8,
            ]
        ];

        $recipes['crafted_cement'] = [
            'craftingLocation' => 'Quarry',
            'components'       => [
                'tcargodust'   => 2,
                'refined_sand' => 5
            ]
        ];

        $recipes['crafted_ceramictiles'] = [
            'craftingLocation' => 'LS Foundry',
            'makes'            => 2,
            'components'       => [
                'refined_flint' => 10,
                'refined_sand'  => 2
            ]
        ];

        $recipes['crafted_circuit'] = [
            'craftingLocation' => 'LS Factory',
            'components'       => [
                'crafted_copperwire' => 1,
                'refined_solder'     => 2,
                'scrap_plastic'      => 10,
            ]
        ];

        $recipes['crafted_computer'] = [
            'craftingLocation' => 'LS Factory',
            'components'       => [
                'crafted_batteries' => 1,
                'crafted_circuit'   => 1,
                'scrap_gold'        => 2
            ]
        ];

        $recipes['crafted_concrete'] = [
            'craftingLocation' => 'Filtering Plant',
            'components'       => [
                'crafted_cement' => 5,
                'liquid_water'   => 1
            ]
        ];

        $recipes['crafted_copperwire'] = [
            'craftingLocation' => 'LS Factory',
            'components'       => [
                'refined_copper' => 4,
                'refined_planks' => 1
            ]
        ];

        $recipes['crafted_rebar'] = [
            'craftingLocation' => 'LS Factory',
            'makes'            => 2,
            'components'       => [
                'refined_amalgam' => 6,
                'refined_bronze'  => 2
            ]
        ];

        /*
        |--------------------------------------------------------------------------
        | Military
        |--------------------------------------------------------------------------
        */

        $recipes['military_explosives'] = [
            'craftingLocation' => 'Military Workshop',
            'components' => [
                'petrochem_kerosene' => 8,// 8x Kerosene
                'petrochem_sulfur' => 10
            ]
        ];

        /*
        |--------------------------------------------------------------------------
        | Petrochem
        |--------------------------------------------------------------------------
        */

        // Actually military...
        $recipes['petrochem_sulfur'] = [
            'craftingLocation' => 'Water Treatment Plant',
            'makes' => 5,
            'components' => [
                'petrochem_waste' => 1,
            ]

        ];

        /*
        |--------------------------------------------------------------------------
        | Refined
        |--------------------------------------------------------------------------
        */
        $recipes['refined_aluminum'] = [
            'craftingLocation' => 'LS Foundry',
            'components'       => [
                'scrap_aluminum' => 2
            ]
        ];

        $recipes['refined_bronze'] = [
            'craftingLocation' => 'LS Foundry',
            'makes'            => 2,
            'components'       => [
                'scrap_aluminum' => 1,
                'scrap_copper'   => 2,
                'scrap_tin'      => 1
            ]
        ];

        $recipes['refined_amalgam'] = [
            'craftingLocation' => 'LS Foundry',
            'makes'            => 2,
            'components'       => [
                'refined_tin'   => 2,
                'scrap_mercury' => 2
            ]
        ];

        $recipes['refined_copper'] = [
            'craftingLocation' => 'LS Foundry',
            'components'       => [
                'scrap_copper' => 2
            ]
        ];

        $recipes['refined_solder'] = [
            'craftingLocation' => 'LS Foundry',
            'components'       => [
                'refined_aluminum' => 2,
                'scrap_lead'       => 2,
            ]
        ];

        $recipes['refined_tin'] = [
            'craftingLocation' => 'LS Foundry',
            'components'       => [
                'scrap_tin' => 2
            ]
        ];

        $recipes['refined_zinc'] = [
            'craftingLocation' => 'LS Foundry',
            'components'       => [
                'scrap_ore' => 2
            ]
        ];

        $recipes['liquid_water'] = [
            'craftingLocation' => 'Water Treatment Plant',
            'components'       => [
                'liquid_water_raw' => 1,
                'scrap_acid'       => 1,
            ]
        ];

        /*
        |--------------------------------------------------------------------------
        | Refrigeration
        |--------------------------------------------------------------------------
        */

//        $recipes['fridge_airline_meal'] = [
//            'craftingLocation' => 'Fridgit Co.',
//            'cost' => 2500,
//            'components' => [
//                'fridge_dairy' => 1,
//                'fridge_veggies' => 1,
//                'fridge_meat' => 1,
//            ]
//        ];
//
//        $recipes['fridge_dairy'] = [
//            'craftingLocation' => 'Grapeseed Farms',
//            'pickupRun' => 'dairy',
//            'components' => []
//        ];
//
//        $recipes['fridge_veggies'] = [
//            'craftingLocation' => 'Great Chaparral Farm',
//            'pickupRun' => 'veggies',
//            'components' => []
//        ];
//
//        $recipes['fridge_meat'] = [
//            'craftingLocation' => 'Great Chaparral Farm',
//            'pickupRun' => 'veggies',
//            'components' => []
//        ];

        /*
        |--------------------------------------------------------------------------
        | Pickup Runs
        |--------------------------------------------------------------------------
        */

        $quarryTemplate = [
            'craftingLocation' => 'Filtering Plant',
            'pickupRun'        => 'quarry',
            'components'       => []
        ];

        $trashTemplate = [
            'craftingLocation' => 'Sorting Facility',
            'pickupRun'        => 'trash',
            'components'       => []
        ];

        $sawmillTemplate = [
            'craftingLocation' => 'Sawmill',
            'pickupRun'        => 'logging camp',
            'components'       => []
        ];

        $electronicsTemplate = [
            'craftingLocation' => 'Sorting Facility',
            'pickupRun'        => 'electronics',
            'components'       => []
        ];

        $toxicWasteTemplate = [
            'craftingLocation' => 'Filtering Plant',
            'pickupRun'        => 'toxic waste',
            'components'       => []
        ];

        $crudeOilTemplate = [
            'craftingLocation' => 'Refinery',
            'pickupRun'        => 'crude oil',
            'components'       => []
        ];

        $rawGasTemplate = [
            'craftingLocation' => 'Refinery',
            'pickupRun'        => 'raw gas',
            'components'       => []
        ];

        $recipes['liquid_water_raw'] = [
            'craftingLocation' => 'Water Treatment Plant',
            'pickupRun'        => 'toxic waste', // Might not need to program a water run
            'components'       => []
        ];

        $recipes['scrap_emerald'] = $quarryTemplate;
        $recipes['scrap_ore']     = $quarryTemplate;
        $recipes['refined_flint'] = $quarryTemplate;
        $recipes['refined_sand']  = $quarryTemplate;
        $recipes['scrap_gravel']  = $quarryTemplate;

        $recipes['scrap_aluminum'] = $trashTemplate;
//        $recipes['scrap_plastic']  = $trashTemplate; //Electronics yields more and also gold. May change to show both in the future.
        $recipes['scrap_tin'] = $trashTemplate;

        $recipes['tcargologs']     = $sawmillTemplate;
        $recipes['tcargodust']     = $sawmillTemplate;
        $recipes['refined_planks'] = $sawmillTemplate;

        $recipes['scrap_copper']  = $electronicsTemplate;
        $recipes['scrap_gold']    = $electronicsTemplate;
        $recipes['scrap_plastic'] = $electronicsTemplate;

        $recipes['scrap_acid']    = $toxicWasteTemplate;
        $recipes['scrap_lead']    = $toxicWasteTemplate;
        $recipes['scrap_mercury'] = $toxicWasteTemplate;

        $recipes['petrochem_kerosene'] = $crudeOilTemplate;
        $recipes['petrochem_diesel'] = $crudeOilTemplate;
        $recipes['petrochem_petrol'] = $crudeOilTemplate;

        $recipes['military_chemicals'] = $rawGasTemplate;
        $recipes['petrochem_propane'] = $rawGasTemplate;
        $recipes['petrochem_waste'] = $rawGasTemplate;

//        $recipes['scrap_aluminum'] = [
//            'craftingLocation' => 'Recycling Center\Sorting Facility',
//            'makes'            => 4,
//            'components'       => [
//                'recycled_trash'   => 1
//            ]
//        ];


        if ($recipe == null) {
            return $recipes;
        }

        return $recipes[$recipe];
    }

    // Alias for readability
    public static function getAllRecipes(): array
    {
        return self::getRecipe();
    }

    public static function getAllNames(): array
    {
        return array_keys(self::getAllRecipes());
    }
}
