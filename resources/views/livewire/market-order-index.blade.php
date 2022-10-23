<?php
/** @var \App\Models\MarketOrder $marketOrder */
?>

<div>
    @auth
        <div class="d-grid mb-4">
            <button class="btn btn-success" wire:click="$emit('newMarketOrder')">
                New
            </button>
        </div>
    @endauth

    @include('partials.market-order-show-radios')

    @include('partials.market-order-show-filters')

    @if($type == 'mine')
        <hr>
        <h1 class="text-center">
            <x-select-choices wire:model="mineFilter">
                <x-select-options :items="['active' => 'Active', 'closed' => 'Closed', 'expired' => 'Expired']" />
            </x-select-choices>
        </h1>
        @if($mineFilter == 'expired')
            <p class="text-center">
                To relist and reset the expiration, click edit and then the update listing button.
            </p>
        @endif

        @if($mineFilter == 'active')
            <p class="text-end">
                <a href="{{ route('marketOrders.show', ['user' => Auth::user()]) }}">Screenshotable View</a>
            </p>
        @endif

        <hr>
    @endif

    <table class="table">
        <thead>
        <tr>
            @if($viewModel->showTypeColumn())
                <td>Type</td>
            @endif
            <td>Item</td>
            <td class="text-center">{{ $viewModel->priceEachLabel() }}</td>
            <td class="text-center">Count</td>
            <td class="text-center">Total Cost</td>
            <td class="text-center">{{ $viewModel->storageOneLabel() }}</td>

            @if($viewModel->showStorageTwoColumn())
                <td class="text-center">{{ $viewModel->storageTwoLabel() }}</td>
            @endif

            @unless($type == 'mine')
                <td class="text-center">Discord Profile</td>
            @else
                @unless($mineFilter == 'closed')
                <td class="text-center">
                    {{ $mineFilter == 'expired' ? 'Expired' : 'Expires' }}
                </td>
                @endunless
            @endunless

            @unless($type == 'mine' && $mineFilter == 'closed')
            <td>{{-- action icons --}}</td>
            @endunless
        </tr>
        </thead>
        <tbody>
        @foreach($allMarketOrders as $marketOrder)
            <tr>
                @if($viewModel->showTypeColumn())
                    <td>
                        {{ str($marketOrder->type)->title() }}
                    </td>
                @endif

                <td>
                    {{ $marketOrder->item->name() }}
                </td>

                <td class="text-center">
                    ${{ number_format($marketOrder->price_each) }}
                </td>

                <td class="text-center">
                    {{ number_format($marketOrder->count) }}
                </td>

                <td class="text-center">
                    ${{ number_format($marketOrder->totalCost) }}
                </td>

                <td class="text-center">
                    {{ $marketOrder->storageName }}
                </td>

                @if($viewModel->showStorageTwoColumn())
                    <td class="text-center">
                        {{ $marketOrder->altStorageName }}
                    </td>
                @endif

                @if($type == 'mine' && $mineFilter != 'closed')
                    <td class="text-center">
                        {{ $marketOrder->expires->diffForHumans() }}
                    </td>
                    <td class="text-end">
                        <i wire:click="$emit('editMarketOrder', '{{ $marketOrder->id }}')"
                           class="bi bi-pencil-fill text-warning cursor-pointer"
                           title="Edit"></i>

                        <i class="bi bi-x-circle-fill text-danger cursor-pointer"
                           wire:click="closeOrder('{{ $marketOrder->id }}')"
                           title="Close"></i>
                    </td>
                @elseif($mineFilter != 'closed')
                    <td class="text-center">
                        <x-discord-profile-link-logo :user="$marketOrder->user" />
                    </td>
                    <td>
                        @if($marketOrder->details)
                        <i class="bi bi-eye text-info cursor-pointer"
                           title="Additional Details"
                           wire:click="$emit('openDetailsModal', '{{ $marketOrder->id }}')">
                        </i>
                        @endif
                    </td>
                @endif

            </tr>
        @endforeach
        </tbody>
    </table>
    <div class="d-flex justify-content-center">
        {{ $allMarketOrders->links() }}
    </div>

    @auth
        <livewire:market-order-create-edit />
    @endauth

    <livewire:market-order-additional-details-modal />
</div>
