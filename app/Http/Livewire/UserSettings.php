<?php

namespace App\Http\Livewire;

use App\Models\User;
use Livewire\Component;

class UserSettings extends Component
{
    use SendsAlerts;

    public User $user;

    protected $rules = [
        'user.api_private_key' => 'string|nullable',
        'user.truckCapacity' => 'integer|nullable',
        'user.pocketCapacity' => 'integer|nullable',
        'user.trainYardCapacity'=> 'integer|nullable'
    ];

    public function updateUser()
    {
        $this->validate();
        $this->user->save();
        $this->successAlert('Details Saved.');
    }

    public function render()
    {
        return view('livewire.user-settings');
    }
}
