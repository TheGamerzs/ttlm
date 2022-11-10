<?php

use App\TT\Items\InventoryItem;
use App\TT\Items\Item;
use App\TT\Trunk;
use Illuminate\Support\Collection;

test('constructor', function () {

    $trunk = new Trunk('name', 1000);

    expect($trunk->name)->toBe('name')
        ->and($trunk->capacity)->toBe(1000);

    $load = [
        new InventoryItem('crafted_rebar', 2),
        new InventoryItem('crafted_copperwire', 2)
    ];
    $trunkWithLoad = new Trunk('with load', 1000, $load);

    expect($trunkWithLoad->load)->toBeInstanceOf(Collection::class)
        ->and($trunkWithLoad->load->count())->toBe(2)
        ->and($trunkWithLoad->load->contains('name', 'crafted_rebar'))->toBeTrue()
        ->and($trunkWithLoad->load->contains('name', 'crafted_copperwire'))->toBeTrue();

});

test('numberOfItemsThatCanFitFromWeight method', function () {

    $trunk = new Trunk('name', 1000);

    expect($trunk->numberOfItemsThatCanFitFromWeight(100))->toBe(10);

});

test('getAvailableCapacity method with only manual', function () {

    $trunk = new Trunk('name', 1000);
    $trunk->capacityUsed = 600;

    expect($trunk->getAvailableCapacity())->toBe(400);
});

test('getAvailableCapacity method with only load', function () {

    $trunk = new Trunk('name', 10000, [new InventoryItem('crafted_rebar', 100, 45)]); // 4500 weight

    expect($trunk->getAvailableCapacity())->toBe(10000-4500);

});

test('getAvailableCapacity method with load and manual', function () {

    $trunk = new Trunk('name', 10000, [new InventoryItem('crafted_rebar', 100, 45)]); // 4500 weight
    $trunk->capacityUsed = 1000;

    expect($trunk->getAvailableCapacity())->toBe(10000-4500-1000);

});

test('capacityUsedPercent method', function () {

    $trunk = new Trunk('name', 10000, [new InventoryItem('crafted_rebar', 100, 45)]); // 4500 weight
    $trunk->capacityUsed = 500;

    expect($trunk->capacityUsedPercent())->toBe(.5);

});

test('numberOfItemsThatCanFitFromWeight method after setting capacity used', function () {

    $trunk = new Trunk('name', 1000);
    $trunk->setCapacityUsed(500);

    expect($trunk->numberOfItemsThatCanFitFromWeight(100))->toBe(5);

});

test('numberOfItemsThatCanFit method', function () {

    $item = new Item('scrap_ore', 15);
    $trunk = new Trunk('name', 1000);

    expect($trunk->numberOfItemsThatCanFit($item))->toBe(66);

});

it('has a display name', function () {

    $trunk = new Trunk('trailerOne', 999);

    expect($trunk->displayName())->toBeInstanceOf(\Illuminate\Support\Stringable::class)
        ->and($trunk->displayName()->toString())->toBe('Trailer One');

});

it('calculates the total weight of items in load', function () {

    $load = [
        new InventoryItem('crafted_rebar', 100, 45),
        new InventoryItem('crafted_computer', 100, 35),
        new InventoryItem('crafted_copperwire', 100, 20),
    ];
    $trunk = new Trunk('mk13', 9775, $load);

    expect($trunk->loadWeight())->toBe(10000);

});
