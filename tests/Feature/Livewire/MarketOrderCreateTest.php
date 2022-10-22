<?php

use App\Http\Livewire\MarketOrderCreateEdit;
use App\Models\MarketOrder;
use App\Models\User;
use function Pest\Laravel\actingAs;

beforeEach(function () {
    fakeStoragesAndPersonalInventoryCallsWithJson();
});

it('starts with defaults to create a new record', function () {

    actingAs(User::factory()->create());

    Livewire::test(MarketOrderCreateEdit::class)
        ->call('startWithItem', 'refined_planks', 200)
        ->assertSet('marketOrder.item_name', 'refined_planks')
        ->assertSet('marketOrder.type', 'sell')
        ->assertSet('marketOrder.count', 200)
        ->assertSet('marketOrder.storage', 'faq_522')
        ->assertEmitted('openMarketOrderModal');

});

it('creates a new record', function () {

    actingAs($user = User::factory()->create());

    Livewire::test(MarketOrderCreateEdit::class)
        ->call('startWithItem', 'crafted_concrete', 200)
        ->set('marketOrder.count', 100)
        ->set('marketOrder.price_each', 850000)
        ->call('save');

    expect(MarketOrder::count())->toBe(1);
    $order = MarketOrder::first();
    expect($order->user_id)->toBe($user->id)
        ->and($order->count)->toBe(100)
        ->and($order->item_name)->toBe('crafted_concrete')
        ->and($order->price_each)->toBe(850000);

});

it('updates a record', function () {

    actingAs($user = User::factory()->create());
    $order = MarketOrder::factory()->for($user)->create([
        'count' => 100,
        'price_each' => 1000
    ]);

//    dd($order->count, $order->count + 100);

    Livewire::test(MarketOrderCreateEdit::class)
        ->call('startEditing', $order->id)
        ->set('marketOrder.count', 200)
        ->set('marketOrder.price_each', 2000)
        ->assertHasNoErrors()
        ->call('save');

    $order->refresh();

    expect($order->count)->toBe(200)
        ->and($order->price_each)->toBe(2000);

});

test('trying to edit another users order', function () {

    $owningUser = User::factory()->create();
    $order = MarketOrder::factory()->for($owningUser)->create(['type' => 'sell']);
    actingAs($invalidUser = User::factory()->create());

    Livewire::test(MarketOrderCreateEdit::class)
        ->call('startEditing', $order->id)
        ->assertForbidden();

});
