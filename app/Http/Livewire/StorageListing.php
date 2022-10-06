<?php

namespace App\Http\Livewire;

use App\TT\ItemFilters\NonTruckingItemsFilter;
use App\TT\ItemFilters\TruckingItemsFilter;
use App\TT\Items\ExportableItem;
use App\TT\Items\InventoryItem;
use App\TT\Items\Weights;
use App\TT\StorageFactory;
use Illuminate\Pipeline\Pipeline;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;
use Livewire\Component;

class StorageListing extends Component
{
    protected $listeners = [
        'refresh' => '$refresh'
    ];

    public array|string $storageName = 'combined';

    public int $truckCapacity;

    public string $sortBy = 'count';

    public string $itemToAddToFullTrailerAlerts = '';

    public array $hiddenExportableInputs = [];

    public string $searchStringInput = '';

    public array|string $typeFilter = 'all';

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

        $this->hiddenExportableInputs = collect(ExportableItem::$data)->mapWithKeys(function ($item, $key) {
            return [$key => ! Auth::user()->hidden_exportable_items->contains($key)];
        })->toArray();
    }

    public function updatedHiddenExportableInputs()
    {
        Auth::user()->hidden_exportable_items = collect($this->hiddenExportableInputs)->reject()->keys();
        Auth::user()->save();
    }

    public function addItemToFullTrailerAlerts()
    {
        Auth::user()->addItemToFullTrailerAlerts($this->itemToAddToFullTrailerAlerts);
    }

    public function removeItemFromFullTrailerAlerts(string $itemName)
    {
        Auth::user()->removeItemFromFullTrailerAlerts($itemName);
    }

    public function getTypeFilterOptions(): Collection
    {
        return collect([
            'all' => 'All',
            'trucking' => 'Only Trucking',
            'non_trucking' => 'Except Trucking',
            'scrap' => 'Trucking: Scrap',
            'refined' => 'Trucking: Refined',
            'crafted' => 'Trucking: Crafted',
            'recycled' => 'Trucking: Recycled (Pick Ups)',
            'fridge' => 'Trucking: Refrigeration',
            'mechanicals' => 'Trucking: Vehicle Components',
            'petrochem' => 'Trucking: Petrochemical',
            'military' => 'Trucking: Military',
            'vehicle_shipment' => 'Vehicle Shipments',
            'upgrade_kit' => 'Upgrade Kits'
        ]);
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

    protected function buildFilterPipes(): array
    {
        $pipes = [
            'App\TT\ItemFilters\SortBy:' . $this->sortBy,
            'App\TT\ItemFilters\NameByStringFilter:' . $this->searchStringInput,
        ];

        // Validation check that user didn't mess with html, or no type filter needed.
        if (! $this->getTypeFilterOptions()->keys()->contains($this->typeFilter) || $this->typeFilter == 'all')
            return $pipes;

        $pipes[] = match($this->typeFilter) {
            'trucking' => TruckingItemsFilter::class,
            'non_trucking' => NonTruckingItemsFilter::class,
            default => 'App\TT\ItemFilters\InternalNameStartsWithFilter:' . $this->typeFilter
        };

        return $pipes;
    }

    public function render()
    {
        $storage = StorageFactory::get($this->storageName);

        $storage = app(Pipeline::class)
            ->send($storage)
            ->through($this->buildFilterPipes())
            ->thenReturn();

        return view('livewire.storage-listing')->with([
            'storage' => $storage->splitAndZip(),
            'exportableItems' => \App\TT\Items\ExportableItem::getAllForStorage($storage, Auth::user())
        ]);
    }
}
