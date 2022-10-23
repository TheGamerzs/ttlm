<?php

namespace App\Http\Livewire;

use App\Models\MarketOrder;
use App\Models\Scopes\ExpiredScope;
use App\TT\StorageFactory;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class MarketOrderCreateEdit extends BaseComponent
{
    use SendsAlerts;

    protected $listeners = [
        'newMarketOrder' => 'startWithItem',
        'editMarketOrder' => 'startEditing',
        'deleteMarketOrder' => 'delete'
    ];

    public MarketOrder $marketOrder;

    public bool $expand = false;

    public bool $warn = false;

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
            'marketOrder.details' => 'nullable|string',
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
        $this->warn = false;
    }

    public function updatedMarketOrderPriceEach($value)
    {
        $this->marketOrder->price_each = str($this->marketOrder->price_each)
            ->replace('k', '000')
            ->replace('m', '000000')
            ->toInteger();
        $this->validateOnly('marketOrder.price_each');
    }

    public function updatedMarketOrderStorage()
    {
        $this->warn = false;
    }

    public function startWithItem(string $itemName = null, string $count = null): void
    {
        $this->resetErrorBag();
        $this->warn = false;

        $this->marketOrder = MarketOrder::make();
        $this->marketOrder->item_name = $itemName ?? StorageFactory::getAllItemNames()->first();
        $this->marketOrder->type = 'sell';

        $this->marketOrder->storage =
            StorageFactory::guessStorageForItem($this->marketOrder->item_name)
            ?? StorageFactory::getRegisteredNames(true, false)->keys()->first();

        $this->marketOrder->count = $count ?? 1;
        $this->marketOrder->price_each = null;

        $this->emit('openMarketOrderModal');
    }

    public function startEditing(int $marketOrderId): void
    {
        $marketOrder = MarketOrder::withoutGlobalScope(ExpiredScope::class)->find($marketOrderId);
        if ($marketOrder->user_id != Auth::id()) abort(403);

        $this->warn = false;
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

    public function save(bool $bypassWarning = false): void
    {
        $storage = StorageFactory::get($this->marketOrder->storage);
        $inventoryItem = $storage->firstWhere('name', $this->marketOrder->item_name);

        if (! $bypassWarning) {
            if (is_null($inventoryItem) || $inventoryItem->count < $this->marketOrder->count) {
                $this->warn = true;
                return;
            }
        }

        if ($this->marketOrder->exists) {
            $this->update();
        } else {
            $this->create();
        }

        $this->emit('refreshMarketOrderIndex');
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

        $this->marketOrder->expires = now()->addWeek();
        $this->marketOrder->save();

        $this->successAlert('Order Updated');
    }

    public function confirmDelete(): void
    {
        $this->askToConfirmDelete('deleteMarketOrder');
    }

    public function delete(): void
    {
        $this->marketOrder->delete();
        $this->marketOrder = MarketOrder::make();
        $this->successAlert('Deleted.');
        $this->emit('closeMarketOrderModal');
        $this->warn = false;
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
