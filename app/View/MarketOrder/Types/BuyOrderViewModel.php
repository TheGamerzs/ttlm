<?php

namespace App\View\MarketOrder\Types;

class BuyOrderViewModel implements MarketOrderViewModelInterface
{
    public function storageOneLabel(): string
    {
        return 'Preferred Storage';
    }

    public function showStorageTwoColumn(): bool
    {
        return true;
    }

    public function storageTwoLabel(): string
    {
        return 'Alternate Storage';
    }

    public function showTypeColumn(): bool
    {
        return false;
    }

    public function priceEachLabel(): string
    {
        return 'Price Each';
    }
}
