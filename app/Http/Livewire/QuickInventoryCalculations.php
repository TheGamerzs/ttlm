<?php

namespace App\Http\Livewire;

use App\TT\Factories\ItemFactory;
use App\TT\Items\ItemData;
use App\TT\Pickup\PickupRunCounts;
use App\TT\StorageFactory;
use App\TT\TrainYardPickUp;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class QuickInventoryCalculations extends BaseComponent
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

    public string $capacityUsedTwo = '';

    public string $capacityUsedTY = '';

    public string $itemForFillTrailer = 'scrap_ore';

    public bool $leaveRoomForProcessed = true;

    /*
    |--------------------------------------------------------------------------
    | Pickup Counts Required To Make
    |--------------------------------------------------------------------------
    */

    public string $pickupCountsYield = '300';

    public string $itemName = 'house'; //used for pickup counts

    public string $storageName = 'combined';

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

    protected function buildPickupCounts(): PickupRunCounts
    {
        $counts = new PickupRunCounts(
            Auth::user()->makeTruckingInventories(),
            $this->itemName,
            $this->pickupCountsYield
        );
        return $counts->setStorageName($this->storageName);
    }

    protected function trainYardPickups(): Collection
    {
        $baseTrunks = Auth::user()->makeInventories()
            ->setCapacityUsed('trainYard', (int) $this->capacityUsedTY);

        return collect([
                'recycled_electronics',
                'recycled_waste',
                'recycled_trash',
                'petrochem_gas',
                'petrochem_oil',
            ])
            ->map(function ($runName) use ($baseTrunks) {
                return new TrainYardPickUp(
                    $runName,
                    Auth::user()->makeInventories()->setCapacityUsed('trainYard', (int) $this->capacityUsedTY),
                    $this->leaveRoomForProcessed
                );
            });
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
        $user            = Auth::user();
        $userInventories = $user->makeInventories()
            ->setCapacityUsed($user->trailer_name, (int)$this->capacityUsed)
            ->setCapacityUsed($user->trailer_two_name, (int)$this->capacityUsedTwo)
            ->setCapacityUsed('trainYard', (int)$this->capacityUsedTY);


        return view('livewire.quick-inventory-calculations')->with([
            'trailerLookupItem' => ItemFactory::make($this->itemForFillTrailer),
            'trainYardPickups'  => $this->trainYardPickups(),
            'userInventories'   => $userInventories,
            'pickupCounts' => $this->buildPickupCounts()
        ]);
    }
}
