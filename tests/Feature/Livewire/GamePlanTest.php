<?php

use App\Http\Livewire\GamePlan;
use App\Models\User;
use function Pest\Laravel\actingAs;

beforeEach(function () {
    actingAs($this->user = User::factory()->create());
});

it('mounts with users game plan items', function () {

    $gamePlanItems = collect([
        'one',
        'two'
    ]);
    $this->user->updateGamePlan($gamePlanItems);

    $component = Livewire::test(GamePlan::class)->instance()->getHydratedData();

    expect($component['listItems']->contains('one'))->toBeTrue()
        ->and($component['listItems']->contains('two'))->toBeTrue();

});

it('adds to game plan', function () {

    Livewire::test(GamePlan::class)
        ->set('newItemTextInput', $itemString = 'New Item For Testing')
        ->call('addItem');

    expect($this->user->getGamePlan()->contains($itemString))->toBeTrue();

});

it('sets an item to update', function () {

    $this->user->updateGamePlan(collect(['one', 'two']));
    Livewire::test(GamePlan::class)
        ->call('updateItem', 1)
        ->assertSet('newItemTextInput', 'two');

    expect($this->user->getGamePlan()->contains('two'))->toBeFalse();

});

it('clears the list', function () {

    $this->user->updateGamePlan(collect(['one', 'two']));
    Livewire::test(GamePlan::class)
        ->call('clearAllItems');

    expect($this->user->getGamePlan()->count())->toBe(0);

});
