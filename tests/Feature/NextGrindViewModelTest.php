<?php

use App\Models\User;
use App\TT\Items\InventoryItem;
use App\TT\RecipeFactory;
use App\View\NextGrindViewModel;

test('items that can be crafted from a full load of components', function () {

    \Pest\Laravel\actingAs($user = User::factory()->create([
        'truckCapacity'    => 9775,
        'truckCapacityTwo' => 6000
    ]));

    fakeStoragesAndPersonalInventoryCallsWithJson();

    $recipe = RecipeFactory::get('crafted_rebar');
    $recipe->autoSetStorageBasedOnLocationOfMostComponents();
    $viewModel = (new NextGrindViewModel($user->makeTruckingInventories()))
        ->setRecipe($recipe);

    expect($viewModel->itemsThatCanBeCraftedFromAFullLoadOfComponents())->toBe(284);

});

it('sets trunk loads when a recipe is set', function () {

    $user = User::factory()->create([
        'truckCapacity' => 9775,
    ]);

    $recipe = RecipeFactory::get('crafted_rebar')
        ->setInStorageForAllComponents(new \App\TT\Storage([
            new InventoryItem('refined_amalgam', 1000),
            new InventoryItem('refined_bronze', 1000),
        ]));

    $viewModel = (new NextGrindViewModel($user->makeTruckingInventories()))
        ->setRecipe($recipe);

    $trunk   = $viewModel->inventories->trunks->first();
    $amalgam = $trunk->load->firstWhere('name', 'refined_amalgam');
    $bronze  = $trunk->load->firstWhere('name', 'refined_bronze');

    expect($amalgam)->toBeInstanceOf(InventoryItem::class)
        ->and($amalgam->count)->toBe(528)
        ->and($bronze)->toBeInstanceOf(InventoryItem::class)
        ->and($bronze->count)->toBe(176);

});

test('custom view name', function () {

    $recipe    = RecipeFactory::get('liberty_goods');
    $viewModel = new NextGrindViewModel(new \App\TT\Inventories());
    $viewModel->setRecipe($recipe);

    expect($viewModel->customViewName())->toBe('next-grind-custom.liberty-goods');

});

it('shows number of runs that can be made based on storage', function (int $copperInStorage, int $planksInStorage, string $expectedString) {

    \Pest\Laravel\actingAs($user = User::factory()->create([
        'truckCapacity'    => 9775,
        'truckCapacityTwo' => 6000
    ]));
    fakePersonalInventoryApiCallWithStoredJson();
    fakeStoragesApiCallWithArray([
        'refined_copper' => $copperInStorage,
        'refined_planks' => $planksInStorage,
    ]);

    $recipe = RecipeFactory::get('crafted_copperwire');
    $recipe->autoSetStorageBasedOnLocationOfMostComponents();
    $viewModel = (new NextGrindViewModel($user->makeTruckingInventories()))->setRecipe($recipe);

    expect($viewModel->runsThatCanBeMadeDisplayString())->toBe($expectedString);
})
    ->with([
        /**
         * Refined Copper
         * 9775 => 708
         * 6000 => 436
         * Run =>  1144
         *
         * Planks
         * 9775 => 177
         * 6000 => 109
         * Run =>  286
         */
        [
            $baseCopper = 1144,
            $basePlanks = 286,
            '1 Run'
        ],
        [
            $baseCopper * .5,
            $basePlanks * .5,
            '0.5 Run'
        ],
        [
            $baseCopper * 2,
            $basePlanks * 2,
            '2 Runs'
        ],
        [
            $baseCopper * 3.5,
            $basePlanks * 3.5,
            '3.5 Runs'
        ],
        [
            $baseCopper * 2,
            $basePlanks * 6,
            '2 Runs'
        ],
        [
            $baseCopper * 6,
            $basePlanks * 3.2,
            '3.2 Runs'
        ],
    ]);
