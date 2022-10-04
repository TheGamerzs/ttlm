<?php

it('returns a total value given a count', function () {

//    'scrap_emerald' => [
//        'each' => 25000,
//        'location' => 'Jewelry Store'
//    ],

    $scrapEmerald = new \App\TT\Items\ExportableItem('scrap_emerald', 1);

    expect($scrapEmerald->getValueFor(1))->toBe(25000)
        ->and($scrapEmerald->getValueFor(2))->toBe(50000);

});

it('returns a the value of the full stack', function () {

    $scrapEmerald = new \App\TT\Items\ExportableItem('scrap_emerald', 6);

    expect($scrapEmerald->totalValue())->toBe(25000*6);

});
