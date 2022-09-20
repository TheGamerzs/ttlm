<?php

namespace App\Http\Livewire;

use App\TT\Items\Item;
use App\TT\RecipeFactory;
use App\TT\ShoppingListBuilder;
use App\TT\StorageFactory;
use Livewire\Component;

class QuickInventoryCalculations extends Component
{
    public int $truckCompacity;

    public string $compacityUsed = '';

    protected array $fillTrailerLookups = [];

    public array $pickupCounts = [];

    public int $pickupCountsYield = 300;

    public string $itemName = 'house'; //used for pickup counts

    public string $storageName = 'faq_522';

    public function mount()
    {
        $this->assignTrailerLookups();
        $this->buildPickupCounts();
    }

    protected function buildPickupCounts(): void
    {
        $this->pickupCounts = ShoppingListBuilder::build(
            RecipeFactory::get(new Item($this->itemName)),
            StorageFactory::get($this->storageName),
            (int) $this->pickupCountsYield,
            $this->truckCompacity
        )['pickupCalculator']
            ->getRunCalculations()
            ->toArray();
    }

    public function updatedItemName()
    {
        $this->buildPickupCounts();
    }

    public function updatedPickupCountsYield()
    {
        $this->buildPickupCounts();
    }

    public function updatedStorageName()
    {
        $this->buildPickupCounts();
    }

    public function hydrate()
    {
        $this->assignTrailerLookups();
    }

    public function assignTrailerLookups()
    {
        $this->fillTrailerLookups = [
            new Item('scrap_ore'),
            new Item('scrap_plastic'),
            new Item('scrap_emerald'),
            new Item('refined_planks'),
            new Item('scrap_copper'),
            new Item('refined_flint')
        ];
    }

    public function render()
    {
        return view('livewire.quick-inventory-calculations')->with([
            'fillTrailerLookups' => $this->fillTrailerLookups
        ]);
    }
}
