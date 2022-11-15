<?php

/*
|--------------------------------------------------------------------------
| Test Case
|--------------------------------------------------------------------------
|
| The closure you provide to your test functions is always bound to a specific PHPUnit test
| case class. By default, that class is "PHPUnit\Framework\TestCase". Of course, you may
| need to change it using the "uses()" function to bind a different classes or traits.
|
*/

use App\TT\StorageFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(Tests\TestCase::class, RefreshDatabase::class)
    ->beforeEach(function () {
        Http::preventStrayRequests();
        resetStorageFactoryStatics();
    })
    ->in('Feature');

/*
|--------------------------------------------------------------------------
| Expectations
|--------------------------------------------------------------------------
|
| When you're writing tests, you often need to check that values meet certain conditions. The
| "expect()" function gives you access to a set of "expectations" methods that you can use
| to assert different things. Of course, you may extend the Expectation API at any time.
|
*/

expect()->extend('toBeOne', function () {
    return $this->toBe(1);
});

/*
|--------------------------------------------------------------------------
| Functions
|--------------------------------------------------------------------------
|
| While Pest is very powerful out-of-the-box, you may have some testing code specific to your
| project that you don't want to repeat in every file. Here you can also expose helpers as
| global functions to help you to reduce the number of lines of code in your test files.
|
*/

function fakePersonalInventoryApiCallWithStoredJson(): void
{
    $apiReturn = file_get_contents(base_path('tests/ApiResponses/UserData.json'));
    Http::fake(['v1.api.tycoon.community/main/data/*' => Http::response($apiReturn)]);
}

function fakeFullBackpackCallWithStoredJson()
{
    $apiReturn = file_get_contents(base_path('tests/ApiResponses/BackpackFull.json'));
    Http::fake(['v1.api.tycoon.community/main/chest/*' => Http::response($apiReturn)]);
}

function fakeEmptyBackpackCallWithStoredJson()
{
    $apiReturn = file_get_contents(base_path('tests/ApiResponses/BackpackEmpty.json'));
    Http::fake(['v1.api.tycoon.community/main/chest/*' => Http::response($apiReturn)]);
}

function fakeStoragesApiCallWithStoredJson(): void
{
    $apiReturn = file_get_contents(base_path('tests/ApiResponses/Storage.json'));
    Http::fake(['v1.api.tycoon.community/main/storages/*' => Http::response($apiReturn)]);
}

function fakeStoragesAndPersonalInventoryCallsWithJson(): void
{
    fakeStoragesApiCallWithStoredJson();
    fakePersonalInventoryApiCallWithStoredJson();
}

function fakeStoragesApiCallWithArray(array $items, string $storageName = 'biz_yellowjack'): void
{
    Http::fake(['v1.api.tycoon.community/main/storages/*' => Http::response( buildFakeStorageApiResponse($items, $storageName) )]);
}

function resetStorageFactoryStatics(): void
{
    StorageFactory::$storages = [];
    StorageFactory::$freshData = false;
}
