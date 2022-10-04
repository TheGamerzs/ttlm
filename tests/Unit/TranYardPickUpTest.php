<?php

//recycled waste = 110kg

test('pickupItemsCountTrailer method', function () {

    $trainYardPickUp = new \App\TT\TrainYardPickUp('recycled_waste', 220, true);

    expect($trainYardPickUp->pickupItemsCountTrailer())->toBe(2);

});

test('pickupItemsCountPocket method', function () {

    $trainYardPickUp = new \App\TT\TrainYardPickUp('recycled_waste', 9775, true, 330);

    expect($trainYardPickUp->pickupItemsCountPocket())->toBe(3);

});

test('pickupItemRefinedWeight method', function () {

    $trainYardPickUp = new \App\TT\TrainYardPickUp('recycled_waste', 9775, true, 330);

    expect($trainYardPickUp->pickupItemRefinedWeight())->toBe(80);

});

test('leftoverWeightNeededForFirstRefine method', function () {

    $trainYardPickUp = new \App\TT\TrainYardPickUp('recycled_waste', 9775, true, 330);

    expect($trainYardPickUp->leftoverWeightNeededForFirstRefine())->toBe(7280);

});

test('usableStorageCapacity method', function () {

    $trainYardPickUp = new \App\TT\TrainYardPickUp('recycled_waste', 9775, true, 330);

    expect($trainYardPickUp->usableStorageCapacity())->toBe(22827);

});

test('oneRunTotalWeight method', function () {

    $trainYardPickUp = new \App\TT\TrainYardPickUp('recycled_waste', 9775, true, 330);

    expect($trainYardPickUp->oneRunTotalWeight())->toBe(10010);

});

test('howManyTimesTrainYardCanBeUsed method', function () {

    $trainYardPickUp = new \App\TT\TrainYardPickUp('recycled_waste', 9775, true, 330);

    expect($trainYardPickUp->howManyTimesTrainYardCanBeUsed())->toBe(2);

});
