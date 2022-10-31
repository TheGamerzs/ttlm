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
        'Trailer One: 651',
        'Trailer Two: 400',
        'Pocket: 21',
    ]);

});

test('quick calculations for item counts to fit in trunks with used capacities', function () {

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
        ])
        ->set('capacityUsed', 5000)
        ->set('capacityUsedTwo', 4000)
        ->set('capacityUsedTY', 20000)
        ->assertSee([
        'Train Yard: 3015',
        'Trailer One: 318',
        'Trailer Two: 133',
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

it('builds users inventories object', function () {

    actingAs($user = User::factory()->create([
        'trainYardCapacity' => 65228,
        'pocketCapacity' => 325,
        'truckCapacity' => 9775,
        'truckCapacityTwo' => 6000
    ]));
    fakeStoragesAndPersonalInventoryCallsWithJson();

    $data = Livewire::test(QuickInventoryCalculations::class,[
            'truckCapacity' => $user->truckCapacity,
            'pocketCapacity' => $user->pocketCapacity,
            'trainYardStorage' => $user->trainYardCapacity,
            'itemForFillTrailer' => 'scrap_ore',
        ])
        ->set('capacityUsed', 5000)
        ->set('capacityUsedTwo', 4000)
        ->set('capacityUsedTY', 20000)
        ->instance()
        ->getHydratedData();

    [$pocket, $train, $trailerOne, $trailerTwo] = $data['userInventories']->trunks;

    expect($data['userInventories'])->toBeInstanceOf(\App\TT\Inventories::class)
        ->and($pocket->name)->toBe('pocket')
        ->and($pocket->capacity)->toBe(325)
        ->and($pocket->capacityUsed)->toBe(0)
        ->and($train->name)->toBe('trainYard')
        ->and($train->capacity)->toBe(65228)
        ->and($train->capacityUsed)->toBe(20000)
        ->and($trailerOne->name)->toBe('trailerOne')
        ->and($trailerOne->capacity)->toBe(9775)
        ->and($trailerOne->capacityUsed)->toBe(5000)
        ->and($trailerTwo->name)->toBe('trailerTwo')
        ->and($trailerTwo->capacity)->toBe(6000)
        ->and($trailerTwo->capacityUsed)->toBe(4000);
});
