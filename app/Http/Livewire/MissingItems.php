<?php

namespace App\Http\Livewire;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Livewire\Component;

class MissingItems extends Component
{
    public function clearIgnore()
    {
        Cache::forget('ignoreMissing');
    }

    public function clearAll()
    {
        Cache::forget('missingItemNames');
        Cache::forget('missingItems');
        Cache::forget('ignoreMissing');
    }

    public function addToIgnore(string $itemName)
    {
        /** @var Collection $missingItemsIgnoring */
        $missingItemsIgnoring = Cache::get('ignoreMissing', collect());
        if (! $missingItemsIgnoring->contains($itemName)) {
            $missingItemsIgnoring->push($itemName);
            Cache::put('ignoreMissing', $missingItemsIgnoring);
        }
    }

    public function deleteFromAll(string $itemName) {
        Cache::put('missingItemNames',
            Cache::get('missingItemNames')->reject(function ($name) use ($itemName) {
                return $name == $itemName;
            })
        );

        Cache::put('missingItems',
            Cache::get('missingItems')->reject(function ($name) use ($itemName) {
                return $name == $itemName;
            })
        );

        Cache::put('ignoreMissing',
            Cache::get('ignoreMissing', collect())->reject(function ($name) use ($itemName) {
                return $name == $itemName;
            })
        );

    }

    public function render()
    {
        /** @var Collection $names */
        $names   = Cache::get('missingItemNames', collect());
        $weights = Cache::get('missingItems', collect());
        $ignore  = Cache::get('ignoreMissing', collect());

        $items = $names->merge($weights)->unique()->reject(function ($name) use ($ignore) {
                return $ignore->contains($name);
            })
            ->reject(function ($name) {
                return str($name)->startsWith('note');
            })
            ->sort();

        return view('livewire.missing-items')
            ->layoutData(['titleAddon' => 'Missing Items'])
            ->with(['items' => $items]);
    }
}
