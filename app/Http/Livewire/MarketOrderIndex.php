<?php

namespace App\Http\Livewire;

use App\Models\MarketOrder;
use App\TT\Items\ItemData;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class MarketOrderIndex extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';

    public string $type = 'all';

    public array|string $itemFilter = '';

    public string $countMinFilter = '';

    public string $countMaxFilter = '';

    public string $priceMinFilter = '';

    public string $priceMaxFilter = '';

    protected $queryString = [
        'type' => ['except' => 'all'],
        'itemFilter' => ['except' => ''],
        'countMinFilter' => ['except' => ''],
        'countMaxFilter' => ['except' => ''],
        'priceMinFilter' => ['except' => ''],
        'priceMaxFilter' => ['except' => ''],
    ];

    public function mount()
    {
        $this->checkAuthForType();
    }

    public function updatedType()
    {
        $this->checkAuthForType();
        $this->resetPage();
    }

    protected function checkAuthForType(): void
    {
        if ($this->type == 'mine' && ! Auth::check()) abort(403);
    }

    public function getItemSelectOptions(): Collection
    {
        return MarketOrder::pluck('item_name')
            ->unique()
            ->mapWithKeys(function ($internalName) {
                return [$internalName => ItemData::getName($internalName)];
            })
            ->sortBy(function ($prettyName) {
                return $prettyName;
            })
            ->prepend('All', '');
    }

    public function closeOrder(MarketOrder $marketOrder): void
    {
        if ($marketOrder->user_id != Auth::id()) abort(403);

        $marketOrder->delete();
    }

    public function render()
    {
        $marketOrders = match($this->type) {
            'buy'   => MarketOrder::buyOrders(),
            'sell'  => MarketOrder::sellOrders(),
            'mine'  => Auth::user()->marketOrders(),
            default => MarketOrder::query()
        };

        if (! empty($this->itemFilter)) {
            $marketOrders->where('item_name', $this->itemFilter);
        }

        if (! empty($this->countMinFilter)) {
            $marketOrders->where('count', '>=', $this->countMinFilter);
        }

        if (! empty($this->countMaxFilter)) {
            $marketOrders->where('count', '<=', $this->countMaxFilter);
        }

        if (! empty($this->priceMinFilter)) {
            $marketOrders->where('price_each', '>=', $this->priceMinFilter);
        }

        if (! empty($this->priceMaxFilter)) {
            $marketOrders->where('price_each', '<=', $this->priceMaxFilter);
        }

        return view('livewire.market-order-index')
            ->with(['allMarketOrders' => $marketOrders->paginate()])
            ->layoutData(['titleAddon' => 'Market Orders']);
    }
}
