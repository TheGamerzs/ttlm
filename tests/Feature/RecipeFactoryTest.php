<?php

use App\TT\Items\Item;
use App\TT\Recipe;
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

    expect($recipe)
        ->inventoryItem       ->toBe($item)
        ->components->count() ->toBe(3)
        ->craftingLocation    ->toBe('LS Factory')
        ->pickupRun           ->toBeFalse()
        ->makes               ->toBe(2)
        ->cost                ->toBe(5000);

});

it('can accept a item name string', function () {

    $recipe = RecipeFactory::get('crafted_batteries');

    expect($recipe)
        ->inventoryItem       ->toBeInstanceOf(Item::class)
        ->components->count() ->toBe(3)
        ->craftingLocation    ->toBe('LS Factory')
        ->pickupRun           ->toBeFalse()
        ->makes               ->toBe(2)
        ->cost                ->toBe(5000);

});

it('is used through make static function on recipe', function () {

    $recipe = Recipe::make('crafted_batteries');

    expect($recipe)
        ->inventoryItem       ->toBeInstanceOf(Item::class)
        ->components->count() ->toBe(3)
        ->craftingLocation    ->toBe('LS Factory')
        ->pickupRun           ->toBeFalse()
        ->makes               ->toBe(2)
        ->cost                ->toBe(5000);

});
