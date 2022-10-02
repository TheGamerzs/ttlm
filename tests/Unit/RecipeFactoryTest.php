<?php

use App\TT\Items\Item;
use App\TT\RecipeFactory;

test('all recipes have a cost', function () {

    $recipes = collect(\App\TT\Recipes::getAllRecipes());
    $recipesWithoutCostCount = $recipes->reject(function ($item, $key) {
        return array_key_exists('cost', $item);
    })->count();

    expect($recipesWithoutCostCount)->toBe(0);

});

it('produces a hydrated recipe object', function () {

    $item = new Item('crafted_batteries');
    $recipe = RecipeFactory::get($item);

    expect($recipe->inventoryItem)->toBe($item)
        ->and($recipe->components->count())->toBe(3)
        ->and($recipe->craftingLocation)->toBe('LS Factory')
        ->and($recipe->pickupRun)->toBeFalse()
        ->and($recipe->makes)->toBe(2)
        ->and($recipe->cost)->toBe(5000);

});
