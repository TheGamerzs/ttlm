<?php

namespace App\View\MarketOrder\Types;

class AllOrdersViewModel implements MarketOrderViewModelInterface
{
    public function storageOneLabel(): string
    {
        return 'Storage One';
    }

    public function showStorageTwoColumn(): bool
    {
        return true;
    }

    public function storageTwoLabel(): string
    {
        return 'Storage Two';
    }

    public function showTypeColumn(): bool
    {
        return true;
    }

    public function priceEachLabel(): string
    {
        return 'Price Each';
    }
}
