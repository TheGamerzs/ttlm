<?php


use App\TT\Items\Item;

it('calculates the total weight of the inventory item', function () {

    $ceramicTiles = new \App\TT\Items\InventoryItem('crafted_ceramictiles', 100);

    // Ceramic tiles weigh 10kg, expect 100 to weigh 4000kg.
    expect($ceramicTiles->getTotalWeight())->toBe(1000)->toBeInt();

});
