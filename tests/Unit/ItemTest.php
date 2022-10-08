<?php

use App\TT\Items\Item;

it('calculates how many can fit in a defined space', function () {

    $ceramicTiles = new Item('crafted_ceramictiles');

    // Ceramic Tiles weigh 10kg. Expect 10 to fit in a trailer with 100kg capacity.
    expect($ceramicTiles->howManyCanFitInSpace(100))->toBe(10)->toBeInt();

});

it('shows a pretty name if one exists', function () {

    $ceramicTiles = new Item('crafted_ceramictiles');

    expect($ceramicTiles->name())->toBe('Ceramic Tiles');

});

it('gets a recipe based on itself', function () {

    $item = new Item('crafted_ceramictiles');
    $recipe = $item->getRecipe();

    expect($recipe)->toBeInstanceOf(\App\TT\Recipe::class)
        ->and($recipe->inventoryItem)->toBe($item);

});
