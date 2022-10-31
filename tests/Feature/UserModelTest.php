<?php

use App\Models\MarketOrder;
use App\Models\User;
use App\TT\Inventories;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use function Pest\Laravel\actingAs;

test('relationships', function () {

    $user = User::factory()->create();
    $d = MarketOrder::factory()->buyOrder()->for($user)->count(3)->create();
    MarketOrder::factory()->sellOrder()->for($user)->count(2)->create();
    $user->load(['marketOrders', 'buyOrders', 'sellOrders']);

    expect($user->marketOrders)->toBeInstanceOf(EloquentCollection::class)
        ->and($user->sellOrders)->toBeInstanceOf(EloquentCollection::class)
        ->and($user->buyOrders)->toBeInstanceOf(EloquentCollection::class)
        ->and($user->marketOrders->count())->toBe(5)
        ->and($user->sellOrders->count())->toBe(2)
        ->and($user->buyOrders->count())->toBe(3);
});

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

it('returns if a user has attribute for second truck capacity', function () {

    $secondCapacityUser = User::factory()->make();
    $noSecondCapacityUser = User::factory()->make(['truckCapacityTwo' => null]);

    expect($secondCapacityUser->hasSecondTrailer())->toBeTrue()
        ->and($noSecondCapacityUser->hasSecondTrailer())->toBeFalse();

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

it('gets a users game plan', function () {

    $user = User::factory()->create();
    Cache::put($user->id . 'gamePlan', collect(['Game Plan Item']));

    $gamePlan = $user->getGamePlan();

    expect($gamePlan->contains('Game Plan Item'))->toBeTrue();

});

it('saves the users crafting goal', function () {

    $user = User::factory()->create();
    actingAs($user);
    expect(Session::has('craftingGoal'))->toBeFalse();

    $user->setCraftingGoal(1000, 'house');
    expect(Session::has('craftingGoal'))->toBeTrue()
        ->and(Session::get('craftingGoal')['count'])->toBe(1000)
        ->and(Session::get('craftingGoal')['recipe'])->toBe('house');

});

it('gets the users crafting goal', function () {

    actingAs($user = User::factory()->create());

    Session::put('craftingGoal', [
        'count' => 500,
        'recipe' => 'some_recipe'
    ]);

    $goal = $user->getCraftingGoal();
    expect($goal['count'])->toBe(500)
        ->and($goal['recipe'])->toBe('some_recipe');

});

it('checks if a crafting goal exists', function (int $count, bool $expected) {

    actingAs($user = User::factory()->create());
    Session::put('craftingGoal', [
        'count' => $count,
        'recipe' => 'some_recipe'
    ]);

    expect($user->hasCraftingGoal())->toBe($expected);

})->with([
    [
        'count' => 500,
        'expected' => true
    ],
    [
        'count' => 0,
        'expected' => false
    ]
]);

it('gives a default when no goal is set', function () {

    $user = User::factory()->create();
    actingAs($user);

    $goal = $user->getCraftingGoal();
    expect($goal['count'])->toBe(0)
        ->and($goal['recipe'])->toBe($user->default_crafting_recipe);

});

it('generates a link for discord dm', function () {

    $user = User::factory()->create();
    $expected = 'https://discordapp.com/users/' . $user->discord_snowflake . '/';

    expect($user->discordProfileLink)->toBe($expected);
});

it('returns an inventories object with all', function () {

    $user = User::factory()->create();
    $inventories = $user->makeInventories();

    expect($inventories)->toBeInstanceOf(Inventories::class)
        ->and($inventories->trunks->count())->toBe(4);

    [$trailerOne, $trailerTwo, $pocket, $train] = $inventories->trunks;

    expect($trailerOne->name)->toBe($user->trailer_name)
        ->and($trailerTwo->name)->toBe($user->trailer_two_name);

});

it('returns and inventories object with just trucking trailers', function () {

    $user = User::factory()->create();
    $inventories = $user->makeTruckingInventories();

    expect($inventories)->toBeInstanceOf(Inventories::class)
        ->and($inventories->trunks->count())->toBe(2);

});
