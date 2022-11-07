<?php

use App\Models\User;
use App\TT\Items\InventoryItem;
use App\TT\StorageFactory;

it('returns a storage object full of inventory items', function () {

    \Pest\Laravel\actingAs(User::factory()->create());
    fakePersonalInventoryApiCallWithStoredJson();
    fakeStoragesApiCallWithArray([
        'crafted_concrete' => 10,
        'refined_planks' => 10
    ], 'fake_storage');
    $storage = StorageFactory::get('fake_storage');

    $storage->each(function (InventoryItem $inventoryItem) {
        expect($inventoryItem)
            ->name->toBeString()
            ->prettyName->toBeString()
            ->weight->toBeInt()->not->toBeEmpty()
            ->count->toBeInt()->not->toBeEmpty();
    });

});

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
