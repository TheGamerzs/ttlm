<?php

namespace App\Http\Livewire;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;

class GamePlan extends BaseComponent
{
    protected $listeners = [
        'addToGamePlan' => 'setTextForNewItem'
    ];

    public array|Collection $listItems = [];

    public string $newItemTextInput = '';

    public function mount(): void
    {
        $this->listItems = Auth::user()->getGamePlan();
    }

    public function addItem(): void
    {
        $this->listItems->push($this->newItemTextInput);
        Auth::user()->updateGamePlan($this->listItems);
        $this->newItemTextInput = '';
    }

    public function updateItem(int $index): void
    {
        $this->newItemTextInput = $this->listItems[$index];
        $this->removeItem($index);
    }

    public function removeItem(int $index): void
    {
        $this->listItems->forget($index);
        Auth::user()->updateGamePlan($this->listItems);
    }

    public function updateOrder($newItemList): void
    {
        $this->listItems = collect($newItemList)->mapWithKeys(function ($item) {
            return [$item['order'] - 1 => $item['value']];
        });
        Auth::user()->updateGamePlan($this->listItems);
    }

    public function clearAllItems()
    {
        Auth::user()->clearGamePlan();
        $this->listItems = collect();
    }

    public function setTextForNewItem(string $text): void
    {
        $this->newItemTextInput = $text;
        $this->emit('openGamePlan');
    }

    public function render()
    {
        return view('livewire.game-plan');
    }
}
