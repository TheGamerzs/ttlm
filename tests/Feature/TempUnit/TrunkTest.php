<?php

use App\TT\Items\InventoryItem;
use App\TT\Items\Item;
use App\TT\Trunk;
use Illuminate\Support\Collection;

test('constructor', function () {

    $trunk = new Trunk('name', 1000);

    expect($trunk->name)->toBe('name')
        ->and($trunk->capacity)->toBe(1000);

    $load = [
        new InventoryItem('crafted_rebar', 2),
        new InventoryItem('crafted_copperwire', 2)
    ];
    $trunkWithLoad = new Trunk('with load', 1000, $load);

    expect($trunkWithLoad->load)->toBeInstanceOf(Collection::class)
        ->and($trunkWithLoad->load->count())->toBe(2)
        ->and($trunkWithLoad->load->contains('name', 'crafted_rebar'))->toBeTrue()
        ->and($trunkWithLoad->load->contains('name', 'crafted_copperwire'))->toBeTrue();

});

test('numberOfItemsThatCanFitFromWeight method', function () {

    $trunk = new Trunk('name', 1000);

    expect($trunk->numberOfItemsThatCanFitFromWeight(100))->toBe(10);

});

test('getAvailableCapacity method with only manual', function () {

    $trunk = new Trunk('name', 1000);
    $trunk->capacityUsed = 600;

    expect($trunk->getAvailableCapacity())->toBe(400);
});

test('getAvailableCapacity method with only load', function () {

    $trunk = new Trunk('name', 10000, [new InventoryItem('crafted_rebar', 100)]); // 4500 weight

    expect($trunk->getAvailableCapacity())->toBe(10000-4500);

});

test('getAvailableCapacity method with load and manual', function () {

    $trunk = new Trunk('name', 10000, [new InventoryItem('crafted_rebar', 100)]); // 4500 weight
    $trunk->capacityUsed = 1000;

    expect($trunk->getAvailableCapacity())->toBe(10000-4500-1000);

});

test('numberOfItemsThatCanFitFromWeight method after setting capacity used', function () {

    $trunk = new Trunk('name', 1000);
    $trunk->setCapacityUsed(500);

    expect($trunk->numberOfItemsThatCanFitFromWeight(100))->toBe(5);

});

test('numberOfItemsThatCanFit method', function () {

    $item = new Item('scrap_ore'); // 15kg weight
    $trunk = new Trunk('name', 1000);

    expect($trunk->numberOfItemsThatCanFit($item))->toBe(66);

});

it('has a display name', function () {

    $trunk = new Trunk('trailerOne', 999);

    expect($trunk->displayName())->toBeInstanceOf(\Illuminate\Support\Stringable::class)
        ->and($trunk->displayName()->toString())->toBe('Trailer One');

});

it('populates load with components given a recipe', function () {

    $trunk = new Trunk('mk13', 9775);
    $recipe = \App\TT\RecipeFactory::get(new Item('refined_solder'));

    $trunk->fillLoadWithComponentsForRecipe($recipe);

    expect($trunk->load->contains('name', 'refined_aluminum'))->toBeTrue()
        ->and($trunk->load->contains('name', 'scrap_lead'))->toBeTrue();

    $amalgam = $trunk->load->firstWhere('name', 'refined_aluminum');
    $bronze = $trunk->load->firstWhere('name', 'scrap_lead');


    expect($amalgam->count)->toBe(390)
        ->and($bronze->count)->toBe(390);

});

it('populates load given an item name', function () {

    $trunk = new Trunk('mk13', 9775);
    $trunk->fillLoadWithItem('recycled_waste');
    /** @var InventoryItem $waste */
    $waste = $trunk->load->first();

    expect($waste)->toBeInstanceOf(InventoryItem::class)
        ->and($waste->name)->toBe('recycled_waste')
        ->and($waste->count)->toBe(88);
});

it('calculates the total weight of items in load', function () {

    $load = [
        new InventoryItem('crafted_rebar', 100),
        new InventoryItem('crafted_computer', 100),
        new InventoryItem('crafted_copperwire', 100),
    ];
    $trunk = new Trunk('mk13', 9775, $load);

    expect($trunk->loadWeight())->toBe(10000);

});
