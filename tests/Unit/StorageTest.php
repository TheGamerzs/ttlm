<?php

use \App\TT\Storage;

it('returns the total summed weight of all items', function () {
//    'refined_tin' => 10,
//    'refined_zinc' => 10,
//    'scrap_acid' => 5,
    $storage = new Storage([
        new \App\TT\Items\InventoryItem('refined_tin', 1),
        new \App\TT\Items\InventoryItem('refined_zinc', 1),
        new \App\TT\Items\InventoryItem('scrap_acid', 1)
    ]);

    expect($storage->totalWeight())->toBe(25);

});

it('sorts items by weight', function () {
//    'refined_tin' => 10,
//    'refined_zinc' => 10,
//    'scrap_acid' => 5,
//    'recycled_electronics' => 130,

    $storage = new Storage([
        new \App\TT\Items\InventoryItem('refined_tin', 1),
        new \App\TT\Items\InventoryItem('refined_zinc', 1),
        new \App\TT\Items\InventoryItem('scrap_acid', 1),
        new \App\TT\Items\InventoryItem('recycled_electronics', 1)
    ]);

    expect($storage->sortByWeight()->first()->name)->toBe('recycled_electronics');

});

it('sorts items by count', function () {
//    'refined_tin' => 10,
//    'refined_zinc' => 10,
//    'scrap_acid' => 5,
//    'recycled_electronics' => 130,

    $storage = new Storage([
        new \App\TT\Items\InventoryItem('refined_tin', 1),
        new \App\TT\Items\InventoryItem('refined_zinc', 1),
        new \App\TT\Items\InventoryItem('scrap_acid', 100),
        new \App\TT\Items\InventoryItem('recycled_electronics', 1)
    ]);

    expect($storage->sortByCount()->first()->name)->toBe('scrap_acid');

});
