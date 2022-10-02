<?php

test('all recipes have a cost', function () {

    $recipes = collect(\App\TT\Recipes::getAllRecipes());
    $recipesWithoutCostCount = $recipes->reject(function ($item, $key) {
        return array_key_exists('cost', $item);
    })->count();

    expect($recipesWithoutCostCount)->toBe(0);

});
