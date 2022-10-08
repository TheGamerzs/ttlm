<?php

use App\Http\Livewire\MarketOrders;
use App\Models\MarketOrder;
use App\Models\User;

uses(\Illuminate\Foundation\Testing\RefreshDatabase::class);

test('ok response', function () {

    \Pest\Laravel\actingAs(User::factory()->create());
    \Pest\Laravel\get(route('marketOrders'))->assertOk();

});

test('Item filter', function () {

    MarketOrder::factory()->buyOrder()->create(['item_name' => 'should_see']);
    MarketOrder::factory()->buyOrder()->create(['item_name' => 'should_not_see']);
    $component = Livewire::test(MarketOrdersTestable::class)
        ->set('itemFilter', 'should_see')
        ->instance()->getHydratedData();


    expect($component['allMarketOrders']->contains('item_name', 'should_see'))->toBeTrue()
        ->and($component['allMarketOrders']->contains('item_name', 'should_not_see'))->toBeFalse();

});

test('Count Filter Min', function () {

    MarketOrder::factory()->buyOrder()->create(['item_name' => 'should_see', 'count' => 100]);
    MarketOrder::factory()->buyOrder()->create(['item_name' => 'should_not_see', 'count' => 50]);
    $component = Livewire::test(MarketOrdersTestable::class)
        ->set('countMinFilter', '75')
        ->instance()->getHydratedData();


    expect($component['allMarketOrders']->contains('item_name', 'should_see'))->toBeTrue()
        ->and($component['allMarketOrders']->contains('item_name', 'should_not_see'))->toBeFalse();

});

test('Count Filter Max', function () {

    MarketOrder::factory()->buyOrder()->create(['item_name' => 'should_see', 'count' => 50]);
    MarketOrder::factory()->buyOrder()->create(['item_name' => 'should_not_see', 'count' => 100]);
    $component = Livewire::test(MarketOrdersTestable::class)
        ->set('countMaxFilter', '75')
        ->instance()->getHydratedData();


    expect($component['allMarketOrders']->contains('item_name', 'should_see'))->toBeTrue()
        ->and($component['allMarketOrders']->contains('item_name', 'should_not_see'))->toBeFalse();

});

test('Price Each Filter Min', function () {

    MarketOrder::factory()->buyOrder()->create(['item_name' => 'should_see', 'price_each' => 100]);
    MarketOrder::factory()->buyOrder()->create(['item_name' => 'should_not_see', 'price_each' => 50]);
    $component = Livewire::test(MarketOrdersTestable::class)
        ->set('priceMinFilter', '75')
        ->instance()->getHydratedData();


    expect($component['allMarketOrders']->contains('item_name', 'should_see'))->toBeTrue()
        ->and($component['allMarketOrders']->contains('item_name', 'should_not_see'))->toBeFalse();

});

test('Price Each Filter Max', function () {

    MarketOrder::factory()->buyOrder()->create(['item_name' => 'should_see', 'price_each' => 50]);
    MarketOrder::factory()->buyOrder()->create(['item_name' => 'should_not_see', 'price_each' => 100]);
    $component = Livewire::test(MarketOrdersTestable::class)
        ->set('priceMaxFilter', '75')
        ->instance()->getHydratedData();


    expect($component['allMarketOrders']->contains('item_name', 'should_see'))->toBeTrue()
        ->and($component['allMarketOrders']->contains('item_name', 'should_not_see'))->toBeFalse();

});

class MarketOrdersTestable extends MarketOrders
{
    public function getHydratedData()
    {
        return collect($this->preRenderedView->getData())->except(['_instance']);
    }
}
