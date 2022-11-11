<?php

use App\Http\Livewire\MarketOrderShow;
use App\Models\MarketOrder;
use App\Models\User;
use function Pest\Laravel\actingAs;

it('gets the users market orders', function () {

    actingAs($user = User::factory()->create());
    $marketOrder = MarketOrder::factory()->buyOrder()->for($user)->create();

    $component = Livewire::test(MarketOrderShow::class, ['user' => $user])
        ->assertOk();

    expect($component->viewData('orders')['buy']->contains($marketOrder))->toBeTrue();
});

test('404 on user with no market orders', function () {

    actingAs($user = User::factory()->create());

    $component = Livewire::test(MarketOrderShow::class, ['user' => $user])
        ->assertStatus(404);

});

test('empty collections added when user does not have any orders of type', function () {

    actingAs($user = User::factory()->create());
    MarketOrder::factory()->buyOrder()->for($user)->create();

    $component = Livewire::test(MarketOrderShow::class, ['user' => $user])
        ->assertOk();

    $keys = $component->viewData('orders')->keys();

    foreach (['buy', 'sell', 'move'] as $type) {
        expect($keys->contains($type))->toBeTrue();
    }

});
