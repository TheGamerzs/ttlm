<?php

use App\TT\Items\InventoryItem;
use \App\TT\Storage;
use Illuminate\Pipeline\Pipeline;

test('Name By String Filter', function () {

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

test('Min Count Filter', function () {

    $pipes = [
        'App\TT\ItemFilters\MinCountFilter:' . '50',
    ];

    $storage = new Storage([
        new InventoryItem('scrap_ore', 1),
        new InventoryItem('refined_planks', 100)
    ]);

    /** @var Storage $result */
    $result = app(Pipeline::class)
        ->send($storage)
        ->through($pipes)
        ->thenReturn();

    expect($result->count())->toBe(1)
        ->and($result->contains('name', 'scrap_ore'))->toBeFalse()
        ->and($result->contains('name', 'refined_planks'))->toBeTrue();

});

test('Max Count Filter', function () {

    $pipes = [
        'App\TT\ItemFilters\MaxCountFilter:' . '50',
    ];

    $storage = new Storage([
        new InventoryItem('scrap_ore', 1),
        new InventoryItem('refined_planks', 100)
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



test('Min Total Weight Filter', function () {

    $pipes = [
        'App\TT\ItemFilters\MinTotalWeightFilter:' . '400',
    ];

    $storage = new Storage([
        new InventoryItem('scrap_ore', 20),      // Total Weight: 300
        new InventoryItem('refined_planks', 100) // Total Weight: 1500
    ]);


    /** @var Storage $result */
    $result = app(Pipeline::class)
        ->send($storage)
        ->through($pipes)
        ->thenReturn();

    expect($result->count())->toBe(1)
        ->and($result->contains('name', 'scrap_ore'))->toBeFalse()
        ->and($result->contains('name', 'refined_planks'))->toBeTrue();

});

test('Max Total Weight Filter', function () {

    $pipes = [
        'App\TT\ItemFilters\MaxTotalWeightFilter:' . '500',
    ];

    $storage = new Storage([
        new InventoryItem('scrap_ore', 20),      // Total Weight: 300
        new InventoryItem('refined_planks', 100) // Total Weight: 1500
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


