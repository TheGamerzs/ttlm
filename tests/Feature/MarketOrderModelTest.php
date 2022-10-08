<?php

use App\Models\MarketOrder;
use App\Models\User;

uses(\Illuminate\Foundation\Testing\RefreshDatabase::class);


test('relationships', function () {

    $order = MarketOrder::factory()->buyOrder()->create();

    expect($order->user)->toBeInstanceOf(User::class);

});
