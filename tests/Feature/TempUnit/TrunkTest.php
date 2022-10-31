<?php

use App\TT\Items\Item;
use App\TT\Trunk;

test('constructor', function () {

    $trunk = new Trunk('name', 1000);

    expect($trunk->name)->toBe('name')
        ->and($trunk->capacity)->toBe(1000);

});

test('numberOfItemsThatCanFitFromWeight method', function () {

    $trunk = new Trunk('name', 1000);

    expect($trunk->numberOfItemsThatCanFitFromWeight(100))->toBe(10);

});

test('getAvailableCapacity method', function () {

    $trunk = new Trunk('name', 1000);
    $trunk->capacityUsed = 600;

    expect($trunk->getAvailableCapacity())->toBe(400);
});

test('numberOfItemsThatCanFitFromWeight method after setting capacity used', function () {

    $trunk = new Trunk('name', 1000);
    $trunk->setCapacityUsed(500);

    expect($trunk->numberOfItemsThatCanFitFromWeight(100))->toBe(5);

});

test('numberOfItemsThatCanFit method', function () {

    $item = new Item('scrap_ore'); // 15kg weight
    $trunk = new Trunk('name', 1000);

    expect($trunk->numberOfItemsThatCanFit($item))->toBe(66);

});

it('has a display name', function () {

    $trunk = new Trunk('pocket', 999);

    expect($trunk->displayName())->toBeInstanceOf(\Illuminate\Support\Stringable::class)
        ->and($trunk->displayName()->toString())->toBe('Pocket');

});
