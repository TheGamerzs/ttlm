<?php

namespace App\Http\Livewire;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Livewire\Component;

class UserSettings extends Component
{
    use SendsAlerts;

    public User $user;

    protected $rules = [
        'user.api_public_key' => 'string|nullable',
        'user.truckCapacity' => 'integer|nullable|min:400',
        'user.pocketCapacity' => 'integer|nullable|min:10',
        'user.trainYardCapacity'=> 'integer|nullable|min:10800',
        'user.default_crafting_recipe' => 'string|required',
        'user.dark_mode' => 'boolean|required',
        'user.auto_delist_market_orders' => 'boolean|required'
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

    public function getCharges(): string
    {
        if (Auth::id() < 3) {
            $chargesResponse = Http::withHeaders(['X-Tycoon-Key' => config('app.tt_api_private_key')])
                ->get('v1.api.tycoon.community/main/charges.json');
            return number_format(json_decode($chargesResponse->body())[0]);
        }
        return 0;
    }

    public function render()
    {
        return view('livewire.user-settings');
    }
}
