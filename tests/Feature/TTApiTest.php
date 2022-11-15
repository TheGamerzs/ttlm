<?php

use App\Models\User;
use App\TT\TTApi;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;
use function Pest\Laravel\actingAs;

it('gets the user id from discord snowflake', function () {

    $object = file_get_contents(base_path('tests/ApiResponses/UserIdFromDiscordSnowflake.json'));
    Http::fake([
        'v1.api.tycoon.community/main/snowflake2user/*' => Http::response($object)
    ]);
    $return = TTApi::ttIdFromDiscordSnowflake('faked');

    expect($return->code)->toBe('200')
        ->and($return->user_id)->toBe(645753);

});

it('throws an exception if instantiated without a logged in user', function () {

    new TTApi();

})->throws(Exception::class, 'Attempted to create an API object without a logged in user.');

it('creates proper headers', function () {

    actingAs(User::factory()->create());

    $headers = (new TTApi())->buildHeaders();

    expect(array_key_exists('X-Tycoon-Key', $headers))->toBeTrue()
        ->and(array_key_exists('X-Tycoon-Public-Key', $headers))->toBeFalse();

});

it('creates proper headers when a user has a public key', function () {

    actingAs(User::factory()->create(['api_public_key' => 'adf']));

    $headers = (new TTApi())->buildHeaders();

    expect(array_key_exists('X-Tycoon-Key', $headers))->toBeTrue()
        ->and(array_key_exists('X-Tycoon-Public-Key', $headers))->toBeTrue();

});

it('creates a time expiring cache entry when making a call', function () {

    fakeStoragesApiCallWithStoredJson();

    $user = User::factory()->create();
    actingAs($user);
    (new TTApi())->getStorages();

    expect(Cache::has($user->id . 'lockedApi'))->toBeTrue();

});

it('increments calls made on user model', function () {

    fakeStoragesApiCallWithStoredJson();

    actingAs($user = User::factory()->create());
    (new TTApi())->getStorages();

    expect($user->calls_made)->toBe(2);

});

it('gets a users personal inventory', function () {

    fakePersonalInventoryApiCallWithStoredJson();

    actingAs($user = User::factory()->create());
    $inventory = (new TTApi())->getUserInventory();

    expect($inventory)->toBeInstanceOf(Collection::class)
        ->and($inventory->count())->toBe(32);

});

it('gets a users backpack inventory', function () {

    fakeFullBackpackCallWithStoredJson();

    actingAs($user = User::factory()->create());
    $backpack = (new TTApi())->getUserBackpack();

    expect(property_exists($backpack, 'data'))->toBeTrue()
        ->and(collect($backpack->data)->count())->toBe(61)
        ->and(collect($backpack->data)->keys()->contains('exp_bonus_ee2'))->toBeTrue();
});
