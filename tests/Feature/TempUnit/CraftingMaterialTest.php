<?php

use App\TT\Items\CraftingMaterial;
use App\TT\Items\InventoryItem;
use App\TT\Items\Item;
use App\TT\Recipe;
use App\TT\RecipeFactory;
use \App\TT\Storage;

it('calculates total weight needed', function () {

    $recipe = RecipeFactory::get(new Item('crafted_ceramictiles'));
    $ceramicTiles = new CraftingMaterial('crafted_ceramictiles', $recipe);
    expect($ceramicTiles->getTotalWeightNeeded())->toBe(10);

    $multipleTiles = new CraftingMaterial('crafted_ceramictiles', $recipe, 2);
    expect($multipleTiles->getTotalWeightNeeded())->toBe(20);

});

it('accepts a storage collection and assigns inStorage parameter', function () {

    $recipe = new Recipe(new Item('house'));
    $ceramicTiles = new CraftingMaterial('crafted_ceramictiles', $recipe);
    $storage = new Storage([
        new InventoryItem('crafted_ceramictiles', 4)
    ]);

    $ceramicTiles->setInStorage($storage);
    expect($ceramicTiles->inStorage)->toBe(4);

});

it('calculates how many are craftable from storage based on count assigned by recipe', function () {

    $recipe = new Recipe(new Item('house'));

    $ceramicTiles = new CraftingMaterial('crafted_ceramictiles', $recipe, 2);
    $storage = new Storage([
        new InventoryItem('crafted_ceramictiles', 4)
    ]);

    // Storage has not been set yet, expect return of 0.
    expect($ceramicTiles->recipesCraftableFromStorage())->toBe(0);

    // Four in storage, recipe calls for 2ea, 2 recipes can be produced.
    $ceramicTiles->setInStorage($storage);
    expect($ceramicTiles->recipesCraftableFromStorage())->toBe(2);

});



