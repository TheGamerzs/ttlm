<?php

use App\Models\User;
use App\TT\Factories\ItemFactory;
use App\TT\Items\InventoryItem;
use App\TT\Storage;
use App\TT\StorageFactory;
use function Pest\Laravel\actingAs;

it('returns a storage object full of inventory items', function () {

    actingAs(User::factory()->create());
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

    actingAs(User::factory()->create());
    fakePersonalInventoryApiCallWithStoredJson();
    fakeStoragesApiCallWithArray([
        'crafted_concrete' => 1
    ]);

    $withoutInjected = StorageFactory::getAllItemNamesInCombinedStorage();
    expect($withoutInjected->contains('crafted_rebar'))->toBeFalse();

    $withInjected = StorageFactory::getAllItemNamesInCombinedStorage(false, true);
    expect($withInjected->contains('crafted_rebar'))->toBeTrue();

});

it('finds storages that an item exists in', function () {

    actingAs(User::factory()->create());
    fakePersonalInventoryApiCallWithStoredJson();
    fakeStoragesApiCallWithStoredJson();
    $item = ItemFactory::make('scrap_gold');

    expect(StorageFactory::findStoragesForItem($item)->keys())
        ->contains('biz_granny')->toBeTrue()
        ->contains('faq_522')->toBeTrue();
});

it('finds the storage that contains the highest count for an item', function () {

    actingAs(User::factory()->create());
    fakePersonalInventoryApiCallWithStoredJson();
    fakeStoragesApiCallWithStoredJson();

    expect(StorageFactory::guessStorageForItem('scrap_gold'))->toBe('faq_522');

});

test('getRegisteredNames static function', function () {

    actingAs(User::factory()->create());
    fakeStoragesAndPersonalInventoryCallsWithJson();
    \Spatie\Snapshots\assertMatchesJsonSnapshot(StorageFactory::getRegisteredNames());
    \Spatie\Snapshots\assertMatchesJsonSnapshot(StorageFactory::getRegisteredNames(true));
    \Spatie\Snapshots\assertMatchesJsonSnapshot(StorageFactory::getRegisteredNames(true, false));
    \Spatie\Snapshots\assertMatchesJsonSnapshot(StorageFactory::getRegisteredNames(false, false));

});

test('getCountFromCombinedForItem static method', function () {

    actingAs(User::factory()->create());
    fakeStoragesAndPersonalInventoryCallsWithJson();
    $randomItems = StorageFactory::get()->shuffle()->take(5);

    foreach ($randomItems as $item) {
        expect(StorageFactory::getCountFromCombinedForItem($item))->toBe($item->count);
    }

});

it('has a users backpack storage when one exists', function () {

    actingAs($user = User::factory()->backpackTrue()->create());
    fakeStoragesAndPersonalInventoryCallsWithJson();
    fakeFullBackpackCallWithStoredJson();
    $backpack = StorageFactory::get('backpack');

    expect($backpack)
        ->toBeInstanceOf(Storage::class)
        ->count()->toBe(61)
        ->first()->toBeInstanceOf(InventoryItem::class)
        ->and(StorageFactory::$storages)->toHaveCount(8);
});

it('does not include a backpack storage for a user that does not have one', function () {

    actingAs($user = User::factory()->create());
    fakeStoragesAndPersonalInventoryCallsWithJson();
    StorageFactory::get();

    expect(array_key_exists('backpack', StorageFactory::$storages))->toBeFalse();

});

test('when user has backpack enabled but does not have one in game', function () {

    actingAs($user = User::factory()->backpackTrue()->create());
    fakeStoragesAndPersonalInventoryCallsWithJson();
    fakeEmptyBackpackCallWithStoredJson();
    StorageFactory::get();

    expect(array_key_exists('backpack', StorageFactory::$storages))->toBeFalse()
        ->and(StorageFactory::$storages)->toHaveCount(7);

});
