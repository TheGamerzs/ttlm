<?php

use App\Http\Livewire\QuickInventoryCalculations;
use App\Models\User;
use function Pest\Laravel\actingAs;

test('quick calculations for item counts to fit in trunks', function () {

    actingAs($user = User::factory()->create([
        'trainYardCapacity' => 65228,
        'pocketCapacity' => 325,
        'truckCapacity' => 9775,
        'truckCapacityTwo' => 6000
    ]));
    fakeStoragesAndPersonalInventoryCallsWithJson();

    Livewire::test(QuickInventoryCalculations::class,[
        'truckCapacity' => $user->truckCapacity,
        'pocketCapacity' => $user->pocketCapacity,
        'trainYardStorage' => $user->trainYardCapacity,
        'itemForFillTrailer' => 'scrap_ore',
    ])->assertSee([
        'Train Yard: 4348',
        'Trailer: 651',
        'Trailer Two: 400',
        'Pocket: 21',
    ]);

});

it('does not show a trailer two if user does not have one set', function () {

    actingAs($user = User::factory()->create([
        'trainYardCapacity' => 65228,
        'pocketCapacity' => 325,
        'truckCapacity' => 9775,
        'truckCapacityTwo' => null
    ]));
    fakeStoragesAndPersonalInventoryCallsWithJson();

    Livewire::test(QuickInventoryCalculations::class,[
        'truckCapacity' => $user->truckCapacity,
        'pocketCapacity' => $user->pocketCapacity,
        'trainYardStorage' => $user->trainYardCapacity,
        'itemForFillTrailer' => 'scrap_ore',
    ])->assertDontSee([
        'Trailer Two: 400',
    ]);

});
