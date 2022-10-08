<?php

use App\Http\Livewire\NextGrind;
use App\Models\User;
use App\TT\Recipe;
use App\TT\Storage;
use Illuminate\Support\Facades\Session;
use Livewire\Livewire;
use function Pest\Laravel\actingAs;

beforeEach(function () {

    \Illuminate\Support\Facades\Http::preventStrayRequests();
    $storage = \App\TT\StorageFactory::$storages['fake'] = new Storage([
        new \App\TT\Items\InventoryItem('crafted_batteries', 100),
        new \App\TT\Items\InventoryItem('crafted_circuit', 150),
        new \App\TT\Items\InventoryItem('scrap_gold', 500),
        new \App\TT\Items\InventoryItem('refined_solder', 100),
        new \App\TT\Items\InventoryItem('refined_zinc', 100),
        new \App\TT\Items\InventoryItem('scrap_acid', 100),
        new \App\TT\Items\InventoryItem('crafted_copperwire', 200),
        new \App\TT\Items\InventoryItem('refined_solder', 200),
        new \App\TT\Items\InventoryItem('scrap_plastic', 200),
    ]);
    $this->parentRecipe = \App\TT\RecipeFactory::get(new \App\TT\Items\Item('crafted_computer'));
    $this->parentRecipe->setInStorageForAllComponents($storage);
});

it('mounts with required properties', function () {

    actingAs(User::factory()->create());

    $data = Livewire::test(NextGrindTestable::class, ['parentRecipe' => $this->parentRecipe, 'truckCapacity' => 9775])
        ->instance()->getHydratedData();

    expect($data['parentRecipe'])->toBeInstanceOf(Recipe::class)
        ->and($data['nextRecipeToGrind'])->toBeInstanceOf(Recipe::class)
        ->and($data['nextRecipeToGrind']->components->first()->inStorage)->toBe(100);

});

it('sets the goal from session', function () {

    actingAs(User::factory()->create());

    Session::put('craftingGoal', [
        'recipe' => 'house',
        'count' => 100
    ]);

    Livewire::test(NextGrind::class, ['parentRecipe' => $this->parentRecipe, 'truckCapacity' => 9775])
        ->assertSet('goalRecipe', 'house')
        ->assertSet('goalCount', 100);

});

it('updates or creates a goal', function () {

    actingAs(User::factory()->create());
    Livewire::test(NextGrind::class, ['parentRecipe' => $this->parentRecipe, 'truckCapacity' => 9775])
        ->set('goalRecipe', 'house')
        ->set('goalCount', 1000)
        ->call('updateGoal')
        ->assertSet('goalWasUpdated', true)
        ->assertEmitted('refreshParentRecipeTable')
        ->assertSet('iWantFromGoal', true);

    expect(Session::has('craftingGoal'))->toBeTrue();
    $goal = Session::get('craftingGoal');
    expect($goal['recipe'])->toBe('house')
        ->and($goal['count'])->toBe('1000');

});

it('sets I want from goal', function () {

    actingAs(User::factory()->create());

    Session::put('craftingGoal', [
        'recipe' => 'house',
        'count' => 10000
    ]);

    Livewire::test(NextGrind::class, ['parentRecipe' => $this->parentRecipe, 'truckCapacity' => 9775])
        ->assertSet('iWant', '4900')
        ->assertSet('nextRecipeToGrind', 'crafted_batteries')
        ->assertSet('iWantFromGoal', true);

});

it('changes storage', function () {

    actingAs(User::factory()->create());
    $storage = \App\TT\StorageFactory::$storages['fake-2'] = new Storage([
        new \App\TT\Items\InventoryItem('crafted_batteries', 1),
        new \App\TT\Items\InventoryItem('crafted_circuit', 1),
        new \App\TT\Items\InventoryItem('scrap_gold', 5),
        new \App\TT\Items\InventoryItem('refined_solder', 1),
        new \App\TT\Items\InventoryItem('refined_zinc', 1),
        new \App\TT\Items\InventoryItem('scrap_acid', 1),
    ]);

    $data = Livewire::test(NextGrindTestable::class, ['parentRecipe' => $this->parentRecipe, 'truckCapacity' => 9775])
        ->updateProperty('storageName', 'fake-2')
        ->instance()->getHydratedData();

    expect($data['storageName'])->toBe('fake-2')
        // asserts that recipe's components have had storage counts set
        ->and($data['nextRecipeToGrind']->components->first()->inStorage)->toBe(1);

});

it('changes next recipe to grind', function () {

    actingAs(User::factory()->create());
    $data = Livewire::test(NextGrindTestable::class, ['parentRecipe' => $this->parentRecipe, 'truckCapacity' => 9775])
        ->assertSet('nextRecipeToGrind', 'crafted_batteries')
        ->call('setNextRecipeToGrind', 'crafted_circuit')
        ->assertSet('nextRecipeToGrind', 'crafted_circuit')
        ->instance()->getHydratedData();

    expect($data['nextRecipeToGrind']->components->first()->inStorage)->toBe(200)
        ->and($data['parentRecipe']->components->first()->inStorage)->toBe(100);
});


class NextGrindTestable extends NextGrind
{
    public function getHydratedData()
    {
        return collect($this->preRenderedView->getData())->except(['_instance']);
    }
}
