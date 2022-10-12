<?php

namespace App\Http\Livewire;

use App\TT\Items\Item;
use App\TT\Items\ItemData;
use App\TT\RecipeFactory;
use App\TT\ShoppingListBuilder;
use App\TT\StorageFactory;
use App\TT\TrainYardPickUp;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Session;
use Livewire\Component;

class QuickInventoryCalculations extends Component
{
    protected $listeners = [
        'refresh' => '$refresh'
    ];

    /*
    |--------------------------------------------------------------------------
    | Single Item Capacity
    |--------------------------------------------------------------------------
    */

    public int $truckCapacity;

    public ?int $trainYardStorage;

    public int $pocketCapacity;

    public string $capacityUsed = '';

    public string $capacityUsedTY = '';

    public array|string $itemForFillTrailer = 'scrap_ore';

    public bool $leaveRoomForProcessed = true;

    /*
    |--------------------------------------------------------------------------
    | Pickup Counts Required To Make
    |--------------------------------------------------------------------------
    */

    public string $pickupCountsYield = '300';

    public array|string $itemName = 'house'; //used for pickup counts

    public array|string $storageName = 'combined';

    public array $pickupCounts = [];

    /*
    |--------------------------------------------------------------------------
    | Lifecycle
    |--------------------------------------------------------------------------
    */

    public function mount()
    {
        $this->itemName          = Session::get('pickUpCountsItem', 'house');
        $this->pickupCountsYield = Session::get('pickUpCountsYield', 100);
        $this->storageName       = Session::get('pickUpCountsStorage', 'combined');
        $this->buildPickupCounts();
    }

    public function updatedItemName($value)
    {
        Session::put('pickUpCountsItem', $value);
        $this->buildPickupCounts();
    }

    public function updatedPickupCountsYield($value)
    {
        Session::put('pickUpCountsYield', $value);
        $this->buildPickupCounts();
    }

    public function updatedStorageName($value)
    {
        if (is_string($this->storageName)) {
            Session::put('pickUpCountsStorage', $value);
            $this->buildPickupCounts();
        }
    }

    /*
    |--------------------------------------------------------------------------
    | Business
    |--------------------------------------------------------------------------
    */

    protected function buildPickupCounts(): void
    {
        // Livewire is doing some bullshit of assigning an array, then a string, and this is getting called both times.
        if (! is_string($this->itemName)) return;

        $this->pickupCounts = ShoppingListBuilder::build(
            RecipeFactory::get(new Item($this->itemName)),
            StorageFactory::get($this->storageName),
            (int)$this->pickupCountsYield,
            $this->truckCapacity
        )['pickupCalculator']
            ->getRunCalculations()
            ->filter(function ($count, $name) {
                return $count > 0;
            })
            ->toArray();
    }

    protected function trainYardPickups(): array
    {
        return [
            new TrainYardPickUp('recycled_electronics',
                $this->truckCapacity,
                $this->leaveRoomForProcessed,
                $this->pocketCapacity,
                $this->trainYardStorage - (int)$this->capacityUsedTY
            ),
            new TrainYardPickUp('recycled_waste',
                $this->truckCapacity,
                $this->leaveRoomForProcessed,
                $this->pocketCapacity,
                $this->trainYardStorage - (int)$this->capacityUsedTY
            ),
            new TrainYardPickUp('recycled_trash',
                $this->truckCapacity,
                $this->leaveRoomForProcessed,
                $this->pocketCapacity,
                $this->trainYardStorage - (int)$this->capacityUsedTY
            ),
            new TrainYardPickUp('petrochem_gas',
                $this->truckCapacity,
                $this->leaveRoomForProcessed,
                $this->pocketCapacity,
                $this->trainYardStorage - (int)$this->capacityUsedTY
            ),
            new TrainYardPickUp('petrochem_oil',
                $this->truckCapacity,
                $this->leaveRoomForProcessed,
                $this->pocketCapacity,
                $this->trainYardStorage - (int)$this->capacityUsedTY
            ),
        ];
    }

    public function getItemNamesThatExistInStorage(): Collection
    {
        return StorageFactory::get('combined')->pluck('name')->mapWithKeys(function ($internalName) {
            return [$internalName => ItemData::getName($internalName)];
        })->sort();
    }


    /*
    |--------------------------------------------------------------------------
    | Render
    |--------------------------------------------------------------------------
    */

    public function render()
    {
        return view('livewire.quick-inventory-calculations')->with([
            'trailerLookupItem' => new Item($this->itemForFillTrailer),
            'trainYardPickups' => $this->trainYardPickups()
        ]);
    }
}
