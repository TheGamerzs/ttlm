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


it('populates load with components given a recipe', function () {

    $trunk = new Trunk('mk13', 9775);
    $recipe = RecipeFactory::get('refined_solder');
    $recipe->components = collect([
        new \App\TT\Items\CraftingMaterial('refined_aluminum', $recipe, 2, 10),
        new \App\TT\Items\CraftingMaterial('scrap_lead', $recipe, 2, 15)
    ]);


    $trunk->fillLoadWithComponentsForRecipe($recipe, false);

    expect($trunk->load->contains('name', 'refined_aluminum'))->toBeTrue()
        ->and($trunk->load->contains('name', 'scrap_lead'))->toBeTrue();

    $amalgam = $trunk->load->firstWhere('name', 'refined_aluminum');
    $bronze = $trunk->load->firstWhere('name', 'scrap_lead');


    expect($amalgam->count)->toBe(390)
        ->and($bronze->count)->toBe(390);

});
