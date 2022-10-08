<?php

namespace App\Http\Livewire;

use App\TT\ItemFilters\NonTruckingItemsFilter;
use App\TT\ItemFilters\TruckingItemsFilter;
use App\TT\Items\ExportableItem;
use App\TT\Items\InventoryItem;
use App\TT\Items\ItemData;
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

    public array|string $itemToAddToFullTrailerAlerts = '';

    public array $hiddenExportableInputs = [];

    public array $customStorageInput = [];

    // Filter Inputs
    public string $searchStringInput = '';

    public array|string $typeFilter = 'all';

    public string $minCountFilter = '';

    public string $maxCountFilter = '';

    public string $minTotalWeightFilter = '';

    public string $maxTotalWeightFilter = '';

    public function mount()
    {
        $this->itemToAddToFullTrailerAlerts = ItemData::getAllInternalTruckingNames()->reject(function ($name) {
            return Auth::user()->full_trailer_alerts->contains($name);
        })->first();

        $this->hiddenExportableInputs = collect(ExportableItem::$data)->map(function ($item, $key) {
            return ! Auth::user()->hidden_exportable_items->contains($key);
        })->toArray();

        StorageFactory::get();
        $this->customStorageInput = collect(StorageFactory::getRegisteredNames())
            ->reject(function ($item) {
                return collect(['combined', 'custom_combined_storage'])->contains($item);
            })
            ->mapWithKeys(function ($item, $key) {
                return [$item => Auth::user()->custom_combined_storage->contains($item)];
            })->toArray();
    }

    public function updatedHiddenExportableInputs()
    {
        Auth::user()->hidden_exportable_items = collect($this->hiddenExportableInputs)->reject()->keys();
        Auth::user()->save();
    }

    public function updatedCustomStorageInput()
    {
        Auth::user()->custom_combined_storage = collect($this->customStorageInput)->filter()->keys();
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

        if (! empty($this->minCountFilter) && is_numeric($this->minCountFilter)) {
            $pipes[] = 'App\TT\ItemFilters\MinCountFilter:' . (int) $this->minCountFilter;
        }

        if (! empty($this->maxCountFilter) && is_numeric($this->maxCountFilter)) {
            $pipes[] = 'App\TT\ItemFilters\MaxCountFilter:' . (int) $this->maxCountFilter;
        }

        if (! empty($this->minTotalWeightFilter) && is_numeric($this->minTotalWeightFilter)) {
            $pipes[] = 'App\TT\ItemFilters\MinTotalWeightFilter:' . (int) $this->minTotalWeightFilter;
        }

        if (! empty($this->maxTotalWeightFilter) && is_numeric($this->maxTotalWeightFilter)) {
            $pipes[] = 'App\TT\ItemFilters\MaxTotalWeightFilter:' . (int) $this->maxTotalWeightFilter;
        }


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
            'newStorage' => $storage->split(2),
            'exportableItems' => \App\TT\Items\ExportableItem::getAllForStorage($storage, Auth::user())
        ]);
    }
}
