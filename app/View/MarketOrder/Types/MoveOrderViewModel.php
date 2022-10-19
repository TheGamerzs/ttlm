<?php

namespace App\View\MarketOrder\Types;

class MoveOrderViewModel implements MarketOrderViewModelInterface
{
    public function storageOneLabel(): string
    {
        return 'Moving From';
    }

    public function showStorageTwoColumn(): bool
    {
        return true;
    }

    public function storageTwoLabel(): string
    {
        return 'Moving To';
    }

    public function showTypeColumn(): bool
    {
        return false;
    }

    public function priceEachLabel(): string
    {
        return 'Price / KG';
    }
}
