<?php

use App\TT\Items\Item;

it('calculates how many can fit in a defined space', function () {

    $ceramicTiles = new Item('crafted_ceramictiles');

    // Ceramic Tiles weigh 10kg. Expect 10 to fit in a trailer with 100kg capacity.
    expect($ceramicTiles->howManyCanFitInSpace(100))->toBe(10)->toBeInt();

});
