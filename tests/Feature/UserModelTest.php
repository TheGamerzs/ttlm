<?php

use App\Models\User;
use function Pest\Laravel\actingAs;

uses(\Illuminate\Foundation\Testing\RefreshDatabase::class);

it('returns if a user can make api calls', function () {

    $noKeyUser = User::factory()->make(['api_private_key' => null]);
    $noIdUser = User::factory()->make(['tt_id' => null]);
    $canCallUser = User::factory()->make();

    expect($noKeyUser->canMakeApiCall())->toBeFalse()
        ->and($noIdUser->canMakeApiCall())->toBeFalse()
        ->and($canCallUser->canMakeApiCall())->toBeTrue();

});

it('returns if a user has all attributes needed for calculations', function () {

    $noTruckUser = User::factory()->make(['truckCapacity' => null]);
    $noPocketUser = User::factory()->make(['pocketCapacity' => null]);
    $canCalculateUser = User::factory()->make();

    expect($noTruckUser->canCalculate())->toBeFalse()
        ->and($noPocketUser->canCalculate())->toBeFalse()
        ->and($canCalculateUser->canCalculate())->toBeTrue();

});

it('returns if a user has attribute for train yard calculations', function () {

    $noTrainUser = User::factory()->make(['trainYardCapacity' => null]);
    $canCalculateUser = User::factory()->make();

    expect($noTrainUser->hasTrainYard())->toBeFalse()
        ->and($canCalculateUser->hasTrainYard())->toBeTrue();

});

test('user is redirected to settings page when they cant use api', function () {

    $noKeyUser = User::factory()->create(['api_private_key' => null]);
    actingAs($noKeyUser)->get('crafting')->assertRedirect('settings');

    $noIdUser = User::factory()->create(['tt_id' => null]);
    actingAs($noIdUser)->get('crafting')->assertRedirect('settings');

});

test('user is redirected to settings page when they dont have truckCapacity or pocketCapacity set', function () {

    $noTruckUser = User::factory()->create(['truckCapacity' => null]);
    actingAs($noTruckUser)->get('crafting')->assertRedirect('settings');

    $noPocketUser = User::factory()->create(['pocketCapacity' => null]);
    actingAs($noPocketUser)->get('crafting')->assertRedirect('settings');

});
