<?php

namespace App\Http\Livewire;

use App\TT\Items\Item;
use App\TT\RecipeFactory;
use App\TT\ShoppingListBuilder;
use App\TT\StorageFactory;
use App\TT\TrainYardPickUp;
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

    public string $itemForFillTrailer = 'scrap_ore';

    /*
    |--------------------------------------------------------------------------
    | Pickup Counts Required To Make
    |--------------------------------------------------------------------------
    */

    public string $pickupCountsYield = '300';

    public string $itemName = 'house'; //used for pickup counts

    public string $storageName = 'combined';

    public array $pickupCounts = [];


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
        Session::put('pickUpCountsStorage', $value);
        $this->buildPickupCounts();
    }

    /*
    |--------------------------------------------------------------------------
    | Business
    |--------------------------------------------------------------------------
    */

    protected function buildPickupCounts(): void
    {
//        dump($this->itemName, $this->storageName, $this->pickupCountsYield, $this->truckCapacity);
        $this->pickupCounts = ShoppingListBuilder::build(
            RecipeFactory::get(new Item($this->itemName)),
            StorageFactory::get($this->storageName),
            (int)$this->pickupCountsYield,
            $this->truckCapacity
        )['pickupCalculator']
            ->getRunCalculations()
            ->filter()
            ->toArray();
    }

    protected function trainYardPickups(): array
    {
        return [
            new TrainYardPickUp('recycled_electronics', $this->truckCapacity, $this->pocketCapacity, $this->trainYardStorage),
            new TrainYardPickUp('recycled_waste', $this->truckCapacity, $this->pocketCapacity, $this->trainYardStorage),
            new TrainYardPickUp('recycled_trash', $this->truckCapacity, $this->pocketCapacity, $this->trainYardStorage),
        ];
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
