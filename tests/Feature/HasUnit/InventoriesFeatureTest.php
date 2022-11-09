<?php

use App\TT\Inventories;
use App\TT\Items\InventoryItem;
use App\TT\RecipeFactory;
use App\TT\Trunk;

it('sets trunk loads when a recipe is set', function () {

    $inventories = new Inventories([
        new Trunk('mk13', 9775),
        new Trunk('mk13-2', 9775)
    ]);

    $recipe = RecipeFactory::get('crafted_rebar')
        ->setInStorageForAllComponents(new \App\TT\Storage([
            new InventoryItem('refined_amalgam', 1100),
            new InventoryItem('refined_bronze', 1000),
        ]));

    $inventories->fillTrunksWithRecipeComponents($recipe);

    /** @var Trunk $trunk */
    foreach ($inventories as $trunk) {
        $amalgam = $trunk->load->firstWhere('name', 'refined_amalgam');
        $bronze = $trunk->load->firstWhere('name', 'refined_bronze');

        expect($amalgam)->toBeInstanceOf(InventoryItem::class)
            ->and($amalgam->count)->toBe(528)
            ->and($bronze)->toBeInstanceOf(InventoryItem::class)
            ->and($bronze->count)->toBe(176);
    }

});

it('sets partial loads if the recipe components in storage can not fill trunks', function () {

    $inventories = new Inventories([
        $trunkOne = new Trunk('mk13', 9775),
        $trunkTwo = new Trunk('mk13-2', 9775)
    ]);

    $recipe = RecipeFactory::get('crafted_rebar')
        ->setInStorageForAllComponents(new \App\TT\Storage([
            new InventoryItem('refined_amalgam', 728),
            new InventoryItem('refined_bronze', 276),
        ]));

    $inventories->fillTrunksWithRecipeComponents($recipe);


    /*
    |--------------------------------------------------------------------------
    | Trunk One
    |--------------------------------------------------------------------------
    */
    $amalgam = $trunkOne->load->firstWhere('name', 'refined_amalgam');
    $bronze = $trunkOne->load->firstWhere('name', 'refined_bronze');

    expect($amalgam)->toBeInstanceOf(InventoryItem::class)
        ->and($amalgam->count)->toBe(528)
        ->and($bronze)->toBeInstanceOf(InventoryItem::class)
            ->and($bronze->count)->toBe(176);

    /*
    |--------------------------------------------------------------------------
    | Trunk Two
    | Trunk One will leave 200 amalgam and 100 bronze left over. The recipe is
    | then limited to 33 yields by the 200 amalgam, meaning trunk two should
    | then have 198 amalgam (33*6), and 66 bronze (33*2)
    |--------------------------------------------------------------------------
    */
    $amalgam = $trunkTwo->load->firstWhere('name', 'refined_amalgam');
    $bronze = $trunkTwo->load->firstWhere('name', 'refined_bronze');

    expect($amalgam)->toBeInstanceOf(InventoryItem::class)
        ->and($amalgam->count)->toBe(198)
        ->and($bronze)->toBeInstanceOf(InventoryItem::class)
            ->and($bronze->count)->toBe(66);

    /*
    |--------------------------------------------------------------------------
    | Make sure the original recipe components inStorage property is in tact
    |--------------------------------------------------------------------------
    */

    $amalgam = $recipe->components->firstWhere('name', 'refined_amalgam');
    $bronze  = $recipe->components->firstWhere('name', 'refined_bronze');

    expect($amalgam->inStorage)->toBe(728)
        ->and($bronze->inStorage)->toBe(276);


});
