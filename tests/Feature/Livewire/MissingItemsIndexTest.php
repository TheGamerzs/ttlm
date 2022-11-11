<?php

use App\Http\Livewire\MissingItems;
use Illuminate\Support\Collection;

it('clears ignored items', function () {

    Cache::put('ignoreMissing', collect());
    (new MissingItems())->clearIgnore();

    expect(Cache::has('ignoreMissing'))->toBeFalse();

});

it('clears all data', function () {

    Cache::put('ignoreMissing', collect());
    Cache::put('missingItemNames', collect());
    Cache::put('missingItems', collect());

    (new MissingItems())->clearAll();

    expect(Cache::has('ignoreMissing'))->toBeFalse()
        ->and(Cache::has('missingItemNames'))->toBeFalse()
        ->and(Cache::has('missingItems'))->toBeFalse();

});

it('ignores an item', function () {

    (new MissingItems())->addToIgnore('ignore-me');
    expect(Cache::get('ignoreMissing')->contains('ignore-me'))->toBeTrue();

});

it('removes an item from all data trackers', function () {

    Cache::put('ignoreMissing', collect(['removed', 'not-removed']));
    Cache::put('missingItemNames', collect(['removed', 'not-removed']));
    Cache::put('missingItems', collect(['removed', 'not-removed']));

    (new MissingItems())->deleteFromAll('removed');

    expect(Cache::get('ignoreMissing'))
            ->contains('removed')->toBeFalse()
            ->contains('not-removed')->toBeTrue()
        ->and(Cache::get('missingItemNames'))
            ->contains('removed')->toBeFalse()
            ->contains('not-removed')->toBeTrue()
        ->and(Cache::get('missingItems'))
            ->contains('removed')->toBeFalse()
            ->contains('not-removed')->toBeTrue();
});

test('render', function () {

    Cache::put('missingItemNames', collect(['show-me', 'dont-show-me', 'note: do not want']));
    Cache::put('ignoreMissing', collect(['dont-show-me']));
    $items = Livewire::test(MissingItems::class)
        ->assertOk()
        ->viewData('items');

    expect($items)->toBeInstanceOf(Collection::class)
        ->contains('show-me')->toBeTrue()
        ->contains('dont-show-me')->toBeFalse()
        ->contains('note: do not want')->toBeFalse();

});
