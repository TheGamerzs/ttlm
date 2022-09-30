<?php

namespace App\Http\Livewire;

use App\Models\User;
use Livewire\Component;

class UserSettings extends Component
{
    use SendsAlerts;

    public User $user;

    protected $rules = [
        'user.api_public_key' => 'string|nullable',
        'user.truckCapacity' => 'integer|nullable|min:400',
        'user.pocketCapacity' => 'integer|nullable|min:10',
        'user.trainYardCapacity'=> 'integer|nullable|min:10800'
    ];

    public function tryToGetTTId(): void
    {
        $this->user->setTTIdFromApi();
    }

    public function updateUser(): void
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
