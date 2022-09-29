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
        'user.truckCapacity' => 'integer|nullable|min:0',
        'user.pocketCapacity' => 'integer|nullable|min:0',
        'user.trainYardCapacity'=> 'integer|nullable|min:0'
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
