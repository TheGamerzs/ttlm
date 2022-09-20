<?php

use App\TT\Items\Item;

it('calculates how many can fit in a defined space', function () {

    $ceramicTiles = new Item('crafted_ceramictiles');

    // Ceramic Tiles weigh 40kg. Expect 2 to fit in a trailer with 100kg compacity.
    expect($ceramicTiles->howManyCanFitInSpace(100))->toBe(2)->toBeInt();

});
