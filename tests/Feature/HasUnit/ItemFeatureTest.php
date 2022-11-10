<?php

use App\TT\Factories\ItemFactory;
use App\TT\Items\InventoryItem;
use App\TT\Items\Item;
use App\TT\RecipeFactory;

it('gets a recipe based on itself', function () {

    $item = new Item('crafted_ceramictiles');
    $recipe = $item->getRecipe();

    expect($recipe)->toBeInstanceOf(\App\TT\Recipe::class)
        ->and($recipe->inventoryItem)->toBe($item);

});

test('an inventory item can be created from a crafting material', function () {

    $recipe = RecipeFactory::get('crafted_rebar');
    $craftingMaterial = ItemFactory::makeCraftingMaterial('test', $recipe, 2);
    $count = 100;

    $inventoryItem = InventoryItem::fromCraftingMaterial($craftingMaterial, $count);
    expect($inventoryItem->count)->toBe(100)
        ->and($inventoryItem->name)->toBe($craftingMaterial->name);
});
