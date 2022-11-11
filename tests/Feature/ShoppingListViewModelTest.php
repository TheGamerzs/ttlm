<?php

use App\Models\User;
use App\TT\RecipeFactory;
use App\TT\ShoppingListBuilder;
use App\TT\Storage;
use App\TT\StorageFactory;
use App\View\ShoppingList\ShoppingListViewModel;
use function Pest\Laravel\actingAs;

beforeEach(function () {
    actingAs(User::factory()->create());
    fakeStoragesAndPersonalInventoryCallsWithJson();
});

it('returns display objects for the view to use', function () {

    $totalNeededList = ShoppingListBuilder::build(
        RecipeFactory::get('house'),
        new Storage(),
        1000,
        9775
    );

    $stillNeededList = ShoppingListBuilder::build(
        RecipeFactory::get('house'),
        StorageFactory::get('combined'),
        1000,
        9775
    );

    $viewModel = new ShoppingListViewModel($totalNeededList, $stillNeededList);

    \Spatie\Snapshots\assertMatchesJsonSnapshot($viewModel->getDisplayItems('crafted'));
    \Spatie\Snapshots\assertMatchesJsonSnapshot($viewModel->getDisplayItems('refined'));
    \Spatie\Snapshots\assertMatchesJsonSnapshot($viewModel->getDisplayItems('scrap'));
    \Spatie\Snapshots\assertMatchesJsonSnapshot($viewModel->getDisplayItems('pickup'));

    expect($viewModel->totalCraftingCost())->toBe('~$300,671,300')
        ->and($viewModel->remainingCraftingCost())->toBe('~$203,010,650');
});

test('showType method', function () {
    $totalNeededList = ShoppingListBuilder::build(
        RecipeFactory::get('house'),
        new Storage(),
        1000,
        9775
    );
    $stillNeededList = ShoppingListBuilder::build(
        RecipeFactory::get('house'),
        StorageFactory::get('combined'),
        1000,
        9775
    );

    $viewModel = new ShoppingListViewModel($totalNeededList, $stillNeededList);
    expect($viewModel)
        ->showType('scrap')->toBeTrue()
        ->showType('refined')->toBeTrue()
        ->showType('crafted')->toBeTrue();

    $totalNeededList['scrap'] = collect();
    $totalNeededList['refined'] = collect();
    $totalNeededList['crafted'] = collect();

    $viewModel = new ShoppingListViewModel($totalNeededList, $stillNeededList);
    expect($viewModel)
        ->showType('scrap')->toBeFalse()
        ->showType('refined')->toBeFalse()
        ->showType('crafted')->toBeFalse();
});
