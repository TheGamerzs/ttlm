<?php

use App\TT\Factories\ItemFactory;

it('makes an item from stored json', function () {

    $item = ItemFactory::make('crafted_ceramictiles');

    expect($item)
        ->name->toBeString()->toBe('crafted_ceramictiles')
        ->weight->toBeInt()->toBe(10)
        ->prettyName->toBeString()->toBe('Truck Cargo: Ceramic Tiles');

});

it('creates an empty item when one does not exist', function () {

    $item = ItemFactory::make('non-existent');

    expect($item)
        ->name->toBeString()->toBe('non-existent')
        ->weight->toBeInt()->toBe(0)
        ->prettyName->toBeNull();

});

it('makes an inventory item from stored json', function () {

    $item = ItemFactory::makeInventoryItem('crafted_ceramictiles', 20);

    expect($item)
        ->name->toBeString()->toBe('crafted_ceramictiles')
        ->weight->toBeInt()->toBe(10)
        ->prettyName->toBeString()->toBe('Truck Cargo: Ceramic Tiles')
        ->count->toBeInt()->toBe(20);

});

it('creates an empty inventory item when one does not exist', function () {

    $item = ItemFactory::makeInventoryItem('non-existent', 10);

    expect($item)
        ->name->toBeString()->toBe('non-existent')
        ->weight->toBeInt()->toBe(0)
        ->prettyName->toBeNull()
        ->count->toBeInt()->toBe(10);

});

it('makes a crafting material', function () {

    $item = ItemFactory::makeCraftingMaterial('crafted_rebar', \App\TT\RecipeFactory::get('house'), 1);

    expect($item)
        ->name->toBeString()->toBe('crafted_rebar')
        ->weight->toBeInt()->toBe(45)
        ->recipe->toBeInstanceOf(\App\TT\Recipe::class)
        ->recipeCount->toBeInt()->toBe(1);

});

it('makes an exportable item', function () {

    $item = ItemFactory::makeExportableItem('refined_zinc', 100);

    expect($item)
        ->toBeInstanceOf(\App\TT\Items\ExportableItem::class)
        ->name->toBeString()->toBe('refined_zinc')
        ->weight->toBeInt()->toBe(10)
        ->count->toBeInt()->toBe(100);

});
