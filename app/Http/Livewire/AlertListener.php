<?php

namespace App\Http\Livewire;

use Illuminate\Support\Facades\Session;

class AlertListener extends BaseComponent
{
    use SendsAlerts;

    protected array $alert = [];

    public function mount()
    {
        if (Session::has('failedApiAlert')) {
            $this->alert = [
                'title' => 'You can not make API Calls',
                'message' => 'Your Transport Tycoon ID needs to be set.',
                'type' => 'error'
            ];
        }
        if (Session::has('noCapacitiesSetAlert')) {
            $this->alert = [
                'title' => 'Capacities Needed',
                'message' => 'That page requires a Trucking Capacity and Pocket Capacity to function correctly. Please update it here and try again.',
                'type' => 'error'
            ];
        }
        if (Session::has('cantGetTTApiAlert')) {
            $this->alert = [
                'title' => 'Discord Not Linked To TT',
                'message' => 'Please Visit the TT discord to link your discord account to your TT account.',
                'type' => 'error'
            ];
        }
    }

    public function render()
    {
        return view('livewire.alert-listener')->with(['alert' => $this->alert]);
    }

}
