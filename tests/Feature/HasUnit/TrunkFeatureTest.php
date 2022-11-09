<?php

use App\TT\Items\InventoryItem;
use App\TT\RecipeFactory;
use App\TT\Trunk;

it('populates load given an item name', function () {

    $trunk = new Trunk('mk13', 9775);
    $trunk->fillLoadWithItem('recycled_waste');
    /** @var InventoryItem $waste */
    $waste = $trunk->load->first();

    expect($waste)->toBeInstanceOf(InventoryItem::class)
        ->and($waste->name)->toBe('recycled_waste')
        ->and($waste->count)->toBe(88);
});

it('fills a load and diminishes the recipe components in storage number', function () {

    $trunk = new Trunk('mk13', 9775);
    $recipe = RecipeFactory::get('crafted_rebar')
        ->setInStorageForAllComponents(new \App\TT\Storage([
            new InventoryItem('refined_amalgam', 728),
            new InventoryItem('refined_bronze', 276),
        ]));

    $trunk->fillLoadWithComponentsForRecipe($recipe, true, true);

    $amalgam = $recipe->components->firstWhere('name', 'refined_amalgam');
    $bronze = $recipe->components->firstWhere('name', 'refined_bronze');

    expect($amalgam->inStorage)->toBe(200)
        ->and($bronze->inStorage)->toBe(100);

});
