<?php

namespace App\TT;

use Illuminate\Support\Collection;

class ShoppingListBuilder
{
    public static Collection $allComponents;

    public static Collection $scrapOverrides;

    public static Collection $refinedOverrides;

    public static Storage $storage;

    public static array $diminishingStorage;

    public static function setDefaults(): void
    {
        self::$allComponents = collect();
        self::$scrapOverrides = collect([
            'tcargodust',
            'liquid_water_raw',
            'refined_planks',
            'refined_flint',
            'refined_sand'
        ]);
        self::$refinedOverrides = collect(['liquid_water']);
    }

    public static function build(Recipe $recipe, Storage $storage, int $count, int $truckCapacity)
    {
        self::setDefaults();
        self::$storage = $storage;
        self::$diminishingStorage = $storage->mapWithKeys(function ($item, $key) {
            return [$item->name => $item->count];
        })->toArray();

        $decorated = new RecipeShoppingListDecorator($recipe, $count);

        $cleaned = self::$allComponents
            ->filter(function (RecipeShoppingListDecorator $item) {
                return $item->count > 0;
            })
            ->groupBy(function (RecipeShoppingListDecorator $item) {
                return $item->getType();
            });


        $calculator = new PickupRunCalculator($truckCapacity);
        if (! $cleaned->keys()->contains('scrap') ) {
            $cleaned['pickupCalculator'] = $calculator;
            return $cleaned;
        }

        $scrapPickups = $cleaned['scrap'];
        /** @var RecipeShoppingListDecorator $item */
        foreach ($scrapPickups as $item) {
            $calculator->addNeededCount($item->recipeName, $item->count);
        }

        /**
         * @var Collection $levelGroup
         * This chunk goes through all the items and essentially combines duplicates by adding counts together.
         */
        foreach($cleaned as $key => $levelGroup) {
            $cleaned[$key] = $levelGroup->groupBy('recipeName')
                ->map(function (Collection $items) {
                    $keep = $items->pop();
                    foreach ($items as $item) {
                        $keep->count += $item->count;
                    }
                    return $keep;
                });
        }

        $cleaned['pickupCalculator'] = $calculator;
        return $cleaned;
    }
}

//        $pickups = $cleaned['scrap']->groupBy(function (RecipeShoppingListDecorator $item) {
//            return match ($item->recipeName) {
//                'scrap_copper', 'scrap_gold', 'scrap_plastic' => 'electronics',
//                'scrap_acid', 'scrap_lead', 'scrap_mercury' => 'toxic waste',
//                'scrap_aluminum', 'scrap_tin' => 'trash', // 'scrap_plastic' could go here too
//                'tcargodust', 'refined_planks' => 'logging',
//                'liquid_water_raw' => 'water',
//                default => 'unhandled'
//            };
//        });
