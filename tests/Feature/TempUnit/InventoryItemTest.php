<?php


use App\TT\Items\InventoryItem;
use App\TT\Items\Item;

it('calculates the total weight of the inventory item', function () {

    $ceramicTiles = new InventoryItem('crafted_ceramictiles', 100);

    // Ceramic tiles weigh 10kg, expect 100 to weigh 4000kg.
    expect($ceramicTiles->getTotalWeight())->toBe(1000)->toBeInt();

});

it('makes one from a crafting material', function () {

    $recipe = \App\TT\RecipeFactory::get(new Item('crafted_rebar'));
    $craftingMaterial = $recipe->components->first();
    $count = 100;

    $inventoryItem = InventoryItem::fromCraftingMaterial($craftingMaterial, $count);
    expect($inventoryItem->count)->toBe(100)
        ->and($inventoryItem->name)->toBe($craftingMaterial->name);
});
