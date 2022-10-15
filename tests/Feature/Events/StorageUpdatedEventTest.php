<?php

use App\Models\MarketOrder;
use App\Models\User;
use App\TT\StorageFactory;
use function Pest\Laravel\actingAs;

uses(\Illuminate\Foundation\Testing\RefreshDatabase::class);

afterEach(function () {
    StorageFactory::$storages = [];
    StorageFactory::$freshData = false;
});

it('fires when storages are synced through api', function () {

    StorageFactory::$storages = [];
    StorageFactory::$freshData = false;
    Http::preventStrayRequests();
    $items = ['crafted_concrete' => 10, 'crafted_batteries' => 500];
    Http::fake(['v1.api.tycoon.community/main/storages/*' => Http::response( buildFakeStorageApiResponse($items) )]);
    actingAs( $user = User::factory()->create() );
    Event::fake();

    StorageFactory::get();

    Event::assertDispatched(\App\Providers\StorageUpdatedFromTT::class);

});

it('does not fire when using cached response', function () {

    Http::preventStrayRequests();
    $items = ['crafted_concrete' => 10, 'crafted_batteries' => 500];
    actingAs( $user = User::factory()->create() );
    Cache::put($user->id . 'tt_api_storage', buildFakeStorageApiResponse($items));
    Event::fake();

    StorageFactory::get();

    Event::assertNotDispatched(\App\Providers\StorageUpdatedFromTT::class);

});

/*
 * Create a market order to sell 100 crafted concrete, sync storages to show user only has 10,
 * expect the market order to be soft deleted.
 */
it('removes market orders on storage sync if the user can not cover it anymore', function () {

    Http::preventStrayRequests();
    $items = ['crafted_concrete' => 10, 'crafted_batteries' => 500];
    Http::fake(['v1.api.tycoon.community/main/storages/*' => Http::response( buildFakeStorageApiResponse($items) )]);

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
