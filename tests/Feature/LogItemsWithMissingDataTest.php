<?php

use Illuminate\Support\Facades\Cache;

it('logs missing item names', function () {

    $madeUp = \App\TT\Items\ItemData::getFromDataId('fooBar');

    expect($madeUp)->toBeNull()
        ->and(Cache::has('missingItemNames'))->toBeTrue()
        ->and(Cache::get('missingItemNames')->contains('fooBar'))->toBeTrue();

    \App\TT\Items\Weights::getWeight('fooBar');
    \App\TT\Items\Weights::getWeight('fooBar');

    expect(Cache::get('missingItemNames')->count())->toBe(1);

});
