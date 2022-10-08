<?php

namespace App\Http\Livewire;

use App\Models\MarketOrder;
use Livewire\Component;
use Livewire\WithPagination;

class MarketOrders extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';

    public string $type = 'all';

    protected $queryString = [
        'type' => ['except' => 'all'],
    ];

    public function updatingType()
    {
        $this->resetPage();
    }

    public function render()
    {
        $marketOrders = match($this->type) {
            'buy' => MarketOrder::buyOrders(),
            'sell' => MarketOrder::sellOrders(),
            default => MarketOrder::query()
        };

        return view('livewire.market-orders')
            ->with(['allMarketOrders' => $marketOrders->paginate()])
            ->layoutData(['titleAddon' => 'Market Orders']);
    }
}
