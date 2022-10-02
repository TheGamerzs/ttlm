<?php

use Illuminate\Support\Facades\Cache;

it('logs missing item names', function () {

    $madeUp = \App\TT\Items\Weights::getWeight('fooBar');

    expect($madeUp)->toBeNull()
        ->and(Cache::has('missingItems'))
        ->and(Cache::get('missingItems')->contains('fooBar'))->toBeTrue();

    \App\TT\Items\Weights::getWeight('fooBar');
    \App\TT\Items\Weights::getWeight('fooBar');

    expect(Cache::get('missingItems')->count())->toBe(1);

});
