<?php

use App\TT\Items\CraftingMaterial;
use App\TT\Items\InventoryItem;
use App\TT\Items\Item;
use App\TT\Recipe;

test('totalWeightOfComponentsToCraft method', function () {

    $recipe = new Recipe(new Item('crafted_copperwire', 20));
    $recipe->components = collect([
        new CraftingMaterial('refined_copper', $recipe, 4, 10),
        new CraftingMaterial('refined_planks', $recipe, 1, 15)
    ]);


    expect($recipe->totalWeightOfComponentsToCraft())->toBe(55)
        ->and($recipe->totalWeightOfComponentsToCraft(2))->toBe(110);

});

test('howManyCanFit method', function () {

    $recipe = new Recipe(new Item('crafted_copperwire', 20));
    $recipe->components = collect([
        new CraftingMaterial('refined_copper', $recipe, 4, 10),
        new CraftingMaterial('refined_planks', $recipe, 1, 15)
    ]);

    expect($recipe->howManyRecipesCanFit(200))->toBe(3);

});

test('costPerItem method', function () {

    $recipe = new Recipe(new Item('crafted_batteries'));
    $recipe->makes = 2;
    $recipe->cost = 5000;

    expect($recipe->costPerItem())->toBe(2500);

});

test('setInStorageForAllComponents method', function () {

    $recipe = new Recipe(new Item('crafted_copperwire', 20));
    $recipe->components = collect([
        new CraftingMaterial('refined_copper', $recipe, 4, 10),
        new CraftingMaterial('refined_planks', $recipe, 1, 15)
    ]);

    $storage = new \App\TT\Storage([
        new InventoryItem('refined_copper', 50, 10),
        new InventoryItem('refined_planks', 50, 15)
    ]);

    $recipe->setInStorageForAllComponents($storage);

    expect($recipe->components->count())->toBe(2);
    foreach ($recipe->components as $craftingMaterial) {
        expect($craftingMaterial->inStorage)->toBe(50);
    }

});

test('craftableRecipesFromStorage method', function () {

    $recipe = new Recipe(new Item('crafted_copperwire', 20));
    $recipe->components = collect([
        new CraftingMaterial('refined_copper', $recipe, 4, 10),
        new CraftingMaterial('refined_planks', $recipe, 1, 15)
    ]);

    $storage = new \App\TT\Storage([
        new InventoryItem('refined_copper', 50, 10),
        new InventoryItem('refined_planks', 50, 15)
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

    $recipe = new Recipe(new Item('crafted_ceramictiles', 10));
    $recipe->makes = 2;
    $recipe->components = collect([
        new CraftingMaterial('refined_flint', $recipe, 10, 5),
        new CraftingMaterial('refined_sand', $recipe, 2, 5)
    ]);
    $storage = new \App\TT\Storage([
        new InventoryItem('refined_flint', 10, 5),
        new InventoryItem('refined_sand', 2, 5)
    ]);
    $recipe->setInStorageForAllComponents($storage);

    // Recipe yields two, give storage with enough for one recipe expect craftableItems to be two.
    expect($recipe->craftableItemsFromStorage())->toBe(2);

});

it('returns the item that is limiting the number of recipes that can be crafted the most', function () {
        // AKA The item that needs to be focused on next in order to be able to craft more.

    $recipe = new Recipe(new Item('crafted_ceramictiles', 10));
    $recipe->makes = 2;
    $recipe->components = collect([
        new CraftingMaterial('refined_flint', $recipe, 10, 5),
        new CraftingMaterial('refined_sand', $recipe, 2, 5)
    ]);

    $storage = new \App\TT\Storage([
        new InventoryItem('refined_flint', 30, 5),
        new InventoryItem('refined_sand', 30, 5)
    ]);
    $recipe->setInStorageForAllComponents($storage);

    expect($recipe->mostLimitedBy()->name)->toBe('refined_flint');

});

it('returns a name', function () {

    $recipe = new Recipe($item = new Item('crafted_ceramictiles'));

    expect($recipe->displayName())->toBe($item->name());

});

it('returns a component by name', function () {

    $recipe = new Recipe(new Item('crafted_ceramictiles'));
    $recipe->components = collect([
        new CraftingMaterial('refined_flint', $recipe, 10, 5),
        new CraftingMaterial('refined_sand', $recipe, 2, 5)
    ]);

    expect($recipe->getComponent('refined_flint'))->toBeInstanceOf(CraftingMaterial::class)
        ->and($recipe->getComponent('refined_flint')->name)->toBe('refined_flint');

});

it('has a kebab of the recipe internal name', function () {

    $recipe = new Recipe(new Item('liberty_goods'));

    expect($recipe->kebabName())->toBe('liberty-goods');
});
