<?php

it('returns if a user can make api calls', function () {

    $noKeyUser = \App\Models\User::factory()->make(['api_private_key' => null]);
    $noIdUser = \App\Models\User::factory()->make(['tt_id' => null]);
    $canCallUser = \App\Models\User::factory()->make();

    expect($noKeyUser->canMakeApiCall())->toBeFalse()
        ->and($noIdUser->canMakeApiCall())->toBeFalse()
        ->and($canCallUser->canMakeApiCall())->toBeTrue();

});

it('returns if a user has all attributes needed for calculations', function () {

    $noTruckUser = \App\Models\User::factory()->make(['truckCompacity' => null]);
    $noPocketUser = \App\Models\User::factory()->make(['pocketCompacity' => null]);
    $canCalculateUser = \App\Models\User::factory()->make();

    expect($noTruckUser->canCalculate())->toBeFalse()
        ->and($noPocketUser->canCalculate())->toBeFalse()
        ->and($canCalculateUser->canCalculate())->toBeTrue();

});

it('returns if a user has attribute for train yard calculations', function () {

    $noTrainUser = \App\Models\User::factory()->make(['trainYardCompacity' => null]);
    $canCalculateUser = \App\Models\User::factory()->make();

    expect($noTrainUser->hasTrainYard())->toBeFalse()
        ->and($canCalculateUser->hasTrainYard())->toBeTrue();

});
