<?php

use App\Models\User;
use App\TT\StorageFactory;

it('returns a pretty name for a storage id', function () {

    $return = StorageFactory::getPrettyName('biz_granny');
    expect($return)->toBe('Grandma\'s House');

});

it('returns a pretty name for faction storages', function () {

    $return = StorageFactory::getPrettyName('faq_1999');
    expect($return)->toBe('Faction - 1999');

});

it('gets all item names from a combined storage with trucking names', function () {

    \Pest\Laravel\actingAs(User::factory()->create());
    fakePersonalInventoryApiCallWithStoredJson();
    fakeStoragesApiCallWithArray([
        'crafted_concrete' => 1
    ]);

    $withoutInjected = StorageFactory::getAllItemNamesInCombinedStorage();
    expect($withoutInjected->contains('crafted_rebar'))->toBeFalse();

    $withInjected = StorageFactory::getAllItemNamesInCombinedStorage(false, true);
    expect($withInjected->contains('crafted_rebar'))->toBeTrue();


});
