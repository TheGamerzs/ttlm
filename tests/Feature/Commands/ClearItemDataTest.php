<?php

use Illuminate\Support\Facades\Cache;

it('clears cached item data', function () {

    Cache::put('itemData', collect());

    \Pest\Laravel\artisan('item-data:reset');

    expect(Cache::has('itemData'))->toBeFalse();

});
