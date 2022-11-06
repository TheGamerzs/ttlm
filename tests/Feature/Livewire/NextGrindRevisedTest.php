<?php

use App\Http\Livewire\NextGrindRevised;
use App\Models\User;
use App\TT\Items\InventoryItem;
use App\TT\Items\Item;
use App\TT\Recipe;
use App\TT\RecipeFactory;
use App\TT\StorageFactory;
use function Pest\Laravel\actingAs;

beforeEach(function () {
    actingAs($this->user = User::factory()->create());

    resetStorageFactoryStatics();
    fakePersonalInventoryApiCallWithStoredJson();
    fakeStoragesApiCallWithArray(['crafted_batteries' => 5]);
    StorageFactory::get();

    StorageFactory::$storages['first'] = new \App\TT\Storage([
        new InventoryItem('crafted_batteries', 10),
    ]);

    $this->testableComponent = Livewire::test(NextGrindRevised::class,
        [
            'truckCapacity' => 9775,
            'parentRecipe' => RecipeFactory::get(new Item('house'))
        ]);
});

test('parent recipe is added to hydrate list and assigned to protected property', function () {

    /** @var NextGrindRevised $component */
    $component = $this->testableComponent->instance();


    expect(array_key_exists('parentRecipe', $component->toHydrate))->toBeTrue()
        ->and($component->toHydrate['parentRecipe'])->toBe('house')
        ->and($component->getParentRecipe())->toBeInstanceOf(Recipe::class);

});

test('recipe is added to hydrate list and assigned to protected property', function () {

    /** @var NextGrindRevised $component */
    $component = $this->testableComponent->instance();

    expect(array_key_exists('recipe', $component->toHydrate))->toBeTrue()
        ->and($component->toHydrate['recipe'])->toBe('crafted_computer')
        ->and($component->getRecipe())->toBeInstanceOf(Recipe::class);

});

it('hydrates protected properties', function () {

    /** @var NextGrindRevised $component */
    $component = $this->testableComponent->call('$refresh')->instance();

    expect($component->getRecipe())->toBeInstanceOf(Recipe::class)
        ->and($component->getParentRecipe())->toBeInstanceOf(Recipe::class)
        ->and($component->getRecipe()->internalName())->toBe('crafted_computer')
        ->and($component->getParentRecipe()->internalName())->toBe('house');

});

it('assigns goal count and recipe from user and knows it is using a goal', function () {

    $this->user->setCraftingGoal(1000, 'house');

    /** @var NextGrindRevised $component */
    $component = Livewire::test(NextGrindRevised::class,
        [
            'truckCapacity' => 9775,
            'parentRecipe' => RecipeFactory::get(new Item('house'))
        ])
        ->assertSet('goalCount', 1000)
        ->assertSet('goalRecipe', 'house')
        ->instance();

    expect($component->usingGoal())->toBeTrue();
});

it('assigns a not active goal count and knows it is using full trailer loads for calculations', function () {

    /** @var NextGrindRevised $component */
    $component = $this->testableComponent
        ->assertSet('goalCount', 0)
        ->assertSet('goalRecipe', $this->user->default_crafting_recipe)
        ->instance();

    expect($component->usingGoal())->toBeFalse();
});

it('updates a users crafting goal', function () {

    /** @var NextGrindRevised $component */
    $component = Livewire::test(NextGrindRevised::class,
        [
            'truckCapacity' => 9775,
            'parentRecipe' => RecipeFactory::get(new Item('house'))
        ])
        ->set('goalCount', 750)
        ->set('goalRecipe', 'crafted_computer')
        ->call('updateGoal')
        ->instance();

    expect($this->user->getCraftingGoal()['count'])->toBe(750)
        ->and($this->user->getCraftingGoal()['recipe'])->toBe('crafted_computer')
        ->and($component->usingGoal())->toBeTrue();
});

it('refreshes the parent recipe table component when the goal is updated', function () {

    $this->testableComponent
        ->call('updateGoal')
        ->assertEmitted('refreshParentRecipeTable');

});

test('changing recipe from emit call', function () {

    /** @var NextGrindRevised $component */
    $component = $this->testableComponent->instance();

    expect($component->toHydrate['parentRecipe'])->toBe('house')
        ->and($component->getParentRecipe())->toBeInstanceOf(Recipe::class)
        ->and($component->toHydrate['recipe'])->toBe('crafted_computer')
        ->and($component->getRecipe())->toBeInstanceOf(Recipe::class);

    /** @var NextGrindRevised $updatedComponent */
    $updatedComponent = $this->testableComponent
        ->call('changeRecipe', 'crafted_rebar')
        ->instance();

    expect($updatedComponent->toHydrate['recipe'])->toBe('crafted_rebar')
        ->and($updatedComponent->getRecipe())->toBeInstanceOf(Recipe::class)
        ->and($updatedComponent->getRecipe()->internalName())->toBe('crafted_rebar');
});

it('mounts with a storage', function () {

    /** @var NextGrindRevised $component */
    $component = $this->testableComponent->instance();

    expect($component->storageName)->toBe('first')
        ->and($component->getStorage())->toBeInstanceOf(\App\TT\Storage::class)
        ->and($component->getStorage()->contains('name', 'crafted_batteries'));

});

it('hydrates with a storage', function () {

    /** @var NextGrindRevised $component */
    $component = $this->testableComponent->call('$refresh')->instance();

    expect($component->storageName)->toBe('first')
        ->and($component->getStorage())->toBeInstanceOf(\App\TT\Storage::class)
        ->and($component->getStorage()->contains('name', 'crafted_batteries'));

});

it('updates the storage', function () {

    StorageFactory::$storages['second'] = new \App\TT\Storage([
        new InventoryItem('refined_planks', 1)
    ]);

    /** @var NextGrindRevised $component */
    $component = $this->testableComponent->updateProperty('storageName', 'second')->instance();

    expect($component->getStorage()->contains('name', 'refined_planks'))->toBeTrue();

});

test('pickup runs', function (string $recipeName, string $runName) {

    $componentClass = new NextGrindRevised();
    $componentClass->truckCapacity = 15775;
    $componentClass->changeRecipe($recipeName);
    $yields = $componentClass->pickupRunYields();

    \Spatie\Snapshots\assertMatchesSnapshot($yields);

})
    ->with([
    [
        'scrap_emerald',
        'quarry'
    ],
    [
        'refined_planks',
        'logging camp'
    ],
    [
        'scrap_aluminum',
        'trash'
    ],
    [
        'scrap_copper',
        'electronics'
    ],
    [
        'scrap_acid',
        'toxic waste'
    ],
    [
        'petrochem_kerosene',
        'crude oil'
    ],
    [
        'military_chemicals',
        'raw gas'
    ],
    [
        'fridge_veggies',
        'veggies'
    ],
    [
        'fridge_dairy',
        'dairy'
    ],
]);
