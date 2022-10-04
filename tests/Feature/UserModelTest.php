<?php

use App\Models\User;
use function Pest\Laravel\actingAs;

uses(\Illuminate\Foundation\Testing\RefreshDatabase::class);

it('returns if a user can make api calls', function () {

    $noIdUser = User::factory()->make(['tt_id' => null]);
    $canCallUser = User::factory()->make();

    expect($noIdUser->canMakeApiCall())->toBeFalse()
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

    $noIdUser = User::factory()->create(['tt_id' => null]);
    actingAs($noIdUser)
        ->get('crafting')
        ->assertRedirect('settings')
        ->assertSessionHas('failedApiAlert');

});

test('user is redirected to settings page when they dont have truckCapacity or pocketCapacity set', function () {

    $noTruckUser = User::factory()->create(['truckCapacity' => null]);
    actingAs($noTruckUser)
        ->get('crafting')
        ->assertRedirect('settings')
        ->assertSessionHas('noCapacitiesSetAlert');

    $noPocketUser = User::factory()->create(['pocketCapacity' => null]);
    actingAs($noPocketUser)
        ->get('crafting')
        ->assertRedirect('settings')
        ->assertSessionHas('noCapacitiesSetAlert');

});

it('knows if the user makes api calls with a public key', function () {

    $noPublicKeyUser = User::factory()->create();
    $publicKeyUser = User::factory()->create(['api_public_key' => 'adsfasdfasdf']);

    expect($noPublicKeyUser->usesPublicKey())->toBeFalse()
        ->and($publicKeyUser->usesPublicKey())->toBeTrue();

});

it('adds an item to full trailer alerts', function () {

    $user = User::factory()->create();
    $user->addItemToFullTrailerAlerts('refined_amalgam');
    $user->refresh();

    expect($user->full_trailer_alerts->contains('refined_amalgam'))->toBeTrue();

});

it('removes an item to full trailer alerts', function () {

    $user = User::factory()->create();
    $user->removeItemFromFullTrailerAlerts('scrap_ore');
    $user->refresh();

    expect($user->full_trailer_alerts->contains('scrap_ore'))->toBeFalse();
});

it('updates the game plan', function () {

    $user = User::factory()->create();
    $user->updateGamePlan(collect());

    expect(Cache::has($user->id.'gamePlan'))->toBeTrue();

});

it('clears a game plan', function () {

    $user = User::factory()->create();
    $user->clearGamePlan();

    expect(Cache::has($user->id.'gamePlan'))->toBeFalse();

});
