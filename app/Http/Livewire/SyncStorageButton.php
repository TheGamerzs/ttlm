<?php

namespace App\Http\Livewire;

use App\TT\StorageFactory;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Livewire\Component;

class SyncStorageButton extends Component
{
    public function sync()
    {
        Cache::forget(Auth::id() . 'tt_api_storage');
        StorageFactory::get();
        $this->emit('refresh');
    }

    public function getApiChargesRemaining()
    {
        return Cache::get(Auth::id() . 'api_charges');
    }

    public function render()
    {
        return view('livewire.sync-storage-button');
    }
}
