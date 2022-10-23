<?php

namespace App\Http\Livewire;

use App\Models\User;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Livewire\Component;

class MarketOrderShow extends Component
{
    public User $user;

    public function render()
    {
        $orders = $this->user->load('marketOrders')->marketOrders->groupBy('type');

        // inject empty collections where they don't exist.
        foreach (['buy', 'sell', 'move'] as $type) {
            if (! $orders->keys()->contains($type)) {
                $orders->put($type, new EloquentCollection);
            }
        }

        return view('livewire.market-order-show')
            ->with(['orders' => $orders])
            ->layoutData(['titleAddon' => $this->user->name . "'s Market Orders"]);
    }
}
