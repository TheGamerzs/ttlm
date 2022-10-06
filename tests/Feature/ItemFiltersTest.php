<?php

use App\TT\Items\InventoryItem;
use \App\TT\Storage;
use Illuminate\Pipeline\Pipeline;

test('NameByStringFilter class', function () {

    $pipes = [
        'App\TT\ItemFilters\NameByStringFilter:' . 'Ore',
    ];

    $storage = new Storage([
        new InventoryItem('scrap_ore', 3),
        new InventoryItem('refined_planks', 4)
    ]);

    /** @var Storage $result */
    $result = app(Pipeline::class)
        ->send($storage)
        ->through($pipes)
        ->thenReturn();

    expect($result->count())->toBe(1)
        ->and($result->contains('name', 'scrap_ore'))->toBeTrue()
        ->and($result->contains('name', 'refined_planks'))->toBeFalse();

});
