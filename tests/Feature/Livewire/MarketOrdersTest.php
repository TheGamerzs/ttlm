<?php

use App\Models\User;

uses(\Illuminate\Foundation\Testing\RefreshDatabase::class);

test('ok response', function () {

    \Pest\Laravel\actingAs(User::factory()->create());
    \Pest\Laravel\get(route('marketOrders'))->assertOk();

});
