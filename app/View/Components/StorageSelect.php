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
        $lookup = [
            'biz_granny' => 'Grandmas House',
            'biz_yellowjack' => 'Yellowjack',
            'biz_hookies' => 'Hookies',
            'faq_522' => 'Faction',
            'gohq' => 'Oil Refinery',
            'combined' => 'Combined',
            'biz_train' => 'Train Yard'
        ];

        if (array_key_exists($name, $lookup)) {
            return $lookup[$name];
        }
        return $name;
    }

    public function render(): View
    {
        return view('components.storage-select');
    }
}
