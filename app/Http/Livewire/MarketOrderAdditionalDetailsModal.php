<?php

namespace App\Http\Livewire;

use App\Models\MarketOrder;
use Livewire\Component;

class MarketOrderAdditionalDetailsModal extends Component
{
    public MarketOrder $marketOrder;

    protected $listeners = [
        'openDetailsModal' => 'show'
    ];

    public function show(MarketOrder $marketOrder)
    {
        $this->marketOrder = $marketOrder;
        $this->emit('showMarketOrderDetailsModal');
    }

    public function render()
    {
        return view('livewire.market-order-additional-details-modal');
    }
}
