<?php

use App\TT\Storage;

it('adds a needed count', function () {

    $calc = new \App\TT\Pickup\PickupRunCalculator(9775, new Storage());
    $calc->addNeededCount('itemOne', 200);

    expect($calc->neededCounts['itemOne']['itemName'])->toBe('itemOne')
        ->and($calc->neededCounts['itemOne']['needed'])->toBe(200);

});

it('increases the needed amount for a count already in array', function () {

    $calc = new \App\TT\Pickup\PickupRunCalculator(9775, new Storage());
    $calc->addNeededCount('itemOne', 200);
    $calc->addNeededCount('itemOne', 200);

    expect($calc->neededCounts['itemOne']['needed'])->toBe(400);

});

it('calculates a water run', function () {

    // Unfiltered Water = 100kg
    // Acid = 5kg
    // With a truck capacity of 300kg, given needed 4 Filtered Water, expect 2 runs.
    $calc = new \App\TT\Pickup\PickupRunCalculator(300, new Storage());
    $calc->addNeededCount('liquid_water_raw', 4);
    $results = $calc->getRunCalculations();
    expect($results['liquid_water_raw'])->toBe(2);

});

it('calculates sawdust', function () {

    // Log = 60kg
    // 1 Log = 10 sawdust
    // Given truck capacity of 60kg (1 log), 22 needed sawdust, expect 3 runs.

    $calc = new \App\TT\Pickup\PickupRunCalculator(60, new Storage());
    $calc->addNeededCount('tcargodust', 22);
    $results = $calc->getRunCalculations();
    expect($results['tcargodust'])->toBe(3);

});

it('calculates planks and sawdust', function () {

    // Log = 60kg
    // 1 Log = 1 plank and 2 sawdust
    // Given truck capacity of 60kg (1 log), 5 needed planks, expect 5 plank runs.
    // Those 5 runs will also yield 10 sawdust, given 15 needed, also expect 1 sawdust run to cover the remaining 5 needed.

    $calc = new \App\TT\Pickup\PickupRunCalculator(60, new Storage());
    $calc->addNeededCount('tcargodust', 15);
    $calc->addNeededCount('refined_planks', 5);
    $results = $calc->getRunCalculations();
    expect($results['tcargodust'])->toBe(1)
        ->and($results['refined_planks'])->toBe(5);

});

test('easy calculation method using electronics runs based on a single need', function () {

//    'electronics' => [
//        'yields' => [
//            'scrap_copper' => 8,
//            'scrap_plastic' => 12,
//            'scrap_gold' => 1,
//        ],
//        'baseWeight' => 130
    $calc = new \App\TT\Pickup\PickupRunCalculator(130, new Storage());
    $calc->addNeededCount('scrap_copper', 16);
    expect($calc->getRunCalculations()['recycled_electronics'])->toBe(2);

    $calc = new \App\TT\Pickup\PickupRunCalculator(130, new Storage());
    $calc->addNeededCount('scrap_plastic', 25);
    expect($calc->getRunCalculations()['recycled_electronics'])->toBe(3);

    $calc = new \App\TT\Pickup\PickupRunCalculator(130, new Storage());
    $calc->addNeededCount('scrap_gold', 4);
    expect($calc->getRunCalculations()['recycled_electronics'])->toBe(4);

});

test('easy calculation method using electronics run based on multiple needs', function () {

    // With multiple needs, calculate each and return the highest one.
    // Given a need for 5 gold (5 runs) 20 copper (3 runs) and 24 plastic (2 runs), expect 5 runs needed.
    $calc = new \App\TT\Pickup\PickupRunCalculator(130, new Storage());
    $calc->addNeededCount('scrap_gold', 5);
    $calc->addNeededCount('scrap_copper', 20);
    $calc->addNeededCount('scrap_plastic', 24);
    expect($calc->getRunCalculations()['recycled_electronics'])->toBe(5);

});
