<?php

namespace App\View\MarketOrder\Types;

class SellOrderViewModel implements MarketOrderViewModelInterface
{

    public function storageOneLabel(): string
    {
        return "Located In";
    }

    public function showStorageTwoColumn(): bool
    {
        return false;
    }

    public function storageTwoLabel(): string
    {
        return '';
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
