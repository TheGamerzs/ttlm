<?php

use App\TT\Items\CraftingMaterial;
use App\TT\Items\InventoryItem;
use App\TT\Items\Item;
use App\TT\RecipeFactory;
use Illuminate\Support\Collection;

test('totalWeightOfComponentsToCraft method', function () {

//      Recipe:
//        'components'       => [
//            'refined_copper' => 4,
//            'refined_planks' => 1
//        ]
//
//    Weights
//    'refined_copper' => 10,
//    'refined_planks' => 15,
    $recipe = RecipeFactory::get(new Item('crafted_copperwire'));

    expect($recipe->totalWeightOfComponentsToCraft())->toBe(55)
        ->and($recipe->totalWeightOfComponentsToCraft(2))->toBe(110);

});

test('howManyCanFit method', function () {

    $recipe = RecipeFactory::get(new Item('crafted_copperwire'));

    expect($recipe->howManyRecipesCanFit(200))->toBe(3);

});

test('costPerItem method', function () {

    $recipe = RecipeFactory::get(new Item('crafted_batteries'));

    expect($recipe->costPerItem())->toBe(2500);

});

test('setInStorageForAllComponents method', function () {

    $recipe = RecipeFactory::get(new Item('crafted_copperwire'));
    $storage = new \App\TT\Storage([
        new InventoryItem('refined_copper', 50),
        new InventoryItem('refined_planks', 50)
    ]);

    $recipe->setInStorageForAllComponents($storage);

    expect($recipe->components->count())->toBe(2);
    foreach ($recipe->components as $craftingMaterial) {
        expect($craftingMaterial->inStorage)->toBe(50);
    }

});

test('craftableRecipesFromStorage method', function () {

    $recipe = RecipeFactory::get(new Item('crafted_copperwire'));
    $storage = new \App\TT\Storage([
        new InventoryItem('refined_copper', 50),
        new InventoryItem('refined_planks', 50)
    ]);
    $recipe->setInStorageForAllComponents($storage);

    // Recipe is limited by copper, having 50 and needing 4 each. 50/4=12.5 expect 12.
    expect($recipe->craftableRecipesFromStorage())->toBe(12)->toBeInt();

});

test('craftableItemsFromStorage method', function () {

//    $recipes['crafted_ceramictiles'] = [
//        'craftingLocation' => 'LS Foundry',
//        'makes'            => 2,
//        'components'       => [
//            'refined_flint' => 10,
//            'refined_sand'  => 2
//        ]
//    ];

    $recipe = RecipeFactory::get(new Item('crafted_ceramictiles'));
    $storage = new \App\TT\Storage([
        new InventoryItem('refined_flint', 10),
        new InventoryItem('refined_sand', 2)
    ]);
    $recipe->setInStorageForAllComponents($storage);

    // Recipe yields two, give storage with enough for one recipe expect craftableItems to be two.
    expect($recipe->craftableItemsFromStorage())->toBe(2);

});

it('returns the item that is limiting the number of recipes that can be crafted the most', function () {
        // AKA The item that needs to be focused on next in order to be able to craft more.

    $recipe = RecipeFactory::get(new Item('crafted_ceramictiles'));
    $storage = new \App\TT\Storage([
        new InventoryItem('refined_flint', 30),
        new InventoryItem('refined_sand', 30)
    ]);
    $recipe->setInStorageForAllComponents($storage);

    expect($recipe->mostLimitedBy()->name)->toBe('refined_flint');

});

it('returns a name', function () {

    $recipe = RecipeFactory::get($item = new Item('crafted_ceramictiles'));

    expect($recipe->displayName())->toBe($item->name());

});

it('returns a component by name', function () {

    $recipe = RecipeFactory::get(new Item('crafted_ceramictiles'));

    expect($recipe->getComponent('refined_flint'))->toBeInstanceOf(CraftingMaterial::class)
        ->and($recipe->getComponent('refined_flint')->name)->toBe('refined_flint');

});

it('returns inventory items of components ratioed to recipe to fill a trunk load', function () {

    $recipe = RecipeFactory::get(new Item('crafted_rebar'));
    $items = $recipe->componentsThatCanFitAsInventoryItems(9775, false);

    expect($items)->toBeInstanceOf(Collection::class)
        ->and($items->count())->toBe(2);

    $amalgam = $items->firstWhere('name', 'refined_amalgam');
    expect($amalgam)->toBeInstanceOf(InventoryItem::class)
        ->and($amalgam->count)->toBe(528);

    $bronze = $items->firstWhere('name', 'refined_bronze');
    expect($bronze)->toBeInstanceOf(InventoryItem::class)
        ->and($bronze->count)->toBe(176);

});

it('has a kebab of the recipe internal name', function () {

    $recipe = RecipeFactory::get(new Item('liberty_goods'));

    expect($recipe->kebabName())->toBe('liberty-goods');
});
