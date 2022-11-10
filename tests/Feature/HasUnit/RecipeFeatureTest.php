<?php


use App\TT\Items\InventoryItem;
use App\TT\RecipeFactory;
use Illuminate\Support\Collection;

it('returns inventory items of components ratioed to recipe to fill a trunk load', function () {

    $recipe = RecipeFactory::get('crafted_rebar');

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
