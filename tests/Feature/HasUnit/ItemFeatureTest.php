<?php

use App\TT\Items\Item;

it('gets a recipe based on itself', function () {

    $item = new Item('crafted_ceramictiles');
    $recipe = $item->getRecipe();

    expect($recipe)->toBeInstanceOf(\App\TT\Recipe::class)
        ->and($recipe->inventoryItem)->toBe($item);

});
