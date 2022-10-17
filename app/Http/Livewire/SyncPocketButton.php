<?php

namespace App\Http\Livewire;

use App\TT\StorageFactory;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Livewire\Component;

class SyncPocketButton extends Component
{
    use SendsAlerts;

    public function sync()
    {
        if (Cache::has(Auth::id() . 'lockedApi')) {
            $this->warningAlert('You are clicking too fast.', 'There is a 10 second cool down in between attempted syncs.');
            return;
        }
        Cache::forget(Auth::id() . 'tt_api_user_data');
        StorageFactory::get();
        $this->successAlert('Personal Inventory Synced.');
        $this->emit('refresh');
    }

    public function render()
    {
        return view('livewire.sync-pocket-button');
    }
}
