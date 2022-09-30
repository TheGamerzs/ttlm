<?php

use App\Models\User;
use App\TT\TTApi;
use Illuminate\Support\Facades\Http;
use function Pest\Laravel\actingAs;

uses(\Illuminate\Foundation\Testing\DatabaseTransactions::class);

it('gets the user id from discord snowflake', function () {

    Http::preventStrayRequests();

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

    $apiReturn = file_get_contents(base_path('tests/ApiResponses/Storage.json'));
    Http::preventStrayRequests();
    Http::fake(['v1.api.tycoon.community/main/storages/*' => Http::response($apiReturn)]);

    $user = User::factory()->create();
    actingAs($user);
    (new TTApi())->getStorages();

    expect(Cache::has($user->id . 'lockedApi'))->toBeTrue();

});
