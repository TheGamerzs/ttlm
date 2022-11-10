<?php


use App\TT\Items\InventoryItem;

it('calculates the total weight of the inventory item', function () {

    $ceramicTiles = new InventoryItem('crafted_ceramictiles', 100, 10);

    // Ceramic tiles weigh 10kg, expect 100 to weigh 4000kg.
    expect($ceramicTiles->getTotalWeight())->toBe(1000)->toBeInt();

});
