<?php

namespace App\View\MarketOrder\Types;

interface MarketOrderViewModelInterface
{
    public function storageOneLabel(): string;

    public function showStorageTwoColumn(): bool;

    public function storageTwoLabel(): string;

    public function showTypeColumn(): bool;

    public function priceEachLabel(): string;

}
