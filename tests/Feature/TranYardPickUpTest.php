<?php

//recycled waste = 110kg

use App\TT\Inventories;

beforeEach(function () {
    $this->truckingInventories = (new Inventories)
        ->createTrunk('mk13', 9775)
        ->createTrunk('mk15', 6000)
        ->createTrunk('pocket', 325)
        ->createTrunk('trainYard', 65228);
});

it('constructs', function () {

    $trainYardPickUp = new \App\TT\TrainYardPickUp('recycled_waste', $this->truckingInventories, true);

    expect($trainYardPickUp->pickupName)->toBe('recycled_waste')
        ->and($trainYardPickUp->inventories)->toBeInstanceOf(Inventories::class);

    foreach ($trainYardPickUp->getTrunksExceptTrainYard() as $trunk) {
        expect($trunk->load->contains('name', 'recycled_waste'))->toBeTrue();
    }

    $trainYardTrunk = $trainYardPickUp->inventories->trunks->firstWhere('name', 'trainYard');

    foreach (['scrap_acid', 'scrap_lead', 'scrap_mercury'] as $shouldContain) {
        expect($trainYardTrunk->load->contains('name', $shouldContain))->toBeTrue();
    }

});

it('calculates the total count of pickup items that can be carried', function () {

    $trainYardPickUp = new \App\TT\TrainYardPickUp('recycled_waste', $this->truckingInventories, false);

    expect($trainYardPickUp->fullLoadCount())->toBe(144)
        ->and($trainYardPickUp->runsThatCanFitInTrainYard())->toBe(4);

});
