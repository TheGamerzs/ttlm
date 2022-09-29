<?php

namespace App\View\Components;

use App\TT\StorageFactory;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class StorageSelect extends Component
{
    public function storageNames(): array
    {
        return StorageFactory::getRegisteredNames();
    }

    public function prettyName(string $name): string
    {
        return StorageFactory::getPrettyName($name);
    }

    public function render(): View
    {
        return view('components.storage-select');
    }
}
