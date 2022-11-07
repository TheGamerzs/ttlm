<?php

use App\TT\Factories\ItemFactory;
use App\TT\Storage;
use Illuminate\Pipeline\Pipeline;

test('Name By String Filter', function () {

    $pipes = [
        'App\TT\ItemFilters\NameByStringFilter:' . 'Ore',
    ];

    $storage = new Storage([
        ItemFactory::makeInventoryItem('scrap_ore', 3),
        ItemFactory::makeInventoryItem('refined_planks', 4)
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
        ItemFactory::makeInventoryItem('scrap_ore', 1),
        ItemFactory::makeInventoryItem('refined_planks', 100)
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
        ItemFactory::makeInventoryItem('scrap_ore', 1),
        ItemFactory::makeInventoryItem('refined_planks', 100)
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
        ItemFactory::makeInventoryItem('scrap_ore', 20),      // Total Weight: 300
        ItemFactory::makeInventoryItem('refined_planks', 100) // Total Weight: 1500
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
        ItemFactory::makeInventoryItem('scrap_ore', 20),      // Total Weight: 300
        ItemFactory::makeInventoryItem('refined_planks', 100) // Total Weight: 1500
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


