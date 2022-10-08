<?php

namespace App\Http\Livewire;

use App\Models\MarketOrder;
use App\TT\Items\ItemNames;
use Livewire\Component;
use Livewire\WithPagination;

class MarketOrders extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';

    public string $type = 'all';

    public array|string $itemFilter = '';

    public string $countMinFilter = '';

    public string $countMaxFilter = '';

    public string $priceMinFilter = '';

    public string $priceMaxFilter = '';

    protected $queryString = [
        'type' => ['except' => 'all'],
    ];

    public function updatingType()
    {
        $this->resetPage();
    }

    public function getItemSelectOptions()
    {
        return MarketOrder::pluck('item_name')
            ->unique()
            ->mapWithKeys(function ($itemName) {
                return [$itemName => ItemNames::getName($itemName) ?? $itemName];
            })
            ->prepend('Items:', '');
    }

    public function render()
    {
        $marketOrders = match($this->type) {
            'buy' => MarketOrder::buyOrders(),
            'sell' => MarketOrder::sellOrders(),
            default => MarketOrder::query()
        };

        if (! empty($this->itemFilter)) {
            $marketOrders->where('item_name', $this->itemFilter);
        }

        if (! empty($this->countMinFilter)) {
            $marketOrders->where('count', '>=', $this->countMinFilter);
        }

        if (! empty($this->countMaxFilter)) {
            $marketOrders->where('count', '<=', $this->countMaxFilter);
        }

        if (! empty($this->priceMinFilter)) {
            $marketOrders->where('price_each', '>=', $this->priceMinFilter);
        }

        if (! empty($this->priceMaxFilter)) {
            $marketOrders->where('price_each', '<=', $this->priceMaxFilter);
        }

        return view('livewire.market-orders')
            ->with(['allMarketOrders' => $marketOrders->paginate()])
            ->layoutData(['titleAddon' => 'Market Orders']);
    }
}
