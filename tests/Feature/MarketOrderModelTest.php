<?php

use App\Models\MarketOrder;
use App\Models\User;

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
    $moveOrder = MarketOrder::factory()->moveOrder()->create();
    $moveItem = new \App\TT\Items\Item($moveOrder->item_name);

    expect($order->totalCost)->toBe($order->price_each * $order->count)
        ->and($moveOrder->totalCost)->toBe($moveOrder->price_each * $moveOrder->count * $moveItem->weight);
});

it('has a storage name attribute', function () {

    $order = MarketOrder::factory()->sellOrder()->create(['storage' => 'bhsl']);

    expect($order->storageName)->toBe('Big House Storage LSIA');

});

// Example: Order is a buy order for an item, find sell orders for the same item.
it('finds the inverse of buy and sell orders', function () {

    $user = User::factory()->create();
    $shouldFind = MarketOrder::factory()->sellOrder()->create(['item_name' => 'biz_token']);

    $order = MarketOrder::make([
        'user_id' => $user->id,
        'item_name' => 'biz_token',
        'type' => 'buy'
    ]);

    $results = $order->findInverseOrders();

    expect($results->count())->toBe(1)
        ->and($results->first()->is($shouldFind))->toBeTrue();
});

test('findInverseOrders returns a blank eloquent collection when the type is move', function () {

    $order = MarketOrder::factory()->moveOrder()->create();

    expect($order->findInverseOrders())->toBeInstanceOf(\Illuminate\Database\Eloquent\Collection::class)
        ->and($order->findInverseOrders()->count())->toBe(0);

});

test('soft deletes', function () {

    $order = MarketOrder::factory()->create();
    $order->delete();

    expect(MarketOrder::count())->toBe(0)
        ->and(MarketOrder::withTrashed()->count())->toBe(1)
        ->and($order->exists)->toBeTrue();

});

test('expired global scope', function () {

    $order = MarketOrder::factory()->create(['expires' => now()->subDay()]);
    $notExpired = MarketOrder::factory()->create(['expires' => now()->addDay()]);
    $results = MarketOrder::all();

    expect($results->contains($order))->toBeFalse()
        ->and($results->contains($notExpired))->toBeTrue();

});

test('with expired scope', function () {

    $order = MarketOrder::factory()->create(['expires' => now()->subDay()]);

    expect(MarketOrder::withExpired()->get()->contains($order))->toBeTrue();

});

test('only expired scope', function () {

    $expired = MarketOrder::factory()->create(['expires' => now()->subDay()]);
    $notExpired = MarketOrder::factory()->create(['expires' => now()->addDay()]);
    $results = MarketOrder::onlyExpired()->get();

    expect($results->contains($expired))->toBeTrue()
        ->and($results->contains($notExpired))->toBeFalse();

});
