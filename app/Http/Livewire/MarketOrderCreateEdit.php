<?php

namespace App\Http\Livewire;

use App\Models\MarketOrder;
use App\TT\StorageFactory;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Livewire\Component;

class MarketOrderCreateEdit extends Component
{
    use SendsAlerts;

    protected $listeners = [
        'newMarketOrder' => 'startWithItem',
        'editMarketOrder' => 'startEditing'
    ];

    public MarketOrder $marketOrder;

    public bool $expand = false;

    protected $messages = [
        'marketOrder.storage_additional.required' => 'This is required for a move order.',
        'marketOrder.count' => 'Required.',
        'marketOrder.price_each' => 'Required.',
    ];

    public function rules(): array
    {
        return [
            'marketOrder.item_name' => 'required',
            'marketOrder.count' => 'required|numeric|min:1',
            'marketOrder.price_each' => 'required|numeric|min:1',
            'marketOrder.storage' => 'required',
            'marketOrder.storage_additional' => Rule::requiredIf($this->marketOrder->type == 'move'),
            'marketOrder.type' => [
                'required',
                Rule::in(['buy', 'sell', 'move'])
            ]
        ];
    }

    public function mount(): void
    {
        $this->marketOrder = MarketOrder::make();
    }

    public function hydrate(): void
    {
        StorageFactory::get();
    }

    public function updatedMarketOrderCount($value)
    {
        $this->marketOrder->count = str($this->marketOrder->count)
            ->replace('k', '000')
            ->replace('m', '000000')
            ->toInteger();
        $this->validateOnly('marketOrder.count');
    }

    public function updatedMarketOrderPriceEach($value)
    {
        $this->marketOrder->price_each = str($this->marketOrder->price_each)
            ->replace('k', '000')
            ->replace('m', '000000')
            ->toInteger();
        $this->validateOnly('marketOrder.price_each');
    }

    public function startWithItem(string $itemName): void
    {
        $this->resetErrorBag();

        $this->marketOrder = MarketOrder::make();
        $this->marketOrder->item_name = $itemName;
        $this->marketOrder->type = 'sell';
        $this->marketOrder->storage = StorageFactory::getRegisteredNames(true, false)->keys()->first();
        $this->marketOrder->count = null;
        $this->marketOrder->price_each = null;

        $this->emit('openMarketOrderModal');
    }

    public function startEditing(MarketOrder $marketOrder): void
    {
        if ($marketOrder->user_id != Auth::id()) abort(403);

        $this->marketOrder = $marketOrder;
        $this->emit('openMarketOrderModal');
    }

    public function getItemOptionsList(): \Illuminate\Support\Collection|\App\TT\Storage
    {
        return StorageFactory::getAllItemNames(true);
    }

    public function inverseType(): string
    {
        return $this->marketOrder->type == 'sell'
            ? 'buy'
            : 'sell';
    }

    public function save(): void
    {
        if ($this->marketOrder->exists) {
            $this->update();
        } else {
            $this->create();
        }
    }

    public function create(): void
    {
        $this->validate();
        $this->marketOrder->user_id = Auth::id();
        $this->marketOrder->expires = now()->addWeek();
        $this->marketOrder->save();

        $this->successAlert('Order Created');
    }

    public function update(): void
    {
        $this->validate();

        $this->marketOrder->save();
        $this->successAlert('Order Updated');
    }

    public function render()
    {
        if ($this->marketOrder->exists) {
            $this->expand = false;
        } else {
            $inverseOrders = $this->marketOrder->findInverseOrders();
            $this->expand = (bool) $inverseOrders->count();
        }

        return view('livewire.market-order-create-edit')->with([
            'inverseOrders' => $inverseOrders ?? new EloquentCollection()
        ]);
    }
}
