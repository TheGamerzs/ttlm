<?php

use App\TT\Items\InventoryItem;
use App\TT\Items\Item;
use App\TT\Recipe;
use App\View\NextGrindViewModel;

it('sets a recipe from a string', function () {

    $viewModel = new NextGrindViewModel(new \App\TT\Inventories());
    $viewModel->setRecipeFromString('house');

    expect($viewModel->recipe)->toBeInstanceOf(Recipe::class)
        ->and($viewModel->recipe->internalName())->toBe('house');

});

test('items that can be crafted from a full load of components', function () {

    $user = \App\Models\User::factory()->create([
        'truckCapacity' => 9775,
        'truckCapacityTwo' => 6000
    ]);

    $viewModel = (new NextGrindViewModel($user->makeTruckingInventories()))->setRecipeFromString('crafted_rebar');

    expect($viewModel->itemsThatCanBeCraftedFromAFullLoadOfComponents())->toBe(284);

});

it('sets trunk loads when a recipe is set', function () {

    $user = \App\Models\User::factory()->create([
        'truckCapacity' => 9775,
    ]);

    $recipe = \App\TT\RecipeFactory::get(new Item('crafted_rebar'))
        ->setInStorageForAllComponents(new \App\TT\Storage([
            new InventoryItem('refined_amalgam', 1000),
            new InventoryItem('refined_bronze', 1000),
        ]));

    $viewModel = (new NextGrindViewModel($user->makeTruckingInventories()))
        ->setRecipe($recipe);

    $trunk = $viewModel->inventories->trunks->first();
    $amalgam = $trunk->load->firstWhere('name', 'refined_amalgam');
    $bronze = $trunk->load->firstWhere('name', 'refined_bronze');

    expect($amalgam)->toBeInstanceOf(InventoryItem::class)
        ->and($amalgam->count)->toBe(528)
        ->and($bronze)->toBeInstanceOf(InventoryItem::class)
        ->and($bronze->count)->toBe(176);

});
