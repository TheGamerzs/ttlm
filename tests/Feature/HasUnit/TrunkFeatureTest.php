<?php

use App\TT\Items\InventoryItem;
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
