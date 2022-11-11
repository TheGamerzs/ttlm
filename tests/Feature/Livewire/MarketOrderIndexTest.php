<?php

use App\Http\Livewire\MarketOrderIndex;
use App\Models\MarketOrder;
use App\Models\User;
use function Pest\Laravel\actingAs;

test('ok response', function () {

    fakeStoragesAndPersonalInventoryCallsWithJson();
    actingAs(User::factory()->create());
    \Pest\Laravel\get(route('marketOrders'))->assertOk();

});

test('Item filter', function () {

    MarketOrder::factory()->buyOrder()->create(['item_name' => 'should_see']);
    MarketOrder::factory()->buyOrder()->create(['item_name' => 'should_not_see']);
    $component = Livewire::test(MarketOrderIndex::class)
        ->set('type', 'buy')
        ->set('itemFilter', 'should_see')
        ->instance()->getHydratedData();


    expect($component['allMarketOrders']->contains('item_name', 'should_see'))->toBeTrue()
        ->and($component['allMarketOrders']->contains('item_name', 'should_not_see'))->toBeFalse();

});

test('Count Filter Min', function () {

    MarketOrder::factory()->buyOrder()->create(['item_name' => 'should_see', 'count' => 100]);
    MarketOrder::factory()->buyOrder()->create(['item_name' => 'should_not_see', 'count' => 50]);
    $component = Livewire::test(MarketOrderIndex::class)
        ->set('countMinFilter', '75')
        ->set('type', 'buy')
        ->instance()->getHydratedData();


    expect($component['allMarketOrders']->contains('item_name', 'should_see'))->toBeTrue()
        ->and($component['allMarketOrders']->contains('item_name', 'should_not_see'))->toBeFalse();

});

test('Count Filter Max', function () {

    MarketOrder::factory()->buyOrder()->create(['item_name' => 'should_see', 'count' => 50]);
    MarketOrder::factory()->buyOrder()->create(['item_name' => 'should_not_see', 'count' => 100]);
    $component = Livewire::test(MarketOrderIndex::class)
        ->set('type', 'buy')
        ->set('countMaxFilter', '75')
        ->instance()->getHydratedData();


    expect($component['allMarketOrders']->contains('item_name', 'should_see'))->toBeTrue()
        ->and($component['allMarketOrders']->contains('item_name', 'should_not_see'))->toBeFalse();

});

test('Price Each Filter Min', function () {

    MarketOrder::factory()->buyOrder()->create(['item_name' => 'should_see', 'price_each' => 100]);
    MarketOrder::factory()->buyOrder()->create(['item_name' => 'should_not_see', 'price_each' => 50]);
    $component = Livewire::test(MarketOrderIndex::class)
        ->set('type', 'buy')
        ->set('priceMinFilter', '75')
        ->instance()->getHydratedData();


    expect($component['allMarketOrders']->contains('item_name', 'should_see'))->toBeTrue()
        ->and($component['allMarketOrders']->contains('item_name', 'should_not_see'))->toBeFalse();

});

test('Price Each Filter Max', function () {

    MarketOrder::factory()->buyOrder()->create(['item_name' => 'should_see', 'price_each' => 50]);
    MarketOrder::factory()->buyOrder()->create(['item_name' => 'should_not_see', 'price_each' => 100]);
    $component = Livewire::test(MarketOrderIndex::class)
        ->set('type', 'buy')
        ->set('priceMaxFilter', '75')
        ->instance()->getHydratedData();


    expect($component['allMarketOrders']->contains('item_name', 'should_see'))->toBeTrue()
        ->and($component['allMarketOrders']->contains('item_name', 'should_not_see'))->toBeFalse();

});

it('deletes a market order', function () {

    $marketOrder = MarketOrder::factory()->buyOrder()->create();
    actingAs($marketOrder->user);
    fakeStoragesAndPersonalInventoryCallsWithJson();
    Livewire::test(MarketOrderIndex::class)
        ->call('closeOrder', $marketOrder->id)
        ->assertEmitted('ask')
        ->call('softDeleteOrder', $marketOrder->id);

    expect($marketOrder->refresh()->deleted_at)->not->toBeNull();

});

test('user owns market order to delete', function () {

    $marketOrder = MarketOrder::factory()->buyOrder()->create();
    actingAs($user = User::factory()->create());
    fakeStoragesAndPersonalInventoryCallsWithJson();
    Livewire::test(MarketOrderIndex::class)
        ->call('softDeleteOrder', $marketOrder->id)
        ->assertForbidden();

    expect($marketOrder->refresh()->deleted_at)->toBeNull();

});
