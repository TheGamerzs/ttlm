<?php

use App\TT\Items\ItemData;
use Illuminate\Support\Facades\Cache;

it('logs missing item names', function () {

    $madeUp = ItemData::getFromDataId('fooBar');

    expect($madeUp)->toBeNull()
        ->and(Cache::has('missingItemNames'))->toBeTrue()
        ->and(Cache::get('missingItemNames')->contains('fooBar'))->toBeTrue();

    ItemData::getWeight('fooBar');
    ItemData::getWeight('fooBar');

    expect(Cache::get('missingItemNames')->count())->toBe(1);

});
