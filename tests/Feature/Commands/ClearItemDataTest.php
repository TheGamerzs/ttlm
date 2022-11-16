<?php

use Illuminate\Support\Facades\Cache;
use function Pest\Laravel\artisan;

it('clears cached item data', function () {

    Cache::put('itemData', collect());

    artisan('item-data:reset');

    expect(Cache::has('itemData'))->toBeFalse();

});
