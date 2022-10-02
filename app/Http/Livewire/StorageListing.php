<?php

namespace App\Http\Livewire;

use App\TT\Items\Weights;
use App\TT\StorageFactory;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Livewire\Component;

class StorageListing extends Component
{
    protected $listeners = [
        'refresh' => '$refresh'
    ];

    public string $storageName = 'combined';

    public int $truckCapacity;

    public string $sortBy = 'count';

    public string $itemToAddToFullTrailerAlerts = '';

    public function mount()
    {
        $lookup = collect([
            'scrap_ore',
            'scrap_emerald',
            'petrochem_petrol',
            'petrochem_propane',
            'scrap_plastic',
            'scrap_copper',
            'refined_copper',
            'refined_zinc',
        ]);
        $this->itemToAddToFullTrailerAlerts = collect(Weights::$weights)->keys()->reject(function ($name) use ($lookup) {
            return $lookup->contains($name);
        })->first();
    }

    public function addItemToFullTrailerAlerts()
    {
        Auth::user()->addItemToFullTrailerAlerts($this->itemToAddToFullTrailerAlerts);
    }

    public function removeItemFromFullTrailerAlerts(string $itemName)
    {
        Auth::user()->removeItemFromFullTrailerAlerts($itemName);
    }

    public function sync()
    {
        Cache::forget(Auth::id() . 'tt_api_storage');
        $this->emit('refresh');
    }

    public function fullTrailerAlerts(): \App\TT\Storage
    {
        $lookup = Auth::user()->full_trailer_alerts;

        return StorageFactory::get($this->storageName)
            ->whereIn('name', $lookup)
            ->filter(function ($craftingMaterial) {
                return $craftingMaterial->getTotalWeight() > $this->truckCapacity;
            })->sortByWeight();
    }

    public function render()
    {
        $storage = $this->sortBy == 'weight'
            ? StorageFactory::get($this->storageName)->sortByWeight()
            : StorageFactory::get($this->storageName)->sortByCount();

        return view('livewire.storage-listing')->with([
            'storage' => $storage->splitAndZip(),
            'sellableItems' => \App\TT\Items\SellableItem::getAllForStorage($storage)
        ]);
    }
}
