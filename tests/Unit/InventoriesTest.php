<?php

use App\TT\Inventories;
use App\TT\Trunk;

it('can construct with given trunks', function () {

    $inventories = new Inventories([
        new Trunk('one', 9),
        new Trunk('two', 8)
    ]);

    expect($inventories->trunks->count())->toBe(2);

});

test('count method', function () {

    $inventories = new Inventories([
        new Trunk('one', 9),
        new Trunk('two', 8)
    ]);

    expect($inventories->count())->toBe(2);

});

test('createInventory method', function () {

    $inventories = new Inventories();

    $inventories->createTrunk('name', 999);
    $testing = $inventories->trunks->first();

    expect($testing)->toBeInstanceOf(Trunk::class)
        ->and($testing->name)->toBe('name')
        ->and($testing->capacity)->toBe(999);
});

it('does not create a trunk without a valid integer', function () {

    $inventories = new Inventories([new Trunk('valid', 44)]);
    $inventories->createTrunk('invalid', null);

    expect($inventories->count())->toBe(1);

});

test('addInventory method', function () {

    $inventories = new Inventories();
    $trunk = new Trunk('name', 1000);

    $inventories->addTrunk($trunk);
    $testing = $inventories->trunks->first();

    expect($testing)->toBeInstanceOf(Trunk::class)
        ->and($testing->name)->toBe('name')
        ->and($testing->capacity)->toBe(1000);

});

test('set trunk used capacities', function () {

    $inventories = new Inventories([
        new Trunk('one', 9),
        new Trunk('two', 8)
    ]);

    $inventories->setCapacityUsed('one', 3)
        ->setCapacityUsed('two', 4);

    [$one, $two] = $inventories->trunks;

    expect($one->capacityUsed)->toBe(3)
        ->and($two->capacityUsed)->toBe(4);
});

it('ignores call when attempting to set capacity with an invalid trunk name', function () {

    $inventories = new Inventories();
    $inventories->setCapacityUsed('nonexistent', 999);

    // Test no exception.
    expect($inventories->count())->toBe(0);
});

it('creates a combined trunk', function () {

    $inventories = new Inventories([
        new Trunk('mk14', 10000),
        new Trunk('mk15', 6000)
    ]);

    $inventories->createCombined();
    expect($inventories->count())->toBe(3);
    $combined = $inventories->trunks->firstWhere('name', 'combined');
    expect($combined->capacity)->toBe(16000);

});
