<?php

use App\Http\Livewire\ShoppingListIndex;
use App\Models\User;
use function Pest\Laravel\actingAs;

test('it works and redirects to itself with new data', function () {

    actingAs($user = User::factory()->create());
    fakeStoragesAndPersonalInventoryCallsWithJson();
    Livewire::test(ShoppingListIndex::class)
        ->assertOk()
        ->call('setRecipe', 'crafted_rebar', 5000)
        ->assertRedirect(route('shoppingList', ['recipeName' => 'crafted_rebar', 'count' => 5000]));

});
