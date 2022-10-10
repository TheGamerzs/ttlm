<?php

namespace App\Http\Livewire;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Livewire\Component;

class MissingItems extends Component
{
    public function addToIgnore(string $itemName)
    {
        /** @var Collection $missingItemsIgnoring */
        $missingItemsIgnoring = Cache::get('ignoreMissing', collect());
        if (! $missingItemsIgnoring->contains($itemName)) {
            $missingItemsIgnoring->push($itemName);
            Cache::put('ignoreMissing', $missingItemsIgnoring);
        }
    }

    public function render()
    {
        /** @var Collection $names */
        $names   = Cache::get('missingItemsNames');
        $weights = Cache::get('missingItems');
        $ignore  = Cache::get('ignoreMissing', collect());

        $items = $names->merge($weights)->unique()->reject(function ($name) use ($ignore) {
            return $ignore->contains($name);
        });

        return view('livewire.missing-items')
            ->layoutData(['titleAddon' => 'Missing Items'])
            ->with(['items' => $items]);
    }
}
