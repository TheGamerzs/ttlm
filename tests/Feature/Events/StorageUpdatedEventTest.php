<?php

use App\Models\MarketOrder;
use App\Models\User;
use App\TT\StorageFactory;
use function Pest\Laravel\actingAs;


beforeAll(function () {
    resetStorageFactoryStatics();
});

beforeEach(function () {
    fakePersonalInventoryApiCallWithStoredJson();
});

afterEach(function () {
    resetStorageFactoryStatics();
});

it('fires when storages are synced through api', function () {

    fakeStoragesApiCallWithArray([
        'crafted_concrete' => 10,
        'crafted_batteries' => 500
    ]);

    actingAs( $user = User::factory()->create() );
    Event::fake();
    StorageFactory::get();

    Event::assertDispatched(\App\Events\StorageUpdatedFromTT::class);

});

it('does not fire when using cached response', function () {

    fakeStoragesApiCallWithArray($items = [
        'crafted_concrete' => 10,
        'crafted_batteries' => 500
    ]);

    actingAs( $user = User::factory()->create() );
    Cache::put($user->id . 'tt_api_storage', buildFakeStorageApiResponse($items));
    Event::fake();

    StorageFactory::get();

    Event::assertNotDispatched(\App\Events\StorageUpdatedFromTT::class);

});

/*
 * Create a market order to sell 100 crafted concrete, sync storages to show user only has 10,
 * expect the market order to be soft deleted.
 */
it('removes market orders on storage sync if the user can not cover it anymore', function () {

    fakeStoragesApiCallWithArray([
        'crafted_concrete' => 10,
        'crafted_batteries' => 500
    ]);

    actingAs( $user = User::factory()->create() );
    (new \App\TT\TTApi())->getStorages();

    $orderWithLessInventory = MarketOrder::factory()
        ->sellOrder()
        ->for($user)
        ->create([
            'item_name' => 'crafted_concrete',
            'count' => 100
        ]);
    $orderWithNoInventory = MarketOrder::factory()
        ->sellOrder()
        ->for($user)
        ->create([
            'item_name' => 'crafted_computer',
            'count' => 100
        ]);
    $orderWithMoreInventory = MarketOrder::factory()
        ->sellOrder()
        ->for($user)
        ->create([
            'item_name' => 'crafted_batteries',
            'count' => 100
        ]);

    StorageFactory::get();
    $orderWithLessInventory->refresh();
    $orderWithNoInventory->refresh();
    $orderWithMoreInventory->refresh();

    expect($orderWithLessInventory->deleted_at)->not()->toBeNull()
        ->and($orderWithNoInventory->deleted_at)->not()->toBeNull()
        ->and($orderWithMoreInventory->deleted_at)->toBeNull();

});

it('does not remove them when the user has auto_delist_market_orders disabled', function () {

    fakeStoragesApiCallWithArray([
        'crafted_concrete' => 10,
        'crafted_batteries' => 500
    ]);

    actingAs( $user = User::factory()->create(['auto_delist_market_orders' => false]) );
    (new \App\TT\TTApi())->getStorages();

    $orderWithLessInventory = MarketOrder::factory()
        ->sellOrder()
        ->for($user)
        ->create([
            'item_name' => 'crafted_concrete',
            'count' => 100
        ]);
    $orderWithNoInventory = MarketOrder::factory()
        ->sellOrder()
        ->for($user)
        ->create([
            'item_name' => 'crafted_computer',
            'count' => 100
        ]);
    $orderWithMoreInventory = MarketOrder::factory()
        ->sellOrder()
        ->for($user)
        ->create([
            'item_name' => 'crafted_batteries',
            'count' => 100
        ]);

    StorageFactory::get();
    $orderWithLessInventory->refresh();
    $orderWithNoInventory->refresh();
    $orderWithMoreInventory->refresh();

    expect($orderWithLessInventory->deleted_at)->toBeNull()
        ->and($orderWithNoInventory->deleted_at)->toBeNull()
        ->and($orderWithMoreInventory->deleted_at)->toBeNull();

});
