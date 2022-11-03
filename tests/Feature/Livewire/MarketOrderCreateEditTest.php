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

it('updates a record and resets expires to one week from now', function () {

    actingAs($user = User::factory()->create());
    $order = MarketOrder::factory()->for($user)->create([
        'count' => 100,
        'price_each' => 1000,
        'expires' => now()->addDay(),
        'item_name' => 'petrochem_sulfur',
        'storage' => 'biz_yellowjack'
    ]);
    Livewire::test(MarketOrderCreateEdit::class)
        ->call('startEditing', $order->id)
        ->set('marketOrder.count', 200)
        ->set('marketOrder.price_each', 2000)
        ->assertHasNoErrors()
        ->call('save');

    $order->refresh();

    expect($order->count)->toBe(200)
        ->and($order->price_each)->toBe(2000)
        ->and($order->expires->isSameAs(now()->addWeek()))->toBeTrue();

});

test('trying to edit another users order', function () {

    $owningUser = User::factory()->create();
    $order = MarketOrder::factory()->for($owningUser)->create(['type' => 'sell']);
    actingAs($invalidUser = User::factory()->create());

    Livewire::test(MarketOrderCreateEdit::class)
        ->call('startEditing', $order->id)
        ->assertForbidden();

});

it('can edit an expired record', function () {

    actingAs($user = User::factory()->create());
    $order = MarketOrder::factory()->for($user)->create([
        'count' => 100,
        'price_each' => 1000,
        'expires' => now()->subDay(),
        'item_name' => 'petrochem_sulfur',
        'storage' => 'biz_yellowjack'
    ]);

    Livewire::test(MarketOrderCreateEdit::class)
        ->call('startEditing', $order->id)
        ->set('marketOrder.count', 200)
        ->set('marketOrder.price_each', 2000)
        ->assertHasNoErrors()
        ->call('save');

    $order->refresh();

    expect($order->count)->toBe(200)
        ->and($order->price_each)->toBe(2000)
        ->and($order->expires->isSameAs(now()->addWeek()))->toBeTrue();

});

it('warns when the user selects storage, item, and count, that they do not have', function () {

    actingAs($user = User::factory()->create());

    Livewire::test(MarketOrderCreateEdit::class)
        ->call('startWithItem', 'scrap_gold', 100)
        ->set('marketOrder.storage', 'biz_granny')
        ->set('marketOrder.price_each', 100)
        ->call('save')
        ->assertSet('warn', true);

    expect(MarketOrder::count())->toBe(0);

});

it('does not warn when the user for types other than sell', function () {

    actingAs($user = User::factory()->create());

    Livewire::test(MarketOrderCreateEdit::class)
        ->call('startWithItem', 'scrap_gold', 100)
        ->set('marketOrder.storage', 'biz_granny')
        ->set('marketOrder.price_each', 100)
        ->set('marketOrder.type', 'buy')
        ->call('save')
        ->assertSet('warn', false);

    expect(MarketOrder::count())->toBe(1);

});

it('can bypass the warning', function () {

    actingAs($user = User::factory()->create());

    Livewire::test(MarketOrderCreateEdit::class)
        ->call('startWithItem', 'scrap_gold', 100)
        ->set('warn', true)
        ->set('marketOrder.storage', 'biz_granny')
        ->set('marketOrder.price_each', 100)
        ->call('save', true);

    expect(MarketOrder::count())->toBe(1);

});

it('can handle large numbers for price each', function () {

    actingAs($user = User::factory()->create());

    Livewire::test(MarketOrderCreateEdit::class)
        ->call('startWithItem', 'crafted_concrete', 200)
        ->set('marketOrder.count', 100)
        ->set('marketOrder.price_each', 8500000000000)
        ->call('save');

    expect(MarketOrder::count())->toBe(1);
    $order = MarketOrder::first();
    expect($order->user_id)->toBe($user->id)
        ->and($order->count)->toBe(100)
        ->and($order->item_name)->toBe('crafted_concrete')
        ->and($order->price_each)->toBe(8500000000000);

});
