<?php

use App\TT\Inventories;
use App\TT\Trunk;

test('createInventory method', function () {

    $inventories = new Inventories();

    $inventories->createTrunk('name', 999);
    $testing = $inventories->items->first();

    expect($testing)->toBeInstanceOf(Trunk::class)
        ->and($testing->name)->toBe('name')
        ->and($testing->capacity)->toBe(999);
});

test('addInventory method', function () {

    $inventories = new Inventories();
    $trunk = new Trunk('name', 1000);

    $inventories->addTrunk($trunk);
    $testing = $inventories->items->first();

    expect($testing)->toBeInstanceOf(Trunk::class)
        ->and($testing->name)->toBe('name')
        ->and($testing->capacity)->toBe(1000);

});

