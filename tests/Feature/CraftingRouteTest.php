<?php

use App\Models\User;
use function Pest\Laravel\actingAs;
use function Pest\Laravel\get;

test('crafting route', function () {

    fakeStoragesAndPersonalInventoryCallsWithJson();

    actingAs(User::factory()->create());

    get(route('craftingPage'))->assertOk();

});
