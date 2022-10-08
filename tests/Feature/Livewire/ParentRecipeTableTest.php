<?php

use App\Http\Livewire\ParentRecipeTable;
use App\Models\User;
use App\TT\Items\Item;
use \App\TT\Storage;
use function Pest\Laravel\actingAs;

beforeEach(function () {
    \Illuminate\Support\Facades\Http::preventStrayRequests();
    $this->parentRecipe = \App\TT\RecipeFactory::get(new Item('house'));
    \App\TT\StorageFactory::$storages['fake'] = new Storage([
        new \App\TT\Items\InventoryItem('crafted_computer', 100),
        new \App\TT\Items\InventoryItem('crafted_concrete', 100),
        new \App\TT\Items\InventoryItem('crafted_copperwire', 100),
        new \App\TT\Items\InventoryItem('crafted_rebar', 100),
        new \App\TT\Items\InventoryItem('crafted_ceramictiles', 100),
        new \App\TT\Items\InventoryItem('refined_planks', 100)
    ]);
});

it('mounts with required parameters set', function () {

    actingAs($user = User::factory()->create());

    /** @var ParentRecipeTableExtended $instance */
    $data = Livewire::test(ParentRecipeTableExtended::class, ['parentRecipe' => $this->parentRecipe, 'truckCapacity' => 9775])
        ->instance()->getHydratedData();

    expect($data['storageName'])->toBe('fake')
        ->and($data['parentRecipe'])->toBeInstanceOf(\App\TT\Recipe::class)
        // asserts that recipe's components have had storage counts set
        ->and($data['parentRecipe']->components->first()->inStorage)->toBe(100);
});

it('updates the storage', function () {

    // set another storage to change to, has lower counts so it won't be automatically selected on mount.
    \App\TT\StorageFactory::$storages['fake-2'] = new Storage([
        new \App\TT\Items\InventoryItem('crafted_computer', 50),
        new \App\TT\Items\InventoryItem('crafted_concrete', 50),
        new \App\TT\Items\InventoryItem('crafted_copperwire', 50),
        new \App\TT\Items\InventoryItem('crafted_rebar', 50),
        new \App\TT\Items\InventoryItem('crafted_ceramictiles', 50),
        new \App\TT\Items\InventoryItem('refined_planks', 50)
    ]);

    actingAs($user = User::factory()->create());

    $data = Livewire::test(ParentRecipeTableExtended::class, ['parentRecipe' => $this->parentRecipe, 'truckCapacity' => 9775])
        ->updateProperty('storageName', 'fake-2')
        ->instance()->getHydratedData();

    expect($data['storageName'])->toBe('fake-2')
        // asserts that recipe's components have had storage counts set
        ->and($data['parentRecipe']->components->first()->inStorage)->toBe(50);

});


class ParentRecipeTableExtended extends ParentRecipeTable
{
    public function getHydratedData()
    {
        return collect($this->preRenderedView->getData())->except(['_instance']);
    }
}
