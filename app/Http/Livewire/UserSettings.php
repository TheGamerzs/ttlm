<?php

namespace App\Http\Livewire;

use App\Models\User;
use Livewire\Component;

class UserSettings extends Component
{
    use SendsAlerts;

    public User $user;

    protected $rules = [
        'user.api_private_key' => 'required|string',
        'user.truckCompacity' => 'integer|nullable',
        'user.pocketCompacity' => 'integer|nullable',
        'user.trainYardCompacity'=> 'integer|nullable'
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
