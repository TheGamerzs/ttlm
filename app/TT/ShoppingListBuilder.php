<?php

namespace App\TT;

use App\TT\Pickup\PickupRunCalculator;
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
            'refined_sand',
            'petrochem_diesel',
            'petrochem_kerosene',
            'petrochem_petrol',
            'military_chemicals',
            'petrochem_propane',
            'petrochem_waste',
        ]);
        self::$refinedOverrides = collect([
            'liquid_water',
            'petrochem_sulfur'
        ]);
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
            ->sortByDesc(function (RecipeShoppingListDecorator $item) {
                return $item->count;
            })
            ->groupBy(function (RecipeShoppingListDecorator $item) {
                return $item->getType();
            });

        // inject empty collections if needed
        foreach (['crafted', 'refined', 'scrap'] as $type) {
            if (! $cleaned->keys()->contains($type)) {
                $cleaned->put($type, collect());
            }
        }

        $calculator = new PickupRunCalculator($truckCapacity, $storage);
        if (! $cleaned->keys()->contains('scrap') ) {
            $calculator->getRunCalculations();
            $cleaned['pickupCalculator'] = $calculator;
            $cleaned['totalCost'] = 0;
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


        $calculator->getRunCalculations();

        $cleaned['totalCost'] = self::$allComponents->sum(function (RecipeShoppingListDecorator $item) {
            return $item->getTotalCraftingCost();
        });
        $cleaned['totalCost'] += collect($calculator->baseItemsCosts)->sum();
        $cleaned['totalCost'] += $recipe->costPerItem() * $count;

        $cleaned['pickupCalculator'] = $calculator;
        return $cleaned;
    }
}

