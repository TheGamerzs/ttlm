<?php

use App\Models\MarketOrder;
use App\Models\User;

uses(\Illuminate\Foundation\Testing\RefreshDatabase::class);


test('relationships and casts', function () {

    $order = MarketOrder::factory()->buyOrder()->create();

    expect($order->user)->toBeInstanceOf(User::class);

});

it('has an item attribute', function () {

    $order = MarketOrder::factory()->sellOrder()->create();

    expect($order->item)->toBeInstanceOf(\App\TT\Items\Item::class)
        ->and($order->item->name)->toBe($order->item_name);

});

it('has a total cost attribute', function () {

    $order = MarketOrder::factory()->sellOrder()->create();

    expect($order->totalCost)->toBe($order->price_each * $order->count);
});

it('has a storage name attribute', function () {

    $order = MarketOrder::factory()->sellOrder()->create(['storage' => 'bhsl']);

    expect($order->storageName)->toBe('Big House Storage LSIA');

});
