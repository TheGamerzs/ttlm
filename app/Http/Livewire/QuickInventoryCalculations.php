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

    public int $truckCompacity;

    public string $compacityUsed = '';

    public string $itemForFillTrailer = 'scrap_ore';

    public array $pickupCounts = [];

    public int $pickupCountsYield = 300;

    public string $itemName = 'house'; //used for pickup counts

    public string $storageName = 'faq_522';

    public int $trainYardStorage = 30107;

    public function mount()
    {
        $this->itemName          = Session::get('pickUpCountsItem', 'house');
        $this->pickupCountsYield = Session::get('pickUpCountsYield', 100);
        $this->storageName       = Session::get('pickUpCountsStorage', 'combined');
        $this->buildPickupCounts();
    }

    protected function buildPickupCounts(): void
    {
//        dump($this->itemName, $this->storageName, $this->pickupCountsYield, $this->truckCompacity);
        $this->pickupCounts = ShoppingListBuilder::build(
            RecipeFactory::get(new Item($this->itemName)),
            StorageFactory::get($this->storageName),
            (int)$this->pickupCountsYield,
            $this->truckCompacity
        )['pickupCalculator']
            ->getRunCalculations()
            ->filter()
            ->toArray();
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

    protected function trainYardPickups(): array
    {
        return [
            new TrainYardPickUp('recycled_electronics', $this->truckCompacity, 600, $this->trainYardStorage),
            new TrainYardPickUp('recycled_waste', $this->truckCompacity, 600, $this->trainYardStorage),
            new TrainYardPickUp('recycled_trash', $this->truckCompacity, 600, $this->trainYardStorage),
        ];
    }

    public function render()
    {
        return view('livewire.quick-inventory-calculations')->with([
            'trailerLookupItem' => new Item($this->itemForFillTrailer),
            'trainYardPickups' => $this->trainYardPickups()
        ]);
    }
}
