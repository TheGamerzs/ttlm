<?php

namespace App\Http\Livewire;

use App\Models\MarketOrder;
use App\TT\Items\ItemData;
use App\View\MarketOrder\Types\AllOrdersViewModel;
use App\View\MarketOrder\Types\BuyOrderViewModel;
use App\View\MarketOrder\Types\MarketOrderViewModelInterface;
use App\View\MarketOrder\Types\MoveOrderViewModel;
use App\View\MarketOrder\Types\SellOrderViewModel;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Livewire\WithPagination;

class MarketOrderIndex extends BaseComponent
{
    use WithPagination, SendsAlerts;
    protected $paginationTheme = 'bootstrap';

    protected $listeners = [
        'closeMarketOrder' => 'softDeleteOrder',
        'refreshMarketOrderIndex' => '$refresh'
    ];

    public string $type = 'sell';

    public string $itemFilter = '';

    public string $countMinFilter = '';

    public string $countMaxFilter = '';

    public string $priceMinFilter = '';

    public string $priceMaxFilter = '';

    public string $mineFilter = 'active';

    protected $queryString = [
        'type' => ['except' => ''],
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

    public function softDeleteOrder(MarketOrder $marketOrder): void
    {
        if ($marketOrder->user_id != Auth::id()) abort(403);

        $marketOrder->delete();
    }

    public function closeOrder($marketOrderId): void
    {
        $this->askToConfirmDelete('closeMarketOrder', $marketOrderId);
    }

    protected function viewModel(): MarketOrderViewModelInterface
    {
        return match($this->type) {
            'buy'   => new BuyOrderViewModel(),
            'sell'  => new SellOrderViewModel(),
            'move'  => new MoveOrderViewModel(),
            default => new AllOrdersViewModel()
        };
    }

    public function render()
    {
        $marketOrders = match($this->type) {
            'buy'   => MarketOrder::buyOrders(),
            'sell'  => MarketOrder::sellOrders(),
            'move'  => MarketOrder::moveOrders(),
            'mine'  => Auth::user()->marketOrders()->orderBy('type'),
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

        if ($this->type == 'mine') {
            match($this->mineFilter) {
                'expired' => $marketOrders->onlyExpired(),
                'closed'  => $marketOrders->onlyTrashed(),
                default   => null
            };
        }

        return view('livewire.market-order-index')
            ->with([
                'allMarketOrders' => $marketOrders->paginate(),
                'viewModel' => $this->viewModel()
            ])
            ->layoutData(['titleAddon' => 'Market Orders']);
    }
}
